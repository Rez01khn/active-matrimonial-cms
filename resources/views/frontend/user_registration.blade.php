@extends('frontend.layouts.app')

@section('content')
<div class="py-4 py-lg-5 bg-mat-cream">
	<div class="container">
		<div class="row">
			<div class="col-xxl-8 col-xl-9 col-lg-10 mx-auto">
				<div class="card reg-wizard-card">
					<!-- Stepper Progress Header -->
					<div class="reg-stepper-header">
						<div class="reg-stepper-wrap">
							<div class="reg-stepper-progress-bg">
								<div class="reg-stepper-progress-fill" id="wizardProgressFill"></div>
							</div>

							<!-- Step 1 Node -->
							<div class="reg-step-node active" id="stepNode1">
								<div class="reg-step-circle">
									<span class="step-num">01</span>
									<i class="las la-check step-check d-none"></i>
								</div>
								<div class="reg-step-title">{{ translate('Basic Info') }}</div>
								<div class="reg-step-sub">{{ translate('Account & Access') }}</div>
							</div>

							<!-- Step 2 Node -->
							<div class="reg-step-node" id="stepNode2">
								<div class="reg-step-circle">
									<span class="step-num">02</span>
									<i class="las la-check step-check d-none"></i>
								</div>
								<div class="reg-step-title">{{ translate('Personal Details') }}</div>
								<div class="reg-step-sub">{{ translate('Bio & Background') }}</div>
							</div>

							<!-- Step 3 Node -->
							<div class="reg-step-node" id="stepNode3">
								<div class="reg-step-circle">
									<span class="step-num">03</span>
									<i class="las la-check step-check d-none"></i>
								</div>
								<div class="reg-step-title">{{ translate('Partner Preferences') }}</div>
								<div class="reg-step-sub">{{ translate('Match Criteria') }}</div>
							</div>
						</div>
					</div>

					<div class="card-body p-4 p-md-5">
						<form class="form-default" id="reg-form" role="form" action="{{ route('register') }}" method="POST">
							@csrf

							<!-- ==========================================
							     STEP 1: BASIC INFO & ACCOUNT
							     ========================================== -->
							<div class="wizard-step-pane active" id="stepPane1">
								<div class="wizard-step-header">
									<span class="wizard-pill-badge"><i class="las la-user-circle mr-1"></i> {{ translate('Step 1 of 3') }}</span>
									<h2 class="wizard-step-header-title">{{ translate('Create Your Account') }}</h2>
									<p class="wizard-step-header-desc mb-0">{{ translate('Enter your basic information to set up your profile and login access.') }}</p>
								</div>

								@php
									$on_behalves_list = isset($on_behalves) ? $on_behalves : \App\Models\OnBehalf::all();
								@endphp
								@if ($on_behalves_list->isNotEmpty())
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="on_behalf">{{ translate('Creating Profile For (On Behalf)') }} <span class="text-danger">*</span></label>
											<select class="form-control aiz-selectpicker @error('on_behalf') is-invalid @enderror" name="on_behalf" required>
												@foreach ($on_behalves_list as $on_behalf)
												<option value="{{ $on_behalf->id }}">{{ $on_behalf->name }}</option>
												@endforeach
											</select>
											@error('on_behalf')
											<span class="invalid-feedback" role="alert">{{ $message }}</span>
											@enderror
										</div>
									</div>
								</div>
								@endif

								<div class="row">
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="first_name">{{ translate('First Name') }} <span class="text-danger">*</span></label>
											<input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" placeholder="{{ translate('e.g. Rahim') }}" value="{{ old('first_name') }}" required>
											<span class="invalid-feedback step-error" id="err_first_name">{{ translate('First name is required.') }}</span>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="last_name">{{ translate('Last Name') }} <span class="text-danger">*</span></label>
											<input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" placeholder="{{ translate('e.g. Chowdhury') }}" value="{{ old('last_name') }}" required>
											<span class="invalid-feedback step-error" id="err_last_name">{{ translate('Last name is required.') }}</span>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="gender">{{ translate('Gender') }} <span class="text-danger">*</span></label>
											<select id="gender" class="form-control aiz-selectpicker select_gender" name="gender" required>
												<option startdate="{{ get_max_date_male() }}" value="1" @if(old('gender') == 1) selected @endif>{{ translate('Male') }}</option>
												<option startdate="{{ get_max_date_female() }}" value="2" @if(old('gender') == 2) selected @endif>{{ translate('Female') }}</option>
											</select>
											<span class="invalid-feedback step-error" id="err_gender">{{ translate('Please select your gender.') }}</span>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="date_of_birth">{{ translate('Date Of Birth') }} <span class="text-danger">*</span></label>
											<input type="text" id="date_of_birth" class="form-control aiz-date-range" name="date_of_birth"
												placeholder="YYYY-MM-DD" data-single="true" data-show-dropdown="true" data-max-date="{{ get_max_date_male() }}"
												autocomplete="off" value="{{ old('date_of_birth') }}" required>
											<span class="invalid-feedback step-error" id="err_date_of_birth">{{ translate('Valid date of birth is required.') }}</span>
										</div>
									</div>
								</div>

								@if (addon_activation('otp_system'))
								<div>
									<div id="emailOrPhoneDiv">
										<div class="d-flex justify-content-between align-items-start">
											<label class="form-label fw-600" for="email">{{ translate('Email / Phone') }} <span class="text-danger">*</span></label>
											<button class="btn btn-link p-0 opacity-70 text-primary fs-12 fw-600" type="button" onclick="toggleEmailPhone(this)">{{ translate('Use Email Instead') }}</button>
										</div>
										<div class="form-group phone-form-group mb-3">
											<div class="input-group registration-iti">
												<input type="tel" id="phone-code" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
												@if(get_setting('registration_verification') == '1')
												<button class="btn btn-primary ml-2" type="button" id="sendOtpPhoneBtn" onclick="sendVerificationCode(this)">
													{{ translate('Verify') }}
												</button>
												@endif
											</div>
											<span class="invalid-feedback step-error" id="err_phone">{{ translate('Valid phone number is required.') }}</span>
										</div>

										<input type="hidden" name="country_code" value="">

										<div class="form-group email-form-group mb-3 d-none">
											<div class="input-group">
												<input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Address') }}" name="email" id="signinAddonEmail" autocomplete="off">
												@if(get_setting('registration_verification') == '1')
												<button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)">
													{{ translate('Verify') }}
												</button>
												@endif
											</div>
											<span class="invalid-feedback step-error" id="err_email">{{ translate('Valid email address is required.') }}</span>
										</div>
									</div>
									<div class="form-group mb-3 d-none">
										<label class="form-label fw-600" for="verification_code">{{ translate('Verification Code') }}</label>
										<div class="input-group">
											<input type="text" class="form-control @error('verification_code') is-invalid @enderror border-right-0" name="code" id="verification_code" placeholder="{{ translate('Verification Code') }}" maxlength="6">
											<span class="btn border border-left-0" id="verifyOtpBtn">
												<i class="las la-lg la-arrow-right"></i>
											</span>
										</div>
									</div>
								</div>
								@else
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group mb-3 email-phone-div" id="emailOrPhoneDiv">
											<label class="form-label fw-600" for="email">{{ translate('Email address') }} <span class="text-danger">*</span></label>
											<div class="input-group">
												<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="signinSrEmail" placeholder="{{ translate('e.g. rahim@example.com') }}" value="{{ old('email') }}" required>
												@if(get_setting('registration_verification') == '1')
												<button class="btn btn-primary ml-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode()">
													{{ translate('Verify') }}
												</button>
												@endif
											</div>
											<span class="invalid-feedback step-error" id="err_email">{{ translate('Valid email address is required.') }}</span>
										</div>
										<div class="form-group mb-3 d-none">
											<label class="form-label fw-600" for="verification_code">{{ translate('Verification Code') }}</label>
											<div class="input-group">
												<input type="text" class="form-control @error('verification_code') is-invalid @enderror border-right-0" name="code" id="verification_code" placeholder="{{ translate('Verification Code') }}" maxlength="6">
												<span class="btn border border-left-0" id="verifyOtpBtn">
													<i class="las la-lg la-arrow-right"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
								@endif

								<div class="row">
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="password">{{ translate('Password') }} <span class="text-danger">*</span></label>
											<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="********" required>
											<small class="text-muted fs-11">{{ translate('Minimum 8 characters') }}</small>
											<span class="invalid-feedback step-error" id="err_password">{{ translate('Password must be at least 8 characters.') }}</span>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="password_confirmation">{{ translate('Confirm password') }} <span class="text-danger">*</span></label>
											<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="********" required>
											<small class="text-muted fs-11">{{ translate('Repeat password') }}</small>
											<span class="invalid-feedback step-error" id="err_password_confirmation">{{ translate('Passwords do not match.') }}</span>
										</div>
									</div>
								</div>

								@if(addon_activation('referral_system'))
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="referral_code">{{ translate('Referral Code') }} <small class="text-muted">({{ translate('Optional') }})</small></label>
											<input type="text" class="form-control" value="{{ old('referral_code') }}" placeholder="{{ translate('Enter referral code if any') }}" name="referral_code">
										</div>
									</div>
								</div>
								@endif

								@if(get_setting('google_recaptcha_activation') == 1 && get_setting('recaptcha_user_register') == 1)
								@if ($errors->has('g-recaptcha-response'))
								<span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white d-block" role="alert">
									<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
								</span>
								@endif
								@endif

								<div class="mb-4">
									<label class="aiz-checkbox">
										<input type="checkbox" name="checkbox_example_1" id="terms_agree" required checked>
										<span class="opacity-80 fs-13">{{ translate('By signing up you agree to our') }}
											<a href="{{ env('APP_URL').'/terms-conditions' }}" target="_blank" class="text-primary fw-600">{{ translate('terms and conditions') }}.</a>
										</span>
										<span class="aiz-square-check"></span>
									</label>
									<span class="invalid-feedback step-error" id="err_terms">{{ translate('You must accept terms & conditions.') }}</span>
								</div>

								<!-- Step 1 Next Button -->
								<div class="pt-2">
									<button type="button" class="btn btn-mat-maroon btn-block py-2 fw-700 fs-15 shadow-sm" onclick="goToStep(2)">
										<span>{{ translate('Continue to Personal Details') }}</span>
										<i class="las la-arrow-right ml-2 fs-16"></i>
									</button>
								</div>
							</div>

							<!-- ==========================================
							     STEP 2: PERSONAL DETAILS & BACKGROUND
							     ========================================== -->
							<div class="wizard-step-pane" id="stepPane2">
								<div class="wizard-step-header">
									<span class="wizard-pill-badge"><i class="las la-id-card mr-1"></i> {{ translate('Step 2 of 3') }}</span>
									<h2 class="wizard-step-header-title">{{ translate('Personal Details & Roots') }}</h2>
									<p class="wizard-step-header-desc mb-0">{{ translate('Add your religion, location, and lifestyle background to receive compatible matches.') }}</p>
								</div>

								@php
									$marital_statuses_list = isset($marital_statuses) ? $marital_statuses : \App\Models\MaritalStatus::all();
									$religions_list = isset($religions) ? $religions : \App\Models\Religion::all();
									$states_list = isset($states) ? $states : \App\Models\State::all();
									$cities_list = isset($cities) ? $cities : \App\Models\City::all();
								@endphp

								<div class="row">
									<!-- Marital Status -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="marital_status_id">{{ translate('Marital Status') }} <span class="wizard-field-badge">{{ translate('Recommended') }}</span></label>
											<select class="form-control aiz-selectpicker" name="marital_status_id" id="marital_status_id" data-live-search="true">
												<option value="">{{ translate('Select Marital Status') }}</option>
												@foreach ($marital_statuses_list as $status)
												<option value="{{ $status->id }}">{{ $status->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<!-- Religion -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="religion_id">{{ translate('Religion') }} <span class="wizard-field-badge">{{ translate('Recommended') }}</span></label>
											<select class="form-control aiz-selectpicker" name="religion_id" id="religion_id" data-live-search="true">
												<option value="">{{ translate('Select Religion') }}</option>
												@foreach ($religions_list as $religion)
												<option value="{{ $religion->id }}">{{ $religion->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<!-- Height -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="height">{{ translate('Height') }}</label>
											<select class="form-control aiz-selectpicker" name="height" id="height" data-live-search="true">
												<option value="">{{ translate('Select Height') }}</option>
												<option value="4.6">4'6" (137 cm)</option>
												<option value="4.8">4'8" (142 cm)</option>
												<option value="4.10">4'10" (147 cm)</option>
												<option value="5.0">5'0" (152 cm)</option>
												<option value="5.2">5'2" (157 cm)</option>
												<option value="5.4">5'4" (163 cm)</option>
												<option value="5.6">5'6" (168 cm)</option>
												<option value="5.8">5'8" (173 cm)</option>
												<option value="5.10">5'10" (178 cm)</option>
												<option value="6.0">6'0" (183 cm)</option>
												<option value="6.2">6'2" (188 cm)</option>
												<option value="6.4">6'4" (193 cm)</option>
											</select>
										</div>
									</div>

									<!-- Profession / Designation -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="profession">{{ translate('Profession / Career') }}</label>
											<input type="text" class="form-control" name="profession" id="profession" placeholder="{{ translate('e.g. Software Engineer, Doctor, Banker') }}">
										</div>
									</div>
								</div>

								<div class="row">
									<!-- Division / State -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="state_id">{{ translate('Living Division') }}</label>
											<select class="form-control aiz-selectpicker" name="state_id" id="state_id" data-live-search="true" onchange="loadCities(this.value)">
												<option value="">{{ translate('Select Division') }}</option>
												@foreach ($states_list as $state)
												<option value="{{ $state->id }}">{{ $state->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<!-- District / City -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="city_id">{{ translate('District / City') }}</label>
											<select class="form-control aiz-selectpicker" name="city_id" id="city_id" data-live-search="true">
												<option value="">{{ translate('Select District') }}</option>
												@foreach ($cities_list as $city)
												<option value="{{ $city->id }}">{{ $city->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<!-- Step 2 Navigation Buttons -->
								<div class="d-flex align-items-center justify-content-between pt-3">
									<button type="button" class="btn btn-wizard-prev px-4 py-2" onclick="goToStep(1)">
										<i class="las la-arrow-left mr-1"></i> {{ translate('Back') }}
									</button>
									<button type="button" class="btn btn-mat-maroon px-4 py-2 fw-700 fs-15 shadow-sm" onclick="goToStep(3)">
										<span>{{ translate('Continue to Partner Preferences') }}</span>
										<i class="las la-arrow-right ml-2 fs-16"></i>
									</button>
								</div>
							</div>

							<!-- ==========================================
							     STEP 3: PARTNER PREFERENCES
							     ========================================== -->
							<div class="wizard-step-pane" id="stepPane3">
								<div class="wizard-step-header">
									<span class="wizard-pill-badge"><i class="las la-heart mr-1"></i> {{ translate('Step 3 of 3') }}</span>
									<h2 class="wizard-step-header-title">{{ translate('Partner Expectations') }}</h2>
									<p class="wizard-step-header-desc mb-0">{{ translate('Specify who you are looking for so our smart matchmaking system suggests ideal matches right away.') }}</p>
								</div>

								<!-- Age Range Preferences -->
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600">{{ translate('Preferred Partner Age (Min - Max)') }}</label>
											<div class="d-flex align-items-center gap-2">
												<select class="form-control aiz-selectpicker" name="partner_age_min" id="partner_age_min">
													<option value="">Min Age</option>
													@for ($age = 18; $age <= 60; $age++)
													<option value="{{ $age }}" @if($age == 21) selected @endif>{{ $age }} {{ translate('Years') }}</option>
													@endfor
												</select>
												<span class="px-2 text-muted fw-700">-</span>
												<select class="form-control aiz-selectpicker" name="partner_age_max" id="partner_age_max">
													<option value="">Max Age</option>
													@for ($age = 18; $age <= 65; $age++)
													<option value="{{ $age }}" @if($age == 32) selected @endif>{{ $age }} {{ translate('Years') }}</option>
													@endfor
												</select>
											</div>
										</div>
									</div>

									<!-- Partner Religion -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="partner_religion_id">{{ translate('Preferred Religion') }}</label>
											<select class="form-control aiz-selectpicker" name="partner_religion_id" id="partner_religion_id" data-live-search="true">
												<option value="">{{ translate('Any Religion') }}</option>
												@foreach ($religions_list as $religion)
												<option value="{{ $religion->id }}">{{ $religion->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<!-- Partner Marital Status -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="partner_marital_status_id">{{ translate('Preferred Marital Status') }}</label>
											<select class="form-control aiz-selectpicker" name="partner_marital_status_id" id="partner_marital_status_id" data-live-search="true">
												<option value="">{{ translate('Any Marital Status') }}</option>
												@foreach ($marital_statuses_list as $status)
												<option value="{{ $status->id }}">{{ $status->name }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<!-- Partner Division -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="partner_state_id">{{ translate('Preferred Region / Division') }}</label>
											<select class="form-control aiz-selectpicker" name="partner_state_id" id="partner_state_id" data-live-search="true">
												<option value="">{{ translate('Any Division') }}</option>
												@foreach ($states_list as $state)
												<option value="{{ $state->id }}">{{ $state->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<!-- Partner Min Height -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="partner_height">{{ translate('Preferred Minimum Height') }}</label>
											<select class="form-control aiz-selectpicker" name="partner_height" id="partner_height" data-live-search="true">
												<option value="">{{ translate('Any Height') }}</option>
												<option value="4.8">4'8" or taller</option>
												<option value="5.0">5'0" or taller</option>
												<option value="5.2">5'2" or taller</option>
												<option value="5.4">5'4" or taller</option>
												<option value="5.6">5'6" or taller</option>
												<option value="5.8">5'8" or taller</option>
												<option value="5.10">5'10" or taller</option>
												<option value="6.0">6'0" or taller</option>
											</select>
										</div>
									</div>

									<!-- Partner Profession -->
									<div class="col-lg-6">
										<div class="form-group mb-3">
											<label class="form-label fw-600" for="partner_profession">{{ translate('Preferred Profession / Sector') }}</label>
											<input type="text" class="form-control" name="partner_profession" id="partner_profession" placeholder="{{ translate('e.g. Any, Doctor, Engineer, Govt Officer') }}">
										</div>
									</div>
								</div>

								<!-- Step 3 Navigation / Submit Buttons -->
								<div class="d-flex align-items-center justify-content-between pt-3">
									<button type="button" class="btn btn-wizard-prev px-4 py-2" onclick="goToStep(2)">
										<i class="las la-arrow-left mr-1"></i> {{ translate('Back') }}
									</button>
									<button type="submit" id="createAccountBtn" class="btn btn-mat-gold-submit px-4 py-2 fw-700 fs-15 shadow-lg">
										<i class="las la-check-circle mr-2 fs-18"></i>
										<span>{{ translate('Complete Registration & Find Matches') }}</span>
									</button>
								</div>
							</div>

							<!-- Social Join Options -->
							@if(get_setting('google_login_activation') == 1 || get_setting('facebook_login_activation') == 1 || get_setting('twitter_login_activation') == 1 || get_setting('apple_login_activation') == 1)
							<div class="mt-5 pt-3 border-top">
								<div class="separator mb-3 text-center">
									<span class="bg-white px-3 text-muted fs-12">{{ translate('Or Join With Social Account') }}</span>
								</div>
								<ul class="list-inline social colored text-center mb-0">
									@if(get_setting('facebook_login_activation') == 1)
									<li class="list-inline-item">
										<a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook" title="{{ translate('Facebook') }}"><i class="lab la-facebook-f"></i></a>
									</li>
									@endif
									@if(get_setting('google_login_activation') == 1)
									<li class="list-inline-item">
										<a href="{{ route('social.login', ['provider' => 'google']) }}" class="google" title="{{ translate('Google') }}"><i class="lab la-google"></i></a>
									</li>
									@endif
									@if(get_setting('twitter_login_activation') == 1)
									<li class="list-inline-item">
										<a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter" title="{{ translate('Twitter') }}"><i class="lab la-twitter"></i></a>
									</li>
									@endif
									@if(get_setting('apple_login_activation') == 1)
									<li class="list-inline-item">
										<a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple" title="{{ translate('Apple') }}"><i class="lab la-apple"></i></a>
									</li>
									@endif
								</ul>
							</div>
							@endif

							<div class="text-center mt-4">
								<p class="text-muted mb-0 fs-13">{{ translate("Already have an account?") }}
									<a href="{{ route('login') }}" class="text-primary fw-700 ml-1">{{ translate('Login to your account') }}</a>
								</p>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	// Current active step tracker
	let currentWizardStep = 1;

	function goToStep(step) {
		// If trying to move forward from Step 1 to Step 2, validate Step 1 fields
		if (step > 1 && currentWizardStep === 1) {
			if (!validateStep1()) {
				return false;
			}
		}

		// Update UI panes
		$('.wizard-step-pane').removeClass('active');
		$('#stepPane' + step).addClass('active');

		// Update Stepper Nodes and progress line
		for (let i = 1; i <= 3; i++) {
			let $node = $('#stepNode' + i);
			if (i < step) {
				$node.removeClass('active').addClass('completed');
				$node.find('.step-num').addClass('d-none');
				$node.find('.step-check').removeClass('d-none');
			} else if (i === step) {
				$node.addClass('active').removeClass('completed');
				$node.find('.step-num').removeClass('d-none');
				$node.find('.step-check').addClass('d-none');
			} else {
				$node.removeClass('active completed');
				$node.find('.step-num').removeClass('d-none');
				$node.find('.step-check').addClass('d-none');
			}
		}

		// Progress line width
		const progressWidth = step === 1 ? '0%' : (step === 2 ? '50%' : '100%');
		$('#wizardProgressFill').css('width', progressWidth);

		currentWizardStep = step;

		// Scroll to card top smoothly
		$('html, body').animate({
			scrollTop: $('.reg-wizard-card').offset().top - 80
		}, 300);
	}

	function validateStep1() {
		let isValid = true;
		$('.step-error').hide();
		$('.form-control').removeClass('is-invalid');

		// First Name
		const firstName = $('#first_name').val().trim();
		if (!firstName) {
			$('#first_name').addClass('is-invalid');
			$('#err_first_name').show();
			isValid = false;
		}

		// Last Name
		const lastName = $('#last_name').val().trim();
		if (!lastName) {
			$('#last_name').addClass('is-invalid');
			$('#err_last_name').show();
			isValid = false;
		}

		// Date of Birth
		const dob = $('#date_of_birth').val().trim();
		if (!dob) {
			$('#date_of_birth').addClass('is-invalid');
			$('#err_date_of_birth').show();
			isValid = false;
		}

		// Phone or Email check
		const emailGroupVisible = $('.email-form-group').is(':visible') || $('#signinSrEmail').length > 0;
		if (emailGroupVisible) {
			const emailInput = $('#signinAddonEmail').length > 0 ? $('#signinAddonEmail') : $('#signinSrEmail');
			const emailVal = emailInput.val().trim();
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailVal || !emailRegex.test(emailVal)) {
				emailInput.addClass('is-invalid');
				$('#err_email').show();
				isValid = false;
			}
		} else {
			const phoneVal = $('#phone-code').val().trim();
			if (!phoneVal) {
				$('#phone-code').addClass('is-invalid');
				$('#err_phone').show();
				isValid = false;
			}
		}

		// Password
		const pass = $('#password').val();
		if (!pass || pass.length < 8) {
			$('#password').addClass('is-invalid');
			$('#err_password').show();
			isValid = false;
		}

		// Password Confirmation
		const passConfirm = $('#password_confirmation').val();
		if (pass !== passConfirm) {
			$('#password_confirmation').addClass('is-invalid');
			$('#err_password_confirmation').show();
			isValid = false;
		}

		// Terms Checkbox
		if (!$('#terms_agree').is(':checked')) {
			$('#err_terms').show();
			isValid = false;
		}

		if (!isValid) {
			if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
				AIZ.plugins.notify('danger', '{{ translate("Please fill in all required fields in Step 1 correctly.") }}');
			}
		}

		return isValid;
	}

	// Dynamic district loader for Step 2
	function loadCities(stateId) {
		if (!stateId) return;
		$.ajax({
			url: "{{ route('cities.get_cities_by_state') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				state_id: stateId
			},
			success: function(response) {
				var $citySelect = $('#city_id');
				$citySelect.empty();
				$citySelect.append('<option value="">{{ translate("Select District") }}</option>');
				$.each(response, function(index, city) {
					$citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
				});
				if ($citySelect.hasClass('aiz-selectpicker')) {
					$citySelect.selectpicker('refresh');
				}
			}
		});
	}

	// Datepicker Initializer
	document.addEventListener('DOMContentLoaded', function() {
		const select = document.getElementById('gender');
		const dobInput = document.getElementById("date_of_birth");

		function initDatepicker(maxDate) {
			if ($(dobInput).data('daterangepicker')) {
				$(dobInput).data('daterangepicker').remove();
			}
			$(dobInput).daterangepicker({
				singleDatePicker: $(dobInput).data('single') ?? true,
				showDropdowns: $(dobInput).data('show-dropdown') ?? true,
				maxDate: maxDate,
				startDate: maxDate,
				autoUpdateInput: true,
				locale: {
					format: $(dobInput).data('format') || 'YYYY-MM-DD',
					applyLabel: "Select",
					cancelLabel: "Clear",
				},
			});
			dobInput.value = maxDate;
		}

		if (select && select.selectedIndex >= 0) {
			initDatepicker(select.options[select.selectedIndex].getAttribute('startdate'));
			select.addEventListener('change', function() {
				const selectedOption = this.options[this.selectedIndex];
				const maxDate = selectedOption.getAttribute('startdate');
				dobInput.setAttribute('data-max-date', maxDate);
				initDatepicker(maxDate);
			});
		}
	});
</script>

@if(get_setting('google_recaptcha_activation') == 1 && get_setting('recaptcha_user_register') == 1)
	@include('partials.recaptcha', ['action' => 'recaptcha_user_register','form_id' => 'reg-form'])
@endif
@if(addon_activation('otp_system'))
	@include('partials.emailOrPhone')
@endif
@if (get_setting('registration_verification'))
	@include('partials.verifyEmailOrPhone')
@endif

<script type="text/javascript">
	const regVerifyRequired = {{ get_setting('registration_verification') ? 'true' : 'false' }};
	const createBtn = $('#createAccountBtn');
	const termsCheckbox = $('input[name="checkbox_example_1"]');

	function toggleCreateBtn() {
		const termsChecked = termsCheckbox.is(':checked');
		const regVerified = regVerifyRequired ? (typeof verifyBtn !== 'undefined' && verifyBtn && verifyBtn.classList.contains('disabled')) : true;
		let enableBtn = false;
		if (regVerifyRequired) {
			enableBtn = termsChecked && regVerified;
		} else {
			enableBtn = termsChecked;
		}
		createBtn.prop('disabled', !enableBtn);
	}

	document.addEventListener('DOMContentLoaded', function() {
		toggleCreateBtn();
		termsCheckbox.on('change', toggleCreateBtn);
	});
</script>
@endsection
