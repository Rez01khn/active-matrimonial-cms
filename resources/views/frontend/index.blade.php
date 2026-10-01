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
                                        <option value="2">{{ translate('Bride') }}</option>
                                        <option value="1">{{ translate('Groom') }}</option>
                                    </select>
                                </div>
                                <!-- Religion -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Religion') }}</label>
                                    <select class="form-control aiz-selectpicker" name="religion_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Religion') }}</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Age Range -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Age Range') }}</label>
                                    <select class="form-control aiz-selectpicker" name="age">
                                        <option value="">25 - 30</option>
                                        <option value="18-24">18 - 24</option>
                                        <option value="25-30">25 - 30</option>
                                        <option value="31-40">31 - 40</option>
                                        <option value="41-50">41 - 50</option>
                                    </select>
                                </div>
                                <!-- Location -->
                                <div class="col-lg col-md-4 col-6 mb-3 mb-lg-0">
                                    <label>{{ translate('Location') }}</label>
                                    <select class="form-control aiz-selectpicker" name="city_id" data-live-search="true">
                                        <option value="">Dhaka</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
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

    <!-- 4. Meet Our Featured Members -->
    <section class="py-6 bg-mat-section position-relative">
        <div class="container">
            <div class="text-center max-w-700px mx-auto mb-5">
                <h2 class="font-serif fw-700 text-mat-maroon fs-36 mb-2">Meet Our Featured Members</h2>
                <p class="text-muted fs-14">Explore a selection of profiles looking for meaningful connections.</p>
            </div>

            <!-- Member Carousel Container -->
            <div class="member-carousel-wrap px-lg-4">
                <!-- Left Navigation Arrow -->
                <button type="button" class="member-nav-btn prev-btn d-none d-md-flex" onclick="$('#member-slider').slick('slickPrev')">
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
                <button type="button" class="member-nav-btn next-btn d-none d-md-flex" onclick="$('#member-slider').slick('slickNext')">
                    <i class="las la-angle-right fs-18"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- 5. How It Works Section (Fixed Parallax Luxury Section) -->
    <section class="how-it-works-parallax" style="background-image: url('{{ static_asset('assets/img/custom/hero_full_bg.jpg') }}');">
        <!-- Soft Cream Overlay -->
        <div class="how-it-works-overlay"></div>

        <!-- Floral Corner Watermarks -->
        <svg class="how-it-works-watermark-left" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180C40 140 80 120 120 100M120 100C100 80 90 40 80 10M120 100C140 80 170 70 190 60" stroke="#8C6239" stroke-width="2" stroke-linecap="round"/>
            <path d="M40 150C30 140 25 125 30 110C45 115 55 130 50 145" fill="#8C6239" fill-opacity="0.3"/>
            <path d="M90 115C80 100 80 80 90 65C105 75 110 95 100 110" fill="#8C6239" fill-opacity="0.3"/>
            <path d="M140 85C155 75 170 80 180 95C165 100 150 95 140 85" fill="#8C6239" fill-opacity="0.3"/>
        </svg>
        <svg class="how-it-works-watermark-right" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180C40 140 80 120 120 100M120 100C100 80 90 40 80 10M120 100C140 80 170 70 190 60" stroke="#8C6239" stroke-width="2" stroke-linecap="round"/>
            <path d="M40 150C30 140 25 125 30 110C45 115 55 130 50 145" fill="#8C6239" fill-opacity="0.3"/>
            <path d="M90 115C80 100 80 80 90 65C105 75 110 95 100 110" fill="#8C6239" fill-opacity="0.3"/>
            <path d="M140 85C155 75 170 80 180 95C165 100 150 95 140 85" fill="#8C6239" fill-opacity="0.3"/>
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
    <section class="py-6 bg-mat-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Side Multi-Image Collage (No Video Elements) -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="story-collage-grid row gutters-10">
                        <!-- Primary Couple Portrait -->
                        <div class="col-7">
                            <div class="story-img-card main-img-card">
                                <img src="{{ static_asset('assets/img/custom/story_couple.jpg') }}" alt="Rahim & Nusrat" class="story-img">
                            </div>
                        </div>
                        <!-- Stacked Accent Photos -->
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

                <!-- Right Side Quote Content -->
                <div class="col-lg-6 pl-lg-5">
                    <h2 class="font-serif fw-700 text-mat-maroon fs-34 mb-4">Real People. Real Connections. Real Stories.</h2>
                    <blockquote class="font-serif italic fs-18 text-dark mb-4 lh-1-6">
                        "We joined looking for someone compatible with our values. We never expected that a simple profile would lead us to each other."
                    </blockquote>
                    <div class="fw-700 fs-16 text-mat-maroon">— Rahim & Nusrat</div>
                    <div class="fs-13 text-muted mb-4">Married in 2024</div>
                    <a href="{{ route('happy_stories') }}" class="story-read-btn">
                        <span>Read Their Story</span>
                        <i class="las la-arrow-right fs-16"></i>
                    </a>
                </div>
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
    </script>
@endsection