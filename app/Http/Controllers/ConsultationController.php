<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\User;
use App\Notifications\DbStoreNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    /**
     * Member: Consultations Index & Booking Form
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $target_user = null;

        if ($request->filled('target')) {
            $target_user = User::find($request->target);
        }

        // Latest active/scheduled meeting where user is host or target
        $upcoming_meeting = Consultation::with(['user', 'targetUser'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('target_user_id', $user->id);
            })
            ->where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at', 'asc')
            ->first();

        // Any pending meeting request (waiting for admin to schedule)
        $pending_request = Consultation::with(['user', 'targetUser'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('target_user_id', $user->id);
            })
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Past / Completed discussion history
        $history = Consultation::with(['user', 'targetUser'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('target_user_id', $user->id);
            })
            ->where(function ($query) {
                $query->whereIn('status', ['completed', 'cancelled'])
                      ->orWhereNotNull('discussion_history');
            })
            ->where('id', '!=', optional($upcoming_meeting)->id)
            ->latest()
            ->get();

        // All user consultations (for history table)
        $all_consultations = Consultation::with(['user', 'targetUser'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('target_user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('frontend.member.consultations.index', compact(
            'user',
            'target_user',
            'upcoming_meeting',
            'pending_request',
            'history',
            'all_consultations'
        ));
    }

    /**
     * Member: Store new consultation request
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::user()->membership != 2) {
            flash(translate('Please upgrade to a Premium package to schedule matchmaker meetings.'))->warning();
            return redirect()->route('packages');
        }

        $request->validate([
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_slot' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'target_user_id' => 'nullable|exists:users,id',
        ]);

        $target_user_id = $request->target_user_id;
        $meeting_type = $target_user_id ? 'candidate' : 'matchmaker';

        $consultation = Consultation::create([
            'user_id' => Auth::id(),
            'target_user_id' => $target_user_id,
            'meeting_type' => $meeting_type,
            'phone' => Auth::user()->phone ?? null,
            'preferred_date' => $request->preferred_date,
            'preferred_slot' => $request->preferred_slot,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // 1. Notify Admin
        try {
            $admin = User::where('user_type', 'admin')->first();
            if ($admin) {
                $notify_type = 'consultation_request';
                $id = unique_notify_id();
                $notify_by = Auth::id();
                $info_id = $consultation->id;
                $message = $target_user_id
                    ? Auth::user()->first_name . ' ' . Auth::user()->last_name . ' requested an arranged candidate meeting.'
                    : Auth::user()->first_name . ' ' . Auth::user()->last_name . ' requested a matchmaker consultation.';
                $route = route('admin.consultations.index');

                Notification::send($admin, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            }
        } catch (Exception $e) {
            // suppressed
        }

        // 2. Notify Target Candidate if applicable
        if ($target_user_id) {
            try {
                $target_candidate = User::find($target_user_id);
                if ($target_candidate) {
                    $notify_type = 'candidate_meeting_request';
                    $id = unique_notify_id();
                    $notify_by = Auth::id();
                    $info_id = $consultation->id;
                    $message = Auth::user()->first_name . ' ' . Auth::user()->last_name . ' requested an arranged matchmaker meeting with you.';
                    $route = route('consultations.index');

                    Notification::send($target_candidate, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
                }
            } catch (Exception $e) {
                // suppressed
            }
        }

        $msg = $target_user_id
            ? translate('Your arranged candidate meeting request has been submitted. Our matchmaker will coordinate with both parties.')
            : translate('Your matchmaker consultation request has been submitted successfully.');

        flash($msg)->success();
        return redirect()->route('consultations.index');
    }

    /**
     * Member: Cancel pending consultation request
     */
    public function cancel($id): RedirectResponse
    {
        $consultation = Consultation::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($consultation->status === 'pending') {
            $consultation->status = 'cancelled';
            $consultation->save();
            flash(translate('Your consultation request has been cancelled successfully.'))->success();
        } else {
            flash(translate('Cannot cancel an already scheduled or completed meeting.'))->error();
        }

        return back();
    }

    /**
     * Admin: Listing & management
     */
    public function admin_index(Request $request): View
    {
        $status = $request->status;
        $search = $request->search;

        $consultations = Consultation::with(['user', 'targetUser'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($uq) use ($search) {
                          $uq->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
                      })
                      ->orWhereHas('targetUser', function ($tuq) use ($search) {
                          $tuq->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('code', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.consultations.index', compact('consultations', 'status', 'search'));
    }

    /**
     * Admin: Update meeting schedule or discussion history
     */
    public function admin_update(Request $request, $id): RedirectResponse
    {
        $consultation = Consultation::with(['user', 'targetUser'])->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'scheduled_at' => 'nullable|date',
            'meeting_link' => 'nullable|string|max:255',
            'discussion_history' => 'nullable|string',
        ]);

        $prev_status = $consultation->status;
        $consultation->status = $request->status;

        if ($request->filled('scheduled_at')) {
            $consultation->scheduled_at = $request->scheduled_at;
        }

        if ($request->has('meeting_link')) {
            $consultation->meeting_link = $request->meeting_link;
        }

        if ($request->has('discussion_history')) {
            $consultation->discussion_history = $request->discussion_history;
        }

        $consultation->save();

        // Send notifications based on status change
        try {
            $recipients = array_filter([$consultation->user, $consultation->targetUser]);

            if ($request->status === 'scheduled' && $consultation->scheduled_at) {
                $formatted_time = Carbon::parse($consultation->scheduled_at)->format('d M, Y h:i A');
                $message = translate('Your matchmaker meeting has been scheduled for ') . $formatted_time . translate('. Video meeting link is now available.');
                
                foreach ($recipients as $recipient) {
                    Notification::send($recipient, new DbStoreNotification(
                        'meeting_scheduled',
                        unique_notify_id(),
                        Auth::id(),
                        $consultation->id,
                        $message,
                        route('consultations.index')
                    ));
                }
            } elseif ($request->status === 'completed' || $request->filled('discussion_history')) {
                $message = translate('Matchmaker discussion notes & feedback summary have been published for your recent session.');
                
                foreach ($recipients as $recipient) {
                    Notification::send($recipient, new DbStoreNotification(
                        'meeting_discussion_updated',
                        unique_notify_id(),
                        Auth::id(),
                        $consultation->id,
                        $message,
                        route('consultations.index')
                    ));
                }
            }
        } catch (Exception $e) {
            // suppressed notification error
        }

        flash(translate('Consultation updated successfully.'))->success();
        return back();
    }

    /**
     * Admin: Destroy consultation
     */
    public function admin_destroy($id): RedirectResponse
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete();

        flash(translate('Consultation deleted successfully.'))->success();
        return back();
    }
}
