<div class="aiz-user-sidenav-wrap sticky-top c-scrollbar-light position-relative z-1 shadow-none" id="memberSidebarWrap">
    <!-- Mobile Drawer Header with Close Button (< 1200px) -->
    <div class="member-mobile-sidebar-header d-flex align-items-center justify-content-between p-3 border-bottom d-xl-none bg-white">
        <div class="d-flex align-items-center">
            @if(get_setting('header_logo'))
                <img src="{{ uploaded_asset(get_setting('header_logo')) }}" alt="{{ get_setting('website_name') }}" height="28">
            @else
                <span class="fs-15 fw-700 text-mat-maroon font-serif">{{ get_setting('website_name') }}</span>
            @endif
            <span class="fs-12 fw-700 text-muted ml-2 text-uppercase">{{ translate('Menu') }}</span>
        </div>
        <button type="button" class="btn btn-circle btn-sm btn-soft-danger member-sidebar-close-btn p-1" id="memberSidebarCloseBtn" aria-label="{{ translate('Close') }}">
            <i class="las la-times fs-20"></i>
        </button>
    </div>

    <div class="aiz-user-sidenav rounded overflow-hidden">
        @php
            $auth_user = Auth::user();
            $gender = $auth_user->member->gender ?? 1;
            $gender_placeholder = ($gender == 2)
                ? static_asset('assets/img/female-avatar-place.png')
                : static_asset('assets/img/avatar-place.png');

            $user_photo_url = null;
            if (!empty($auth_user->photo)) {
                $user_photo_url = uploaded_asset($auth_user->photo);
            } elseif (!empty($auth_user->avatar)) {
                $user_photo_url = uploaded_asset($auth_user->avatar);
            } elseif (!empty($auth_user->member->profile_picture)) {
                $user_photo_url = uploaded_asset($auth_user->member->profile_picture);
            }
            if (empty($user_photo_url)) {
                $user_photo_url = $gender_placeholder;
            }

            // Dynamic profile completeness percentage calculation (Exact Weights)
            // 1. Basic Information (15%)
            $has_basic_info = !empty($auth_user->first_name)
                && !empty($auth_user->member->gender)
                && !empty($auth_user->member->birthday);

            // 2. Address / Location (15%)
            $has_address = ($auth_user->addresses && $auth_user->addresses->count() > 0);

            // 3. Education records (15%)
            $has_education = ($auth_user->education && $auth_user->education->count() > 0);

            // 4. Career records (15%)
            $has_career = ($auth_user->career && $auth_user->career->count() > 0);

            // 5. Physical Attributes (10%)
            $has_physical = $auth_user->physical_attributes && (!empty($auth_user->physical_attributes->height) || !empty($auth_user->physical_attributes->weight));

            // 6. Family Information (10%)
            $has_family = $auth_user->families && (!empty($auth_user->families->father) || !empty($auth_user->families->mother) || !empty($auth_user->families->sibling));

            // 7. Photo upload (15%)
            $has_photo = !empty($auth_user->photo) || !empty($auth_user->avatar) || !empty($auth_user->member->profile_picture);

            // 8. Partner Expectations (10%)
            $has_partner_exp = $auth_user->partner_expectations && (
                !empty($auth_user->partner_expectations->general) ||
                !empty($auth_user->partner_expectations->residence_country_id) ||
                !empty($auth_user->partner_expectations->religion_id) ||
                !empty($auth_user->partner_expectations->marital_status_id) ||
                !empty($auth_user->partner_expectations->height)
            );

            $profile_completion_percentage = 0;
            if ($has_basic_info)   $profile_completion_percentage += 15;
            if ($has_address)      $profile_completion_percentage += 15;
            if ($has_education)    $profile_completion_percentage += 15;
            if ($has_career)       $profile_completion_percentage += 15;
            if ($has_physical)     $profile_completion_percentage += 10;
            if ($has_family)       $profile_completion_percentage += 10;
            if ($has_photo)        $profile_completion_percentage += 15;
            if ($has_partner_exp)  $profile_completion_percentage += 10;
        @endphp

        <div class="px-3 pt-4 pb-2 text-center">
            <div class="member-avatar-wrapper">
                <img src="{{ $user_photo_url }}"
                     alt="{{ $auth_user->first_name }}"
                     class="member-avatar-img"
                     onerror="this.onerror=null;this.src='{{ $gender_placeholder }}';">
                <a href="{{ route('profile_settings') }}#basic-info"
                   class="member-avatar-upload-btn"
                   title="{{ translate('Update Profile Photo') }}">
                    <i class="las la-camera"></i>
                </a>
            </div>

            <h4 class="h6 fw-700 mb-1 text-dark">{{ $auth_user->first_name . ' ' . $auth_user->last_name }}</h4>

            <div class="d-flex justify-content-center align-items-center flex-wrap mt-1">
                <span class="member-code-badge mr-1">
                    <i class="las la-id-badge mr-1"></i>#{{ $auth_user->code }}
                </span>
                <span class="member-package-badge">
                    <i class="las la-crown mr-1 text-warning"></i>{{ $auth_user->member->package->name ?? translate('Free Member') }}
                </span>
            </div>
        </div>

        {{-- Biodata Completion Progress --}}
        <div class="biodata-progress-wrap">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fs-11 fw-600 text-muted text-uppercase">{{ translate('Biodata Status') }}</span>
                <span class="fs-12 fw-700 text-primary">{{ $profile_completion_percentage }}%</span>
            </div>
            <div class="biodata-progress-bar-bg">
                <div class="biodata-progress-bar-fill" style="width: {{ $profile_completion_percentage }}%;"></div>
            </div>
            <div class="text-right mt-1">
                <a href="{{ route('profile_settings') }}" class="fs-11 text-primary fw-600">
                    {{ translate('Edit Biodata') }} &rarr;
                </a>
            </div>
        </div>

        <div class="text-center mb-3 px-3">
            <a href="{{ route('member_profile', $auth_user->id) }}"
                class="btn btn-block btn-mat-gold btn-sm py-2">
                <i class="las la-id-card mr-1"></i>{{ translate('View Public Biodata') }}
            </a>
        </div>

        <div class="sidemnenu mb-3">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">

                <li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['dashboard']) }}">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile_settings') }}" class="aiz-side-nav-link {{ areActiveRoutes(['profile_settings']) }}">
                        <i class="las la-user-edit aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('My Biodata / Profile') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('gallery-image_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['gallery-image_index']) }}">
                        <i class="las la-images aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Photo Gallery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('my_interests.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['my_interests.index']) }}">
                        <i class="las la-heart aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Proposals & Interests') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('all.messages') }}" class="aiz-side-nav-link {{ areActiveRoutes(['all.messages']) }}">
                        <i class="las la-envelope aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Messages') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('consultations.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['consultations.index']) }}">
                        <i class="las la-video aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Matchmaker Meeting') }}</span>
                    </a>
                </li>

                @if(Auth::user()->member->auto_profile_match == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('my_matched_profiles') }}" class="aiz-side-nav-link {{ areActiveRoutes(['my_matched_profiles']) }}">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Matched Profiles') }}</span>
                        </a>
                    </li>
                @endif

                <li class="aiz-side-nav-item">
                    <a href="{{ route('my_shortlists') }}" class="aiz-side-nav-link {{ areActiveRoutes(['my_shortlists']) }}">
                        <i class="las la-bookmark aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Shortlisted Profiles') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile-viewers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['profile-viewers.index']) }}">
                        <i class="las la-eye aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Profile Viewers') }}</span>
                    </a>
                </li>

                @if (get_setting('profile_picture_privacy') == 'only_me' || get_setting('gallery_image_privacy') == 'only_me')
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-lock aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Picture Requests') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @if (get_setting('profile_picture_privacy') == 'only_me')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('profile-picture-view-request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Avatar Requests') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('gallery_image_privacy') == 'only_me')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('gallery-image-view-request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Gallery Requests') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (get_setting('wallet_system'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('wallet.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wallet.index']) }}">
                            <i class="las la-wallet aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('My Wallet') }}</span>
                        </a>
                    </li>
                @endif

                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                        <i class="las la-crown aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Membership Packages') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('packages') }}" class="aiz-side-nav-link {{ areActiveRoutes(['packages']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Upgrade Package') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('package_purchase_history') }}" class="aiz-side-nav-link {{ areActiveRoutes(['package_purchase_history']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Purchase History') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                        <i class="las la-cog aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Account Settings') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('member.change_password') }}" class="aiz-side-nav-link {{ areActiveRoutes(['member.change_password']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Change Password') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link" onclick="account_deactivation()">
                                @if (Auth::user()->deactivated == 0)
                                    <span class="aiz-side-nav-text">{{ translate('Deactivate Account') }}</span>
                                @else
                                    <span class="aiz-side-nav-text">{{ translate('Reactivate Account') }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link text-danger" onclick="account_delete()">
                                <span class="aiz-side-nav-text">{{ translate('Delete Account') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="mb-3 px-3">
            <a href="javascript:void(0);" class="btn btn-block btn-mat-maroon btn-sm py-2"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="las la-sign-out-alt mr-1"></i>
                <span>{{ translate('Logout') }}</span>

                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
        </div>
    </div>
</div>
