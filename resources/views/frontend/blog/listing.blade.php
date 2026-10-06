@extends('frontend.layouts.app')

@section('content')
<div class="blog-listing-page">

    <!-- 1. Page Header & Hero Banner -->
    <section class="blog-hero-section">
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
                <span class="current">{{ translate('Blog') }}</span>
            </div>

            <div class="row align-items-center">
                <!-- Left Column -->
                <div class="col-lg-6 col-md-6 pr-lg-4 mb-4 mb-md-0">
                    <div class="blog-hero-eyebrow">
                        <span>{{ translate('OUR BLOG') }}</span>
                    </div>
                    <h1 class="blog-hero-title">
                        {{ translate('Thoughts, Tips &') }}<br>
                        {{ translate('Inspiration for a Better Journey') }}
                    </h1>
                    <p class="blog-hero-subtitle">
                        {{ translate('Explore our latest articles on relationships, marriage, lifestyle, personal growth and more.') }}
                    </p>
                    
                    <!-- Centered delicate divider with heart -->
                    <div class="blog-hero-divider">
                        <span class="line"></span>
                        <span class="heart">&hearts;</span>
                        <span class="line"></span>
                    </div>
                </div>

                <!-- Right Column: Visual & Calligraphy -->
                <div class="col-lg-6 col-md-6 position-relative">
                    <!-- Floating Calligraphy Script Accent -->
                    <div class="blog-calligraphy-badge">
                        <div class="blog-calligraphy-text">
                            Better People<br>
                            Better Relationships<br>
                            A Brighter Future
                        </div>
                        <span class="blog-calligraphy-heart">&hearts;</span>
                    </div>

                    <!-- Soft Ambient Edge Blended Couple Portrait -->
                    <div class="stories-hero-couple-wrapper">
                        <div class="about-hero-couple-blend w-100">
                            <img src="{{ static_asset('assets/img/custom/about_hero_couple.jpg') }}"
                                 alt="Thoughts, Tips & Inspiration - Couple looking out over city skyline"
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Category Filter Pills & 3. Main Two-Column Layout -->
    <section class="py-5 bg-white">
        <div class="container">
            
            <!-- Category Filter Pills Row -->
            <div class="blog-categories-filter-bar mb-4">
                <a href="{{ route('blog') }}" class="blog-filter-pill {{ empty($category_slug) || $category_slug == 'all' ? 'active' : '' }}">
                    {{ translate('All') }}
                </a>
                <a href="{{ route('blog', ['category' => 'relationships']) }}" class="blog-filter-pill {{ $category_slug == 'relationships' ? 'active' : '' }}">
                    {{ translate('Relationships') }}
                </a>
                <a href="{{ route('blog', ['category' => 'marriage-tips']) }}" class="blog-filter-pill {{ $category_slug == 'marriage-tips' ? 'active' : '' }}">
                    {{ translate('Marriage Tips') }}
                </a>
                <a href="{{ route('blog', ['category' => 'lifestyle']) }}" class="blog-filter-pill {{ $category_slug == 'lifestyle' ? 'active' : '' }}">
                    {{ translate('Lifestyle') }}
                </a>
                <a href="{{ route('blog', ['category' => 'personal-growth']) }}" class="blog-filter-pill {{ $category_slug == 'personal-growth' ? 'active' : '' }}">
                    {{ translate('Personal Growth') }}
                </a>
                <a href="{{ route('blog', ['category' => 'others']) }}" class="blog-filter-pill {{ $category_slug == 'others' ? 'active' : '' }}">
                    {{ translate('Others') }}
                </a>
            </div>

            <!-- Two-Column Main Grid -->
            <div class="row align-items-start">
                
                <!-- A. Left Main Content Area (8 Columns) -->
                <div class="col-lg-8 col-md-12 mb-5 mb-lg-0">
                    
                    @php
                        // Map predefined images and estimated read times for the 6 showcased blog posts
                        $articleImages = [
                            'how-to-build-a-strong-foundation-in-a-relationship' => 'assets/img/custom/blog_card_1.jpg',
                            'essential-things-to-consider-before-marriage' => 'assets/img/custom/blog_card_2.jpg',
                            'simple-habits-for-a-happier-and-healthier-life' => 'assets/img/custom/blog_card_3.jpg',
                            'the-power-of-patience-in-love-and-life' => 'assets/img/custom/blog_card_4.jpg',
                            'digital-communication-in-modern-relationships' => 'assets/img/custom/blog_card_5.jpg',
                            'small-gestures-that-make-a-big-difference' => 'assets/img/custom/blog_card_6.jpg',
                        ];

                        $readTimes = [
                            'how-to-build-a-strong-foundation-in-a-relationship' => '5 min read',
                            'essential-things-to-consider-before-marriage' => '6 min read',
                            'simple-habits-for-a-happier-and-healthier-life' => '4 min read',
                            'the-power-of-patience-in-love-and-life' => '5 min read',
                            'digital-communication-in-modern-relationships' => '4 min read',
                            'small-gestures-that-make-a-big-difference' => '5 min read',
                        ];
                    @endphp

                    @if($blogs->count() > 0)
                        <!-- 2-Column Article Cards Grid -->
                        <div class="blog-articles-grid">
                            @foreach($blogs as $blog)
                                @php
                                    $imgUrl = isset($articleImages[$blog->slug]) 
                                        ? static_asset($articleImages[$blog->slug]) 
                                        : ($blog->banner ? uploaded_asset($blog->banner) : static_asset('assets/img/custom/about_laptop_workspace.jpg'));
                                    
                                    $readTime = isset($readTimes[$blog->slug]) ? $readTimes[$blog->slug] : '5 min read';
                                    $catName = $blog->category ? $blog->category->category_name : 'General';
                                @endphp
                                
                                <article class="blog-card-item">
                                    <!-- Image Container -->
                                    <div class="blog-card-media">
                                        <a href="{{ url('blog/' . $blog->slug) }}" class="d-block w-100 h-100">
                                            <img src="{{ $imgUrl }}" alt="{{ $blog->title }}">
                                        </a>
                                        <!-- Category Tag Badge -->
                                        <span class="blog-card-tag">{{ $catName }}</span>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="blog-card-body">
                                        <div>
                                            <h2 class="blog-card-title">
                                                <a href="{{ url('blog/' . $blog->slug) }}">
                                                    {{ $blog->title }}
                                                </a>
                                            </h2>
                                            <p class="blog-card-excerpt">
                                                {{ $blog->short_description }}
                                            </p>
                                        </div>

                                        <div>
                                            <!-- Meta Info: Date and Read Time -->
                                            <div class="blog-card-meta">
                                                <span class="blog-card-meta-item">
                                                    <i class="lar la-calendar"></i>
                                                    {{ $blog->created_at->format('M d, Y') }}
                                                </span>
                                                <span class="blog-card-meta-item">
                                                    <i class="lar la-clock"></i>
                                                    {{ $readTime }}
                                                </span>
                                            </div>

                                            <!-- Action Link -->
                                            <a href="{{ url('blog/' . $blog->slug) }}" class="blog-read-more-link">
                                                {{ translate('Read More') }} &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Centered Pagination -->
                        <div class="blog-pagination-wrapper">
                            @if ($blogs->onFirstPage())
                                <span class="blog-page-btn disabled opacity-40">&lsaquo; {{ translate('Previous') }}</span>
                            @else
                                <a href="{{ $blogs->previousPageUrl() }}" class="blog-page-btn">&lsaquo; {{ translate('Previous') }}</a>
                            @endif

                            @for ($i = 1; $i <= min(3, $blogs->lastPage()); $i++)
                                <a href="{{ $blogs->url($i) }}" class="blog-page-btn {{ $blogs->currentPage() == $i ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor

                            @if ($blogs->hasMorePages())
                                <a href="{{ $blogs->nextPageUrl() }}" class="blog-page-btn">{{ translate('Next') }} &rsaquo;</a>
                            @else
                                <span class="blog-page-btn disabled opacity-40">{{ translate('Next') }} &rsaquo;</span>
                            @endif
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="las la-book-open fs-48 text-muted mb-3 d-block"></i>
                            <h4 class="font-serif fw-700 text-mat-maroon mb-2">{{ translate('No Articles Found') }}</h4>
                            <p class="text-muted">{{ translate('Try adjusting your search or category filter.') }}</p>
                            <a href="{{ route('blog') }}" class="btn btn-mat-maroon btn-sm rounded-pill mt-2">
                                {{ translate('View All Articles') }}
                            </a>
                        </div>
                    @endif

                </div>

                <!-- B. Right Sidebar Widgets (4 Columns) -->
                <div class="col-lg-4 col-md-12">
                    
                    <!-- Widget 1: Search Box -->
                    <div class="blog-sidebar-widget">
                        <form action="{{ route('blog') }}" method="GET" class="blog-search-form">
                            <input type="text" 
                                   name="search" 
                                   class="blog-search-input" 
                                   placeholder="{{ translate('Search articles...') }}" 
                                   value="{{ $search ?? '' }}">
                            <button type="submit" class="blog-search-btn" aria-label="Search">
                                <i class="las la-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Widget 2: Categories Card -->
                    <div class="blog-sidebar-widget">
                        <h3 class="blog-widget-title">
                            <i class="las la-users"></i>
                            <span>{{ translate('Categories') }}</span>
                        </h3>
                        <ul class="blog-categories-list">
                            @php
                                $categoriesData = [
                                    ['name' => 'Relationships', 'slug' => 'relationships', 'icon' => 'lar la-heart', 'count' => '12'],
                                    ['name' => 'Marriage Tips', 'slug' => 'marriage-tips', 'icon' => 'lar la-heart', 'count' => '08'],
                                    ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'icon' => 'las la-shield-alt', 'count' => '06'],
                                    ['name' => 'Personal Growth', 'slug' => 'personal-growth', 'icon' => 'las la-compass', 'count' => '07'],
                                    ['name' => 'Others', 'slug' => 'others', 'icon' => 'las la-circle', 'count' => '05'],
                                ];
                            @endphp

                            @foreach($categoriesData as $c)
                                <li class="blog-category-item">
                                    <a href="{{ route('blog', ['category' => $c['slug']]) }}" 
                                       class="blog-category-link {{ ($category_slug ?? '') == $c['slug'] ? 'active' : '' }}">
                                        <div class="blog-category-left">
                                            <i class="{{ $c['icon'] }}"></i>
                                            <span>{{ translate($c['name']) }}</span>
                                        </div>
                                        <div class="blog-category-right">
                                            <span>{{ $c['count'] }}</span>
                                            <i class="las la-angle-right fs-10"></i>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Widget 3: Popular Posts Card -->
                    <div class="blog-sidebar-widget">
                        <h3 class="blog-widget-title">
                            <i class="las la-fire text-danger"></i>
                            <span>{{ translate('Popular Posts') }}</span>
                        </h3>
                        <div class="blog-popular-list">
                            @php
                                $popularArticles = [
                                    [
                                        'title' => 'How to Build a Strong Foundation in a Relationship',
                                        'slug' => 'how-to-build-a-strong-foundation-in-a-relationship',
                                        'img' => 'assets/img/custom/blog_card_1.jpg',
                                        'date' => 'Sep 28, 2025'
                                    ],
                                    [
                                        'title' => 'Essential Things to Consider Before Marriage',
                                        'slug' => 'essential-things-to-consider-before-marriage',
                                        'img' => 'assets/img/custom/blog_card_2.jpg',
                                        'date' => 'Sep 25, 2025'
                                    ],
                                    [
                                        'title' => 'The Power of Patience in Love and Life',
                                        'slug' => 'the-power-of-patience-in-love-and-life',
                                        'img' => 'assets/img/custom/blog_card_4.jpg',
                                        'date' => 'Sep 16, 2025'
                                    ],
                                    [
                                        'title' => 'Simple Habits for a Happier and Healthier Life',
                                        'slug' => 'simple-habits-for-a-happier-and-healthier-life',
                                        'img' => 'assets/img/custom/blog_card_3.jpg',
                                        'date' => 'Sep 20, 2025'
                                    ],
                                    [
                                        'title' => 'Digital Communication in Modern Relationships',
                                        'slug' => 'digital-communication-in-modern-relationships',
                                        'img' => 'assets/img/custom/blog_card_5.jpg',
                                        'date' => 'Sep 12, 2025'
                                    ],
                                ];
                            @endphp

                            @foreach($popularArticles as $pop)
                                <a href="{{ url('blog/' . $pop['slug']) }}" class="blog-popular-item">
                                    <img src="{{ static_asset($pop['img']) }}" alt="{{ $pop['title'] }}" class="blog-popular-thumb">
                                    <div>
                                        <h4 class="blog-popular-title">{{ $pop['title'] }}</h4>
                                        <span class="blog-popular-date">{{ $pop['date'] }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Widget 4: Newsletter Box ("Stay Updated") -->
                    <div class="blog-sidebar-widget">
                        <h3 class="blog-widget-title">
                            <i class="las la-envelope"></i>
                            <span>{{ translate('Stay Updated') }}</span>
                        </h3>
                        <p class="blog-newsletter-desc">
                            {{ translate('Get the latest articles, tips and inspiration delivered to your inbox.') }}
                        </p>
                        <form onsubmit="handleNewsletterSubmit(event)">
                            <input type="email" 
                                   id="newsletterEmailInput"
                                   class="blog-newsletter-input" 
                                   placeholder="{{ translate('Your email address') }}" 
                                   required>
                            <button type="submit" class="blog-newsletter-btn" id="newsletterSubmitBtn">
                                {{ translate('Subscribe') }} &rarr;
                            </button>
                        </form>
                    </div>

                    <!-- Widget 5: Decorative Card -->
                    <div class="blog-decorative-card">
                        <img src="{{ static_asset('assets/img/custom/blog_flowers.jpg') }}" 
                             alt="Better Together - Floral Art" 
                             class="blog-decorative-img">
                        <div class="blog-decorative-script">
                            Better<br>
                            Together
                        </div>
                        <span class="blog-decorative-heart">&hearts;</span>
                    </div>

                </div>

            </div>

        </div>
    </section>

</div>
@endsection

@section('script')
<script type="text/javascript">
    function handleNewsletterSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('newsletterEmailInput');
        const btn = document.getElementById('newsletterSubmitBtn');
        if (input && input.value) {
            btn.innerHTML = '{{ translate("Subscribed!") }} ✓';
            btn.style.backgroundColor = 'var(--mat-maroon)';
            input.value = '';
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('success', 'Thank you for subscribing to our newsletter!');
            }
        }
    }
</script>
@endsection
