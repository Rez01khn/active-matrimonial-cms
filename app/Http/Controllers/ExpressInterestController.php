<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChatThread;
use App\Models\ExpressInterest;
use App\Models\User;
use App\Notifications\DbStoreNotification;
use App\Services\FirbaseNotification;
use App\Utility\EmailUtility;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Facades\FirebaseNotificationService as Larafirebase;
use Exception;

class ExpressInterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $interests = DB::table('express_interests')
            ->orderBy('id', 'desc')
            ->where('interested_by', Auth::user()->id)
            ->join('users', 'express_interests.user_id', '=', 'users.id')
            ->select('express_interests.id')
            ->distinct()
            ->paginate(10);

        return view('frontend.member.my_interests', compact('interests'));
    }

    public function interest_requests(Request $request)
    {
        $user_id = Auth::user()->id;
        session(['interest_requests_last_viewed_at' => now()->toDateTimeString()]);

        $active_tab = $request->get('tab', 'requests');
        if (!in_array($active_tab, ['requests', 'sent', 'received'])) {
            $active_tab = 'requests';
        }

        // Tab counts
        $requests_count = ExpressInterest::where('user_id', $user_id)->count();
        $sent_count = ExpressInterest::where('interested_by', $user_id)->count();
        $received_count = $requests_count;
        $total_received_count = $requests_count;

        if ($active_tab === 'sent') {
            $query = ExpressInterest::where('interested_by', $user_id)
                ->with([
                    'user.member.marital_status',
                    'user.education',
                    'user.career',
                    'user.physical_attributes',
                    'user.hobbies',
                    'user.addresses.city',
                    'user.addresses.country'
                ]);

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query = $query->whereHas('user', function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                });
            }
        } else {
            // Tab 1: Requests received from other members
            $query = ExpressInterest::where('user_id', $user_id)
                ->with([
                    'interested_by_user.member.marital_status',
                    'interested_by_user.education',
                    'interested_by_user.career',
                    'interested_by_user.physical_attributes',
                    'interested_by_user.hobbies',
                    'interested_by_user.addresses.city',
                    'interested_by_user.addresses.country'
                ]);

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query = $query->whereHas('interested_by_user', function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
                });
            }
        }

        $interests = $query->latest('id')->paginate(10)->appends($request->all());

        return view('frontend.member.interest_requests', compact(
            'interests',
            'active_tab',
            'requests_count',
            'sent_count',
            'received_count',
            'total_received_count'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'result' => false,
                'status' => 'unauthenticated',
                'message' => translate('Please login to express interest.')
            ], 401);
        }

        $interested_by_user = Auth::user();

        // Must be an active paid package holder to express interest
        if (!is_paid_member($interested_by_user->id)) {
            return response()->json([
                'result' => false,
                'status' => 'upgrade_required',
                'message' => translate('Please upgrade to a premium package to express interest.')
            ]);
        }

        $interested_by_member = $interested_by_user->member;
        if (!$interested_by_member) {
            return response()->json([
                'result' => false,
                'message' => translate('Member profile not found.')
            ]);
        }

        // Cannot express interest in oneself
        if ($request->id == $interested_by_user->id) {
            return response()->json([
                'result' => false,
                'message' => translate('You cannot express interest in your own profile.')
            ]);
        }

        // Check if interest already sent
        $already_sent = ExpressInterest::where('user_id', $request->id)
            ->where('interested_by', $interested_by_user->id)
            ->first();

        if ($already_sent) {
            return response()->json([
                'result' => true,
                'status' => 'already_sent',
                'message' => translate('Interest already expressed.')
            ]);
        }

        // Check remaining interest count
        if ($interested_by_member->remaining_interest < 1) {
            return response()->json([
                'result' => false,
                'status' => 'limit_exhausted',
                'message' => translate('You have exhausted your remaining express interest limit. Please upgrade or renew your package.')
            ]);
        }

        // Store express interest data
        $express_interest                = new ExpressInterest;
        $express_interest->user_id       = $request->id;
        $express_interest->interested_by = $interested_by_user->id;

        if ($express_interest->save()) {
            // Decrement interested by user's remaining express interest value
            if ($interested_by_member->remaining_interest > 0) {
                $interested_by_member->remaining_interest -= 1;
                $interested_by_member->save();
            }

            $notify_user = User::where('id', $request->id)->first();

            // Express Interest Store Notification for member
            if ($notify_user) {
                try {
                    $notify_type = 'express_interest';
                    $id = unique_notify_id();
                    $notify_by = $interested_by_user->id;
                    $info_id = $express_interest->id;
                    $message = $interested_by_user->first_name . ' ' . $interested_by_user->last_name . ' ' . translate(' has Expressed Interest On You.');
                    $route = route('interest_requests');

                    // fcm 
                    if (get_setting('firebase_push_notification') == 1) {
                        $fcmTokens = User::where('id', $request->id)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                        self::sendFirebaseNotification($fcmTokens, $notify_user, $notify_type, $message, $notify_by);
                    }

                    Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
                } catch (Exception $e) {
                    // notification error suppressed
                }

                // Express Interest email send to member
                if ($notify_user->email != null && get_email_template('email_on_express_interest', 'status')) {
                    EmailUtility::email_on_request($notify_user, 'email_on_express_interest');
                }

                // Express Interest Send SMS to member
                if ($notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('express_interest', 'status') == 1)) {
                    SmsUtility::sms_on_request($notify_user, 'express_interest');
                }
            }

            return response()->json([
                'result' => true,
                'message' => translate('Interest expressed successfully!')
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => translate('Something went wrong')
        ]);
    }

    public function accept_interest(Request $request)
    {
        $interest_id = $request->interest_id ?? $request->id;
        $interest = ExpressInterest::findOrFail($interest_id);

        if ($interest->user_id != Auth::user()->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Unauthorized action.')
                ], 403);
            }
            flash(translate('Unauthorized action.'))->error();
            return back();
        }

        $interest->status = 1;
        if ($interest->save()) {
            $existing_chat_thread = ChatThread::where(function ($query) use ($interest) {
                $query->where('sender_user_id', $interest->interested_by)->where('receiver_user_id', $interest->user_id);
            })->orWhere(function ($query) use ($interest) {
                $query->where('receiver_user_id', $interest->interested_by)->where('sender_user_id', $interest->user_id);
            })->first();

            if ($existing_chat_thread == null) {
                $chat_thread                    = new ChatThread;
                $chat_thread->thread_code       = $interest->interested_by . date('Ymd') . $interest->user_id;
                $chat_thread->sender_user_id    = $interest->interested_by;
                $chat_thread->receiver_user_id  = $interest->user_id;
                $chat_thread->save();
            }

            $notify_user = User::where('id', $interest->interested_by)->first();

            // Express Interest Store Notification for member
            try {
                $notify_type = 'accept_interest';
                $id = unique_notify_id();
                $notify_by = Auth::user()->id;
                $info_id = $interest->id;
                $message = Auth::user()->first_name . ' ' . Auth::user()->last_name . ' ' . translate(' has accepted your interest.');
                $route = route('all.messages');

                // fcm 
                if (get_setting('firebase_push_notification') == 1 && $notify_user) {
                    $fcmTokens = User::where('id', $interest->interested_by)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                    self::sendFirebaseNotification($fcmTokens, $notify_user, $notify_type, $message, $notify_by);
                }
                // end of fcm

                if ($notify_user) {
                    Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
                }
            } catch (Exception $e) {
                // suppressed notification exception
            }

            // Express Interest email send to member
            if ($notify_user && $notify_user->email != null && get_email_template('email_on_accepting_interest', 'status')) {
                EmailUtility::email_on_accept_request($notify_user, 'email_on_accepting_interest');
            }

            // Express Interest Send SMS to member
            if ($notify_user && $notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('accept_interest', 'status') == 1)) {
                SmsUtility::sms_on_accept_request($notify_user, 'accept_interest');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => true,
                    'message' => translate('Interest has been accepted successfully! You can now start chatting.')
                ]);
            }

            flash(translate('Interest has been accepted successfully!'))->success();
            return redirect()->route('interest_requests');
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Sorry! Something went wrong.')
                ], 500);
            }

            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function reject_interest(Request $request)
    {
        $interest_id = $request->interest_id ?? $request->id;
        $interest = ExpressInterest::findOrFail($interest_id);

        if ($interest->user_id != Auth::user()->id && $interest->interested_by != Auth::user()->id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Unauthorized action.')
                ], 403);
            }
            flash(translate('Unauthorized action.'))->error();
            return back();
        }

        $notify_target_id = ($interest->user_id == Auth::user()->id) ? $interest->interested_by : $interest->user_id;

        if (ExpressInterest::destroy($interest_id)) {

            $notify_user = User::where('id', $notify_target_id)->first();
            try {
                $notify_type = 'reject_interest';
                $id = unique_notify_id();
                $notify_by = Auth::user()->id;
                $info_id = $interest->id;
                $message = Auth::user()->first_name . ' ' . Auth::user()->last_name . ' ' . translate(' has declined/removed the interest.');
                $route = route('interest_requests');

                // fcm 
                if (get_setting('firebase_push_notification') == 1 && $notify_user) {
                    $fcmTokens = User::where('id', $notify_target_id)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                    self::sendFirebaseNotification($fcmTokens, $notify_user, $notify_type, $message, $notify_by);
                }
                // end of fcm

                if ($notify_user) {
                    Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
                }
            } catch (Exception $e) {
                // suppressed notification exception
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => true,
                    'message' => translate('Interest request has been declined/removed successfully.')
                ]);
            }

            flash(translate('Interest request has been declined/removed successfully.'))->success();
            return redirect()->back();
        } else {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Sorry! Something went wrong.')
                ], 500);
            }

            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function sendFirebaseNotification($fcmTokens = null, $notify_user, $notify_type, $message, $notify_by = null)
    {
        // send firebase notification for mobile app
        if ($notify_user->fcm_token != null) {
            $data = (object)[];
            $data->fcm_token = $notify_user->fcm_token;
            $data->title = $notify_type;
            $data->text = $message;
            $data->notify_by = $notify_by;
            FirbaseNotification::send($data);
        }
        // end of  firebase notification

        Larafirebase::withTitle(str_replace("_", " ", $notify_type))
            ->withBody($message)
            ->sendMessage($fcmTokens);
    }
}
