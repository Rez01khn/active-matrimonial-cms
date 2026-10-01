<!-- Top Bar -->
<div class="top-navbar-matrimony d-none d-lg-block">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left Info -->
            <div class="d-flex align-items-center gap-4">
                <a href="mailto:{{ get_setting('footer_email', 'hello@yourmatrimony.com') }}" class="d-flex align-items-center mr-4 text-white text-decoration-none">
                    <i class="las la-envelope mr-1 fs-15 text-mat-gold-light"></i>
                    <span>{{ get_setting('footer_email', 'hello@yourmatrimony.com') }}</span>
                </a>
                <a href="tel:{{ get_setting('header_helpline_no', '+900 1012 345670') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <i class="las la-phone mr-1 fs-15 text-mat-gold-light"></i>
                    <span>{{ get_setting('header_helpline_no', '+900 1012 345670') }}</span>
                </a>
            </div>

            <!-- Right Info & Social Links -->
            <div class="d-flex align-items-center">
                <div class="social-links mr-4 d-flex align-items-center gap-3">
                    <a href="{{ get_setting('facebook_link', '#') }}" target="_blank" class="text-white opacity-80 hov-opacity-100 mx-2 fs-14">
                        <i class="lab la-facebook-f"></i>
                    </a>
                    <a href="{{ get_setting('instagram_link', '#') }}" target="_blank" class="text-white opacity-80 hov-opacity-100 mx-2 fs-14">
                        <i class="lab la-instagram"></i>
                    </a>
                    <a href="{{ get_setting('youtube_link', '#') }}" target="_blank" class="text-white opacity-80 hov-opacity-100 mx-2 fs-14">
                        <i class="lab la-youtube"></i>
                    </a>
                </div>
                <div class="border-left pl-3 border-secondary d-flex align-items-center">
                    <a href="tel:+8001012345679" class="d-flex align-items-center text-white text-decoration-none">
                        <i class="las la-phone mr-1 fs-15 text-mat-gold-light"></i>
                        <span>+800 1012 345679</span>
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
                @if(get_setting('header_logo') != null)
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
                        <a href="{{ route('home') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'home') active @endif">
                            {{ translate('Home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('active_members') ? route('active_members') : url('/member-listing') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'active_members') active @endif">
                            {{ translate('Find Matches') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('packages') ? route('packages') : url('/packages') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'packages') active @endif">
                            {{ translate('Membership Plans') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('happy_stories') ? route('happy_stories') : url('/happy-stories') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'happy_stories') active @endif">
                            {{ translate('Success Stories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/custom-page/about-us') }}" class="nav-link nav-link-matrimony">
                            {{ translate('About Us') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ Route::has('blog') ? route('blog') : url('/blog') }}" class="nav-link nav-link-matrimony @if(Route::currentRouteName() == 'blog') active @endif">
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
