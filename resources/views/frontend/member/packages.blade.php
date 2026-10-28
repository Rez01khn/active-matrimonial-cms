@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    use Carbon\Carbon;

    $member = $user->member ?? null;
    $current_package = ($member && $member->package) ? $member->package : null;
    $current_pkg_id = $member ? $member->current_package_id : null;
    $current_pkg_name = $current_package ? $current_package->name : translate('Free Member');

    $validity_date = $member ? $member->package_validity : null;
    $is_expired = true;
    $days_remaining = 0;
    $formatted_validity = translate('No Active Expiry');

    if (!empty($validity_date)) {
        try {
            $expiry = Carbon::parse($validity_date);
            $formatted_validity = $expiry->format('d M, Y');
            if ($expiry->isFuture() || $expiry->isToday()) {
                $is_expired = false;
                $days_remaining = Carbon::now()->diffInDays($expiry);
            }
        } catch (\Exception $e) {
            $is_expired = true;
        }
    }

    $rem_interests = $member ? (int) $member->remaining_interest : 0;
    $rem_contacts = $member ? (int) $member->remaining_contact_view : 0;
    $rem_gallery = $member ? (int) $member->remaining_photo_gallery : 0;
    $auto_match_on = ($member && $member->auto_profile_match == 1);

    $total_packages = $packages->count();
@endphp

<!-- 1. Page Header & Breadcrumb -->
<div class="mp-pkg-header-wrap">
    <ul class="mp-pkg-breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}" class="mp-pkg-breadcrumb-link">{{ translate('Dashboard') }}</a>
        </li>
        <li class="mp-pkg-breadcrumb-sep">›</li>
        <li class="mp-pkg-breadcrumb-active">{{ translate('Membership Packages') }}</li>
    </ul>

    <div class="mp-pkg-title-bar">
        <div>
            <h1 class="mp-pkg-page-title">{{ translate('Membership Packages') }}</h1>
            <p class="mp-pkg-page-subtitle">
                {{ translate('Manage your current membership credits or upgrade to unlock direct contact details and priority matching.') }}
            </p>
        </div>
        <div>
            <a href="{{ route('package_purchase_history') }}" class="mp-pkg-history-btn">
                <i class="las la-history"></i>
                <span>{{ translate('Purchase History') }}</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. Current Active Plan Summary Banner -->
<div class="mp-current-plan-banner">
    <div class="mp-current-plan-top">
        <div class="mp-current-plan-identity">
            <div class="mp-current-plan-icon-box">
                <i class="las la-crown"></i>
            </div>
            <div>
                <div class="mp-current-plan-eyebrow">
                    <span>{{ translate('Your Current Plan') }}</span>
                    @if(!$is_expired)
                        <span class="mp-plan-status-pill mp-plan-status-active">
                            <i class="las la-check-circle"></i> {{ translate('Active') }}
                        </span>
                    @else
                        <span class="mp-plan-status-pill mp-plan-status-expired">
                            <i class="las la-exclamation-circle"></i> {{ translate('Expired') }}
                        </span>
                    @endif
                </div>
                <h2 class="mp-current-plan-name">{{ $current_pkg_name }}</h2>
            </div>
        </div>

        <div class="mp-current-plan-validity-box">
            <i class="las la-calendar-check mp-current-plan-validity-icon"></i>
            <div>
                <span class="mp-current-plan-validity-label">{{ translate('Package Validity') }}</span>
                <span class="mp-current-plan-validity-val">
                    @if(!$is_expired)
                        {{ $formatted_validity }} ({{ $days_remaining }} {{ translate('Days Left') }})
                    @else
                        {{ $formatted_validity }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- 4 Real-Time Remaining Quota Cards -->
    <div class="mp-quota-grid">
        <div class="mp-quota-item">
            <div class="mp-quota-icon">
                <i class="las la-heart"></i>
            </div>
            <div class="mp-quota-info">
                <div class="mp-quota-val">{{ $rem_interests }}</div>
                <div class="mp-quota-label">{{ translate('Remaining Interests') }}</div>
            </div>
        </div>

        <div class="mp-quota-item">
            <div class="mp-quota-icon">
                <i class="las la-phone-volume"></i>
            </div>
            <div class="mp-quota-info">
                <div class="mp-quota-val">{{ $rem_contacts }}</div>
                <div class="mp-quota-label">{{ translate('Remaining Contact Views') }}</div>
            </div>
        </div>

        <div class="mp-quota-item">
            <div class="mp-quota-icon">
                <i class="las la-images"></i>
            </div>
            <div class="mp-quota-info">
                <div class="mp-quota-val">{{ $rem_gallery }}</div>
                <div class="mp-quota-label">{{ translate('Remaining Gallery Uploads') }}</div>
            </div>
        </div>

        <div class="mp-quota-item">
            <div class="mp-quota-icon">
                <i class="las la-user-check"></i>
            </div>
            <div class="mp-quota-info">
                <div class="mp-quota-val">
                    {{ $auto_match_on ? translate('Enabled') : translate('Disabled') }}
                </div>
                <div class="mp-quota-label">{{ translate('Auto Profile Matching') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- 3. Section Heading -->
<div class="mp-pkg-section-head">
    <div>
        <h3 class="mp-pkg-section-title">{{ translate('Choose Your Upgrade Plan') }}</h3>
        <p class="mp-pkg-section-desc">
            {{ translate('All plans include instant credit activation and full privacy control.') }}
        </p>
    </div>
</div>

<!-- 4. Tiered Membership Package Cards Grid -->
<div class="row gutters-15">
    @forelse ($packages as $index => $package)
        @php
            $is_current = ($current_pkg_id == $package->id);
            $is_free = ($package->price == 0 || $package->id == 1);

            // Determine visual tier style
            $is_vip = ($total_packages >= 3 && $index === $total_packages - 1) || stripos($package->name, 'vip') !== false || stripos($package->name, 'platinum') !== false || stripos($package->name, 'diamond') !== false;
            $is_popular = !$is_vip && (($total_packages >= 2 && $index === 1) || stripos($package->name, 'gold') !== false || stripos($package->name, 'standard') !== false);

            $card_classes = 'mp-pkg-card';
            if ($is_vip) {
                $card_classes .= ' mp-pkg-card-vip';
            } elseif ($is_popular) {
                $card_classes .= ' mp-pkg-card-popular';
            }
            if ($is_current && !$is_expired) {
                $card_classes .= ' mp-pkg-card-current';
            }

            // Tier Icon fallback
            $tier_icons = ['las la-feather-alt', 'las la-crown', 'las la-gem', 'las la-star'];
            $tier_icon = $tier_icons[$index % count($tier_icons)];

            // Tagline
            if ($is_free) {
                $tagline = translate('Basic starter access');
            } elseif ($is_vip) {
                $tagline = translate('Maximum priority & exclusive access');
            } elseif ($is_popular) {
                $tagline = translate('Best value for active matchmaking');
            } else {
                $tagline = translate('Enhanced connection features');
            }

            // Column sizing: 4 columns if 4+ packages, 3 columns if 3 packages
            $col_class = ($total_packages === 3)
                ? 'col-xl-4 col-lg-4 col-md-6 col-12 mb-4'
                : 'col-xl-3 col-lg-3 col-md-6 col-12 mb-4';
        @endphp

        <div class="{{ $col_class }}">
            <div class="{{ $card_classes }}">
                @if($is_current && !$is_expired)
                    <div class="mp-pkg-ribbon mp-pkg-ribbon-current">{{ translate('Current Plan') }}</div>
                @elseif($is_popular)
                    <div class="mp-pkg-ribbon">{{ translate('Most Popular') }}</div>
                @elseif($is_vip)
                    <div class="mp-pkg-ribbon">{{ translate('VIP Exclusive') }}</div>
                @endif

                <div>
                    <!-- Package Icon -->
                    <div class="mp-pkg-icon-wrap">
                        @if(!empty($package->image) && uploaded_asset($package->image))
                            <img src="{{ uploaded_asset($package->image) }}" alt="{{ $package->name }}" class="mp-pkg-icon-img">
                        @else
                            <i class="{{ $tier_icon }}"></i>
                        @endif
                    </div>

                    <!-- Package Title & Tagline -->
                    <h4 class="mp-pkg-name">{{ $package->name }}</h4>
                    <p class="mp-pkg-tagline">{{ $tagline }}</p>

                    <!-- Package Price & Validity -->
                    <div class="mp-pkg-price-row">
                        @if($is_free)
                            <span class="mp-pkg-price-val">{{ translate('Free') }}</span>
                        @else
                            <span class="mp-pkg-price-val">{{ single_price($package->price) }}</span>
                        @endif
                        <span class="mp-pkg-price-period">/ {{ $package->validity }} {{ translate('Days') }}</span>
                    </div>

                    <div class="mp-pkg-divider"></div>

                    <!-- Package Features List -->
                    <ul class="mp-pkg-features">
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->express_interest }}</strong> {{ translate('Express Interests') }}</span>
                        </li>
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->contact }}</strong> {{ translate('Contact Info Views') }}</span>
                        </li>
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->photo_gallery }}</strong> {{ translate('Gallery Photo Uploads') }}</span>
                        </li>
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->profile_viewers_view }}</strong> {{ translate('Profile Viewer Views') }}</span>
                        </li>

                        @if(get_setting('profile_picture_privacy') == 'only_me')
                            <li class="mp-pkg-feature-item">
                                <i class="las la-check-circle"></i>
                                <span><strong>{{ $package->profile_image_view }}</strong> {{ translate('Private Profile Photo Views') }}</span>
                            </li>
                        @endif

                        @if(get_setting('gallery_image_privacy') == 'only_me')
                            <li class="mp-pkg-feature-item">
                                <i class="las la-check-circle"></i>
                                <span><strong>{{ $package->gallery_image_view }}</strong> {{ translate('Private Gallery Views') }}</span>
                            </li>
                        @endif

                        <li class="mp-pkg-feature-item {{ $package->auto_profile_match == 0 ? 'mp-pkg-feature-disabled' : '' }}">
                            @if($package->auto_profile_match == 1)
                                <i class="las la-check-circle"></i>
                                <span>{{ translate('Show Auto Profile Match') }}</span>
                            @else
                                <i class="las la-times-circle"></i>
                                <span>{{ translate('Show Auto Profile Match') }}</span>
                            @endif
                        </li>
                    </ul>
                </div>

                <!-- Action CTA -->
                <div class="mp-pkg-action-wrap">
                    @if($is_current && !$is_expired)
                        @if($is_free)
                            <span class="btn-mp-pkg-current">
                                <i class="las la-check-circle fs-16"></i>
                                <span>{{ translate('Current Active Plan') }}</span>
                            </span>
                        @else
                            <a href="{{ route('package_payment_methods', encrypt($package->id)) }}" class="btn-mp-pkg-current" title="{{ translate('Click to renew or extend this package') }}">
                                <i class="las la-sync-alt fs-15"></i>
                                <span>{{ translate('Active Plan (Renew)') }}</span>
                            </a>
                        @endif
                    @elseif($is_free)
                        <a href="{{ route('free_package_purchase', $package->id) }}" class="btn-mp-pkg-outline">
                            <span>{{ translate('Activate Free Plan') }}</span>
                        </a>
                    @else
                        <a href="{{ route('package_payment_methods', encrypt($package->id)) }}"
                           class="{{ ($is_vip || $is_popular) ? 'btn-mp-pkg-gold' : 'btn-mp-pkg-outline' }}">
                            <span>{{ translate('Choose Plan') }}</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="mp-pkg-trust-bar text-center py-5">
                <h4 class="mp-pkg-section-title mb-2">{{ translate('No Active Packages Available') }}</h4>
                <p class="mp-pkg-section-desc">{{ translate('Please check back soon or contact support for membership assistance.') }}</p>
            </div>
        </div>
    @endforelse
</div>

<!-- 5. Trust & Benefits Bar -->
<div class="mp-pkg-trust-bar">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="mp-trust-item">
                <div class="mp-trust-icon">
                    <i class="las la-bolt"></i>
                </div>
                <div>
                    <h5 class="mp-trust-title">{{ translate('Instant Activation') }}</h5>
                    <p class="mp-trust-desc">{{ translate('Credits are added to your profile immediately upon payment.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="mp-trust-item">
                <div class="mp-trust-icon">
                    <i class="las la-shield-alt"></i>
                </div>
                <div>
                    <h5 class="mp-trust-title">{{ translate('100% Secure Payment') }}</h5>
                    <p class="mp-trust-desc">{{ translate('Protected checkout via bKash, Nagad, Cards & Bank Transfer.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 mb-md-0">
            <div class="mp-trust-item">
                <div class="mp-trust-icon">
                    <i class="las la-lock"></i>
                </div>
                <div>
                    <h5 class="mp-trust-title">{{ translate('Complete Privacy') }}</h5>
                    <p class="mp-trust-desc">{{ translate('You control who can view your photos and contact details.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="mp-trust-item">
                <div class="mp-trust-icon">
                    <i class="las la-headset"></i>
                </div>
                <div>
                    <h5 class="mp-trust-title">{{ translate('Dedicated Support') }}</h5>
                    <p class="mp-trust-desc">{{ translate('Our matchmaking support team is ready to assist you 24/7.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.confirm_modal')
    @include('modals.package_update_alert_modal')
@endsection

