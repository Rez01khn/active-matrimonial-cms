@extends('frontend.layouts.app')

@section('content')

<!-- Brand Typography & Styling -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">

<style>
    :root {
        --mat-maroon: #4A1521;
        --mat-maroon-dark: #2C0A12;
        --mat-gold: #967240;
        --mat-gold-light: #C5A059;
        --mat-gold-bg: #FAF6F1;
        --mat-border: #ECE7E1;
        --mat-font-serif: 'Playfair Display', Georgia, serif;
        --mat-font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .plans-hero-section {
        position: relative;
        background-image: url('{{ static_asset('assets/img/custom/plans_hero_couple.jpg') }}');
        background-repeat: no-repeat !important;
        background-size: cover !important;
        background-position: center center !important;
        padding: 65px 0 60px;
        border-bottom: 1px solid var(--mat-border);
        overflow: hidden;
    }
    .plans-hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(255, 253, 249, 0.94) 0%, rgba(255, 253, 249, 0.88) 45%, rgba(255, 253, 249, 0.50) 100%);
        z-index: 1;
    }
    .plans-hero-title {
        font-family: var(--mat-font-serif);
        font-size: 38px;
        font-weight: 700;
        color: var(--mat-maroon);
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    .plans-hero-subtitle {
        font-size: 15px;
        color: #64748B;
        line-height: 1.6;
        max-width: 520px;
    }


    /* Luxury Pricing Card */
    .lux-card {
        background: #FFFFFF;
        border: 1px solid var(--mat-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(74, 21, 33, 0.04);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
    }
    .lux-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(74, 21, 33, 0.12);
        border-color: #D8CEBE;
    }

    /* Card Top Visual Showcase Header */
    .lux-card-header {
        padding: 24px 22px 18px;
        position: relative;
        color: #FFFFFF;
        min-height: 175px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    .lux-header-theme-royal {
        background: linear-gradient(135deg, #2D0913 0%, #150307 100%);
    }
    .lux-header-theme-gold {
        background: linear-gradient(135deg, #2F1A08 0%, #170B03 100%);
    }
    .lux-header-theme-diamond {
        background: linear-gradient(135deg, #1C2B3A 0%, #0B131C 100%);
    }
    .lux-header-theme-pearl {
        background: linear-gradient(135deg, #2C201A 0%, #17100D 100%);
    }

    /* Floating Visual Art in Header */
    .lux-art-wrap {
        position: absolute;
        right: -10px;
        bottom: -5px;
        width: 140px;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.92;
        pointer-events: none;
        transition: transform 0.3s ease;
    }
    .lux-card:hover .lux-art-wrap {
        transform: scale(1.08) rotate(2deg);
    }
    .lux-art-img {
        max-width: 130px;
        max-height: 130px;
        object-fit: contain;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.45));
    }

    .lux-badge-pill {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #FAF5EB;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
    }
    .lux-badge-gold {
        background: rgba(197, 160, 89, 0.3);
        border-color: rgba(197, 160, 89, 0.6);
        color: #FDE68A;
    }

    .lux-pkg-title {
        font-family: var(--mat-font-serif);
        font-size: 22px;
        font-weight: 700;
        color: #FFFFFF;
        margin: 4px 0 2px;
        line-height: 1.2;
    }
    .lux-pkg-subtitle {
        font-size: 11.5px;
        opacity: 0.8;
        margin-bottom: 12px;
        line-height: 1.3;
    }
    .lux-price-pill {
        display: inline-flex;
        align-items: baseline;
        gap: 6px;
        background: rgba(0, 0, 0, 0.35);
        padding: 4px 12px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        width: fit-content;
        z-index: 2;
    }
    .lux-price-val {
        font-size: 18px;
        font-weight: 800;
        color: #FFFFFF;
    }
    .lux-price-period {
        font-size: 11px;
        opacity: 0.8;
        font-weight: 500;
    }

    /* 2x2 Metric Grid */
    .lux-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-bottom: 18px;
    }
    .lux-stat-box {
        background: #FAF6F0;
        border: 1px solid #ECE4D8;
        border-radius: 12px;
        padding: 10px 8px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .lux-stat-box:hover {
        background: #F5EEDB;
        border-color: #DFD2BE;
    }
    .lux-stat-label {
        font-size: 10.5px;
        color: #78716C;
        font-weight: 600;
        text-transform: capitalize;
        display: block;
        margin-bottom: 2px;
    }
    .lux-stat-value {
        font-size: 15px;
        font-weight: 800;
        color: #292524;
        line-height: 1.1;
    }

    /* Key Benefits List */
    .lux-benefits-title {
        font-size: 12.5px;
        font-weight: 700;
        color: #1C1917;
        margin-bottom: 10px;
        letter-spacing: -0.2px;
    }
    .lux-benefits-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }
    .lux-benefits-item {
        display: flex;
        align-items: flex-start;
        font-size: 12px;
        color: #44403C;
        line-height: 1.45;
        margin-bottom: 8px;
    }
    .lux-benefits-icon {
        color: #16A34A;
        font-size: 15px;
        margin-right: 8px;
        margin-top: 1px;
        flex-shrink: 0;
    }

    /* CTA Buttons */
    .btn-lux-primary {
        background: linear-gradient(135deg, #B8860B 0%, #D4AF37 50%, #996515 100%);
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 13.5px;
        border-radius: 999px;
        padding: 10px 20px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none !important;
        box-shadow: 0 4px 14px rgba(184, 134, 11, 0.28);
        border: none;
        transition: all 0.25s ease;
    }
    .btn-lux-primary:hover {
        background: linear-gradient(135deg, #C59A1E 0%, #E0BE4C 50%, #A8731F 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(184, 134, 11, 0.38);
        color: #FFFFFF !important;
    }
    .btn-lux-outline {
        background: #FFFFFF;
        color: #292524 !important;
        font-weight: 600;
        font-size: 12.5px;
        border-radius: 999px;
        padding: 8px 18px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none !important;
        border: 1px solid #DFD7CD;
        margin-top: 8px;
        transition: all 0.2s ease;
    }
    .btn-lux-outline:hover {
        background: #FAF8F5;
        border-color: var(--mat-gold);
        color: var(--mat-maroon) !important;
    }
</style>

<!-- 1. Page Header & Hero Banner -->
<section class="plans-hero-section">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-10 text-center text-md-left">
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center list-unstyled p-0 mb-2 fs-12" style="gap: 6px;">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">{{ translate('Home') }}</a></li>
                        <li class="text-muted">›</li>
                        <li class="text-dark font-weight-700">{{ translate('Membership Plans') }}</li>
                    </ul>
                </nav>
                <h1 class="plans-hero-title mb-2">{{ translate('Membership Plans') }}</h1>
                <p class="plans-hero-subtitle mb-0">
                    {{ translate('Choose the perfect plan that fits your journey to find love and build a beautiful future together.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 2. High-Converting Luxury Pricing Cards Grid -->
@php
    $total_packages = $packages->count();
    $current_pkg_id = (auth()->check() && auth()->user()->member) ? auth()->user()->member->current_package_id : null;

    // Artwork & Theme metadata matching user reference image
    $artworks = [
        0 => [
            'theme' => 'lux-header-theme-royal',
            'badge' => 'VIP Royal',
            'icon' => 'las la-crown',
            'subtitle' => 'The Ultimate Matchmaking Experience',
        ],
        1 => [
            'theme' => 'lux-header-theme-gold',
            'badge' => 'Premium Match',
            'icon' => 'las la-ring',
            'subtitle' => 'Premium Matchmaking Perfected',
        ],
        2 => [
            'theme' => 'lux-header-theme-diamond',
            'badge' => 'Best Value',
            'icon' => 'las la-gem',
            'subtitle' => 'Elegant & Powerful Matching',
        ],
        3 => [
            'theme' => 'lux-header-theme-pearl',
            'badge' => 'Smart Choice',
            'icon' => 'las la-certificate',
            'subtitle' => 'Reliable & Flexible Matching',
        ],
    ];
@endphp

<div class="container pt-5 pb-5 mb-4">
    <div class="row justify-content-center">
        @forelse ($packages as $index => $package)
            @php
                $is_current = ($current_pkg_id == $package->id);
                $is_free = ($package->price == 0 || $package->id == 1);

                $art_conf = $artworks[$index % count($artworks)];
                $header_theme = $art_conf['theme'];
                $header_badge = $art_conf['badge'];
                $header_icon = $art_conf['icon'];
                $header_subtitle = $art_conf['subtitle'];

                if ($is_free) {
                    $header_badge = translate('Free Starter');
                    $header_subtitle = translate('Get started with basic matching');
                } elseif ($package->price > 50000) {
                    $header_badge = translate('VIP Royal');
                }

                // Grid layout responsive calculation
                $col_classes = 'col-xl-3 col-lg-4 col-md-6 col-12 mb-4 d-flex align-items-stretch';
                if ($total_packages == 3) {
                    $col_classes = 'col-xl-4 col-lg-4 col-md-6 col-12 mb-4 d-flex align-items-stretch';
                } elseif ($total_packages == 2) {
                    $col_classes = 'col-xl-5 col-lg-6 col-md-6 col-12 mb-4 d-flex align-items-stretch';
                } elseif ($total_packages == 1) {
                    $col_classes = 'col-xl-5 col-lg-6 col-md-8 col-12 mb-4 d-flex align-items-stretch';
                }
            @endphp

            <div class="{{ $col_classes }}">
                <div class="lux-card w-100">
                    <div>
                        <!-- Top Showcase Header -->
                        <div class="lux-card-header {{ $header_theme }}">
                            <div class="d-flex align-items-center justify-content-between position-relative z-2">
                                <div class="d-flex align-items-center text-warning" style="font-size: 18px;">
                                    <i class="{{ $header_icon }}"></i>
                                </div>
                                <span class="lux-badge-pill {{ $index == 0 || $index == 1 ? 'lux-badge-gold' : '' }}">
                                    {{ $header_badge }}
                                </span>
                            </div>

                            <div class="position-relative z-2">
                                <h3 class="lux-pkg-title">{{ $package->name }}</h3>
                                <p class="lux-pkg-subtitle">{{ $header_subtitle }}</p>

                                <div class="lux-price-pill">
                                    <span class="lux-price-val">
                                        @if($is_free)
                                            {{ translate('Free') }}
                                        @else
                                            {{ single_price($package->price) }}
                                        @endif
                                    </span>
                                    <span class="lux-price-period">
                                        / {{ $package->validity }} {{ translate('Days') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Floating Artwork / Package Image -->
                            <div class="lux-art-wrap">
                                @if(!empty($package->image) && uploaded_asset($package->image))
                                    <img src="{{ uploaded_asset($package->image) }}" alt="{{ $package->name }}" class="lux-art-img">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white-50" style="font-size: 80px; opacity: 0.25;">
                                        <i class="{{ $header_icon }}"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body: Stat Grid + Benefits -->
                        <div style="padding: 22px 20px;">
                            <!-- 2x2 Quick Metrics Grid -->
                            <div class="lux-stat-grid">
                                <div class="lux-stat-box">
                                    <span class="lux-stat-label">{{ translate('Proposals') }}</span>
                                    <span class="lux-stat-value" style="color: var(--mat-maroon);">{{ $package->express_interest }}</span>
                                </div>
                                <div class="lux-stat-box">
                                    <span class="lux-stat-label">{{ translate('Contacts') }}</span>
                                    <span class="lux-stat-value" style="color: var(--mat-maroon);">{{ $package->contact }}</span>
                                </div>
                                <div class="lux-stat-box">
                                    <span class="lux-stat-label">{{ translate('Support') }}</span>
                                    <span class="lux-stat-value fs-13">{{ $index == 0 ? '24/7 Dedicated' : ($index == 1 ? 'Priority 24/7' : 'Standard') }}</span>
                                </div>
                                <div class="lux-stat-box">
                                    <span class="lux-stat-label">{{ translate('Photo Upload') }}</span>
                                    <span class="lux-stat-value fs-13">{{ $package->photo_gallery }} {{ translate('Photos') }}</span>
                                </div>
                            </div>

                            <!-- Key Membership Benefits -->
                            <div class="lux-benefits-title">{{ translate('Key Membership Benefits') }}</div>
                            <ul class="lux-benefits-list">
                                <li class="lux-benefits-item">
                                    <i class="las la-check-circle lux-benefits-icon"></i>
                                    <span><strong>{{ $package->express_interest }}</strong> {{ translate('Express Interest Proposals') }}</span>
                                </li>
                                <li class="lux-benefits-item">
                                    <i class="las la-check-circle lux-benefits-icon"></i>
                                    <span><strong>{{ $package->contact }}</strong> {{ translate('Direct Contact Numbers & Profiles') }}</span>
                                </li>
                                <li class="lux-benefits-item">
                                    <i class="las la-check-circle lux-benefits-icon"></i>
                                    <span><strong>{{ $package->photo_gallery }}</strong> {{ translate('Gallery Photo Uploads') }}</span>
                                </li>
                                <li class="lux-benefits-item">
                                    <i class="las la-check-circle lux-benefits-icon"></i>
                                    <span><strong>{{ $package->profile_viewers_view }}</strong> {{ translate('Profile Viewers Access') }}</span>
                                </li>
                                @if($package->auto_profile_match == 1)
                                    <li class="lux-benefits-item">
                                        <i class="las la-check-circle lux-benefits-icon"></i>
                                        <span>{{ translate('Automated Smart Matchmaking Algorithm') }}</span>
                                    </li>
                                @endif
                                <li class="lux-benefits-item">
                                    <i class="las la-check-circle lux-benefits-icon"></i>
                                    <span>{{ translate('Enhanced Profile Ranking & Visibility') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Dual Action Buttons -->
                    <div class="p-3 border-top" style="background: #FFFDF9; border-color: var(--mat-border) !important; padding: 18px 20px !important;">
                        @auth
                            @if(auth()->user()->user_type == 'admin')
                                <a href="javascript:void(0);" onclick="package_update_alert()" class="btn-lux-primary">
                                    {{ translate('Choose') }} {{ $package->name }}
                                </a>
                            @elseif($is_free)
                                <a href="{{ route('free_package_purchase', $package->id) }}" class="btn-lux-primary">
                                    {{ translate('Get Started Free') }}
                                </a>
                            @else
                                <a href="{{ route('package_payment_methods', encrypt($package->id)) }}" class="btn-lux-primary">
                                    {{ translate('Choose') }} {{ $package->name }}
                                </a>
                            @endif
                        @else
                            <a href="javascript:void(0);" onclick="loginModal()" class="btn-lux-primary">
                                {{ translate('Choose') }} {{ $package->name }}
                            </a>
                        @endauth

                        <a href="{{ route('contact_us') }}" class="btn-lux-outline">
                            <i class="las la-headset mr-1"></i> {{ translate('Talk to Consultant') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="las la-gem text-muted fs-40 mb-2"></i>
                <h4 class="text-muted">{{ translate('No membership packages available right now.') }}</h4>
            </div>
        @endforelse
    </div>
</div>

<!-- 4. Trust Strip -->
<section class="py-5" style="background: #FFFDF9; border-top: 1px solid var(--mat-border);">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <i class="las la-shield-alt fs-30 mb-2" style="color: var(--mat-gold);"></i>
                <h6 class="font-weight-700 text-dark mb-1 fs-14">{{ translate('100% Verified Profiles') }}</h6>
                <p class="text-muted fs-12 mb-0">{{ translate('Manually screened by our team') }}</p>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <i class="las la-lock fs-30 mb-2" style="color: var(--mat-maroon);"></i>
                <h6 class="font-weight-700 text-dark mb-1 fs-14">{{ translate('Privacy Protection') }}</h6>
                <p class="text-muted fs-12 mb-0">{{ translate('You control who sees your info') }}</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="las la-handshake fs-30 mb-2" style="color: var(--mat-gold);"></i>
                <h6 class="font-weight-700 text-dark mb-1 fs-14">{{ translate('Matchmaking Sessions') }}</h6>
                <p class="text-muted fs-12 mb-0">{{ translate('Dedicated relationship consultant') }}</p>
            </div>
            <div class="col-md-3 col-6">
                <i class="las la-headset fs-30 mb-2" style="color: var(--mat-maroon);"></i>
                <h6 class="font-weight-700 text-dark mb-1 fs-14">{{ translate('24/7 Dedicated Support') }}</h6>
                <p class="text-muted fs-12 mb-0">{{ translate('Always here to assist your journey') }}</p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('modal')
    @include('modals.login_modal')
    @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">
    function loginModal(){
        $('#LoginModal').modal();
    }

    function package_update_alert(){
        $('.package_update_alert_modal').modal('show');
    }


</script>
@endsection
