@extends('frontend.layouts.app')

@section('content')
<div class="about-us-page">

    <!-- 1. Page Header & Hero Banner -->
    <section class="about-hero-section">
        <!-- Botanical Corner Line Art Decorations -->
        <svg class="botanical-flourish flourish-hero-tl" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M15 150 C35 110, 70 60, 145 20" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            <path d="M45 118 C36 106, 32 92, 42 86 C52 92, 52 106, 45 118 Z" fill="currentColor" opacity="0.45"/>
            <path d="M62 94 C76 83, 89 86, 85 99 C72 105, 62 99, 62 94 Z" fill="currentColor" opacity="0.45"/>
            <path d="M80 72 C71 58, 69 44, 80 39 C91 47, 89 61, 80 72 Z" fill="currentColor" opacity="0.45"/>
            <path d="M102 51 C115 40, 127 45, 123 57 C111 63, 102 57, 102 51 Z" fill="currentColor" opacity="0.45"/>
            <path d="M126 32 C126 18, 138 13, 145 20 C145 32, 136 37, 126 32 Z" fill="currentColor" opacity="0.45"/>
        </svg>

        <svg class="botanical-flourish flourish-hero-bl d-none d-md-block" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M15 150 C35 110, 70 60, 145 20" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            <path d="M45 118 C36 106, 32 92, 42 86 C52 92, 52 106, 45 118 Z" fill="currentColor" opacity="0.45"/>
            <path d="M62 94 C76 83, 89 86, 85 99 C72 105, 62 99, 62 94 Z" fill="currentColor" opacity="0.45"/>
            <path d="M80 72 C71 58, 69 44, 80 39 C91 47, 89 61, 80 72 Z" fill="currentColor" opacity="0.45"/>
            <path d="M102 51 C115 40, 127 45, 123 57 C111 63, 102 57, 102 51 Z" fill="currentColor" opacity="0.45"/>
            <path d="M126 32 C126 18, 138 13, 145 20 C145 32, 136 37, 126 32 Z" fill="currentColor" opacity="0.45"/>
        </svg>

        <div class="container stories-hero-container">
            <!-- Breadcrumbs -->
            <div class="stories-breadcrumb">
                <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                <span class="separator">&gt;</span>
                <span class="current">{{ translate('About Us') }}</span>
            </div>

            <div class="row align-items-center">
                <!-- Left Column -->
                <div class="col-lg-5 col-md-6 pr-lg-4 mb-4 mb-md-0">
                    <h1 class="about-hero-title">
                        {{ translate('More Than Just') }}<br>
                        {{ translate('A Matrimonial Platform') }}
                    </h1>
                    <p class="about-hero-subtitle">
                        {{ translate('We are on a mission to help genuine people find their life partners through a safe, trusted and meaningful platform.') }}
                    </p>
                    
                    <!-- Centered delicate divider with heart -->
                    <div class="about-hero-divider">
                        <span class="line"></span>
                        <span class="heart">&hearts;</span>
                        <span class="line"></span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-7 col-md-6 position-relative">
                    <!-- Floating Calligraphy Script Accent -->
                    <div class="about-calligraphy-badge">
                        <div class="about-calligraphy-text">
                            Real People,<br>
                            Real Stories,<br>
                            Lasting Love
                        </div>
                        <span class="about-calligraphy-heart">&hearts;</span>
                    </div>

                    <!-- Soft Ambient Edge Blended Couple Portrait -->
                    <div class="stories-hero-couple-wrapper">
                        <div class="about-hero-couple-blend w-100">
                            <img src="{{ static_asset('assets/img/custom/about_hero_couple.jpg') }}"
                                 alt="More Than Just A Matrimonial Platform - Couple looking at cityscape"
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. "Our Story" Section (Story Text + Visual + Stat Cards) -->
    <section class="about-story-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Column: Story Text -->
                <div class="col-lg-5 col-md-12 mb-4 mb-lg-0 pr-lg-4">
                    <div class="about-eyebrow">
                        <span>&mdash;</span>
                        <span>{{ translate('OUR STORY') }}</span>
                    </div>
                    <h2 class="about-heading-serif">
                        {{ translate('A Journey Built on Trust, Values and Genuine Connections') }}
                    </h2>
                    <p class="about-story-text">
                        {{ translate('Matrimony was founded with a simple belief — that every person deserves a chance to find a life partner who truly understands, respects and values them.') }}
                    </p>
                    <p class="about-story-text">
                        {{ translate('What started as a small initiative with a big dream has now grown into a trusted matrimonial platform, helping thousands of people across Bangladesh and beyond build meaningful relationships.') }}
                    </p>
                    <p class="about-story-text">
                        {{ translate('We combine technology with a human touch, ensuring a safe, comfortable and authentic experience for everyone who joins us.') }}
                    </p>
                    <a href="#aboutValuesSection" class="btn-mat-maroon rounded-pill px-4 py-2 mt-2">
                        {{ translate('Our Journey') }} &rarr;
                    </a>
                </div>

                <!-- Center Column: Workspace Laptop Visual -->
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <div class="about-workspace-card">
                        <img src="{{ static_asset('assets/img/custom/about_laptop_workspace.jpg') }}"
                             alt="Matrimony Platform Workspace"
                             class="about-workspace-img">
                    </div>
                </div>

                <!-- Right Column: 3 Vertical Metric Cards -->
                <div class="col-lg-3 col-md-6">
                    <!-- Metric Card 1 -->
                    <div class="about-metric-card">
                        <div class="about-metric-icon-box">
                            <i class="las la-users"></i>
                        </div>
                        <div>
                            <div class="about-metric-num">10,000+</div>
                            <div class="about-metric-title">{{ translate('Happy Members') }}</div>
                            <p class="about-metric-desc">{{ translate('And counting...') }}</p>
                        </div>
                    </div>

                    <!-- Metric Card 2 -->
                    <div class="about-metric-card">
                        <div class="about-metric-icon-box">
                            <i class="las la-heart"></i>
                        </div>
                        <div>
                            <div class="about-metric-num">1,500+</div>
                            <div class="about-metric-title">{{ translate('Successful Matches') }}</div>
                            <p class="about-metric-desc">{{ translate('Real stories, real happiness.') }}</p>
                        </div>
                    </div>

                    <!-- Metric Card 3 -->
                    <div class="about-metric-card">
                        <div class="about-metric-icon-box">
                            <i class="las la-shield-alt"></i>
                        </div>
                        <div>
                            <div class="about-metric-num">5+ Years</div>
                            <div class="about-metric-title">{{ translate('of Trust') }}</div>
                            <p class="about-metric-desc">{{ translate('Building better tomorrows since 2020.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Mission, Vision & Values (3-Card Strip) -->
    <section class="about-mission-section" id="aboutValuesSection">
        <div class="container">
            <div class="row">
                <!-- Card 1: Our Mission -->
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <div class="mission-vision-card">
                        <div class="mission-icon-circle">
                            <i class="las la-bullseye"></i>
                        </div>
                        <h3 class="mission-card-title">{{ translate('Our Mission') }}</h3>
                        <p class="mission-card-desc">
                            {{ translate('To connect sincere and compatible individuals in a safe, respectful and transparent environment, helping them build happy and lasting relationships.') }}
                        </p>
                    </div>
                </div>

                <!-- Card 2: Our Vision -->
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <div class="mission-vision-card">
                        <div class="mission-icon-circle">
                            <i class="las la-eye"></i>
                        </div>
                        <h3 class="mission-card-title">{{ translate('Our Vision') }}</h3>
                        <p class="mission-card-desc">
                            {{ translate('To become the most trusted and preferred matrimonial platform in Bangladesh and beyond, known for authenticity, care and commitment to people\'s happiness.') }}
                        </p>
                    </div>
                </div>

                <!-- Card 3: Our Values -->
                <div class="col-lg-4 col-md-4">
                    <div class="mission-vision-card">
                        <div class="mission-icon-circle">
                            <i class="las la-gem"></i>
                        </div>
                        <h3 class="mission-card-title">{{ translate('Our Values') }}</h3>
                        <p class="mission-card-desc">
                            {{ translate('Trust, Respect, Integrity, Innovation and Compassion — these values guide everything we do, from our technology to our customer support.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. "Because Your Future Matters" (Why Choose Us) -->
    <section class="about-why-us-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Column: Framed Quote Visual -->
                <div class="col-lg-5 col-md-12 mb-4 mb-lg-0 pr-lg-4">
                    <img src="{{ static_asset('assets/img/custom/about_framed_quote.jpg') }}"
                         alt="Better People, Better Futures - Framed Quote"
                         class="about-framed-photo">
                </div>

                <!-- Right Column: 6 Features Grid -->
                <div class="col-lg-7 col-md-12 pl-lg-4">
                    <div class="about-eyebrow">
                        <span>&mdash;</span>
                        <span>{{ translate('WHY CHOOSE US') }}</span>
                    </div>
                    <h2 class="about-heading-serif">
                        {{ translate('Because Your Future Matters') }}
                    </h2>
                    <p class="about-story-text mb-4">
                        {{ translate('We go beyond traditional matchmaking. Our platform is designed to give you a safe, personalized and seamless experience — backed by real people and modern technology.') }}
                    </p>

                    <!-- 2x3 Feature Grid -->
                    <div class="about-features-grid">
                        <!-- Feature 1: Verified Profiles -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-shield-alt"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Verified Profiles') }}</div>
                                <p class="about-feature-desc">{{ translate('Real and genuine members with proper verification.') }}</p>
                            </div>
                        </div>

                        <!-- Feature 2: Smart Matching -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-heart"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Smart Matching') }}</div>
                                <p class="about-feature-desc">{{ translate('Advanced filters to find better compatibility.') }}</p>
                            </div>
                        </div>

                        <!-- Feature 3: Privacy Protection -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-lock"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Privacy Protection') }}</div>
                                <p class="about-feature-desc">{{ translate('Your data and privacy are always our priority.') }}</p>
                            </div>
                        </div>

                        <!-- Feature 4: Safe & Secure -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-star"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Safe & Secure') }}</div>
                                <p class="about-feature-desc">{{ translate('A trusted platform with strict monitoring.') }}</p>
                            </div>
                        </div>

                        <!-- Feature 5: Dedicated Support -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-user-friends"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Dedicated Support') }}</div>
                                <p class="about-feature-desc">{{ translate('Our team is always here to assist you.') }}</p>
                            </div>
                        </div>

                        <!-- Feature 6: Easy Communication -->
                        <div class="about-feature-item">
                            <div class="about-feature-icon-circle">
                                <i class="las la-comment-dots"></i>
                            </div>
                            <div>
                                <div class="about-feature-title">{{ translate('Easy Communication') }}</div>
                                <p class="about-feature-desc">{{ translate('Connect and get to know with simple tools.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. "Our Team" Section (People Behind the Purpose) -->
    <section class="about-team-section">
        <div class="container">
            <div class="row align-items-end justify-content-between mb-2">
                <div class="col-lg-7 col-md-8">
                    <div class="about-eyebrow">
                        <span>&mdash;</span>
                        <span>{{ translate('OUR TEAM') }}</span>
                    </div>
                    <h2 class="about-heading-serif mb-2">
                        {{ translate('People Behind the Purpose') }}
                    </h2>
                    <p class="about-story-text mb-0">
                        {{ translate('We are a passionate team of professionals who care about people, relationships and a better future. Together, we work towards making your journey to a happy life easier and safer.') }}
                    </p>
                </div>
                <div class="col-lg-4 col-md-4 text-md-right mt-3 mt-md-0">
                    <a href="#aboutTeamGrid" class="btn-mat-maroon rounded-pill px-4 py-2">
                        {{ translate('Meet Our Team') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- Team Cards Row (4 Columns on Desktop) -->
            <div class="about-team-grid" id="aboutTeamGrid">
                <!-- Member 1: Rezwan Khan -->
                <div class="about-team-card">
                    <img src="{{ static_asset('assets/img/custom/about_team_rezwan.jpg') }}"
                         alt="Rezwan Khan - Founder & CEO"
                         class="about-team-avatar">
                    <h3 class="about-team-name">Rezwan Khan</h3>
                    <p class="about-team-role">{{ translate('Founder & CEO') }}</p>
                </div>

                <!-- Member 2: Nusrat Jahan -->
                <div class="about-team-card">
                    <img src="{{ static_asset('assets/img/custom/about_team_nusrat.jpg') }}"
                         alt="Nusrat Jahan - Operations Manager"
                         class="about-team-avatar">
                    <h3 class="about-team-name">Nusrat Jahan</h3>
                    <p class="about-team-role">{{ translate('Operations Manager') }}</p>
                </div>

                <!-- Member 3: Tariq Hasan -->
                <div class="about-team-card">
                    <img src="{{ static_asset('assets/img/custom/about_team_tariq.jpg') }}"
                         alt="Tariq Hasan - Tech Lead"
                         class="about-team-avatar">
                    <h3 class="about-team-name">Tariq Hasan</h3>
                    <p class="about-team-role">{{ translate('Tech Lead') }}</p>
                </div>

                <!-- Member 4: Sharmin Akter -->
                <div class="about-team-card">
                    <img src="{{ static_asset('assets/img/custom/about_team_sharmin.jpg') }}"
                         alt="Sharmin Akter - Customer Support Lead"
                         class="about-team-avatar">
                    <h3 class="about-team-name">Sharmin Akter</h3>
                    <p class="about-team-role">{{ translate('Customer Support Lead') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Bottom Pre-Footer CTA Banner ("Let's Build a Beautiful Future Together") -->
    <section class="about-bottom-cta-banner">
        <!-- Subtle dark wedding hands background photo -->
        <img src="{{ static_asset('assets/img/custom/story_wedding_rings.jpg') }}"
             alt="Couple holding hands"
             class="about-bottom-cta-bg">

        <div class="container position-relative z-2">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-8 col-md-7 mb-4 mb-md-0">
                    <h2 class="about-cta-title">
                        {{ translate("Let's Build a Beautiful Future Together") }}
                    </h2>
                    <p class="about-cta-subtitle">
                        {{ translate('Join thousands of happy couples who found their life partner through Matrimony.') }}
                    </p>
                </div>
                <div class="col-lg-4 col-md-5 text-md-right">
                    <a href="{{ route('register') }}" class="about-cta-register-btn">
                        <i class="las la-user-plus mr-1"></i> {{ translate('Register Free') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
