@extends('frontend.layouts.app')

@section('content')

<!-- 1. Page Header & Hero Banner -->
<section class="plans-hero-section">
    <!-- Subtle Floral Watermark Accent (SVG) -->
    <svg class="plans-hero-vines" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0C40 20 70 60 80 110C85 130 90 150 110 160" stroke="var(--mat-gold)" stroke-width="1.5" stroke-dasharray="3 3"/>
        <path d="M10 0C50 30 80 75 90 130" stroke="var(--mat-gold)" stroke-width="1.2"/>
        <circle cx="45" cy="30" r="3" fill="var(--mat-gold)" fill-opacity="0.4"/>
        <circle cx="75" cy="70" r="4" fill="var(--mat-gold)" fill-opacity="0.5"/>
        <circle cx="95" cy="125" r="3.5" fill="var(--mat-gold)" fill-opacity="0.4"/>
        <path d="M45 30C52 24 60 26 62 32C56 36 48 34 45 30Z" fill="var(--mat-gold)" fill-opacity="0.3"/>
        <path d="M75 70C82 64 90 66 92 72C86 76 78 74 75 70Z" fill="var(--mat-gold)" fill-opacity="0.3"/>
    </svg>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 position-relative z-2">
        <div class="row align-items-center">
            
            <!-- Left Column: Title, Subtitle, Divider -->
            <div class="col-lg-6 col-md-7 mb-4 mb-md-0">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center list-unstyled p-0 mb-3 fs-13" style="margin: 0; gap: 8px;">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li class="text-muted fs-12">›</li>
                        <li class="text-dark fw-600">Membership Plans</li>
                    </ul>
                </nav>

                <h1 class="plans-hero-title mb-3">Membership Plans</h1>
                <p class="plans-hero-subtitle mb-0">
                    Choose the perfect plan that fits your journey to find love and build a beautiful future together.
                </p>

                <!-- Centered/Left delicate divider with heart (— ♥ —) -->
                <div class="plans-hero-divider mt-4">
                    <div class="plans-hero-divider-line"></div>
                    <i class="las la-heart text-mat-maroon-dark" style="font-size: 16px;"></i>
                    <div class="plans-hero-divider-line"></div>
                </div>
            </div>

            <!-- Right Column: Blended Couple Photo & Floating Script Accent -->
            <div class="col-lg-6 col-md-5 position-relative">
                <div class="plans-couple-wrap">
                    <img src="{{ static_asset('assets/img/custom/plans_hero_couple.jpg') }}" alt="Real Couples Real Stories" class="plans-couple-img">
                    
                    <!-- Floating Script Accent -->
                    <div class="plans-floating-script d-none d-sm-block">
                        <span>Real People</span><br>
                        <span>Real Stories</span><br>
                        <span>Lasting Love</span>
                        <div class="text-center mt-1 text-mat-gold" style="font-size: 13px;">❤</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 2. Segment Toggle Switch (Centered) -->
<div class="plans-gender-toggle-wrap">
    <div class="plans-gender-toggle">
        <button type="button" class="btn plans-toggle-btn active" id="btn-toggle-men" onclick="switchPlanGender('men')">
            For Men
        </button>
        <button type="button" class="btn plans-toggle-btn" id="btn-toggle-women" onclick="switchPlanGender('women')">
            For Women
        </button>
    </div>
</div>

<!-- 3. Tiered Pricing Cards (4 Columns on Desktop) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 mb-4">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 align-items-stretch">

        <!-- Card 1: Basic Plan (White Card) -->
        <div class="col mb-4 mb-lg-0">
            <div class="plan-card plan-card-basic">
                <div>
                    <!-- Circular Icon Badge -->
                    <div class="plan-icon-circle text-mat-gold" style="background-color: var(--mat-maroon-light-bg);">
                        <i class="las la-feather-alt"></i>
                    </div>

                    <h3 class="plan-title text-mat-maroon">Basic Plan</h3>
                    <p class="plan-subtitle mb-4">Perfect for getting started</p>

                    <!-- Price -->
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="plan-price-num text-mat-maroon mr-2">৳ 1,499</span>
                        <span class="plan-price-period text-muted">/ 3 Months</span>
                    </div>

                    <div style="height: 1px; background-color: var(--mat-border-light); margin-bottom: 20px;"></div>

                    <!-- Features List -->
                    <ul class="plan-features-list">
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Create your profile</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Browse matches</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Send up to 10 interests/day</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>View limited profiles</span>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Button -->
                <div class="mt-auto pt-3">
                    @auth
                        @php $basicPkg = $packages->first(); @endphp
                        <a href="{{ $basicPkg ? route('package_payment_methods', encrypt($basicPkg->id)) : route('packages') }}" class="btn btn-plan-outline w-100">
                            Get Started &rarr;
                        </a>
                    @else
                        <a href="javascript:void(0);" onclick="loginModal()" class="btn btn-plan-outline w-100">
                            Get Started &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Card 2: Standard Plan (Most Popular Highlighted Card) -->
        <div class="col mb-4 mb-lg-0">
            <div class="plan-card plan-card-standard">
                <!-- Ribbon Badge -->
                <div class="plan-popular-ribbon">Most Popular</div>

                <div>
                    <!-- Circular Icon Badge -->
                    <div class="plan-icon-circle text-mat-gold" style="background-color: var(--mat-gold-bg);">
                        <i class="las la-crown"></i>
                    </div>

                    <h3 class="plan-title text-mat-maroon">Standard Plan</h3>
                    <p class="plan-subtitle mb-4">More matches, more possibilities</p>

                    <!-- Price -->
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="plan-price-num text-mat-maroon mr-2">৳ 2,999</span>
                        <span class="plan-price-period text-muted">/ 6 Months</span>
                    </div>

                    <div style="height: 1px; background-color: var(--mat-border); margin-bottom: 20px;"></div>

                    <!-- Features List -->
                    <ul class="plan-features-list">
                        <li class="plan-feature-item" style="color: #333;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-gold fw-700"></i>
                            <span>All Basic features</span>
                        </li>
                        <li class="plan-feature-item" style="color: #333;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-gold fw-700"></i>
                            <span>Send up to 30 interests/day</span>
                        </li>
                        <li class="plan-feature-item" style="color: #333;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-gold fw-700"></i>
                            <span>View all profiles</span>
                        </li>
                        <li class="plan-feature-item" style="color: #333;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-gold fw-700"></i>
                            <span>Access to success stories</span>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Button -->
                <div class="mt-auto pt-3">
                    @auth
                        @php $stdPkg = $packages->skip(1)->first() ?? $packages->first(); @endphp
                        <a href="{{ $stdPkg ? route('package_payment_methods', encrypt($stdPkg->id)) : route('packages') }}" class="btn btn-plan-gold w-100">
                            Get Started &rarr;
                        </a>
                    @else
                        <a href="javascript:void(0);" onclick="loginModal()" class="btn btn-plan-gold w-100">
                            Get Started &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Card 3: Premium Plan (White Card) -->
        <div class="col mb-4 mb-lg-0">
            <div class="plan-card plan-card-premium">
                <div>
                    <!-- Circular Icon Badge -->
                    <div class="plan-icon-circle text-mat-gold" style="background-color: var(--mat-maroon-light-bg);">
                        <i class="las la-gem"></i>
                    </div>

                    <h3 class="plan-title text-mat-maroon">Premium Plan</h3>
                    <p class="plan-subtitle mb-4">The complete experience</p>

                    <!-- Price -->
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="plan-price-num text-mat-maroon mr-2">৳ 4,999</span>
                        <span class="plan-price-period text-muted">/ 12 Months</span>
                    </div>

                    <div style="height: 1px; background-color: var(--mat-border-light); margin-bottom: 20px;"></div>

                    <!-- Features List -->
                    <ul class="plan-features-list">
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>All Standard features</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Unlimited interests</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>See who viewed your profile</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Priority customer support</span>
                        </li>
                        <li class="plan-feature-item" style="color: #444;">
                            <i class="las la-check fs-16 mt-0.5 text-mat-maroon-dark fw-700"></i>
                            <span>Access to premium matches</span>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Button -->
                <div class="mt-auto pt-3">
                    @auth
                        @php $premPkg = $packages->last() ?? $packages->first(); @endphp
                        <a href="{{ $premPkg ? route('package_payment_methods', encrypt($premPkg->id)) : route('packages') }}" class="btn btn-plan-outline w-100">
                            Get Started &rarr;
                        </a>
                    @else
                        <a href="javascript:void(0);" onclick="loginModal()" class="btn btn-plan-outline w-100">
                            Get Started &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Card 4: VIP Plan (Luxury Dark Maroon Card) -->
        <div class="col mb-4 mb-lg-0">
            <div class="plan-card plan-card-vip">
                <div>
                    <!-- Circular Icon Badge -->
                    <div class="plan-icon-circle" style="background-color: rgba(255, 255, 255, 0.12); border: 1px solid rgba(229, 168, 82, 0.35); color: #E5A852;">
                        <i class="las la-star"></i>
                    </div>

                    <h3 class="plan-title text-white">VIP Plan</h3>
                    <p class="plan-subtitle mb-4" style="color: #E2D5C8;">Exclusive. Personalized. Premium.</p>

                    <!-- Price -->
                    <div class="d-flex align-items-baseline mb-4">
                        <span class="plan-price-num text-white mr-2">৳ 9,999</span>
                        <span class="plan-price-period" style="color: #D5C7B8;">/ 12 Months</span>
                    </div>

                    <div style="height: 1px; background-color: rgba(255, 255, 255, 0.15); margin-bottom: 20px;"></div>

                    <!-- Features List -->
                    <ul class="plan-features-list">
                        <li class="plan-feature-item" style="color: #FAF4EE;">
                            <i class="las la-check fs-16 mt-0.5" style="color: #E5A852; font-weight: 700;"></i>
                            <span>All Premium features</span>
                        </li>
                        <li class="plan-feature-item" style="color: #FAF4EE;">
                            <i class="las la-check fs-16 mt-0.5" style="color: #E5A852; font-weight: 700;"></i>
                            <span>Personal matchmaker support</span>
                        </li>
                        <li class="plan-feature-item" style="color: #FAF4EE;">
                            <i class="las la-check fs-16 mt-0.5" style="color: #E5A852; font-weight: 700;"></i>
                            <span>Featured profile placement</span>
                        </li>
                        <li class="plan-feature-item" style="color: #FAF4EE;">
                            <i class="las la-check fs-16 mt-0.5" style="color: #E5A852; font-weight: 700;"></i>
                            <span>Video call assistance</span>
                        </li>
                        <li class="plan-feature-item" style="color: #FAF4EE;">
                            <i class="las la-check fs-16 mt-0.5" style="color: #E5A852; font-weight: 700;"></i>
                            <span>Special events & offers</span>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Button -->
                <div class="mt-auto pt-3">
                    @auth
                        @php $vipPkg = $packages->last() ?? $packages->first(); @endphp
                        <a href="{{ $vipPkg ? route('package_payment_methods', encrypt($vipPkg->id)) : route('packages') }}" class="btn btn-plan-gold w-100">
                            Get Started &rarr;
                        </a>
                    @else
                        <a href="javascript:void(0);" onclick="loginModal()" class="btn btn-plan-gold w-100">
                            Get Started &rarr;
                        </a>
                    @endauth
                </div>
            </div>
        </div>

    </div>
</div>

<!-- 4. "Why Trust Us" Feature Strip (6 Features) -->
<section class="plans-trust-strip">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 text-center">

            <!-- 1. Verified Profiles -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-gold" style="background-color: var(--mat-gold-bg);">
                    <i class="las la-shield-alt"></i>
                </div>
                <h4 class="trust-item-title">Verified Profiles</h4>
                <p class="trust-item-desc">Every profile is manually screened by our team.</p>
            </div>

            <!-- 2. Privacy Protection -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-maroon" style="background-color: var(--mat-maroon-light-bg);">
                    <i class="las la-lock"></i>
                </div>
                <h4 class="trust-item-title">Privacy Protection</h4>
                <p class="trust-item-desc">You decide who sees your photos and contact info.</p>
            </div>

            <!-- 3. Safe Communication -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-gold" style="background-color: var(--mat-gold-bg);">
                    <i class="las la-comment-dots"></i>
                </div>
                <h4 class="trust-item-title">Safe Communication</h4>
                <p class="trust-item-desc">Secure chat and interest-sending system.</p>
            </div>

            <!-- 4. Advanced Filters -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-maroon" style="background-color: var(--mat-maroon-light-bg);">
                    <i class="las la-sliders-h"></i>
                </div>
                <h4 class="trust-item-title">Advanced Filters</h4>
                <p class="trust-item-desc">Filter by education, income, family background and more.</p>
            </div>

            <!-- 5. NID/Passport Verification -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-gold" style="background-color: var(--mat-gold-bg);">
                    <i class="las la-id-card"></i>
                </div>
                <h4 class="trust-item-title">NID/Passport Verification</h4>
                <p class="trust-item-desc">Get a blue tick for verified profiles.</p>
            </div>

            <!-- 6. 24/7 Support -->
            <div class="col mb-3 mb-lg-0">
                <div class="trust-item-icon-box text-mat-maroon" style="background-color: var(--mat-maroon-light-bg);">
                    <i class="las la-headset"></i>
                </div>
                <h4 class="trust-item-title">24/7 Support</h4>
                <p class="trust-item-desc">Dedicated customer care for premium members.</p>
            </div>

        </div>
    </div>
</section>

<!-- 5. Conversion Banner & FAQ Section (Bottom Row) -->
<section class="py-5" style="background-color: var(--mat-bg-section); border-top: 1px solid var(--mat-border-light);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="row align-items-center">

            <!-- Left Side: Ready to Find Your Soulmate -->
            <div class="col-lg-4 mb-4 mb-lg-0 pr-lg-4">
                <i class="las la-heart fs-36 text-mat-maroon-dark"></i>
                <h3 class="font-serif fw-700 mt-2 mb-2 text-mat-maroon-dark" style="font-size: 26px; line-height: 1.25;">
                    Ready to Find Your Soulmate?
                </h3>
                <p class="text-muted fs-14 mb-4" style="line-height: 1.55;">
                    Join thousands of happy couples who found their life partner through our trusted platform.
                </p>
                <a href="{{ route('register') }}" class="btn text-white d-inline-flex align-items-center" style="background: var(--mat-maroon-dark); border-radius: 9999px; padding: 11px 26px; font-size: 14px; font-weight: 600; gap: 8px; text-decoration: none; box-shadow: 0 4px 14px rgba(44, 10, 18, 0.2);">
                    <i class="las la-user-plus fs-16"></i>
                    <span>Register Free &rarr;</span>
                </a>
            </div>

            <!-- Middle Side: Frequently Asked Questions Accordion -->
            <div class="col-lg-5 mb-4 mb-lg-0 px-lg-3">
                <h3 class="font-serif fw-700 mb-3 text-mat-maroon-dark" style="font-size: 20px;">
                    Frequently Asked Questions
                </h3>

                <!-- FAQ 1 -->
                <div class="plans-faq-card open" id="faq-card-1">
                    <div class="plans-faq-header" onclick="toggleFaq('faq-card-1')">
                        <span>Can I change my plan later?</span>
                        <span class="plans-faq-toggle-icon">+</span>
                    </div>
                    <div class="plans-faq-body">
                        Yes, you can upgrade your membership plan at any time from your account settings. The remaining balance will be adjusted accordingly.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="plans-faq-card" id="faq-card-2">
                    <div class="plans-faq-header" onclick="toggleFaq('faq-card-2')">
                        <span>Is my personal information safe?</span>
                        <span class="plans-faq-toggle-icon">+</span>
                    </div>
                    <div class="plans-faq-body">
                        Absolutely. We employ bank-grade encryption and give you 100% control over who can view your photos and contact details.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="plans-faq-card" id="faq-card-3">
                    <div class="plans-faq-header" onclick="toggleFaq('faq-card-3')">
                        <span>What payment methods do you accept?</span>
                        <span class="plans-faq-toggle-icon">+</span>
                    </div>
                    <div class="plans-faq-body">
                        We accept all major credit/debit cards, bKash, Nagad, Rocket, and bank transfers through our secure payment gateway.
                    </div>
                </div>
            </div>

            <!-- Right Side: Decorative Linked Hearts & Script Text -->
            <div class="col-lg-3 text-center pl-lg-4">
                <div class="p-4 rounded-2xl position-relative" style="background: rgba(255, 255, 255, 0.65); border: 1px solid var(--mat-border-light); border-radius: 16px;">
                    <!-- Linked Hearts SVG -->
                    <svg width="60" height="42" viewBox="0 0 60 42" fill="none" class="mx-auto mb-3" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 6C17 1 9 4 9 11C9 19 22 29 22 29C22 29 35 19 35 11C35 4 27 1 22 6Z" fill="#F4ECE1" stroke="var(--mat-gold)" stroke-width="1.6" stroke-linejoin="round"/>
                        <path d="M38 12C33 7 25 10 25 17C25 25 38 35 38 35C38 35 51 25 51 17C51 10 43 7 38 12Z" fill="var(--mat-maroon-light-bg)" stroke="var(--mat-maroon-dark)" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>

                    <div class="font-serif" style="font-style: italic; font-size: 20px; font-weight: 600; color: var(--mat-gold); line-height: 1.3;">
                        Better Matches<br>Brighter Futures
                    </div>
                    <div class="mt-2 text-muted fs-11 opacity-60">✦ ✦ ✦</div>
                </div>
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
    // Login alert
    function loginModal(){
        $('#LoginModal').modal();
    }

    // Package update alert
    function package_update_alert(){
        $('.package_update_alert_modal').modal('show');
    }

    // Gender Switch Handler
    function switchPlanGender(gender) {
        if (gender === 'men') {
            document.getElementById('btn-toggle-men').classList.add('active');
            document.getElementById('btn-toggle-women').classList.remove('active');
        } else {
            document.getElementById('btn-toggle-women').classList.add('active');
            document.getElementById('btn-toggle-men').classList.remove('active');
        }
    }

    // FAQ Accordion Toggle
    function toggleFaq(cardId) {
        var card = document.getElementById(cardId);
        if (card.classList.contains('open')) {
            card.classList.remove('open');
        } else {
            // Close all
            var cards = document.querySelectorAll('.plans-faq-card');
            cards.forEach(function(c) {
                c.classList.remove('open');
            });
            card.classList.add('open');
        }
    }
</script>
@endsection
