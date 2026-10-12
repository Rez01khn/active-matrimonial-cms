<?php

namespace App\Http\Controllers\Auth;

use Notification;
use App\Models\User;
use App\Models\Member;
use App\Models\Package;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Utility\EmailUtility;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Notifications\DbStoreNotification;
use App\Facades\FirebaseNotificationService as Larafirebase;
use App\Http\Controllers\OTPVerificationController;
use App\Models\OnBehalf;
use App\Models\MaritalStatus;
use App\Models\Religion;
use App\Models\State;
use App\Models\City;
use App\Models\SpiritualBackground;
use App\Models\PhysicalAttribute;
use App\Models\Address;
use App\Models\Career;
use App\Models\PartnerExpectation;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function showRegistrationForm()
    {
        $on_behalves = OnBehalf::all();
        $marital_statuses = MaritalStatus::all();
        $religions = Religion::all();
        $states = State::all();
        $cities = City::all();
        return view('frontend.user_registration', compact('on_behalves', 'marital_statuses', 'religions', 'states', 'cities'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'on_behalf'            => 'nullable|integer',
            'first_name'           => ['required', 'string', 'max:255'],
            'last_name'            => ['required', 'string', 'max:255'],
            'gender'               => 'required',
            'date_of_birth'        => 'required|date',
            'phone'                 => 'required_without:email|nullable|string|unique:users',
            'email'                 => 'required_without:phone|nullable|email|unique:users',
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha_activation') == 1 && get_setting('recaptcha_user_register') == 1 , ['required', new RecaptchaRule()], ['sometimes'])
            ],
            'checkbox_example_1'   => ['required', 'string'],
            'marital_status_id'    => 'nullable|integer',
            'religion_id'          => 'nullable|integer',
            'height'               => 'nullable|numeric',
            'state_id'             => 'nullable|integer',
            'city_id'              => 'nullable|integer',
            'profession'           => 'nullable|string|max:255',
            'partner_marital_status_id' => 'nullable|integer',
            'partner_religion_id'       => 'nullable|integer',
            'partner_state_id'          => 'nullable|integer',
            'partner_height'            => 'nullable|numeric',
            'partner_profession'        => 'nullable|string|max:255',
            'partner_age_min'           => 'nullable|integer',
            'partner_age_max'           => 'nullable|integer',
        ],
        [
            //'on_behalf.required' => translate('on_behalf is required'),
            'on_behalf.integer' => translate('on_behalf should be integer value'),
            'first_name.required' => translate('first_name is required'),
            'last_name.required' => translate('last_name is required'),
            'gender.required' => translate('gender is required'),
            'date_of_birth.required' => translate('date_of_birth is required'),
            'date_of_birth.date' => translate('date_of_birth should be in date format'),
            'email.required' => translate('Email is required'),
            'email.email' => translate('Email must be a valid email address'),
            'email.unique' => translate('An user exists with this email'),
            'phone.unique' => translate('An user exists with this phone'),
            'phone.required' => translate('Phone is required'),
            'password.required' => translate('Password is required'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'password.min' => translate('Minimum 8 digits required for password'),
            'checkbox_example_1.required'    => translate('You must agree to our terms and conditions.'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $approval = get_setting('member_verification') == 1 ? 0 : 1;
            if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $user = User::create([
                    'first_name'  => $data['first_name'],
                    'last_name'   => $data['last_name'],
                    'membership'  => 0,
                    'email'       => $data['email'],
                    'password'    => Hash::make($data['password']),
                    'code'        => unique_code(),
                    'approved'    => $approval,
                ]);
            } else {
                if (addon_activation('otp_system')) {
                    $cleanPhone = preg_replace('/\D+/', '', $data['phone']);
                    $user = User::create([
                        'first_name'  => $data['first_name'],
                        'last_name'   => $data['last_name'],
                        'membership'  => 0,
                        'phone'       => '+' . $data['country_code'] . $cleanPhone,
                        'password'    => Hash::make($data['password']),
                        'code'        => unique_code(),
                        'approved'    => $approval,
                        'verification_code' => rand(100000, 999999)
                    ]);
                }
            }

            if (addon_activation('referral_system') && !empty($data['referral_code'])) {
                $reffered_user = User::where('code', '!=', null)->where('code', $data['referral_code'])->first();
                if ($reffered_user != null) {
                    $user->referred_by = $reffered_user->id;
                    $user->save();
                }
            }

            $member                 = new Member;
            $member->user_id        = $user->id;
            $member->gender         = $data['gender'];
            $member->on_behalves_id = $data['on_behalf'] ?? null;
            $member->birthday       = date('Y-m-d', strtotime($data['date_of_birth']));
            if (!empty($data['marital_status_id'])) {
                $member->marital_status_id = $data['marital_status_id'];
            }
            $member->save();

            // Step 2: Spiritual / Religion
            if (!empty($data['religion_id']) || !empty($data['caste_id'])) {
                $spiritual = new SpiritualBackground;
                $spiritual->user_id = $user->id;
                $spiritual->religion_id = $data['religion_id'] ?? null;
                $spiritual->caste_id = $data['caste_id'] ?? null;
                $spiritual->save();
            }

            // Step 2: Physical Attribute (Height)
            if (!empty($data['height'])) {
                $physical = new PhysicalAttribute;
                $physical->user_id = $user->id;
                $physical->height = $data['height'];
                $physical->save();
            }

            // Step 2: Present Address
            if (!empty($data['state_id']) || !empty($data['city_id'])) {
                $address = new Address;
                $address->user_id = $user->id;
                $address->type = 'present';
                $address->country_id = 18; // Bangladesh default
                $address->state_id = $data['state_id'] ?? null;
                $address->city_id = $data['city_id'] ?? null;
                $address->save();
            }

            // Step 2: Career / Profession
            if (!empty($data['profession'])) {
                $career = new Career;
                $career->user_id = $user->id;
                $career->designation = $data['profession'];
                $career->save();
            }

            // Step 3: Partner Expectations
            $partner = new PartnerExpectation;
            $partner->user_id = $user->id;
            if (!empty($data['partner_marital_status_id'])) {
                $partner->marital_status_id = $data['partner_marital_status_id'];
            }
            if (!empty($data['partner_religion_id'])) {
                $partner->religion_id = $data['partner_religion_id'];
            }
            if (!empty($data['partner_state_id'])) {
                $partner->preferred_state_id = $data['partner_state_id'];
            }
            if (!empty($data['partner_height'])) {
                $partner->height = $data['partner_height'];
            }
            if (!empty($data['partner_profession'])) {
                $partner->profession = $data['partner_profession'];
            }
            if (!empty($data['partner_age_min']) || !empty($data['partner_age_max'])) {
                $age_min = $data['partner_age_min'] ?? '18';
                $age_max = $data['partner_age_max'] ?? '45';
                $partner->general = 'Age: ' . $age_min . ' - ' . $age_max;
            }
            $partner->save();

            // Account opening Email to member
            if ($data['email'] != null && env('MAIL_USERNAME') != null) {
                $account_oppening_email = EmailTemplate::where('identifier', 'account_oppening_email')->first();
                if ($account_oppening_email && $account_oppening_email->status == 1) {
                    EmailUtility::account_oppening_email($user->id, $data['password']);
                }
            }

            return $user;
        });
    }

    public function register(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email or Phone already exists.'));
                return back();
            }
        } elseif (User::where('phone', '+' . $request->country_code . $request->phone)->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        auth()->login($user);

        try {
            $notify_type = 'member_registration';
            $id = unique_notify_id();
            $notify_by = $user->id;
            $info_id = $user->id;
            $message = translate('A new member has been registered to your system. Name: ') . $user->first_name . ' ' . $user->last_name;
            
            if($user->membership === 2){
                $route = route('premium.members.index');
            }elseif($user->membership === 1){
                $route = route('free.members.index');
            }else{
                $route = route('unsubscribed.members.index');
            }

            // fcm 
            if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('user_type', 'admin')->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
            }
            // end of fcm
            Notification::send(User::where('user_type', 'admin')->first(), new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
        } catch (\Exception $e) {
            // dd($e);
        }
        if (env('MAIL_USERNAME') != null && (get_email_template('account_opening_email_to_admin', 'status') == 1)) {
            $admin = User::where('user_type', 'admin')->first();
            EmailUtility::account_opening_email_to_admin($user, $admin);
        }


        if($user->email != null){
            if((get_setting('email_verification') != 1 && !addon_activation('otp_system')) || get_setting('registration_verification') === '1'){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                flash(translate('Registration successful.'))->success();
            }
            else {
                try {
                    event(new Registered($user));
                    flash(translate('Registration successfull. Please verify your email.'))->success();
                } catch (\Throwable $e) {
                    dd($e);
                    $user->delete();
                    flash(translate('Registration failed. Please try again later.'))->error();
                }
            }

        }

        if($user->phone != null){
            if(get_setting(('email_verification') != 1 && !addon_activation('otp_system')) || get_setting('registration_verification') === '1'){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                flash(translate('Registration successful.'))->success();
            }
            else{
                try {
                   if (addon_activation('otp_system')){
                        if(get_setting('registration_verification') != '1' ){
                            $otpController = new OTPVerificationController;
                            $otpController->send_code($user);
                        }
                    }
                    flash(translate('Registration successfull. Please verify your phone number.'))->success();
                } catch (\Throwable $e) {
                    dd($e);
                    $user->delete();
                    flash(translate('Registration failed. Please try again later.'))->error();
                }
            }
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        //?? where should redirect user after registration
        if ($user->email == null && $user->email_verified_at == null) {
            return redirect()->route('verification');
        } else {
            return redirect()->route('packages');
        }
    }
}
