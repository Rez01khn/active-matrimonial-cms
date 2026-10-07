@extends('frontend.layouts.app')

@section('content')

    <!-- 1. Hero Section -->
    <section class="hero-matrimony"
        style="background-image: url('{{ static_asset('assets/img/custom/hero_full_bg.jpg') }}');">
        <div class="hero-matrimony-overlay"></div>
        <div class="container hero-content-wrap">
            <div class="row">
                <div class="col-xl-9 col-lg-10">
                    <h1 class="hero-title mb-3">
                        Find Someone Who<br>
                        Completes Your Story
                    </h1>
                    <p class="hero-subtitle mb-3">
                        Trusted matchmaking for meaningful relationships, compatible families, and lifelong connections.
                    </p>

                    <!-- Decorative Ornament Line -->
                    <div class="ornament-line justify-content-start my-3">
                        <span>◇─── ❖ ───◇</span>
                    </div>

                    <!-- Match Search Box -->
                    <div class="hero-search-card">
                        <form action="{{ route('member.listing') }}" method="GET">
                            <div class="form-row align-items-end">
                                <!-- Looking for -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('I am looking for') }}</label>
                                    <select class="form-control aiz-selectpicker" name="gender">
                                        @foreach ($genders as $key => $name)
                                            <option value="{{ $key }}" @if(request('gender', (Auth::check() && Auth::user()->member && Auth::user()->member->gender == 1 ? 2 : (Auth::check() && Auth::user()->member && Auth::user()->member->gender == 2 ? 1 : 2))) == $key) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Religion -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Religion') }}</label>
                                    <select class="form-control aiz-selectpicker" name="religion_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Religion') }}</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion->id }}" @if(request('religion_id') == $religion->id) selected @endif>{{ $religion->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Age Range -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Age Range') }}</label>
                                    <select class="form-control aiz-selectpicker" name="age_range">
                                        <option value="">{{ translate('Select Age Range') }}</option>
                                        @foreach ($age_ranges as $range)
                                            <option value="{{ $range['value'] }}" @if(request('age_range') == $range['value'] || request('age') == $range['value']) selected @endif>
                                                {{ $range['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Location -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Location') }}</label>
                                    <select class="form-control aiz-selectpicker" name="city_id" data-live-search="true">
                                        <option value="">{{ translate('Select Location') }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}" @if(request('city_id') == $city->id) selected @endif>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Find Matches Button -->
                                <div class="col-lg col-md-4 col-12 mb-3 mb-lg-0">
                                    <button type="submit" class="btn-search-matches">
                                        <i class="las la-search fs-16"></i>
                                        <span>{{ translate('Find Matches') }}</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Action Pill Buttons inside Search Card -->
                            <div class="d-flex flex-wrap align-items-center gap-3 mt-4 pt-3 border-top">
                                <a href="{{ route('register') }}" class="btn-hero-pill-gold mr-3 mb-2 mb-sm-0">
                                    <i class="las la-user fs-16"></i>
                                    <span>{{ translate('Register Free') }}</span>
                                </a>
                                <a href="{{ route('packages') }}" class="btn-hero-pill-outline mb-2 mb-sm-0">
                                    <i class="las la-crown fs-16"></i>
                                    <span>{{ translate('View Premium Plans') }}</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Key Stats Bar -->
    <section class="stats-bar-container">
        <div class="container">
            <div class="row">
                <!-- Stat 1: Verified Profiles -->
                <div class="col-lg-3 col-md-6 stat-box">
                    <i class="las la-users stat-icon"></i>
                    <span class="stat-number">10,000+</span>
                    <div class="stat-label">{{ translate('Verified Profiles') }}</div>
                </div>

                <!-- Stat 2: Success Stories -->
                <div class="col-lg-3 col-md-6 stat-box">
                    <i class="las la-heart stat-icon"></i>
                    <span class="stat-number">500+</span>
                    <div class="stat-label">{{ translate('Success Stories') }}</div>
                </div>

                <!-- Stat 3: Communities Served -->
                <div class="col-lg-3 col-md-6 stat-box">
                    <i class="las la-hands-helping stat-icon"></i>
                    <span class="stat-number">50+</span>
                    <div class="stat-label">{{ translate('Communities Served') }}</div>
                </div>

                <!-- Stat 4: Privacy Focused -->
                <div class="col-lg-3 col-md-6 stat-box">
                    <i class="las la-user-shield stat-icon"></i>
                    <span class="stat-number">100%</span>
                    <div class="stat-label">{{ translate('Privacy Focused') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. A Safer Way to Find Your Life Partner -->
    <section class="py-6 bg-mat-cream">
        <div class="container">
            <div class="text-center max-w-700px mx-auto mb-5">
                <div class="text-mat-gold fs-18 mb-1">✦</div>
                <h2 class="font-serif fw-700 text-mat-maroon fs-36 mb-2">
                    A Safer Way to Find <span class="text-mat-maroon">Your Life Partner</span>
                </h2>
                <p class="text-muted fs-14">
                    We combine verified profiles, intelligent matching and privacy-first features to make your matchmaking
                    journey more comfortable and secure.
                </p>
            </div>

            <div class="row">
                <!-- Feature 1 -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">
                            <i class="las la-lock"></i>
                        </div>
                        <h5 class="fw-700 fs-16 text-dark mb-2">100% Privacy Control</h5>
                        <p class="fs-13 text-muted mb-0">Control who can view your profile and personal information.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">
                            <i class="las la-shield-alt"></i>
                        </div>
                        <h5 class="fw-700 fs-16 text-dark mb-2">Verified Profiles</h5>
                        <p class="fs-13 text-muted mb-0">Profiles are reviewed to maintain a trusted community.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">
                            <i class="las la-magic"></i>
                        </div>
                        <h5 class="fw-700 fs-16 text-dark mb-2">Smart Matching</h5>
                        <p class="fs-13 text-muted mb-0">Discover matches based on your preferences and compatibility.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">
                            <i class="las la-user-check"></i>
                        </div>
                        <h5 class="fw-700 fs-16 text-dark mb-2">Manual Screening</h5>
                        <p class="fs-13 text-muted mb-0">Additional screening helps maintain profile quality.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bangladesh Divisions Section -->
    <section class="bd-divisions-section">
        <!-- Botanical Corner Flourishes -->
        <svg class="corner-flourish corner-flourish-tl" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 145C35 110 70 90 110 70M110 70C90 50 80 20 70 0M110 70C130 50 150 40 160 30" stroke="#8C6239" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M35 120C25 110 20 95 25 80C40 85 50 100 45 115" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M80 85C70 70 70 50 80 35C95 45 100 65 90 80" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M130 55C145 45 160 50 160 65C145 70 135 65 130 55" fill="#8C6239" fill-opacity="0.25"/>
        </svg>
        <svg class="corner-flourish corner-flourish-tr" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 145C35 110 70 90 110 70M110 70C90 50 80 20 70 0M110 70C130 50 150 40 160 30" stroke="#8C6239" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M35 120C25 110 20 95 25 80C40 85 50 100 45 115" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M80 85C70 70 70 50 80 35C95 45 100 65 90 80" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M130 55C145 45 160 50 160 65C145 70 135 65 130 55" fill="#8C6239" fill-opacity="0.25"/>
        </svg>

        <div class="container">
            <!-- Section Header -->
            <div class="heritage-header-wrap">
                <div class="heritage-accent-badge">
                    <span class="heritage-divider-wing"></span>
                    <span class="heritage-icon-pin">
                        <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 0C4.477 0 0 4.477 0 10C0 17 10 24 10 24C10 24 20 17 20 10C20 4.477 15.523 0 10 0ZM10 13.5C8.067 13.5 6.5 11.933 6.5 10C6.5 8.067 8.067 6.5 10 6.5C11.933 6.5 13.5 8.067 13.5 10C13.5 11.933 11.933 13.5 10 13.5Z" fill="#8C6239"/>
                        </svg>
                    </span>
                    <span class="heritage-divider-wing right"></span>
                </div>
                <h2 class="heritage-headline">Matches by Region &amp; Roots</h2>
                <p class="heritage-subtitle">
                    Discover compatible brides and grooms from your preferred division and cultural background.
                </p>
            </div>

            <!-- 8 Division Cards Grid -->
            <div class="bd-divisions-grid">
                <!-- 1. Dhaka -->
                <a href="{{ route('member.listing', ['division' => 'Dhaka', 'state_id' => 1]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="10" width="18" height="11" rx="2"></rect>
                                <path d="M12 2L3 8h18L12 2z"></path>
                                <line x1="8" y1="13" x2="8" y2="18"></line>
                                <line x1="12" y1="13" x2="12" y2="18"></line>
                                <line x1="16" y1="13" x2="16" y2="18"></line>
                            </svg>
                        </div>
                        <h3 class="division-title">Dhaka Division</h3>
                        <p class="division-tagline">The heart of Bangladesh, full of opportunities.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_dhaka.jpg') }}" alt="Dhaka Division - National Parliament" loading="lazy">
                    </div>
                </a>

                <!-- 2. Chattogram -->
                <a href="{{ route('member.listing', ['division' => 'Chattogram', 'state_id' => 2]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path>
                                <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7"></path>
                                <path d="M12 4v6"></path>
                                <path d="M8 8h8"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Chattogram Division</h3>
                        <p class="division-tagline">By the sea, full of dreams and new beginnings.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_chattogram.jpg') }}" alt="Chattogram Division - Seaport" loading="lazy">
                    </div>
                </a>

                <!-- 3. Rajshahi -->
                <a href="{{ route('member.listing', ['division' => 'Rajshahi', 'state_id' => 3]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L12 21l7.03-3.39C20.26 16.07 21 14.12 21 12c0-4.97-4.03-9-9-9z"></path>
                                <path d="M12 7v5l3 3"></path>
                                <path d="M9 17h6"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Rajshahi Division</h3>
                        <p class="division-tagline">Rich in culture, beautiful in nature.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_rajshahi.jpg') }}" alt="Rajshahi Division - Bagha Mosque" loading="lazy">
                    </div>
                </a>

                <!-- 4. Khulna -->
                <a href="{{ route('member.listing', ['division' => 'Khulna', 'state_id' => 4]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 19V5"></path>
                                <path d="M8 9c-2 0-3 1.5-3 3.5 0 2 1.5 3.5 3 3.5h8c1.5 0 3-1.5 3-3.5 0-2-1-3.5-3-3.5-1-3-3.5-4-5-4s-4 1-5 4z"></path>
                                <path d="M6 19h12"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Khulna Division</h3>
                        <p class="division-tagline">Where rivers meet endless possibilities.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_khulna.jpg') }}" alt="Khulna Division - Sundarbans" loading="lazy">
                    </div>
                </a>

                <!-- 5. Barishal -->
                <a href="{{ route('member.listing', ['division' => 'Barishal', 'state_id' => 5]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 17l10 4 10-4-3-7H5l-3 7z"></path>
                                <path d="M12 4v10"></path>
                                <path d="M8 8l4-4 4 4"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Barishal Division</h3>
                        <p class="division-tagline">Land of rivers, land of peace.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_barishal.jpg') }}" alt="Barishal Division - River Life" loading="lazy">
                    </div>
                </a>

                <!-- 6. Sylhet -->
                <a href="{{ route('member.listing', ['division' => 'Sylhet', 'state_id' => 6]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22C12 22 20 18 20 10C20 6 16.5 3 12 2C7.5 3 4 6 4 10C4 18 12 22 12 22Z"></path>
                                <path d="M12 2v20"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Sylhet Division</h3>
                        <p class="division-tagline">Tea gardens, waterfalls and natural beauty.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_sylhet.jpg') }}" alt="Sylhet Division - Tea Gardens" loading="lazy">
                    </div>
                </a>

                <!-- 7. Rangpur -->
                <a href="{{ route('member.listing', ['division' => 'Rangpur', 'state_id' => 7]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 21h16"></path>
                                <path d="M6 21V9l6-5 6 5v12"></path>
                                <path d="M9 13h6v8H9z"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Rangpur Division</h3>
                        <p class="division-tagline">Tradition, simplicity and strong roots.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_rangpur.jpg') }}" alt="Rangpur Division - Tajhat Palace" loading="lazy">
                    </div>
                </a>

                <!-- 8. Mymensingh -->
                <a href="{{ route('member.listing', ['division' => 'Mymensingh', 'state_id' => 8]) }}" class="division-card">
                    <div class="division-card-top">
                        <div class="division-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 18l8 3 10-3-2-6H5l-2 6z"></path>
                                <path d="M12 5v7"></path>
                                <path d="M12 5l-4 4"></path>
                            </svg>
                        </div>
                        <h3 class="division-title">Mymensingh Division</h3>
                        <p class="division-tagline">Known for its rivers, fields and friendly people.</p>
                        <span class="division-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="division-card-visual">
                        <img src="{{ static_asset('assets/img/custom/divisions/div_mymensingh.jpg') }}" alt="Mymensingh Division - Brahmaputra River" loading="lazy">
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. Meet Our Featured Members -->
    <section class="py-6 bg-mat-section position-relative">
        <div class="container">
            <div class="text-center max-w-700px mx-auto mb-5">
                <h2 class="font-serif fw-700 text-mat-maroon fs-36 mb-2">Meet Our Featured Members</h2>
                <p class="text-muted fs-14">Explore a selection of profiles looking for meaningful connections.</p>
            </div>

            <!-- Member Carousel Container -->
            <div class="member-carousel-wrap">
                <!-- Left Navigation Arrow -->
                <button type="button" class="member-nav-btn prev-btn d-none d-md-flex" onclick="$('#member-slider').slick('slickPrev')" aria-label="Previous Members">
                    <i class="las la-angle-left fs-18"></i>
                </button>

                <!-- Slider Container -->
                <div class="aiz-carousel gutters-15" id="member-slider"
                    data-items="5"
                    data-xl-items="5"
                    data-lg-items="5"
                    data-md-items="3"
                    data-sm-items="2"
                    data-xs-items="2"
                    data-dots="true"
                    data-autoplay="true"
                    data-infinite="true"
                    data-arrows="false">

                    <!-- Member 1: Ayesha -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/ayesha.jpg') }}" alt="Ayesha" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Ayesha, <span class="fw-400">27</span></h6>
                                    <div class="fs-12 text-muted">Dhaka, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'4" • Professional</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 2: Rahim -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/rahim.jpg') }}" alt="Rahim" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Rahim, <span class="fw-400">30</span></h6>
                                    <div class="fs-12 text-muted">Chittagong, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'8" • Professional</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 3: Nusrat -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/nusrat.jpg') }}" alt="Nusrat" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Nusrat, <span class="fw-400">26</span></h6>
                                    <div class="fs-12 text-muted">Sylhet, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'3" • Student</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 4: Fahim -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/fahim.jpg') }}" alt="Fahim" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Fahim, <span class="fw-400">32</span></h6>
                                    <div class="fs-12 text-muted">Dhaka, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'11" • Professional</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 5: Maliha -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/maliha.jpg') }}" alt="Maliha" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Maliha, <span class="fw-400">28</span></h6>
                                    <div class="fs-12 text-muted">Rajshahi, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'3" • Professional</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 6: Tariq -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/rahim.jpg') }}" alt="Tariq" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Tariq, <span class="fw-400">29</span></h6>
                                    <div class="fs-12 text-muted">Khulna, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'10" • Software Engineer</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 7: Sadia -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/ayesha.jpg') }}" alt="Sadia" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Sadia, <span class="fw-400">25</span></h6>
                                    <div class="fs-12 text-muted">Dhaka, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'5" • Architect</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 8: Tanvir -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/fahim.jpg') }}" alt="Tanvir" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Tanvir, <span class="fw-400">31</span></h6>
                                    <div class="fs-12 text-muted">Barisal, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'9" • Doctor</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 9: Anika -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/nusrat.jpg') }}" alt="Anika" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <h6 class="fw-700 fs-16 mb-0 text-dark">Anika, <span class="fw-400">24</span></h6>
                                    <div class="fs-12 text-muted">Comilla, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'2" • UI Designer</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Member 10: Samira -->
                    <div class="carousel-box">
                        <div class="member-card">
                            <div class="member-card-img-wrap">
                                <img src="{{ static_asset('assets/img/custom/maliha.jpg') }}" alt="Samira" class="member-card-img">
                            </div>
                            <div class="member-card-body">
                                <div class="member-info-content">
                                    <div class="h6 fw-700 fs-16 mb-0 text-dark">Samira, <span class="fw-400">27</span></div>
                                    <div class="fs-12 text-muted">Chittagong, Bangladesh</div>
                                    <div class="member-meta-pill">Muslim • 5'4" • Educator</div>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-mat-gold btn-sm btn-block">{{ translate('View Profile') }}</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Navigation Arrow -->
                <button type="button" class="member-nav-btn next-btn d-none d-md-flex" onclick="$('#member-slider').slick('slickNext')" aria-label="Next Members">
                    <i class="las la-angle-right fs-18"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Popular Jobs in Bangladesh Section -->
    <section class="popular-jobs-section">
        <!-- Botanical Corner Flourishes -->
        <svg class="corner-flourish corner-flourish-tl" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 145C35 110 70 90 110 70M110 70C90 50 80 20 70 0M110 70C130 50 150 40 160 30" stroke="#8C6239" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M35 120C25 110 20 95 25 80C40 85 50 100 45 115" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M80 85C70 70 70 50 80 35C95 45 100 65 90 80" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M130 55C145 45 160 50 160 65C145 70 135 65 130 55" fill="#8C6239" fill-opacity="0.25"/>
        </svg>
        <svg class="corner-flourish corner-flourish-tr" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 145C35 110 70 90 110 70M110 70C90 50 80 20 70 0M110 70C130 50 150 40 160 30" stroke="#8C6239" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M35 120C25 110 20 95 25 80C40 85 50 100 45 115" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M80 85C70 70 70 50 80 35C95 45 100 65 90 80" fill="#8C6239" fill-opacity="0.25"/>
            <path d="M130 55C145 45 160 50 160 65C145 70 135 65 130 55" fill="#8C6239" fill-opacity="0.25"/>
        </svg>

        <div class="container">
            <!-- Section Header -->
            <div class="heritage-header-wrap">
                <div class="heritage-accent-badge">
                    <span class="heritage-divider-wing"></span>
                    <span class="heritage-icon-pin">
                        <svg width="22" height="20" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="6" width="20" height="14" rx="2.5" stroke="#8C6239" stroke-width="2"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="#8C6239" stroke-width="2"/>
                            <path d="M2 11h20" stroke="#8C6239" stroke-width="1.8"/>
                        </svg>
                    </span>
                    <span class="heritage-divider-wing right"></span>
                </div>
                <h2 class="heritage-headline">Find Matches by Profession</h2>
                <p class="heritage-subtitle">
                    Connect with educated and established professionals who share your values, ambition, and lifestyle.
                </p>
            </div>

            <!-- 8 Profession Cards Grid -->
            <div class="popular-jobs-grid">
                <!-- 1. IT & Software -->
                <a href="{{ route('member.listing', ['profession' => 'IT']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                <polyline points="6 9 8 11 6 13"></polyline>
                                <line x1="11" y1="13" x2="15" y2="13"></line>
                            </svg>
                        </div>
                        <h3 class="job-title">IT &amp; Software</h3>
                        <span class="job-count">1,240+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_it_software.jpg') }}" alt="IT & Software" loading="lazy">
                    </div>
                </a>

                <!-- 2. Education & Teaching -->
                <a href="{{ route('member.listing', ['profession' => 'Teacher']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                <line x1="9" y1="7" x2="15" y2="7"></line>
                                <line x1="9" y1="11" x2="13" y2="11"></line>
                            </svg>
                        </div>
                        <h3 class="job-title">Education &amp; Teaching</h3>
                        <span class="job-count">980+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_education.jpg') }}" alt="Education & Teaching" loading="lazy">
                    </div>
                </a>

                <!-- 3. Government Service -->
                <a href="{{ route('member.listing', ['profession' => 'Government']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18"></path>
                                <path d="M5 21V10l7-5 7 5v11"></path>
                                <path d="M9 21v-4a3 3 0 0 1 6 0v4"></path>
                            </svg>
                        </div>
                        <h3 class="job-title">Government Service</h3>
                        <span class="job-count">760+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_government.jpg') }}" alt="Government Service" loading="lazy">
                    </div>
                </a>

                <!-- 4. Healthcare & Medical -->
                <a href="{{ route('member.listing', ['profession' => 'Doctor']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .2.3"></path>
                                <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"></path>
                                <circle cx="20" cy="10" r="2"></circle>
                            </svg>
                        </div>
                        <h3 class="job-title">Healthcare &amp; Medical</h3>
                        <span class="job-count">620+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_healthcare.jpg') }}" alt="Healthcare & Medical" loading="lazy">
                    </div>
                </a>

                <!-- 5. Banking & Finance -->
                <a href="{{ route('member.listing', ['profession' => 'Banker']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="10" width="18" height="11" rx="2"></rect>
                                <path d="M12 2L3 8h18L12 2z"></path>
                                <line x1="8" y1="13" x2="8" y2="18"></line>
                                <line x1="12" y1="13" x2="12" y2="18"></line>
                                <line x1="16" y1="13" x2="16" y2="18"></line>
                            </svg>
                        </div>
                        <h3 class="job-title">Banking &amp; Finance</h3>
                        <span class="job-count">540+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_banking.jpg') }}" alt="Banking & Finance" loading="lazy">
                    </div>
                </a>

                <!-- 6. Engineering -->
                <a href="{{ route('member.listing', ['profession' => 'Engineer']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </div>
                        <h3 class="job-title">Engineering</h3>
                        <span class="job-count">480+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_engineering.jpg') }}" alt="Engineering" loading="lazy">
                    </div>
                </a>

                <!-- 7. Business & Entrepreneurship -->
                <a href="{{ route('member.listing', ['profession' => 'Business']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                                <polyline points="6 10 12 4 18 8"></polyline>
                            </svg>
                        </div>
                        <h3 class="job-title">Business &amp; Entrepreneurship</h3>
                        <span class="job-count">420+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_business.jpg') }}" alt="Business & Entrepreneurship" loading="lazy">
                    </div>
                </a>

                <!-- 8. Other Professions -->
                <a href="{{ route('member.listing', ['profession' => 'Other']) }}" class="job-card">
                    <div class="job-card-content">
                        <div class="job-icon-badge">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#8C6239" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="job-title">Other Professions</h3>
                        <span class="job-count">380+ Profiles</span>
                        <span class="job-arrow-btn">
                            <i class="las la-arrow-right"></i>
                        </span>
                    </div>
                    <div class="job-card-visual">
                        <img src="{{ static_asset('assets/img/custom/jobs/job_other.jpg') }}" alt="Other Professions" loading="lazy">
                    </div>
                </a>
            </div>

            <!-- Bottom Action Button -->
            <div class="text-center mt-3">
                <a href="{{ route('member.listing') }}" class="btn-explore-all-jobs">
                    <span>Explore All Jobs</span>
                    <i class="las la-arrow-right fs-16"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 5. How It Works Section (Fixed Parallax Luxury Section) -->
    <section class="how-it-works-parallax" style="background-image: url('{{ static_asset('assets/img/custom/hero_full_bg.jpg') }}');">
        <!-- Soft Cream Overlay -->
        <div class="how-it-works-overlay"></div>

        <!-- Floral Corner Watermarks -->
        <svg class="how-it-works-watermark-left" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180C40 140 80 120 120 100M120 100C100 80 90 40 80 10M120 100C140 80 170 70 190 60" stroke="var(--mat-gold)" stroke-width="2" stroke-linecap="round"/>
            <path d="M40 150C30 140 25 125 30 110C45 115 55 130 50 145" fill="var(--mat-gold)" fill-opacity="0.3"/>
            <path d="M90 115C80 100 80 80 90 65C105 75 110 95 100 110" fill="var(--mat-gold)" fill-opacity="0.3"/>
            <path d="M140 85C155 75 170 80 180 95C165 100 150 95 140 85" fill="var(--mat-gold)" fill-opacity="0.3"/>
        </svg>
        <svg class="how-it-works-watermark-right" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180C40 140 80 120 120 100M120 100C100 80 90 40 80 10M120 100C140 80 170 70 190 60" stroke="var(--mat-gold)" stroke-width="2" stroke-linecap="round"/>
            <path d="M40 150C30 140 25 125 30 110C45 115 55 130 50 145" fill="var(--mat-gold)" fill-opacity="0.3"/>
            <path d="M90 115C80 100 80 80 90 65C105 75 110 95 100 110" fill="var(--mat-gold)" fill-opacity="0.3"/>
            <path d="M140 85C155 75 170 80 180 95C165 100 150 95 140 85" fill="var(--mat-gold)" fill-opacity="0.3"/>
        </svg>

        <div class="container how-it-works-content">
            <!-- Header & Title -->
            <div class="text-center max-w-700px mx-auto mb-4">
                <div class="how-eyebrow">
                    <span class="how-eyebrow-line"></span>
                    <span>HOW IT WORKS</span>
                    <span class="how-eyebrow-line right"></span>
                </div>
                <h2 class="how-main-title">Getting started is simple.</h2>
                <p class="how-subtitle">Just follow these 3 easy steps.</p>
                <div class="how-heart-divider">
                    <span class="line"></span>
                    <i class="las la-heart heart-icon"></i>
                    <span class="line"></span>
                </div>
            </div>

            <!-- The 3-Step Process (Horizontal Flow on Desktop) -->
            <div class="row align-items-start justify-content-center">
                <!-- Step 01 -->
                <div class="col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
                    <div class="how-step-card">
                        <div class="how-step-graphic-wrap">
                            <div class="how-step-graphic-circle">
                                <img src="{{ static_asset('assets/img/custom/step1_register.jpg') }}" alt="Register" class="how-step-img">
                            </div>
                            <span class="how-step-number-badge">01</span>
                        </div>
                        <h4 class="how-step-title">Register</h4>
                        <p class="how-step-desc">Create your profile and tell us what you're looking for.</p>
                    </div>
                </div>

                <!-- Connector Arrow 1 (Desktop Only) -->
                <div class="col-lg-1 d-none d-lg-block text-center px-0">
                    <div class="how-step-arrow-wrap">
                        <i class="las la-long-arrow-alt-right how-step-arrow"></i>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
                    <div class="how-step-card">
                        <div class="how-step-graphic-wrap">
                            <div class="how-step-graphic-circle">
                                <img src="{{ static_asset('assets/img/custom/step2_connect.jpg') }}" alt="Connect" class="how-step-img">
                            </div>
                            <span class="how-step-number-badge">02</span>
                        </div>
                        <h4 class="how-step-title">Connect</h4>
                        <p class="how-step-desc">Discover compatible profiles and send connection requests.</p>
                    </div>
                </div>

                <!-- Connector Arrow 2 (Desktop Only) -->
                <div class="col-lg-1 d-none d-lg-block text-center px-0">
                    <div class="how-step-arrow-wrap">
                        <i class="las la-long-arrow-alt-right how-step-arrow"></i>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="col-lg-3 col-md-4 col-12 mb-4 mb-lg-0">
                    <div class="how-step-card">
                        <div class="how-step-graphic-wrap">
                            <div class="how-step-graphic-circle">
                                <img src="{{ static_asset('assets/img/custom/step3_interact.jpg') }}" alt="Interact" class="how-step-img">
                            </div>
                            <span class="how-step-number-badge">03</span>
                        </div>
                        <h4 class="how-step-title">Interact</h4>
                        <p class="how-step-desc">Start meaningful conversations and take the relationship forward.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Action CTA -->
            <div class="text-center">
                <a href="{{ route('register') }}" class="how-cta-btn">
                    <span>Create Your Free Profile</span>
                    <i class="las la-arrow-right fs-16"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- 6. Real People. Real Connections. Real Stories. -->
    <section class="py-6 bg-mat-cream home-story-section position-relative">
        <div class="container position-relative px-4 px-md-5">
            <!-- Symmetrically Positioned Prev / Next Arrow Navigation Controls -->
            <button type="button" class="story-nav-btn prev-btn" id="story-prev-btn" aria-label="Previous Story">
                <i class="las la-angle-left"></i>
            </button>
            <button type="button" class="story-nav-btn next-btn" id="story-next-btn" aria-label="Next Story">
                <i class="las la-angle-right"></i>
            </button>

            <!-- Carousel Slider Wrapper (slidesPerView: 1) -->
            <div class="aiz-carousel" id="story-slider"
                data-items="1"
                data-xl-items="1"
                data-lg-items="1"
                data-md-items="1"
                data-sm-items="1"
                data-xs-items="1"
                data-dots="false"
                data-arrows="false"
                data-autoplay="true"
                data-infinite="true">

                @if(isset($happy_stories) && $happy_stories->count() > 0)
                    @foreach($happy_stories as $story)
                        @php
                            $storyPhotos = !empty($story->photos) ? explode(',', $story->photos) : [];
                            $photo1 = isset($storyPhotos[0]) ? (is_numeric($storyPhotos[0]) ? uploaded_asset($storyPhotos[0]) : static_asset($storyPhotos[0])) : static_asset('assets/img/custom/story_couple.jpg');
                            $photo2 = isset($storyPhotos[1]) ? (is_numeric($storyPhotos[1]) ? uploaded_asset($storyPhotos[1]) : static_asset($storyPhotos[1])) : static_asset('assets/img/custom/story_wedding_reception.jpg');
                            $photo3 = isset($storyPhotos[2]) ? (is_numeric($storyPhotos[2]) ? uploaded_asset($storyPhotos[2]) : static_asset($storyPhotos[2])) : static_asset('assets/img/custom/story_wedding_rings.jpg');
                        @endphp
                        <div class="carousel-box">
                            <div class="row align-items-center">
                                <!-- Left Side Multi-Image Collage (No Video Elements) -->
                                <div class="col-lg-6 mb-4 mb-lg-0">
                                    <div class="story-collage-grid row gutters-10">
                                        <!-- Primary Couple Portrait -->
                                        <div class="col-7">
                                            <div class="story-img-card main-img-card">
                                                <img src="{{ $photo1 }}" alt="{{ $story->title }}" class="story-img">
                                            </div>
                                        </div>
                                        <!-- Stacked Accent Photos -->
                                        <div class="col-5 d-flex flex-column justify-content-between">
                                            <div class="story-img-card accent-img-card mb-2">
                                                <img src="{{ $photo2 }}" alt="{{ $story->title }} Reception" class="story-img">
                                            </div>
                                            <div class="story-img-card accent-img-card">
                                                <img src="{{ $photo3 }}" alt="{{ $story->title }} Rings" class="story-img">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side Quote Content -->
                                <div class="col-lg-6 pl-lg-5">
                                    <h2 class="font-serif fw-700 text-mat-maroon fs-34 mb-4">{{ translate('Real People. Real Connections. Real Stories.') }}</h2>
                                    <blockquote class="font-serif italic fs-18 text-dark mb-4 lh-1-6">
                                        &ldquo;{{ Str::limit(strip_tags($story->details), 160) }}&rdquo;
                                    </blockquote>
                                    <div class="fw-700 fs-16 text-mat-maroon">&mdash; {{ $story->title }}</div>
                                    <div class="fs-13 text-muted mb-4">{{ $story->created_at ? translate('Married in ') . $story->created_at->format('Y') : translate('Verified Match') }}</div>
                                    <a href="{{ route('story_details', $story->id) }}" class="story-read-btn">
                                        <span>{{ translate('Read Their Story') }}</span>
                                        <i class="las la-arrow-right fs-16"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback Slide 1 -->
                    <div class="carousel-box">
                        <div class="row align-items-center">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="story-collage-grid row gutters-10">
                                    <div class="col-7">
                                        <div class="story-img-card main-img-card">
                                            <img src="{{ static_asset('assets/img/custom/story_couple.jpg') }}" alt="Rahim & Nusrat" class="story-img">
                                        </div>
                                    </div>
                                    <div class="col-5 d-flex flex-column justify-content-between">
                                        <div class="story-img-card accent-img-card mb-2">
                                            <img src="{{ static_asset('assets/img/custom/story_wedding_reception.jpg') }}" alt="Wedding Reception" class="story-img">
                                        </div>
                                        <div class="story-img-card accent-img-card">
                                            <img src="{{ static_asset('assets/img/custom/story_wedding_rings.jpg') }}" alt="Ring Exchange" class="story-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pl-lg-5">
                                <h2 class="font-serif fw-700 text-mat-maroon fs-34 mb-4">{{ translate('Real People. Real Connections. Real Stories.') }}</h2>
                                <blockquote class="font-serif italic fs-18 text-dark mb-4 lh-1-6">
                                    &ldquo;{{ translate('We joined looking for someone compatible with our values. We never expected that a simple profile would lead us to each other.') }}&rdquo;
                                </blockquote>
                                <div class="fw-700 fs-16 text-mat-maroon">&mdash; Rahim &amp; Nusrat</div>
                                <div class="fs-13 text-muted mb-4">{{ translate('Married in 2024') }}</div>
                                <a href="{{ route('happy_stories') }}" class="story-read-btn">
                                    <span>{{ translate('Read Their Story') }}</span>
                                    <i class="las la-arrow-right fs-16"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>


    <!-- 7. Premium Membership Banner CTA -->
    <section class="py-5 bg-mat-cream">
        <div class="container">
            <div class="premium-cta-banner shadow-lg">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="d-flex align-items-start">
                            <div class="mr-4 d-none d-sm-block">
                                <div
                                    class="rounded-circle border border-warning d-flex align-items-center justify-content-center size-60px text-mat-gold-light">
                                    <i class="las la-crown fs-32"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-serif fw-700 fs-28 mb-2 text-white">Ready to Take the Next Step?</h3>
                                <p class="fs-14 opacity-90 mb-3">Unlock more possibilities with our premium membership.</p>

                                <div class="row fs-13">
                                    <div class="col-sm-6 mb-2 d-flex align-items-center">
                                        <i class="las la-check text-mat-gold-light mr-2 fw-700"></i>
                                        <span>Advanced Matching</span>
                                    </div>
                                    <div class="col-sm-6 mb-2 d-flex align-items-center">
                                        <i class="las la-check text-mat-gold-light mr-2 fw-700"></i>
                                        <span>Premium Profile Visibility</span>
                                    </div>
                                    <div class="col-sm-6 mb-2 d-flex align-items-center">
                                        <i class="las la-check text-mat-gold-light mr-2 fw-700"></i>
                                        <span>More Connection Requests</span>
                                    </div>
                                    <div class="col-sm-6 mb-2 d-flex align-items-center">
                                        <i class="las la-check text-mat-gold-light mr-2 fw-700"></i>
                                        <span>Enhanced Privacy Controls</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 text-lg-right">
                        <a href="{{ route('packages') }}" class="btn btn-mat-light-gold">
                            <span>{{ translate('Explore Membership Plans') }}</span>
                            <i class="las la-arrow-right fs-16 ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Bottom Soulmate Sunset CTA Banner -->
    <section class="soulmate-hero-banner text-center text-white"
        style="background-image: url('{{ static_asset('assets/img/custom/soulmate_couple.jpg') }}');">
        <div class="soulmate-hero-overlay"></div>
        <div class="container position-relative z-1">
            <div class="max-w-600px mx-auto">
                <div class="text-mat-gold-light fs-20 mb-2">✦</div>
                <h2 class="font-serif fw-700 fs-38 mb-3 text-white">Find Your Soulmate Today</h2>
                <p class="fs-15 opacity-90 mb-4">Your journey toward a meaningful relationship starts with one simple step.
                </p>
                <a href="{{ route('register') }}" class="btn btn-mat-gold px-4 py-3">
                    <i class="las la-user-plus fs-18 mr-1"></i>
                    <span>{{ translate('Register Free') }}</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Video Modal -->
    <div class="modal fade" id="videoStoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bg-dark border-0">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="embed-responsive embed-responsive-16by9 rounded">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function scrollCarousel(id, amount) {
            var el = document.getElementById(id);
            if (el) {
                el.scrollBy({ left: amount, behavior: 'smooth' });
            }
        }

        $(document).ready(function() {
            var $storySlider = $('#story-slider');
            if ($storySlider.length > 0) {
                if ($storySlider.hasClass('slick-initialized')) {
                    $storySlider.slick('unslick');
                }
                $storySlider.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 5500,
                    pauseOnHover: true,
                    infinite: true,
                    arrows: false,
                    dots: false,
                    swipe: true,
                    touchMove: true,
                    rtl: $("html").attr("dir") === "rtl"
                });

                $('#story-prev-btn').on('click', function(e) {
                    e.preventDefault();
                    $storySlider.slick('slickPrev');
                });

                $('#story-next-btn').on('click', function(e) {
                    e.preventDefault();
                    $storySlider.slick('slickNext');
                });
            }
        });
    </script>
@endsection