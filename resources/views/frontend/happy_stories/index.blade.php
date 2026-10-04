@extends('frontend.layouts.app')

@section('content')
<div class="success-stories-page">

    <!-- 1. Page Header & Hero Banner -->
    <section class="stories-hero-section py-5">
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
                <span class="current">{{ translate('Success Stories') }}</span>
            </div>

            <div class="row align-items-center">
                <!-- Left Column -->
                <div class="col-lg-5 col-md-6 pr-lg-4 mb-4 mb-md-0">
                    <h1 class="stories-hero-title">
                        {{ translate('Real People, Real Love') }}
                    </h1>
                    <p class="stories-hero-subtitle">
                        {{ translate('Discover inspiring stories of couples who found their life partners through our platform.') }}
                    </p>
                </div>

                <!-- Right Column -->
                <div class="col-lg-7 col-md-6 position-relative">
                    <!-- Floating Calligraphy Script Accent -->
                    <div class="hero-calligraphy-badge">
                        <div class="hero-calligraphy-text">
                            Different<br>
                            Journeys<br>
                            Same Destination<br>
                            Love
                        </div>
                        <span class="hero-calligraphy-heart">&hearts;</span>
                    </div>

                    <!-- Soft Ambient Edge Blended Couple Portrait -->
                    <div class="stories-hero-couple-wrapper">
                        <div class="stories-hero-couple-blend w-100">
                            <img src="{{ static_asset('assets/img/custom/story_hero_couple.jpg') }}"
                                 alt="Real People, Real Love - Couple in Wedding Attire"
                                 class="stories-hero-couple-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. "Featured Success Stories" Section (Carousel / Grid) -->
    <section class="featured-stories-section">
        <div class="container">
            <!-- Header Row -->
            <div class="row align-items-end justify-content-between mb-2">
                <div class="col-lg-6 col-md-7">
                    <div class="section-eyebrow">
                        <i class="las la-heart heart-icon"></i>
                        <span>{{ translate('FEATURED SUCCESS STORIES') }}</span>
                    </div>
                    <h2 class="section-main-heading">
                        {{ translate('Love Stories That Inspire') }}
                    </h2>
                </div>
                <div class="col-lg-5 col-md-5 mt-3 mt-md-0 text-md-right text-lg-left">
                    <p class="section-desc-text ml-md-auto mb-0">
                        {{ translate('These couples found their perfect match on our platform. Their stories are a reminder that true love is always possible.') }}
                    </p>
                </div>
            </div>

            <!-- Vertical Multi-Row Grid Layout -->
            <div class="stories-showcase-container" id="storiesShowcaseContainer">
                
                <!-- ROW 1 - Card 1: Spotlight Video Card (Double Width / Spans 2 Columns) -->
                <div class="story-card-item featured-spotlight" data-card-index="0">
                    <div class="story-card-media">
                        <!-- Top-Left Pill Badge -->
                        <span class="story-type-badge">
                            <i class="las la-video"></i> {{ translate('Video Story') }}
                        </span>

                        <!-- Top-Right Expand/Action Icon -->
                        <button type="button" class="story-action-top-btn" title="View Story" onclick="openStoryVideoModal('Arif & Tasmia', 'Dhaka, Bangladesh', 'Married · 1 Year', 'We met on this platform, and now we\'re building our forever.')">
                            <i class="las la-expand-arrows-alt"></i>
                        </button>

                        <!-- Couple Photo -->
                        <img src="{{ static_asset('assets/img/custom/story_arif_tasmia.jpg') }}" alt="Arif & Tasmia - Success Story">

                        <!-- Centered Circular White Play Button with Maroon Play Triangle -->
                        <button type="button" class="story-play-overlay-btn" aria-label="Play Video Story" onclick="openStoryVideoModal('Arif & Tasmia', 'Dhaka, Bangladesh', 'Married · 1 Year', 'We met on this platform, and now we\'re building our forever.')">
                            <i class="las la-play"></i>
                        </button>

                        <!-- Bottom-Right Duration Badge -->
                        <span class="story-duration-badge">03:45</span>
                    </div>

                    <!-- Info Bar Below -->
                    <div class="story-card-body">
                        <div class="story-spotlight-bar">
                            <!-- Left Details -->
                            <div>
                                <h3 class="story-couple-name mb-1">Arif &amp; Tasmia</h3>
                                <div class="story-meta-row">
                                    <span class="story-meta-item">
                                        <i class="las la-map-marker-alt"></i> Dhaka, Bangladesh
                                    </span>
                                    <span class="story-meta-item">
                                        <i class="las la-ring"></i> Married &middot; 1 Year
                                    </span>
                                </div>
                            </div>
                            <!-- Right Quote -->
                            <div class="story-spotlight-quote">
                                &ldquo;{{ translate("We met on this platform, and now we're building our forever.") }}&rdquo;
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 1 - Card 2: Standard Text Story Card (Spans 1 Column alongside Spotlight) -->
                <div class="story-card-item standard-card" data-card-index="1">
                    <div class="story-card-media">
                        <!-- Top-Left Pill Badge -->
                        <span class="story-type-badge">
                            <i class="las la-file-alt"></i> {{ translate('Text Story') }}
                        </span>

                        <!-- Couple Portrait -->
                        <img src="{{ static_asset('assets/img/custom/story_rafi_nusrat.jpg') }}" alt="Rafi & Nusrat - Success Story">
                    </div>

                    <!-- Body -->
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Rafi &amp; Nusrat</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Chittagong, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 2 Years
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate('We never imagined we would meet someone so compatible. Thank you for making this possible!') }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 2 - Card 3: Standard Text Story Card -->
                <div class="story-card-item standard-card" data-card-index="2">
                    <div class="story-card-media">
                        <!-- Top-Left Pill Badge -->
                        <span class="story-type-badge">
                            <i class="las la-file-alt"></i> {{ translate('Text Story') }}
                        </span>

                        <!-- Couple Portrait -->
                        <img src="{{ static_asset('assets/img/custom/story_hasan_farhana.jpg') }}" alt="Hasan & Farhana - Success Story">
                    </div>

                    <!-- Body -->
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Hasan &amp; Farhana</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Sylhet, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 1.5 Years
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate('Our families connected first, and then we got to know each other. It was a beautiful journey from start to finish.') }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 2 - Card 4: Standard Video Story Card -->
                <div class="story-card-item standard-card" data-card-index="3">
                    <div class="story-card-media">
                        <!-- Top-Left Pill Badge -->
                        <span class="story-type-badge">
                            <i class="las la-video"></i> {{ translate('Video Story') }}
                        </span>

                        <!-- Couple Portrait -->
                        <img src="{{ static_asset('assets/img/custom/story_imran_ayesha.jpg') }}" alt="Imran & Ayesha - Success Story">

                        <!-- Play Overlay Trigger -->
                        <button type="button" class="story-play-overlay-btn" style="width: 44px; height: 44px; font-size: 16px;" aria-label="Play Video" onclick="openStoryVideoModal('Imran & Ayesha', 'Dhaka, Bangladesh', 'Married · 3 Years', 'This platform gave us the opportunity to meet, and we\'re so grateful for that.')">
                            <i class="las la-play"></i>
                        </button>

                        <!-- Bottom-Right Duration Badge -->
                        <span class="story-duration-badge">04:12</span>
                    </div>

                    <!-- Body -->
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Imran &amp; Ayesha</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Dhaka, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 3 Years
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate("This platform gave us the opportunity to meet, and we're so grateful for that.") }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 2 - Card 5: Standard Text Story Card (Fills Column 3 of Row 2) -->
                <div class="story-card-item standard-card" data-card-index="4">
                    <div class="story-card-media">
                        <!-- Top-Left Pill Badge -->
                        <span class="story-type-badge">
                            <i class="las la-file-alt"></i> {{ translate('Text Story') }}
                        </span>

                        <!-- Couple Portrait -->
                        <img src="{{ static_asset('assets/img/custom/story_tanvir_maliha.jpg') }}" alt="Tanvir & Maliha - Success Story">
                    </div>

                    <!-- Body -->
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Tanvir &amp; Maliha</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Dhaka, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 8 Months
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate("We both wanted someone with shared values. This platform connected our hearts effortlessly.") }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 3 (Expandable / Load More Stories) - Card 6: Video Story Card -->
                <div class="story-card-item standard-card story-more-item" data-card-index="5">
                    <div class="story-card-media">
                        <span class="story-type-badge">
                            <i class="las la-video"></i> {{ translate('Video Story') }}
                        </span>
                        <img src="{{ static_asset('assets/img/custom/story_fahim_fatema.jpg') }}" alt="Fahim & Fatema - Success Story">
                        <button type="button" class="story-play-overlay-btn" style="width: 44px; height: 44px; font-size: 16px;" aria-label="Play Video" onclick="openStoryVideoModal('Fahim & Fatema', 'Sylhet, Bangladesh', 'Married · 2.5 Years', 'Finding true compatibility is a blessing. Grateful beyond words for this journey.')">
                            <i class="las la-play"></i>
                        </button>
                        <span class="story-duration-badge">02:50</span>
                    </div>
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Fahim &amp; Fatema</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Sylhet, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 2.5 Years
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate("Finding true compatibility is a blessing. Grateful beyond words for this journey.") }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 3 (Expandable / Load More Stories) - Card 7: Text Story Card -->
                <div class="story-card-item standard-card story-more-item" data-card-index="6">
                    <div class="story-card-media">
                        <span class="story-type-badge">
                            <i class="las la-file-alt"></i> {{ translate('Text Story') }}
                        </span>
                        <img src="{{ static_asset('assets/img/custom/hero_couple.jpg') }}" alt="Shahriar & Sabina - Success Story">
                    </div>
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Shahriar &amp; Sabina</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Khulna, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 1 Year
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate("From our first conversation, everything felt natural and right. Forever thankful to this service.") }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ROW 3 (Expandable / Load More Stories) - Card 8: Text Story Card -->
                <div class="story-card-item standard-card story-more-item" data-card-index="7">
                    <div class="story-card-media">
                        <span class="story-type-badge">
                            <i class="las la-file-alt"></i> {{ translate('Text Story') }}
                        </span>
                        <img src="{{ static_asset('assets/img/custom/story_couple.jpg') }}" alt="Nasir & Rumana - Success Story">
                    </div>
                    <div class="story-card-body">
                        <h3 class="story-couple-name">Nasir &amp; Rumana</h3>
                        <div class="story-meta-row mb-2">
                            <span class="story-meta-item">
                                <i class="las la-map-marker-alt"></i> Rajshahi, Bangladesh
                            </span>
                            <span class="story-meta-item">
                                <i class="las la-ring"></i> Married &middot; 4 Years
                            </span>
                        </div>
                        <p class="story-quote-box">
                            &ldquo;{{ translate("Four years of marital bliss started right here. Trust the process, your soulmate is waiting.") }}&rdquo;
                        </p>
                        <div class="story-card-footer">
                            <button type="button" class="story-heart-toggle-btn" aria-label="Like story" onclick="toggleStoryLike(this)">
                                <i class="lar la-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- "Load More" Action Trigger Button -->
            <div class="stories-load-more-wrapper">
                <button type="button" class="stories-load-more-btn" id="loadMoreStoriesBtn" onclick="handleLoadMoreStories()">
                    <span>{{ translate('Load More Stories') }}</span>
                    <i class="las la-arrow-down"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- 3. Statistics & Trust Counters Strip -->
    <section class="stats-trust-strip-container">
        <!-- Subtle Floral Corner Flourishes -->
        <svg class="botanical-flourish flourish-stats-l" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M15 150 C35 110, 70 60, 145 20" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            <path d="M45 118 C36 106, 32 92, 42 86 C52 92, 52 106, 45 118 Z" fill="currentColor" opacity="0.45"/>
            <path d="M62 94 C76 83, 89 86, 85 99 C72 105, 62 99, 62 94 Z" fill="currentColor" opacity="0.45"/>
            <path d="M80 72 C71 58, 69 44, 80 39 C91 47, 89 61, 80 72 Z" fill="currentColor" opacity="0.45"/>
            <path d="M102 51 C115 40, 127 45, 123 57 C111 63, 102 57, 102 51 Z" fill="currentColor" opacity="0.45"/>
            <path d="M126 32 C126 18, 138 13, 145 20 C145 32, 136 37, 126 32 Z" fill="currentColor" opacity="0.45"/>
        </svg>

        <svg class="botanical-flourish flourish-stats-r" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M15 150 C35 110, 70 60, 145 20" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            <path d="M45 118 C36 106, 32 92, 42 86 C52 92, 52 106, 45 118 Z" fill="currentColor" opacity="0.45"/>
            <path d="M62 94 C76 83, 89 86, 85 99 C72 105, 62 99, 62 94 Z" fill="currentColor" opacity="0.45"/>
            <path d="M80 72 C71 58, 69 44, 80 39 C91 47, 89 61, 80 72 Z" fill="currentColor" opacity="0.45"/>
            <path d="M102 51 C115 40, 127 45, 123 57 C111 63, 102 57, 102 51 Z" fill="currentColor" opacity="0.45"/>
            <path d="M126 32 C126 18, 138 13, 145 20 C145 32, 136 37, 126 32 Z" fill="currentColor" opacity="0.45"/>
        </svg>

        <div class="container">
            <div class="stats-grid-row">
                <!-- Column 1: Verified Profiles -->
                <div class="stat-item-col">
                    <div class="stat-icon-wrapper">
                        <i class="las la-user-check"></i>
                    </div>
                    <div class="stat-metric-number">10,000+</div>
                    <div class="stat-label-title">{{ translate('Verified Profiles') }}</div>
                    <p class="stat-sub-text">{{ translate('Real people, genuine intentions.') }}</p>
                </div>

                <!-- Column 2: Success Stories -->
                <div class="stat-item-col">
                    <div class="stat-icon-wrapper icon-heart">
                        <i class="las la-heart"></i>
                    </div>
                    <div class="stat-metric-number">500+</div>
                    <div class="stat-label-title">{{ translate('Success Stories') }}</div>
                    <p class="stat-sub-text">{{ translate('Real love, real happiness.') }}</p>
                </div>

                <!-- Column 3: Communities Served -->
                <div class="stat-item-col">
                    <div class="stat-icon-wrapper">
                        <i class="las la-users"></i>
                    </div>
                    <div class="stat-metric-number">50+</div>
                    <div class="stat-label-title">{{ translate('Communities Served') }}</div>
                    <p class="stat-sub-text">{{ translate('Diverse backgrounds, united by love.') }}</p>
                </div>

                <!-- Column 4: Safe & Secure -->
                <div class="stat-item-col">
                    <div class="stat-icon-wrapper icon-shield">
                        <i class="las la-shield-alt"></i>
                    </div>
                    <div class="stat-metric-number">100%</div>
                    <div class="stat-label-title">{{ translate('Safe & Secure') }}</div>
                    <p class="stat-sub-text">{{ translate('Your trust is our priority.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Community Stories from Database (if present) -->
    @if(isset($happy_stories) && $happy_stories->count() > 0)
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="font-serif fw-700 text-mat-maroon fs-28 mb-2">{{ translate('More Happy Couples') }}</h3>
                <p class="text-muted fs-14">{{ translate('Read more stories shared directly by our valued members') }}</p>
            </div>
            <div class="row">
                @foreach ($happy_stories as $key => $happy_story)
                    @php
                        $photo = explode(',', $happy_story->photos);
                    @endphp
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border rounded-xl overflow-hidden shadow-sm hov-shadow-md transition-all">
                            <a href="{{ route('story_details', $happy_story->id) }}" class="text-reset d-block overflow-hidden" style="height: 220px;">
                                <img src="{{ uploaded_asset($photo[0]) }}" class="img-fluid w-100 h-100 img-fit" alt="{{ $happy_story->title }}">
                            </a>
                            <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <h4 class="font-serif fw-600 fs-17 mb-2">
                                        <a href="{{ route('story_details', $happy_story->id) }}" class="text-dark">{{ $happy_story->title }}</a>
                                    </h4>
                                    <div class="text-muted fs-12 mb-3">
                                        <span>{{ translate('Posted By') }}:</span>
                                        <span class="text-mat-maroon fw-600">{{ $happy_story->user->first_name.' '.$happy_story->user->last_name }}</span>
                                        <span class="mx-1">&middot;</span>
                                        <span>{{ $happy_story->created_at->format('d M, Y') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('story_details', $happy_story->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill align-self-start">{{ translate('Read Full Story') }} &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{ $happy_stories->appends(request()->input())->links() }}
            </div>
        </div>
    </section>
    @endif

    <!-- 4. Bottom Pre-Footer CTA Banner ("Your Story Could Be Next") -->
    <section class="container px-3">
        <div class="stories-bottom-cta-banner">
            <!-- Left: Warm photo of couple looking at horizon -->
            <div class="banner-left-media">
                <img src="{{ static_asset('assets/img/custom/story_bottom_couple.jpg') }}"
                     alt="Couple looking at sunset horizon"
                     class="banner-couple-photo">
            </div>

            <!-- Center Content -->
            <div class="banner-center-content">
                <h3 class="banner-cta-heading">
                    {{ translate('Your Story Could Be Next') }}
                </h3>
                <p class="banner-cta-subtitle">
                    {{ translate('Join thousands of happy couples who found their life partner through our trusted platform.') }}
                </p>
                <a href="{{ route('register') }}" class="banner-register-btn">
                    <i class="las la-user-plus mr-1"></i> {{ translate('Register Free') }} &rarr;
                </a>
            </div>

            <!-- Right Calligraphy Script -->
            <div class="banner-right-script">
                <div class="banner-script-quote">
                    Find<br>
                    Your<br>
                    Soulmate
                </div>
                <span class="banner-script-heart">&hearts;</span>
            </div>
        </div>
    </section>

</div>

<!-- Interactive Video Story Modal -->
<div class="modal fade" id="storyVideoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-2xl border-0 overflow-hidden shadow-2xl">
            <div class="modal-header bg-mat-maroon text-white py-3 px-4 d-flex align-items-center justify-content-between border-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="las la-film fs-20 text-mat-gold-light mr-2"></i>
                    <div>
                        <h5 class="modal-title font-serif fw-700 fs-18 mb-0" id="videoModalCoupleTitle">Arif &amp; Tasmia</h5>
                        <small class="text-white-50 fs-12" id="videoModalCoupleMeta">Dhaka, Bangladesh &middot; Married · 1 Year</small>
                    </div>
                </div>
                <button type="button" class="close text-white opacity-80 hov-opacity-100 p-0 m-0" data-dismiss="modal" aria-label="Close" style="font-size: 28px; line-height: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 bg-dark position-relative">
                <div class="embed-responsive embed-responsive-16by9 bg-black">
                    <!-- Standard HTML5 Video Player with Wedding Video Showcase -->
                    <video id="storyVideoPlayer" class="embed-responsive-item" controls playsinline poster="{{ static_asset('assets/img/custom/story_arif_tasmia.jpg') }}">
                        <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" type="video/mp4">
                        {{ translate('Your browser does not support video playback.') }}
                    </video>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between align-items-center border-0">
                <p class="fst-italic text-muted fs-13 mb-0" id="videoModalQuote">
                    &ldquo;We met on this platform, and now we're building our forever.&rdquo;
                </p>
                <button type="button" class="btn btn-sm btn-mat-maroon rounded-pill px-4" data-dismiss="modal">
                    {{ translate('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.login_modal')
    @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">
    // Login alert
    function loginModal() {
        $('#LoginModal').modal();
    }

    // Package update alert
    function package_update_alert() {
        $('.package_update_alert_modal').modal('show');
    }

    // Interactive Heart Like Toggle
    function toggleStoryLike(btn) {
        btn.classList.toggle('liked');
        const icon = btn.querySelector('i');
        if (btn.classList.contains('liked')) {
            icon.classList.remove('lar');
            icon.classList.add('las');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('success', 'Story added to your favorites!');
            }
        } else {
            icon.classList.remove('las');
            icon.classList.add('lar');
        }
    }

    // Video Story Modal Handler
    function openStoryVideoModal(coupleName, meta, quote) {
        document.getElementById('videoModalCoupleTitle').innerText = coupleName;
        document.getElementById('videoModalCoupleMeta').innerText = meta;
        document.getElementById('videoModalQuote').innerHTML = '&ldquo;' + quote + '&rdquo;';
        
        $('#storyVideoModal').modal('show');
        
        const player = document.getElementById('storyVideoPlayer');
        if (player) {
            player.play().catch(function(e) {
                // Autoplay might be muted or blocked by browser policy
            });
        }
    }

    $('#storyVideoModal').on('hidden.bs.modal', function () {
        const player = document.getElementById('storyVideoPlayer');
        if (player) {
            player.pause();
            player.currentTime = 0;
        }
    });

    // Load More Stories Action Handler
    function handleLoadMoreStories() {
        const moreItems = document.querySelectorAll('.story-card-item.story-more-item');
        const btn = document.getElementById('loadMoreStoriesBtn');
        if (moreItems.length > 0) {
            moreItems.forEach(function (item) {
                item.classList.add('revealed');
            });
            if (btn) {
                btn.innerHTML = '<span>{{ translate("All Stories Loaded") }}</span> <i class="las la-check"></i>';
                btn.style.opacity = '0.6';
                btn.style.pointerEvents = 'none';
            }
        }
    }
</script>
@endsection
