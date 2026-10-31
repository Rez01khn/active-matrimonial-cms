<!-- Top Bar -->
@php
    $helpline = get_setting('header_helpline', get_setting('contact_phone', get_setting('header_helpline_no', '+01 112 352 566')));
    $helpline_clean = preg_replace('/[^0-9+]/', '', $helpline);
    $facebook_link = get_setting('facebook_link');
    $instagram_link = get_setting('instagram_link');
    $youtube_link = get_setting('youtube_link');
    $has_any_social = !empty($facebook_link) || !empty($instagram_link) || !empty($youtube_link);
@endphp
<div class="top-navbar-matrimony">
    <div class="container h-100 topbar-container">
        <div class="d-flex align-items-center justify-content-between h-100 text-xs">

            @auth
                <!-- Scenario B: Authenticated Identity (Centered in Middle) -->
                <div class="topbar-auth-left d-none d-lg-flex align-items-center flex-shrink-0">
                    <!-- Clean balance spacer -->
                </div>

                @if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')
                    <!-- Logged in as Admin / Staff -->
                    <div class="topbar-center-identity">
                        <a href="{{ route('admin.dashboard') }}" class="topbar-member-identity d-flex align-items-center flex-shrink-0 text-decoration-none" title="{{ translate('Go to Admin Dashboard') }}">
                            <span class="topbar-member-greeting">
                                <span class="topbar-prefix-text">{{ translate('Welcome back') }},</span>
                                <strong class="font-weight-700 ml-1" style="color: #FFFFFF;">{{ Auth::user()->name ?: (Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</strong>
                            </span>
                            <span class="topbar-sep-dot">&middot;</span>
                            <span class="badge badge-inline px-2.5 py-1 text-white font-weight-700 fs-11 rounded-pill shadow-sm" style="background: rgba(197, 160, 89, 0.35); border: 1px solid rgba(197, 160, 89, 0.6); color: #FDE68A !important;">
                                <i class="las la-shield-alt mr-1"></i> {{ translate('ADMIN DASHBOARD') }} &rarr;
                            </span>
                        </a>
                    </div>
                @else
                    <!-- Logged in as Member -->
                    <div class="topbar-center-identity">
                        <a href="{{ route('dashboard') }}" class="topbar-member-identity d-flex align-items-center flex-shrink-0 text-decoration-none" title="{{ translate('Go to Member Dashboard') }}">
                            <span class="topbar-member-greeting">
                                <span class="topbar-prefix-text">{{ get_setting('topbar_member_prefix', 'Welcome back') }},</span>
                                <strong class="font-weight-600 ml-1" style="color: #FFFFFF;">{{ Auth::user()->name ?: Auth::user()->first_name }}</strong>
                            </span>
                            <span class="topbar-sep-dot">&middot;</span>
                            <span class="topbar-member-code" style="color: #E2DCD5;">
                                ID: #{{ Auth::user()->code ?? Auth::id() }}
                            </span>
                            @php
                                $user_member = Auth::user()->member;
                                $pkg_title = ($user_member && $user_member->current_package) 
                                    ? $user_member->current_package->name 
                                    : (($user_member && $user_member->package) ? $user_member->package->name : (Auth::user()->membership == 2 ? translate('Premium') : translate('Free Member')));
                            @endphp
                            <span class="topbar-package-badge ml-1" style="color: var(--mat-gold-light);">
                                [ {{ $pkg_title }} ]
                            </span>
                        </a>
                    </div>
                @endif

                <div class="topbar-spacer d-none d-lg-block"></div>
            @endauth

            @guest
                <!-- Scenario A: Visitor / Guest View - Left Balanced Spacer / Welcome -->
                <div class="topbar-guest-left d-none d-lg-flex align-items-center flex-shrink-0">
                    <span>{{ translate('Welcome to Matrimony') }}</span>
                </div>

                <!-- Scenario A: Dedicated Marquee Ticker (Strictly ONE single message, pure typography) -->
                @php
                    $ticker_raw = get_setting('topbar_ticker_text', 'Register Today & Get 4 Free Interests!');
                    $ticker_text = is_string($ticker_raw) ? trim(preg_replace('/[\x{1F300}-\x{1FAD6}\x{2600}-\x{27BF}]/u', '', $ticker_raw)) : '';
                @endphp
                @if(!empty($ticker_text))
                    <div class="topbar-ticker-container">
                        <div class="topbar-single-marquee">
                            <span class="topbar-single-ticker-text">
                                {{ $ticker_text }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="topbar-spacer"></div>
                @endif
            @endguest

            <!-- Right: Dynamic Contact & Social Links (Zero Collision) -->
            <div class="topbar-right d-flex align-items-center flex-shrink-0">
                @if(!empty($helpline))
                    <a href="tel:{{ $helpline_clean }}" class="d-flex align-items-center topbar-phone-link">
                        <i class="las la-phone topbar-phone-icon fs-13"></i>
                        <span class="d-none d-sm-inline topbar-phone-label">{{ translate('Helpline') }}:</span>
                        <span class="font-weight-600">{{ $helpline }}</span>
                    </a>
                @endif

                <!-- Vertical Divider -->
                <span class="topbar-divider"></span>

                <!-- Dynamic Social Media Icons -->
                <div class="topbar-social-icons d-flex align-items-center">
                    @if(!empty($facebook_link) || !$has_any_social)
                        <a href="{{ $facebook_link ?: '#' }}" target="{{ !empty($facebook_link) && $facebook_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="topbar-social-link" title="Facebook">
                            <i class="lab la-facebook-f"></i>
                        </a>
                    @endif
                    @if(!empty($instagram_link) || !$has_any_social)
                        <a href="{{ $instagram_link ?: '#' }}" target="{{ !empty($instagram_link) && $instagram_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="topbar-social-link" title="Instagram">
                            <i class="lab la-instagram"></i>
                        </a>
                    @endif
                    @if(!empty($youtube_link) || !$has_any_social)
                        <a href="{{ $youtube_link ?: '#' }}" target="{{ !empty($youtube_link) && $youtube_link != '#' ? '_blank' : '_self' }}" rel="noopener noreferrer" class="topbar-social-link" title="YouTube">
                            <i class="lab la-youtube"></i>
                        </a>
                    @endif
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
                @auth
                    <!-- Notifications & User Profile -->
                    <div class="d-flex align-items-center">
                        @if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')
                            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-reset mr-3">
                                <img src="{{ uploaded_asset(Auth::user()->photo) }}"
                                    class="size-35px rounded-circle img-fit mr-2 border border-mat-gold"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                <span class="fw-600 text-mat-maroon d-none d-md-inline">
                                    {{ Auth::user()->first_name }} ({{ translate('Admin') }})
                                </span>
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-mat-maroon btn-sm py-2 px-3 rounded-pill">
                                <i class="las la-sign-out-alt"></i>
                                <span class="d-none d-md-inline">{{ translate('Logout') }}</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-reset mr-3">
                                <img src="{{ uploaded_asset(Auth::user()->photo) }}"
                                    class="size-35px rounded-circle img-fit mr-2 border border-mat-gold"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                <span class="fw-600 text-mat-maroon d-none d-md-inline">
                                    {{ Auth::user()->first_name }}
                                </span>
                            </a>
                            <a href="{{ route('user.logout') }}" class="btn btn-mat-maroon btn-sm py-2 px-3 rounded-pill">
                                <i class="las la-sign-out-alt"></i>
                                <span class="d-none d-md-inline">{{ translate('Logout') }}</span>
                            </a>
                        @endif
                    </div>
                @endauth
                @guest
                    <div class="d-flex align-items-center gap-3" style="gap: 12px;">
                        <a href="{{ route('login') }}" class="btn btn-outline-mat-maroon rounded-pill">
                            <i class="las la-sign-in-alt fs-16"></i>
                            <span>{{ translate('Login') }}</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-mat-maroon rounded-pill">
                            <i class="las la-user-plus fs-16"></i>
                            <span>{{ translate('Register Free') }}</span>
                        </a>
                    </div>
                @endguest

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
                @guest
                    <li class="nav-item pt-2 border-top">
                        <a href="{{ route('login') }}" class="nav-link nav-link-matrimony py-2 font-weight-bold">{{ translate('Log In') }}</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</header>
