@extends('frontend.layouts.member_panel')
@section('panel_content')
    @php
        $user = Auth::user();
        $col  = 4;
        $profile_picture_privacy = get_setting('profile_picture_privacy');
        $gallery_image_privacy = get_setting('gallery_image_privacy');
        if($profile_picture_privacy == 'only_me'){
            $col++;
        }
        if($gallery_image_privacy == 'only_me') {
            $col++;
        }
    @endphp

    {{-- Top Quota Metric Cards --}}
    <div class="row gutters-10 row-cols-xl-{{ $col }} row-cols-2 mb-4">
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-heart"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_interest') }}</div>
                <div class="member-stat-label">{{ translate('Remaining Interests') }}</div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-phone"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_contact_view') }}</div>
                <div class="member-stat-label">{{ translate('Contact Info Views') }}</div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-eye"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_profile_viewer_view') }}</div>
                <div class="member-stat-label">{{ translate('Profile Viewer Views') }}</div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-images"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_photo_gallery') }}</div>
                <div class="member-stat-label">{{ translate('Gallery Uploads') }}</div>
            </div>
        </div>
        @if($profile_picture_privacy == 'only_me')
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-user-circle"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_profile_image_view') }}</div>
                <div class="member-stat-label">{{ translate('Avatar Views') }}</div>
            </div>
        </div>
        @endif
        @if($gallery_image_privacy == 'only_me')
        <div class="col mb-3">
            <div class="member-stat-card">
                <div class="member-stat-icon-wrap">
                    <i class="las la-photo-video"></i>
                </div>
                <div class="member-stat-value">{{ get_remaining_package_value($user->id,'remaining_gallery_image_view') }}</div>
                <div class="member-stat-label">{{ translate('Gallery Views') }}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="row gutters-15">
        {{-- Left Column: Current Package Details --}}
        <div class="col-lg-6 mb-4">
            <div class="member-package-card h-100">
                <div class="member-package-header d-flex justify-content-between align-items-center">
                    <h2 class="fs-16 fw-700 mb-0 text-dark">
                        <i class="las la-crown text-warning mr-1"></i>{{ translate('Current Membership') }}
                    </h2>
                    @if ($user->member->current_package_id != null && package_validity($user->id))
                        <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                    @else
                        <span class="badge badge-inline badge-danger">{{ translate('Expired / Inactive') }}</span>
                    @endif
                </div>

                @if ($user->member->current_package_id != null)
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="member-package-icon-badge">
                                <i class="las la-crown"></i>
                            </div>
                            <h3 class="h5 fw-700 mb-1 text-dark">{{ $user->member->package->name }}</h3>
                            <div class="fs-13 text-muted">
                                {{ translate('Package Validity') }}:
                                @if(package_validity($user->id))
                                    <span class="fw-600 text-dark">{{ $user->member->package_validity }}</span>
                                @else
                                    <span class="text-danger fw-600">{{ translate('Expired') }}</span>
                                @endif
                            </div>
                        </div>

                        <ul class="list-group list-group-flush fs-14 mb-4 border-top border-bottom py-2">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="las la-heart text-primary mr-2"></i>{{ translate('Express Interests') }}</span>
                                <span class="fw-700 text-dark">{{ $user->member->package->express_interest }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="las la-images text-primary mr-2"></i>{{ translate('Gallery Photos') }}</span>
                                <span class="fw-700 text-dark">{{ $user->member->package->photo_gallery }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="las la-phone text-primary mr-2"></i>{{ translate('Contact Info Views') }}</span>
                                <span class="fw-700 text-dark">{{ $user->member->package->contact }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="las la-eye text-primary mr-2"></i>{{ translate('Profile Viewer Views') }}</span>
                                <span class="fw-700 text-dark">{{ $user->member->package->profile_viewers_view }}</span>
                            </li>
                            @if($profile_picture_privacy == 'only_me')
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span><i class="las la-user-circle text-primary mr-2"></i>{{ translate('Avatar Views') }}</span>
                                    <span class="fw-700 text-dark">{{ $user->member->package->profile_image_view }}</span>
                                </li>
                            @endif
                            @if($gallery_image_privacy == 'only_me')
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <span><i class="las la-photo-video text-primary mr-2"></i>{{ translate('Gallery Image Views') }}</span>
                                    <span class="fw-700 text-dark">{{ $user->member->package->gallery_image_view }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span><i class="las la-user-friends text-primary mr-2"></i>{{ translate('Auto Profile Matching') }}</span>
                                @if($user->member->package->auto_profile_match == 1)
                                    <span class="badge badge-inline badge-soft-success"><i class="las la-check mr-1"></i>{{ translate('Enabled') }}</span>
                                @else
                                    <span class="badge badge-inline badge-soft-secondary">{{ translate('Disabled') }}</span>
                                @endif
                            </li>
                        </ul>

                        <div class="text-center">
                            <a href="{{ route('packages') }}" class="btn btn-mat-gold btn-block py-2">
                                <i class="las la-rocket mr-1"></i>{{ translate('Upgrade / Renew Package') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="card-body p-5 text-center">
                        <div class="member-package-icon-badge mb-3">
                            <i class="las la-cube text-muted"></i>
                        </div>
                        <h4 class="h6 fw-700 text-dark mb-2">{{ translate('No Active Membership Package') }}</h4>
                        <p class="text-muted fs-13 mb-4">{{ translate('Upgrade to a premium package to start connecting with verified brides and grooms.') }}</p>
                        <a href="{{ route('packages') }}" class="btn btn-mat-gold px-4 py-2">
                            <i class="las la-crown mr-1"></i>{{ translate('Purchase a Package') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Biodata PDF Card + Activity Tracker + Matched Profiles --}}
        <div class="col-lg-6 mb-4">
            {{-- Biodata PDF & Verification Card --}}
            <div class="member-action-card mb-4">
                <div class="member-action-header d-flex justify-content-between align-items-center">
                    <h3 class="fs-15 fw-700 mb-0 text-dark">
                        <i class="las la-id-badge text-primary mr-1"></i>{{ translate('E-Biodata & Verification') }}
                    </h3>
                    @if ($user->approved == 1)
                        <span class="badge badge-inline badge-soft-success"><i class="las la-check-circle mr-1"></i>{{ translate('Verified') }}</span>
                    @else
                        <span class="badge badge-inline badge-soft-warning"><i class="las la-clock mr-1"></i>{{ translate('Unverified') }}</span>
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            @if ($user->approved == 1)
                                <img src="{{ static_asset('assets/img/verified.png') }}" alt="Verified" width="56" height="56">
                            @else
                                <img src="{{ static_asset('assets/img/non_verified.png') }}" alt="Non Verified" width="56" height="56">
                            @endif
                        </div>
                        <div>
                            @if ($user->approved == 1)
                                <h4 class="fs-14 fw-700 mb-1 text-dark">{{ translate('Verified Official Biodata') }}</h4>
                                <p class="fs-12 text-muted mb-0">{{ translate('Your biodata is verified and carries the trusted badge for families.') }}</p>
                            @else
                                <h4 class="fs-14 fw-700 mb-1 text-dark">{{ translate('Profile Verification Pending') }}</h4>
                                <p class="fs-12 text-muted mb-0">{{ translate('Get verified to increase proposal responses and family trust.') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row gutters-5">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                            <a href="{{ route('member_profile', $user->id) }}" target="_blank" class="btn btn-luxury-outline btn-block btn-sm py-2">
                                <i class="las la-file-download mr-1"></i>{{ translate('Download Biodata / PDF') }}
                            </a>
                        </div>
                        <div class="col-sm-6">
                            @if ($user->approved == 0 && get_setting('member_verification'))
                                <a href="{{ route('member.verification') }}" class="btn btn-mat-gold btn-block btn-sm py-2">
                                    <i class="las la-shield-alt mr-1"></i>{{ translate('Verify Profile') }}
                                </a>
                            @else
                                <a href="{{ route('profile_settings') }}" class="btn btn-mat-maroon btn-block btn-sm py-2">
                                    <i class="las la-edit mr-1"></i>{{ translate('Edit Biodata') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Proposals & Activity Tracker Widget --}}
            <div class="member-action-card mb-4">
                <div class="member-action-header d-flex justify-content-between align-items-center">
                    <h3 class="fs-15 fw-700 mb-0 text-dark">
                        <i class="las la-heartbeat text-danger mr-1"></i>{{ translate('Proposals & Matchmaking Activity') }}
                    </h3>
                    <a href="{{ route('my_interests.index') }}" class="fs-12 text-primary fw-600">
                        {{ translate('View All') }} &rarr;
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="row gutters-10">
                        <div class="col-3">
                            <a href="{{ route('my_interests.index') }}" class="proposal-metric-box">
                                <div class="proposal-metric-num">{{ $sent_interests_count ?? 0 }}</div>
                                <div class="proposal-metric-title">{{ translate('Sent') }}</div>
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('interest_requests') }}" class="proposal-metric-box">
                                <div class="proposal-metric-num">{{ $received_interests_count ?? 0 }}</div>
                                <div class="proposal-metric-title">{{ translate('Received') }}</div>
                            </a>
                        </div>
                        <div class="col-3">
                            <div class="proposal-metric-box">
                                <div class="proposal-metric-num text-success">{{ $accepted_interests_count ?? 0 }}</div>
                                <div class="proposal-metric-title">{{ translate('Connected') }}</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('my_shortlists') }}" class="proposal-metric-box">
                                <div class="proposal-metric-num">{{ $shortlists_count ?? 0 }}</div>
                                <div class="proposal-metric-title">{{ translate('Shortlist') }}</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Matched Profiles Widget --}}
            @if (Auth::user()->member->current_package_id != null)
                <div class="member-action-card">
                    @php
                        $canRefresh = true;
                        if(Auth::user()->match_refresh_updated_at){
                            $nextRefresh = \Carbon\Carbon::parse(Auth::user()->match_refresh_updated_at)->addMinutes(30);
                            if($nextRefresh->isFuture()){
                                $canRefresh = false;
                            }
                        }
                    @endphp
                    <div class="member-action-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h3 class="fs-15 fw-700 mb-0 mr-2 text-dark">
                                <i class="las la-user-check text-primary mr-1"></i>{{ translate('Partner Matches') }}
                            </h3>
                            @if (Auth::user()->member->auto_profile_match == 1)
                                @if($canRefresh)
                                    <a href="{{ route('match.refresh') }}"
                                       class="btn btn-soft-primary btn-icon btn-circle btn-xs"
                                       title="{{ translate('Refresh Matches') }}">
                                        <i class="las la-sync"></i>
                                    </a>
                                @else
                                    <button class="btn btn-soft-secondary btn-icon btn-circle btn-xs"
                                            disabled
                                            title="{{ translate('You can refresh every 30 minutes') }}">
                                        <i class="las la-sync"></i>
                                    </button>
                                @endif
                            @endif
                        </div>
                        <a href="{{ route('my_matched_profiles') }}" class="fs-12 text-primary fw-600">
                            {{ translate('See More') }} &rarr;
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @if(Auth::user()->member->auto_profile_match == 1)
                            <div class="matched-profiles-scroll-list" style="max-height: 380px; overflow-y: auto;">
                                @forelse ($similar_profiles->shuffle() as $similar_profile)
                                    @if($similar_profile->user != null)
                                        @php
                                            $matched_u = $similar_profile->user;
                                            $avatar_image = ($matched_u->member && $matched_u->member->gender == 2)
                                                ? 'assets/img/female-avatar-place.png'
                                                : 'assets/img/avatar-place.png';
                                            $show_pic = show_profile_picture($matched_u);
                                            $pic_url = ($show_pic && $matched_u->photo) ? uploaded_asset($matched_u->photo) : null;
                                            if (!$pic_url) {
                                                $pic_url = static_asset($avatar_image);
                                            }
                                        @endphp
                                        <a href="{{ route('member_profile', $similar_profile->match_id) }}"
                                           class="matched-profile-item row no-gutters align-items-center mb-2 p-2">
                                            <div class="col-auto">
                                                <img src="{{ $pic_url }}"
                                                     onerror="this.onerror=null;this.src='{{ static_asset($avatar_image) }}';"
                                                     class="matched-profile-avatar rounded"
                                                     alt="{{ $matched_u->first_name }}">
                                            </div>
                                            <div class="col pl-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h5 class="fs-14 fw-700 text-dark mb-0 text-truncate">
                                                        {{ $matched_u->first_name . ' ' . $matched_u->last_name }}
                                                    </h5>
                                                    <span class="badge badge-inline badge-soft-primary fs-11">
                                                        {{ round($similar_profile->match_percentage) }}% {{ translate('Match') }}
                                                    </span>
                                                </div>
                                                <div class="fs-12 text-muted text-truncate-2">
                                                    @if(!empty($matched_u->member->birthday))
                                                        <span>{{ \Carbon\Carbon::parse($matched_u->member->birthday)->age }} {{ translate('yrs') }}</span> &bull;
                                                    @endif
                                                    @if(!empty($matched_u->physical_attributes->height))
                                                        <span>{{ $matched_u->physical_attributes->height }} {{ translate('ft') }}</span> &bull;
                                                    @endif
                                                    @if(!empty($matched_u->spiritual_backgrounds->religion->name))
                                                        <span>{{ $matched_u->spiritual_backgrounds->religion->name }}</span> &bull;
                                                    @endif
                                                    @if(!empty($matched_u->member->marital_status->name))
                                                        <span>{{ $matched_u->member->marital_status->name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @empty
                                    <div class="text-center py-4 px-2" style="background: var(--mat-bg-cream); border-radius: 8px;">
                                        <i class="las la-sliders-h text-muted la-2x mb-2"></i>
                                        <h5 class="fs-14 fw-600 text-dark mb-1">{{ translate('No Matched Profiles Found Yet') }}</h5>
                                        <p class="fs-12 text-muted mb-3">{{ translate('Set your partner expectations to get personalized auto recommendations.') }}</p>
                                        <a href="{{ route('profile_settings') }}#partner_expectation" class="btn btn-sm btn-mat-gold">
                                            <i class="las la-sliders-h mr-1"></i>{{ translate('Set Partner Preferences') }} &rarr;
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="text-center py-4 px-2" style="background: var(--mat-bg-cream); border-radius: 8px;">
                                <i class="las la-crown text-warning la-2x mb-2"></i>
                                <h5 class="fs-14 fw-600 text-dark mb-1">{{ translate('Auto Matchmaking Available in Premium') }}</h5>
                                <p class="fs-12 text-muted mb-3">{{ translate('Upgrade your package to unlock intelligent partner matchmaking.') }}</p>
                                <a href="{{ route('packages') }}" class="btn btn-sm btn-mat-gold">
                                    {{ translate('Upgrade Package') }} &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

