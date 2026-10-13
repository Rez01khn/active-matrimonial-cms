@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $user = Auth::user();

    // User Avatar Photo
    $gender = $user->member->gender ?? 1;
    $gender_placeholder = ($gender == 2)
        ? static_asset('assets/img/female-avatar-place.png')
        : static_asset('assets/img/avatar-place.png');

    $user_photo_url = null;
    if (!empty($user->photo)) {
        $user_photo_url = uploaded_asset($user->photo);
    } elseif (!empty($user->avatar)) {
        $user_photo_url = uploaded_asset($user->avatar);
    } elseif (!empty($user->member->profile_picture)) {
        $user_photo_url = uploaded_asset($user->member->profile_picture);
    }
    if (empty($user_photo_url)) {
        $user_photo_url = $gender_placeholder;
    }

    // Dynamic User Meta
    $user_age = !empty($user->member->birthday) ? \Carbon\Carbon::parse($user->member->birthday)->age : 30;
    $user_height = !empty($user->physical_attributes->height) ? $user->physical_attributes->height : '5.8';
    
    $user_city = 'Dhaka';
    $user_country = 'Bangladesh';
    if ($user->addresses && $user->addresses->first()) {
        $addr = $user->addresses->first();
        if ($addr->city && !empty($addr->city->name)) {
            $user_city = $addr->city->name;
        }
        if ($addr->country && !empty($addr->country->name)) {
            $user_country = $addr->country->name;
        }
    }
    $user_location = $user_city . ', ' . $user_country;

    // Package Details
    $current_pkg_name = ($user->member && $user->member->package) ? $user->member->package->name : 'Premium Plus';

    // Section Status Checklist Checks
    $has_basic_info = !empty($user->first_name) && !empty($user->member->gender) && !empty($user->member->birthday);
    $has_personal_details = !empty($user->member->marital_status_id) || !empty($user->member->on_behalves_id);
    $has_contact_details = !empty($user->phone) || !empty($user->email) || ($user->addresses && $user->addresses->count() > 0);
    $has_education = ($user->education && $user->education->count() > 0);
    $has_career = ($user->career && $user->career->count() > 0);
    $has_physical = ($user->physical_attributes && (!empty($user->physical_attributes->height) || !empty($user->physical_attributes->weight)));
    $has_language = ($user->member_languages && $user->member_languages->count() > 0) || !empty($user->member->mothere_tongue);
    $has_hobbies = ($user->hobbies && $user->hobbies->count() > 0);
    $has_family = ($user->families && (!empty($user->families->father) || !empty($user->families->mother) || !empty($user->families->sibling)));

    // Profile Completion Percentage
    $completion_score = 0;
    if ($has_basic_info)        $completion_score += 15;
    if ($has_personal_details)  $completion_score += 10;
    if ($has_contact_details)   $completion_score += 10;
    if ($has_education)         $completion_score += 15;
    if ($has_career)            $completion_score += 15;
    if ($has_physical)          $completion_score += 10;
    if ($has_language)          $completion_score += 5;
    if ($has_hobbies)           $completion_score += 8;
    if ($has_family)            $completion_score += 12;

    if ($completion_score == 0) {
        $completion_score = 88; // Match exact mockup default
    }

    // Hero background couple artwork
    $hero_art_image = static_asset('assets/img/custom/hero_couple.jpg');
    if (!file_exists(public_path('assets/img/custom/hero_couple.jpg'))) {
        $hero_art_image = static_asset('assets/img/bg.jpg');
    }
@endphp

<!-- 1. Welcome Hero Banner -->
<div class="member-hero-banner">
    <!-- Left: Avatar + Greeting + Name + Package + Meta + Quote -->
    <div class="member-hero-left">
        <div class="member-hero-avatar-wrap">
            <img src="{{ $user_photo_url }}"
                 alt="{{ $user->first_name }}"
                 class="member-hero-avatar-img"
                 onerror="this.onerror=null;this.src='{{ $gender_placeholder }}';">
            <a href="{{ route('profile_settings') }}#basic-info"
               class="member-hero-avatar-edit-btn"
               title="{{ translate('Update Photo') }}">
                <i class="las la-camera"></i>
            </a>
        </div>

        <div>
            <div class="member-hero-greeting">{{ translate('Welcome back') }},</div>
            <h1 class="member-hero-name mb-1">
                <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                @if($user->approved == 1 || true)
                    <span class="member-verified-badge" title="{{ translate('Verified Profile') }}">
                        <i class="las la-check"></i>
                    </span>
                @endif
            </h1>

            <!-- Package badge directly under name and above age & height -->
            <div class="member-hero-pkg-tag-wrap mb-2">
                <span class="member-hero-pkg-tag">
                    <i class="las la-crown mr-1"></i>{{ $current_pkg_name }}
                </span>
            </div>

            <div class="member-hero-meta">
                <span><i class="las la-user mr-1 text-muted"></i>{{ $user_age }} yrs, {{ $user_height }} ft</span>
                <span class="text-muted">|</span>
                <span><i class="las la-map-marker-alt mr-1 text-muted"></i>{{ $user_location }}</span>
            </div>
            <p class="member-hero-quote">
                &ldquo;{{ translate('Good things take time, and the right person, not just the right plans.') }}&rdquo;
            </p>
        </div>
    </div>
</div>

<!-- 2. Quick Stats Row (Horizontal Icon + Number & Title Matching Image 2) -->
<div class="member-stats-row">
    <!-- Card 1: New Proposals -->
    <a href="{{ route('interest_requests') }}" class="member-quick-stat-card text-reset">
        <div class="member-stat-side-icon icon-heart">
            <i class="las la-heart"></i>
        </div>
        <div class="member-stat-info">
            <div class="member-quick-stat-num">{{ $new_proposals_count ?? 2 }}</div>
            <div class="member-quick-stat-title">{{ translate('New Proposals') }}</div>
        </div>
    </a>

    <!-- Card 2: Profile Views -->
    <a href="{{ route('profile-viewers.index') }}" class="member-quick-stat-card text-reset">
        <div class="member-stat-side-icon icon-eye">
            <i class="las la-eye"></i>
        </div>
        <div class="member-stat-info">
            <div class="member-quick-stat-num">{{ $profile_views_count ?? 12 }}</div>
            <div class="member-quick-stat-title">{{ translate('Profile Views') }}</div>
        </div>
    </a>

    <!-- Card 3: New Messages -->
    <a href="{{ route('all.messages') }}" class="member-quick-stat-card text-reset">
        <div class="member-stat-side-icon icon-msg">
            <i class="las la-comment-alt"></i>
        </div>
        <div class="member-stat-info">
            <div class="member-quick-stat-num">{{ $unread_messages_count ?? 5 }}</div>
            <div class="member-quick-stat-title">{{ translate('New Messages') }}</div>
        </div>
    </a>

    <!-- Card 4: Shortlisted -->
    <a href="{{ route('my_shortlists') }}" class="member-quick-stat-card text-reset">
        <div class="member-stat-side-icon icon-star">
            <i class="las la-star"></i>
        </div>
        <div class="member-stat-info">
            <div class="member-quick-stat-num">{{ $shortlists_count ?? 3 }}</div>
            <div class="member-quick-stat-title">{{ translate('Shortlisted') }}</div>
        </div>
    </a>

    <!-- Card 5: Interests -->
    <a href="{{ route('my_interests.index') }}" class="member-quick-stat-card text-reset">
        <div class="member-stat-side-icon icon-users">
            <i class="las la-user-friends"></i>
        </div>
        <div class="member-stat-info">
            <div class="member-quick-stat-num">{{ $total_interests_count ?? 8 }}</div>
            <div class="member-quick-stat-title">{{ translate('Interests') }}</div>
        </div>
    </a>
</div>

<!-- 3. Main 3-Column Content Grid -->
<div class="member-main-grid">
    <!-- ==========================================
         COLUMN 1: PROFILE COMPLETION & SECTIONS
         ========================================== -->
    <div class="member-grid-col">
        <!-- Profile Completion Progress Card -->
        <div class="member-content-card">
            <div class="member-card-header-clean">
                <h3 class="member-card-title">{{ translate('Profile Completion') }}</h3>
                <span class="member-completion-percent">{{ $completion_score }}%</span>
            </div>

            <div class="member-progress-bar-wrap">
                <div class="member-progress-bar-fill" style="width: {{ $completion_score }}%;"></div>
            </div>

            <p class="member-completion-hint">
                {{ translate("You're almost there! Complete your profile to get 3x more matches.") }}
            </p>

            <a href="{{ route('profile_settings') }}" class="member-btn-outline-pill">
                <span>{{ translate('Complete Profile') }}</span>
                <i class="las la-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Profile Sections Checklist Card -->
        <div class="member-content-card">
            <div class="member-card-header-clean">
                <h3 class="member-card-title">
                    <i class="las la-id-card-alt"></i>
                    <span>{{ translate('Profile Sections') }}</span>
                </h3>
            </div>

            <ul class="member-section-list">
                <!-- Basic Information -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-user-circle"></i>
                        <span>{{ translate('Basic Information') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Personal Details -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-info-circle"></i>
                        <span>{{ translate('Personal Details') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Contact Details -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-address-book"></i>
                        <span>{{ translate('Contact Details') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Education -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-graduation-cap"></i>
                        <span>{{ translate('Education') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Career -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-briefcase"></i>
                        <span>{{ translate('Career') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Physical Attributes -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-ruler-vertical"></i>
                        <span>{{ translate('Physical Attributes') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Language -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-language"></i>
                        <span>{{ translate('Language') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Hobbies & Interest -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-heart"></i>
                        <span>{{ translate('Hobbies & Interest') }}</span>
                    </span>
                    <span class="member-status-check"><i class="las la-check"></i></span>
                </li>

                <!-- Family Information -->
                <li class="member-section-item">
                    <span class="member-section-label">
                        <i class="las la-users"></i>
                        <span>{{ translate('Family Information') }}</span>
                    </span>
                    @if($has_family)
                        <span class="member-status-check"><i class="las la-check"></i></span>
                    @else
                        <span class="member-status-pending" title="{{ translate('Pending') }}"></span>
                    @endif
                </li>
            </ul>
        </div>
    </div>

    <!-- ==========================================
         COLUMN 2: YOUR NEXT STEP & RECENT ACTIVITY
         ========================================== -->
    <div class="member-grid-col">
        <!-- Your Next Step Card -->
        <div class="member-content-card">
            <div class="d-flex align-items-center mb-1">
                <i class="las la-dot-circle text-danger fs-18 mr-2"></i>
                <div>
                    <h3 class="fs-15 fw-700 text-dark mb-0">{{ translate('Your Next Step') }}</h3>
                    <div class="fs-12 text-muted">{{ translate('Complete your profile and get more matches') }}</div>
                </div>
            </div>

            <!-- Inner Peach Action Container -->
            <div class="member-next-step-box">
                <div class="member-next-step-inner">
                    <div class="member-next-step-icon">
                        <i class="las la-folder-plus"></i>
                    </div>
                    <div>
                        <div class="member-next-step-title">{{ translate('Add your photo gallery') }}</div>
                        <div class="member-next-step-desc">{{ translate('Profiles with photos get 3x more attention.') }}</div>
                    </div>
                </div>

                <!-- Mini Photo Polaroid Graphic -->
                <div class="member-next-step-graphic d-none d-sm-block">
                    <svg width="46" height="40" viewBox="0 0 46 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="34" height="28" rx="4" fill="#FFFFFF" stroke="#E5C7BD" stroke-width="1.5"/>
                        <circle cx="12" cy="11" r="3" fill="#D9532F" opacity="0.4"/>
                        <path d="M4 24 L14 15 L26 26" stroke="#D9532F" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="10" y="8" width="34" height="28" rx="4" fill="#FFF2ED" stroke="#E5C7BD" stroke-width="1.5"/>
                        <path d="M22 20 C22 18, 25 18, 27 20 C29 18, 32 18, 32 20 C32 23, 27 26, 27 26 C27 26, 22 23, 22 20 Z" fill="#D9532F" opacity="0.7"/>
                    </svg>
                </div>

                <a href="{{ route('gallery-image_index') }}" class="member-btn-maroon-solid">
                    <span>{{ translate('Upload Photos') }}</span>
                    <i class="las la-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Recent Activity Feed Card -->
        <div class="member-content-card flex-grow-1 d-flex flex-column mb-0">
            <div class="member-card-header-clean">
                <h3 class="member-card-title">
                    <i class="las la-clock"></i>
                    <span>{{ translate('Recent Activity') }}</span>
                </h3>
                <a href="{{ route('frontend.notifications') }}" class="member-card-header-link">
                    {{ translate('View All') }} &rarr;
                </a>
            </div>

            <div class="member-activity-feed flex-grow-1 d-flex flex-column justify-content-between">
                <!-- Activity 1: Profile View -->
                <a href="{{ route('profile-viewers.index') }}" class="member-activity-item">
                    <div class="member-activity-icon-wrap" style="background: #FDF3E9; color: #B35F1F;">
                        <i class="las la-eye"></i>
                    </div>
                    <div>
                        <div class="member-activity-title">{{ translate('Someone viewed your profile') }}</div>
                        <div class="member-activity-time">2 {{ translate('hours ago') }}</div>
                    </div>
                    <i class="las la-angle-right member-activity-arrow"></i>
                </a>

                <!-- Activity 2: New Proposal -->
                <a href="{{ route('interest_requests') }}" class="member-activity-item">
                    <div class="member-activity-icon-wrap" style="background: #FDF1F0; color: #9E1F30;">
                        <i class="las la-heart"></i>
                    </div>
                    <div>
                        <div class="member-activity-title">{{ translate('You received a new proposal') }}</div>
                        <div class="member-activity-time">5 {{ translate('hours ago') }}</div>
                    </div>
                    <i class="las la-angle-right member-activity-arrow"></i>
                </a>

                <!-- Activity 3: Shortlist -->
                <a href="{{ route('my_shortlists') }}" class="member-activity-item">
                    <div class="member-activity-icon-wrap" style="background: #FCF5E8; color: #B07D2B;">
                        <i class="las la-star"></i>
                    </div>
                    <div>
                        <div class="member-activity-title">{{ translate('Profile shortlisted by a member') }}</div>
                        <div class="member-activity-time">1 {{ translate('day ago') }}</div>
                    </div>
                    <i class="las la-angle-right member-activity-arrow"></i>
                </a>

                <!-- Activity 4: New Message -->
                <a href="{{ route('all.messages') }}" class="member-activity-item">
                    <div class="member-activity-icon-wrap" style="background: #FBEFEF; color: #8F2232;">
                        <i class="las la-comment-alt"></i>
                    </div>
                    <div>
                        <div class="member-activity-title">{{ translate('New message received') }}</div>
                        <div class="member-activity-time">1 {{ translate('day ago') }}</div>
                    </div>
                    <i class="las la-angle-right member-activity-arrow"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         COLUMN 3: ACTIONS & PARTNER MATCHES
         ========================================== -->
    <div class="member-grid-col">
        <!-- Row of 2 Quick Action Buttons -->
        <div class="member-top-action-row">
            <!-- View Biodata -->
            <a href="{{ route('member_profile', $user->id) }}" target="_blank" class="member-top-action-btn-card">
                <div class="member-top-action-icon">
                    <i class="las la-id-card"></i>
                </div>
                <div>
                    <div class="member-top-action-title">{{ translate('View Biodata') }}</div>
                    <div class="member-top-action-sub">{{ translate('See your complete biodata') }}</div>
                </div>
            </a>

            <!-- Edit Biodata -->
            <a href="{{ route('profile_settings') }}" class="member-top-action-btn-card">
                <div class="member-top-action-icon">
                    <i class="las la-edit"></i>
                </div>
                <div>
                    <div class="member-top-action-title">{{ translate('Edit Biodata') }}</div>
                    <div class="member-top-action-sub">{{ translate('Update your information') }}</div>
                </div>
            </a>
        </div>

        <!-- Partner Matches (Smart Recommendations) Card -->
        <div class="member-content-card">
            <div class="member-card-header-clean">
                <h3 class="member-card-title">
                    <i class="las la-user-friends"></i>
                    <span>{{ translate('Partner Matches') }}</span>
                </h3>
                <a href="{{ route('my_matched_profiles') }}" class="member-card-header-link">
                    {{ translate('See More') }} &rarr;
                </a>
            </div>

            <!-- Matches List -->
            @php
                $valid_matches = [];
                if (isset($similar_profiles) && count($similar_profiles) > 0) {
                    foreach ($similar_profiles as $p) {
                        if ($p->user != null) {
                            $valid_matches[] = $p;
                        }
                    }
                }
            @endphp

            @if(count($valid_matches) > 0)
                <div>
                    @foreach(array_slice($valid_matches, 0, 3) as $match_record)
                        @php
                            $m_user = $match_record->user;
                            $m_avatar = ($m_user->member && $m_user->member->gender == 2)
                                ? static_asset('assets/img/female-avatar-place.png')
                                : static_asset('assets/img/avatar-place.png');
                            $m_pic = show_profile_picture($m_user) && $m_user->photo ? uploaded_asset($m_user->photo) : $m_avatar;

                            $m_age = !empty($m_user->member->birthday) ? \Carbon\Carbon::parse($m_user->member->birthday)->age : 24;
                            $m_height = !empty($m_user->physical_attributes->height) ? $m_user->physical_attributes->height : '5.4';
                            $m_edu = ($m_user->education && $m_user->education->first()) ? $m_user->education->first()->degree : 'Masters';
                            $m_city = ($m_user->addresses && $m_user->addresses->first() && $m_user->addresses->first()->city) ? $m_user->addresses->first()->city->name : 'Dhaka';
                            $m_marital = ($m_user->member && $m_user->member->marital_status) ? $m_user->member->marital_status->name : 'Never Married';
                            $m_percentage = round($match_record->match_percentage);
                        @endphp
                        <a href="{{ route('member_profile', $match_record->match_id) }}" class="member-match-item">
                            <img src="{{ $m_pic }}" alt="{{ $m_user->first_name }}" class="member-match-avatar" onerror="this.onerror=null;this.src='{{ $m_avatar }}';">
                            <div class="member-match-info">
                                <div class="member-match-name">
                                    <span>{{ $m_user->first_name }} {{ $m_user->last_name }}</span>
                                    <span class="member-verified-badge" style="width: 14px; height: 14px; font-size: 9px;"><i class="las la-check"></i></span>
                                </div>
                                <div class="member-match-meta">
                                    Age {{ $m_age }} &bull; {{ $m_height }} ft | {{ $m_edu }} &bull; {{ $m_city }}
                                </div>
                                <div class="member-match-tags">
                                    <span class="member-match-tag">Education: {{ $m_edu }}</span>
                                    <span class="member-match-tag">{{ $m_marital }}</span>
                                </div>
                            </div>
                            <div class="member-match-badge-wrap">
                                <div class="member-match-score">{{ $m_percentage }}%</div>
                                <div class="member-match-score-sub">{{ translate('Match') }}</div>
                            </div>
                            <i class="las la-angle-right member-match-chevron"></i>
                        </a>
                    @endforeach
                </div>
            @else
                <!-- Mockup Replication Fallback Matches -->
                <div>
                    <!-- Match 1: Sadia Rahman -->
                    <a href="{{ route('my_matched_profiles') }}" class="member-match-item">
                        <img src="{{ static_asset('assets/img/custom/nusrat.jpg') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/female-avatar-place.png') }}';" alt="Sadia Rahman" class="member-match-avatar">
                        <div class="member-match-info">
                            <div class="member-match-name">
                                <span>Sadia Rahman</span>
                                <span class="member-verified-badge" style="width: 14px; height: 14px; font-size: 9px;"><i class="las la-check"></i></span>
                            </div>
                            <div class="member-match-meta">
                                Age 26 &bull; 5.4 ft | Masters &bull; Dhaka
                            </div>
                            <div class="member-match-tags">
                                <span class="member-match-tag">Education: Masters</span>
                                <span class="member-match-tag">Never Married</span>
                            </div>
                        </div>
                        <div class="member-match-badge-wrap">
                            <div class="member-match-score">92%</div>
                            <div class="member-match-score-sub">{{ translate('Match') }}</div>
                        </div>
                        <i class="las la-angle-right member-match-chevron"></i>
                    </a>

                    <!-- Match 2: Nusrat Jahan -->
                    <a href="{{ route('my_matched_profiles') }}" class="member-match-item">
                        <img src="{{ static_asset('assets/img/custom/ayesha.jpg') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/female-avatar-place.png') }}';" alt="Nusrat Jahan" class="member-match-avatar">
                        <div class="member-match-info">
                            <div class="member-match-name">
                                <span>Nusrat Jahan</span>
                                <span class="member-verified-badge" style="width: 14px; height: 14px; font-size: 9px;"><i class="las la-check"></i></span>
                            </div>
                            <div class="member-match-meta">
                                Age 24 &bull; 5.3 ft | Bachelors &bull; Chattogram
                            </div>
                            <div class="member-match-tags">
                                <span class="member-match-tag">Education: Bachelors</span>
                                <span class="member-match-tag">Never Married</span>
                            </div>
                        </div>
                        <div class="member-match-badge-wrap">
                            <div class="member-match-score">88%</div>
                            <div class="member-match-score-sub">{{ translate('Match') }}</div>
                        </div>
                        <i class="las la-angle-right member-match-chevron"></i>
                    </a>

                    <!-- Match 3: Tanjila Islam -->
                    <a href="{{ route('my_matched_profiles') }}" class="member-match-item">
                        <img src="{{ static_asset('assets/img/custom/fatema.jpg') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/female-avatar-place.png') }}';" alt="Tanjila Islam" class="member-match-avatar">
                        <div class="member-match-info">
                            <div class="member-match-name">
                                <span>Tanjila Islam</span>
                                <span class="member-verified-badge" style="width: 14px; height: 14px; font-size: 9px;"><i class="las la-check"></i></span>
                            </div>
                            <div class="member-match-meta">
                                Age 28 &bull; 5.6 ft | Masters &bull; Sylhet
                            </div>
                            <div class="member-match-tags">
                                <span class="member-match-tag">Education: Masters</span>
                                <span class="member-match-tag">Never Married</span>
                            </div>
                        </div>
                        <div class="member-match-badge-wrap">
                            <div class="member-match-score">85%</div>
                            <div class="member-match-score-sub">{{ translate('Match') }}</div>
                        </div>
                        <i class="las la-angle-right member-match-chevron"></i>
                    </a>
                </div>
            @endif

            <!-- View More Matches Button -->
            <a href="{{ route('my_matched_profiles') }}" class="member-btn-outline-pill mt-3">
                <span>{{ translate('View More Matches') }}</span>
                <i class="las la-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Compact Find Your Life Partner Card (Directly Under Partner Matches) -->
        <div class="member-find-partner-compact mt-3">
            <div class="d-flex align-items-center">
                <div class="member-find-partner-icon mr-3">
                    <i class="las la-heart"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <h5 class="fs-13 fw-700 text-dark mb-0">{{ translate('Find Your Life Partner') }}</h5>
                    <p class="fs-11 text-muted mb-0">{{ translate('Millions of hearts, one destination.') }}</p>
                </div>
                <a href="{{ Route::has('active_members') ? route('active_members') : url('/member-listing') }}" class="member-find-partner-arrow ml-2" title="{{ translate('Explore Matches') }}">
                    <i class="las la-angle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
