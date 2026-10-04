@extends('frontend.layouts.app')

@section('meta_title'){{ $blog->meta_title ?? $blog->title }}@stop
@section('meta_description'){{ $blog->meta_description ?? $blog->short_description }}@stop
@section('meta_keywords'){{ $blog->meta_keywords }}@stop

@section('meta')
    <meta itemprop="name" content="{{ $blog->meta_title ?? $blog->title }}">
    <meta itemprop="description" content="{{ $blog->meta_description ?? $blog->short_description }}">
    <meta itemprop="image" content="{{ $blog->banner ? uploaded_asset($blog->banner) : static_asset('assets/img/custom/blog_card_1.jpg') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->meta_title ?? $blog->title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description ?? $blog->short_description }}">
    <meta name="twitter:image" content="{{ $blog->banner ? uploaded_asset($blog->banner) : static_asset('assets/img/custom/blog_card_1.jpg') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $blog->meta_title ?? $blog->title }}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ route('blog.details', $blog->slug) }}" />
    <meta property="og:image" content="{{ $blog->banner ? uploaded_asset($blog->banner) : static_asset('assets/img/custom/blog_card_1.jpg') }}" />
    <meta property="og:description" content="{{ $blog->meta_description ?? $blog->short_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME', 'Matrimony') }}" />
@endsection

@section('content')

@php
    $cat_name = $blog->category ? $blog->category->category_name : 'Relationships';
    $cat_slug = $blog->category ? $blog->category->slug : 'relationships';
    
    // Fallback banner image if uploaded asset is missing or placeholder
    $banner_src = static_asset('assets/img/custom/blog_card_1.jpg');
    if ($blog->banner && file_exists(public_path(uploaded_asset($blog->banner)))) {
        $banner_src = uploaded_asset($blog->banner);
    } elseif ($blog->id == 2) {
        $banner_src = static_asset('assets/img/custom/blog_card_2.jpg');
    } elseif ($blog->id == 3) {
        $banner_src = static_asset('assets/img/custom/blog_card_3.jpg');
    } elseif ($blog->id == 4) {
        $banner_src = static_asset('assets/img/custom/blog_card_4.jpg');
    } elseif ($blog->id == 5) {
        $banner_src = static_asset('assets/img/custom/blog_card_5.jpg');
    } elseif ($blog->id == 6) {
        $banner_src = static_asset('assets/img/custom/blog_card_6.jpg');
    }
@endphp

<div class="blog-single-page pt-4">
    <!-- Top Article Header & Meta Area -->
    <div class="blog-single-container pt-3">
        <!-- Breadcrumbs -->
        <nav class="blog-single-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="las la-home mr-1"></i>Home</a>
            <span class="blog-single-breadcrumb-separator"><i class="las la-angle-right"></i></span>
            <a href="{{ route('blog') }}">Blog</a>
            <span class="blog-single-breadcrumb-separator"><i class="las la-angle-right"></i></span>
            <a href="{{ route('blog', ['category' => $cat_slug]) }}">{{ $cat_name }}</a>
            <span class="blog-single-breadcrumb-separator"><i class="las la-angle-right"></i></span>
            <span class="blog-single-breadcrumb-current" title="{{ $blog->title }}">{{ $blog->title }}</span>
        </nav>

        <!-- Category Pill Badge -->
        <a href="{{ route('blog', ['category' => $cat_slug]) }}" class="blog-single-category-pill">
            {{ $cat_name }}
        </a>

        <!-- Main Headline -->
        <h1 class="blog-single-title">
            {{ $blog->title }}
        </h1>

        <!-- Article Meta Bar -->
        <div class="blog-single-meta-bar">
            <!-- Left: Author & Date -->
            <div class="blog-single-author-wrap">
                <img src="{{ static_asset('assets/img/custom/dr_farhana.jpg') }}" alt="Dr. Farhana Ahmed" class="blog-single-author-avatar">
                <div>
                    <div class="blog-single-author-name">Dr. Farhana Ahmed</div>
                    <div class="blog-single-author-role">Relationship Counselor & Consultant</div>
                    <div class="blog-single-meta-details">
                        <span class="blog-single-meta-item">
                            <i class="las la-calendar"></i>
                            <span>{{ $blog->created_at ? $blog->created_at->format('M d, Y') : 'Sep 28, 2025' }}</span>
                        </span>
                        <span class="text-stone-300">•</span>
                        <span class="blog-single-meta-item">
                            <i class="las la-clock"></i>
                            <span>5 min read</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Social Share Buttons -->
            <div class="blog-single-shares">
                <span class="blog-share-label">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="blog-share-pill blog-share-fb" title="Share on Facebook">
                    <i class="lab la-facebook-f text-primary"></i>
                    <span>Facebook</span>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="blog-share-pill blog-share-wa" title="Share on WhatsApp">
                    <i class="lab la-whatsapp text-success"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="blog-share-pill blog-share-li" title="Share on LinkedIn">
                    <i class="lab la-linkedin-in text-info"></i>
                    <span>LinkedIn</span>
                </a>
                <button type="button" class="blog-share-pill" onclick="copyArticleLink()" id="copyLinkBtn" title="Copy Article Link">
                    <i class="las la-link"></i>
                    <span id="copyLinkText">Copy Link</span>
                </button>
            </div>
        </div>

        <!-- Featured Hero Image -->
        <div class="blog-single-hero-wrapper">
            <div class="blog-single-hero-img-box">
                <img src="{{ $banner_src }}" alt="{{ $blog->title }}">
            </div>
            <div class="blog-single-hero-caption">
                Photo: Building deep emotional connections through honest communication and mutual respect.
            </div>
        </div>
    </div>

    <!-- Main Two-Column Layout -->
    <div class="blog-single-container py-4">
        <div class="row align-items-start">
            <!-- Left Main Column (col-lg-8) -->
            <div class="col-lg-8 pr-lg-4">
                <article class="blog-article-body">
                    <!-- Lead Paragraph -->
                    <p class="blog-lead-para">
                        At its core, an enduring and joyful relationship isn't an accidental miracle—it is a conscious, continuous choice made every single day. While chemistry sparks initial interest, creating a lifelong union demands mutual emotional investment, shared core values, and the courage to grow together through life's shifting seasons.
                    </p>

                    <p>
                        In our modern, high-speed world, finding genuine companionship often comes with complex questions: How do two independent lives blend seamlessly? What keeps a relationship strong when routine sets in? The answers aren't hidden in grand gestures, but rather in the quiet, foundational habits couples cultivate together from the very beginning.
                    </p>

                    <!-- Section 1 -->
                    <h2>1. Aligning on Shared Core Values</h2>
                    <p>
                        While complementary personalities make life exciting, core values must be deeply aligned. Long-term harmony rests on how you view family priorities, financial stewardship, spiritual beliefs, and personal integrity. When partners share fundamental life philosophies, everyday challenges become collaborative hurdles rather than battlegrounds.
                    </p>
                    <p>
                        Before making long-term commitments, take the time to discuss what a meaningful future looks like for both of you. Transparency around expectations creates psychological safety—the greatest predictor of emotional stability in marriage.
                    </p>

                    <!-- Stylized Blockquote Callout -->
                    <div class="blog-quote-callout">
                        <p>
                            "A great marriage is not when the 'perfect couple' comes together. It is when an imperfect couple learns to enjoy their differences."
                        </p>
                        <div class="blog-quote-author">— Dave Meurer, Relationship Perspectives</div>
                    </div>

                    <!-- Section 2 -->
                    <h2>2. The Art of Intentional Communication</h2>
                    <p>
                        Communication is often called the lifeblood of partnership, but volume is not the same as depth. Truly listening requires setting aside distractions, putting down smartphones, and engaging with emotional attentiveness.
                    </p>
                    <p>
                        Instead of listening merely to prepare a counter-response, practice listening to understand. Validating your partner's emotions—even when you disagree with their logic—signals that their feelings are safe in your hands.
                    </p>

                    <!-- In-Article Matchmaking CTA Card -->
                    <div class="blog-in-article-cta">
                        <div class="blog-in-article-cta-text">
                            <h4>Ready to find someone who shares your life values?</h4>
                            <p>Connect with genuine, verified matrimonial profiles seeking meaningful, lifelong companionship.</p>
                        </div>
                        <a href="{{ url('/member-listing') }}" class="blog-in-article-cta-btn">
                            <span>Explore Verified Matches</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Section 3 -->
                    <h2>3. Navigating Conflict With Empathy and Patience</h2>
                    <p>
                        Disagreements are natural in every healthy relationship. What distinguishes thriving couples from struggling ones is not the absence of conflict, but how they fight. When tension arises, the objective should never be "winning" an argument; it should be solving the problem as a united team.
                    </p>
                    <p>
                        Adopt the golden rule of constructive dialogue: attack the issue, never the person. Gentle beginnings, softening harsh tones, and taking brief cool-down pauses keep minor misunderstandings from escalating into emotional wounds.
                    </p>

                    <!-- Section 4 -->
                    <h2>4. Small Daily Rituals of Emotional Intimacy</h2>
                    <p>
                        Grand vacations and anniversary surprises are wonderful, but everyday intimacy is woven through small micro-connections: a morning cup of tea shared in peace, sending an unexpected encouraging text during a hectic workday, or simply expressing gratitude for everyday contributions.
                    </p>
                    <p>
                        Over years, these tiny deposits into your emotional bank account build a fortress of trust and warmth that sustains you through life's unpredictable storms.
                    </p>

                    @if(!empty($blog->description) && !str_contains($blog->description, 'A happy relationship doesn'))
                    <div class="mt-4">
                        {!! $blog->description !!}
                    </div>
                    @endif
                </article>

                <!-- Tags Row -->
                <div class="blog-tags-wrapper">
                    <span class="blog-tags-label"><i class="las la-tags mr-1"></i>Tags:</span>
                    <a href="{{ route('blog') }}" class="blog-tag-pill">#MatrimonyTips</a>
                    <a href="{{ route('blog') }}" class="blog-tag-pill">#HealthyRelationship</a>
                    <a href="{{ route('blog') }}" class="blog-tag-pill">#PreMarriageCounseling</a>
                    <a href="{{ route('blog') }}" class="blog-tag-pill">#SharedValues</a>
                </div>

                <!-- Author Bio Box -->
                <div class="blog-author-bio-card">
                    <img src="{{ static_asset('assets/img/custom/dr_farhana.jpg') }}" alt="Dr. Farhana Ahmed" class="blog-author-bio-avatar">
                    <div class="blog-author-bio-info">
                        <h3>Written by Dr. Farhana Ahmed</h3>
                        <div class="author-bio-badge">Lead Counselor & Matchmaking Consultant</div>
                        <p>
                            Dr. Farhana Ahmed is a certified relationship counselor and pre-marital advisor with over 12 years of clinical experience helping couples create resilient, joyful marriages grounded in mutual respect and clear communication.
                        </p>
                        <a href="{{ route('blog') }}" class="blog-author-bio-link">
                            <span>View All Articles by Author</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Previous / Next Post Navigation -->
                <div class="blog-prev-next-nav">
                    @if($prev_blog)
                    <a href="{{ route('blog.details', $prev_blog->slug) }}" class="blog-nav-card">
                        <div class="blog-nav-direction">← Previous Article</div>
                        <div class="blog-nav-title">{{ $prev_blog->title }}</div>
                    </a>
                    @else
                    <a href="{{ route('blog') }}" class="blog-nav-card">
                        <div class="blog-nav-direction">← Back to Blog</div>
                        <div class="blog-nav-title">Explore All Articles & Guides</div>
                    </a>
                    @endif

                    @if($next_blog)
                    <a href="{{ route('blog.details', $next_blog->slug) }}" class="blog-nav-card text-right">
                        <div class="blog-nav-direction">Next Article →</div>
                        <div class="blog-nav-title">{{ $next_blog->title }}</div>
                    </a>
                    @else
                    <a href="{{ route('blog') }}" class="blog-nav-card text-right">
                        <div class="blog-nav-direction">Browse Blog →</div>
                        <div class="blog-nav-title">Discover More Inspirational Stories</div>
                    </a>
                    @endif
                </div>

                <!-- Comment Section -->
                <div class="blog-comment-section">
                    <h3 class="blog-comment-heading">Leave a Thoughtful Comment</h3>
                    <p class="blog-comment-subtitle">Your email address will not be published. Required fields are marked *</p>

                    <form class="blog-comment-form" onsubmit="event.preventDefault(); handleCommentSubmit();">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="blog-comment-label">Your Name *</label>
                                <input type="text" class="blog-comment-input" placeholder="e.g. Tariq Rahman" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="blog-comment-label">Email Address *</label>
                                <input type="email" class="blog-comment-input" placeholder="name@example.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="blog-comment-label">Your Comment *</label>
                            <textarea rows="4" class="blog-comment-input" placeholder="Share your perspective or experience..." required></textarea>
                        </div>
                        <button type="submit" class="blog-comment-submit-btn">
                            <span>Post Comment</span>
                            <i class="las la-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar Column (col-lg-4 sticky) -->
            <div class="col-lg-4 mt-5 mt-lg-0 space-y-6" style="position: sticky; top: 100px;">
                <!-- Widget 1: Free Consultation / Matchmaker CTA Card -->
                <div class="blog-sidebar-consult-card">
                    <div class="blog-sidebar-consult-icon">
                        <i class="las la-comments"></i>
                    </div>
                    <h3>Looking for Personal Guidance?</h3>
                    <p>
                        Book a 1-on-1 confidential session with our certified matchmaking advisor to discuss your life goals and ideal partner criteria.
                    </p>
                    <a href="{{ url('/contact-us') }}" class="blog-sidebar-consult-btn">
                        <span>Request Consultation</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </div>

                <!-- Widget 2: Popular Articles -->
                <div class="blog-sidebar-widget">
                    <h3 class="blog-sidebar-title">
                        <span>🔥</span>
                        <span>Popular Posts</span>
                    </h3>
                    <div class="blog-popular-list">
                        @foreach($popular_blogs as $pop_blog)
                        @php
                            $pop_img = static_asset('assets/img/custom/blog_card_' . (($pop_blog->id % 6) + 1) . '.jpg');
                            if ($pop_blog->banner && file_exists(public_path(uploaded_asset($pop_blog->banner)))) {
                                $pop_img = uploaded_asset($pop_blog->banner);
                            }
                        @endphp
                        <a href="{{ route('blog.details', $pop_blog->slug) }}" class="blog-popular-item">
                            <img src="{{ $pop_img }}" alt="{{ $pop_blog->title }}" class="blog-popular-thumb">
                            <div class="blog-popular-content">
                                <h4 class="blog-popular-title">{{ $pop_blog->title }}</h4>
                                <div class="blog-popular-date">
                                    <i class="las la-calendar mr-1"></i>
                                    {{ $pop_blog->created_at ? $pop_blog->created_at->format('M d, Y') : 'Sep 20, 2025' }}
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Widget 3: Categories Card -->
                <div class="blog-sidebar-widget">
                    <h3 class="blog-sidebar-title">
                        <span>👥</span>
                        <span>Categories</span>
                    </h3>
                    <div class="blog-category-list">
                        @foreach($categories as $category)
                        <a href="{{ route('blog', ['category' => $category->slug]) }}" class="blog-category-item {{ (isset($cat_slug) && $cat_slug == $category->slug) ? 'active' : '' }}">
                            <div class="blog-category-item-left">
                                <i class="lar la-heart blog-category-icon"></i>
                                <span class="blog-category-name">{{ $category->category_name }}</span>
                            </div>
                            <div class="blog-category-item-right">
                                <span class="blog-category-count">{{ sprintf('%02d', $category->blogs_count ?? 5) }}</span>
                                <i class="las la-angle-right blog-category-arrow"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Widget 4: Newsletter Box ("Stay Updated") -->
                <div class="blog-sidebar-widget blog-newsletter-widget">
                    <div class="blog-newsletter-header">
                        <span class="blog-newsletter-icon">✉</span>
                        <h3 class="blog-newsletter-title">Stay Updated</h3>
                    </div>
                    <p class="blog-newsletter-desc">
                        Get the latest articles, relationship tips and inspiration delivered directly to your inbox.
                    </p>
                    <form class="blog-newsletter-form" onsubmit="event.preventDefault(); AIZ.plugins.notify('success', 'Thank you for subscribing!');">
                        <input type="email" placeholder="Your email address" class="blog-newsletter-input" required>
                        <button type="submit" class="blog-newsletter-submit">
                            <span>Subscribe</span>
                            <i class="las la-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Related Articles Section -->
    <div class="blog-related-section">
        <div class="blog-single-container">
            <div class="blog-related-heading-wrap">
                <span class="blog-related-eyebrow">Explore Further</span>
                <h2 class="blog-related-heading">Related Articles You Might Like</h2>
                <div class="blog-related-divider">
                    <i class="las la-heart"></i>
                </div>
            </div>

            <div class="blog-related-grid">
                @foreach($related_blogs as $rel_blog)
                @php
                    $rel_cat = $rel_blog->category ? $rel_blog->category->category_name : 'Relationships';
                    $rel_img = static_asset('assets/img/custom/blog_card_' . (($rel_blog->id % 6) + 1) . '.jpg');
                    if ($rel_blog->banner && file_exists(public_path(uploaded_asset($rel_blog->banner)))) {
                        $rel_img = uploaded_asset($rel_blog->banner);
                    }
                @endphp
                <article class="blog-card-item">
                    <div class="blog-card-image-wrap">
                        <img src="{{ $rel_img }}" alt="{{ $rel_blog->title }}" class="blog-card-image">
                        <span class="blog-card-category-badge">{{ $rel_cat }}</span>
                    </div>
                    <div class="blog-card-body">
                        <div>
                            <h3 class="blog-card-title">
                                <a href="{{ route('blog.details', $rel_blog->slug) }}">{{ $rel_blog->title }}</a>
                            </h3>
                            <p class="blog-card-excerpt">
                                {{ Str::limit(strip_tags($rel_blog->short_description ?? $rel_blog->description), 110) }}
                            </p>
                        </div>
                        <div class="blog-card-footer">
                            <div class="blog-card-meta">
                                <span class="blog-card-meta-item">
                                    <i class="las la-calendar"></i>
                                    <span>{{ $rel_blog->created_at ? $rel_blog->created_at->format('M d, Y') : 'Sep 25, 2025' }}</span>
                                </span>
                                <span class="blog-card-meta-item">
                                    <i class="las la-clock"></i>
                                    <span>5 min read</span>
                                </span>
                            </div>
                            <a href="{{ route('blog.details', $rel_blog->slug) }}" class="blog-card-read-more">
                                <span>Read More</span>
                                <i class="las la-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function copyArticleLink() {
    var url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        var copyBtn = document.getElementById('copyLinkText');
        var originalText = copyBtn.innerText;
        copyBtn.innerText = 'Copied!';
        if (typeof AIZ !== 'undefined' && AIZ.plugins) {
            AIZ.plugins.notify('success', 'Article link copied to clipboard!');
        }
        setTimeout(function() {
            copyBtn.innerText = originalText;
        }, 2500);
    }).catch(function() {
        if (typeof AIZ !== 'undefined' && AIZ.plugins) {
            AIZ.plugins.notify('info', 'Please copy URL from browser address bar.');
        }
    });
}

function handleCommentSubmit() {
    if (typeof AIZ !== 'undefined' && AIZ.plugins) {
        AIZ.plugins.notify('success', 'Thank you! Your comment has been submitted for moderation.');
    } else {
        alert('Thank you! Your comment has been submitted for moderation.');
    }
}
</script>
@endsection