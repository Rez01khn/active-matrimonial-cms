<!-- Top Bar -->
@php
    $ticker_raw = get_setting('topbar_ticker_text') ?: 'Register Today & Get 4 Free Proposals!';
    $ticker_text = trim(preg_replace('/[\x{1F300}-\x{1FAD6}\x{2600}-\x{27BF}]/u', '', $ticker_raw));
    if (empty($ticker_text)) {
        $ticker_text = 'Register Today & Get 4 Free Proposals!';
    }
    $helpline = get_setting('header_helpline', get_setting('contact_phone', get_setting('header_helpline_no', '+01 112 352 566')));
    $helpline_clean = preg_replace('/[^0-9+]/', '', $helpline);
    $facebook_link = get_setting('facebook_link') ?: '#';
    $instagram_link = get_setting('instagram_link') ?: '#';
    $youtube_link = get_setting('youtube_link') ?: '#';
@endphp
<div class="top-navbar-matrimony" style="background-color: var(--mat-maroon-dark); color: #FFFFFF; font-size: 12px; height: 38px; line-height: 38px; overflow: hidden; border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-100">
        <div class="d-flex align-items-center justify-content-between h-100">

            <!-- Center: Pure Text Running Marquee Ticker (Strictly ONE single message, zero emojis) -->
            <div class="topbar-center-marquee flex-grow-1 overflow-hidden whitespace-nowrap mr-3 position-relative" style="overflow: hidden; mask-image: linear-gradient(to right, transparent, black 1.5%, black 98.5%, transparent); -webkit-mask-image: linear-gradient(to right, transparent, black 1.5%, black 98.5%, transparent);">
                @if(Auth::check())
                    <span class="text-amber-200 text-xs font-medium tracking-wide" style="color: #FDE68A; font-size: 12px; font-weight: 500; letter-spacing: 0.025em;">
                        {{ translate('Welcome back,') }} <strong class="text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong> &middot; {{ translate('Member ID: #') . (Auth::user()->code ?: Auth::user()->id) }} @if(Auth::user()->membership == 2) <span class="badge badge-warning text-dark ml-1" style="font-size: 10px;">{{ translate('PREMIUM') }}</span> @endif
                    </span>
                @else
                    <a href="{{ route('register') }}" class="topbar-marquee-track text-decoration-none hover:[animation-play-state:paused]" style="white-space: nowrap; text-decoration: none; cursor: pointer; display: inline-block;">
                        <span class="topbar-marquee-item text-amber-200 text-xs font-medium tracking-wide" style="color: #FDE68A; font-size: 12px; font-weight: 500; letter-spacing: 0.025em;">
                            {{ $ticker_text }}
                        </span>
                    </a>
                @endif
            </div>

            <!-- Right: Dynamic Contact & Social Links -->
            <div class="topbar-right d-flex align-items-center flex-shrink-0" style="gap: 12px;">
                @if(!empty($helpline))
                    <!-- Helpline Phone -->
                    <a href="tel:{{ $helpline_clean }}" class="d-flex align-items-center text-white text-decoration-none topbar-phone-link" style="color: #FFFFFF; text-decoration: none; font-size: 12px; font-weight: 500; transition: color 0.2s ease;">
                        <span class="mr-1.5" style="margin-right: 6px; font-size: 12px;">{{ translate('Helpline') }}:</span>
                        <span class="d-inline">{{ $helpline }}</span>
                    </a>
                @endif

                <!-- Subtle 1px Divider -->
                <span class="h-3.5 w-px bg-white/20 mx-3 d-inline-block" style="height: 14px; width: 1px; background-color: rgba(255, 255, 255, 0.2); margin-left: 12px; margin-right: 12px;"></span>

                <!-- Dynamic Social Media Icons (Always visible with fallback) -->
                <div class="topbar-social-icons d-flex align-items-center" style="gap: 10px;">
                    <a href="{{ $facebook_link }}" target="{{ $facebook_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="text-white topbar-social-link" style="color: #FFFFFF; opacity: 0.9; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px;" title="Facebook">
                        <i class="lab la-facebook-f" style="font-size: 13px;"></i>
                    </a>
                    <a href="{{ $instagram_link }}" target="{{ $instagram_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="text-white topbar-social-link" style="color: #FFFFFF; opacity: 0.9; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px;" title="Instagram">
                        <i class="lab la-instagram" style="font-size: 13px;"></i>
                    </a>
                    <a href="{{ $youtube_link }}" target="{{ $youtube_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="text-white topbar-social-link" style="color: #FFFFFF; opacity: 0.9; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px;" title="YouTube">
                        <i class="lab la-youtube" style="font-size: 13px;"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Navigation Header -->
<header class="main-header-matrimony sticky-top">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                @if(get_setting('header_logo') != null && uploaded_asset(get_setting('header_logo')))
                    <img src="{{ uploaded_asset(get_setting('header_logo')) }}" alt="{{ env('APP_NAME') }}" class="h-40px">
                @else
                    <div class="d-flex align-items-center">
                        <div class="mr-2 d-flex align-items-center justify-content-center bg-mat-maroon rounded-circle text-white size-40px">
                            <i class="las la-heart fs-24 text-mat-gold-light"></i>
                        </div>
                        <div>
                            <span class="font-serif fw-700 fs-22 text-mat-maroon lh-1 d-block">Matrimony</span>
                            <span class="fs-10 text-muted lh-1 tracking-wide d-block mt-1">Together for a Better Tomorrow</span>
                        </div>
                    </div>
                @endif
            </a>

            <!-- Navigation Links -->
            <nav class="d-none d-lg-block">
                <ul class="nav align-items-center mb-0">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'home' || request()->is('/')) active @endif">
                            {{ translate('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('active_members') ? route('active_members') : url('/member-listing') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'active_members' || request()->is('member-listing*')) active @endif">
                            {{ translate('Find Matches') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('packages') ? route('packages') : url('/packages') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'packages' || request()->is('packages*')) active @endif">
                            {{ translate('Membership Plans') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('happy_stories') ? route('happy_stories') : url('/happy-stories') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'happy_stories' || request()->is('happy-stories*')) active @endif">
                            {{ translate('Success Stories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('about_us') ? route('about_us') : url('/about-us') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'about_us' || request()->is('about-us*') || request()->is('custom-page/about-us*')) active @endif">
                            {{ translate('About Us') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('blog') ? route('blog') : url('/blog') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'blog' || Route::currentRouteName() == 'blog.details' || request()->is('blog*')) active @endif">
                            {{ translate('Blog') }}
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Auth / Action Button -->
            <div class="d-flex align-items-center">
                @if (Auth::check())
                    <!-- Notifications & User Profile -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-reset mr-3">
                            <img src="{{ uploaded_asset(Auth::user()->photo) }}"
                                class="size-35px rounded-circle img-fit mr-2 border border-mat-gold"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            <span class="fw-600 text-mat-maroon d-none d-md-inline">
                                {{ Auth::user()->first_name }}
                            </span>
                        </a>
                        <a href="{{ route('user.logout') }}" class="btn btn-mat-maroon btn-sm py-2 px-3">
                            <i class="las la-sign-out-alt"></i>
                            <span class="d-none d-md-inline">{{ translate('Logout') }}</span>
                        </a>
                    </div>
                @else
                    <a href="{{ route('register') }}" class="btn btn-mat-maroon">
                        <i class="las la-user-plus fs-16"></i>
                        <span>{{ translate('Register Free') }}</span>
                    </a>
                @endif

                <!-- Mobile Menu Button -->
                <button class="navbar-toggler d-lg-none ml-2 border-0 text-mat-maroon" type="button" data-toggle="collapse" data-target="#mobileNav">
                    <i class="las la-bars fs-24"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div class="collapse d-lg-none mt-3 border-top pt-3" id="mobileNav">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('home') }}" class="nav-link nav-link-matrimony py-2">{{ translate('Home') }}</a></li>
                <li class="nav-item"><a href="{{ url('/member-listing') }}" class="nav-link nav-link-matrimony py-2">{{ translate('Find Matches') }}</a></li>
                <li class="nav-item"><a href="{{ url('/packages') }}" class="nav-link nav-link-matrimony py-2">{{ translate('Membership Plans') }}</a></li>
                <li class="nav-item"><a href="{{ url('/happy-stories') }}" class="nav-link nav-link-matrimony py-2">{{ translate('Success Stories') }}</a></li>
                <li class="nav-item"><a href="{{ url('/custom-page/about-us') }}" class="nav-link nav-link-matrimony py-2">{{ translate('About Us') }}</a></li>
                <li class="nav-item"><a href="{{ url('/blog') }}" class="nav-link nav-link-matrimony py-2">{{ translate('Blog') }}</a></li>
                @if(!Auth::check())
                    <li class="nav-item pt-2 border-top">
                        <a href="{{ route('login') }}" class="nav-link nav-link-matrimony py-2 font-weight-bold">{{ translate('Log In') }}</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</header>
