@extends('frontend.layouts.app')

@section('content')
@php
    $photosList = !empty($happy_story->photos) ? explode(',', $happy_story->photos) : [];
    $parsedPhotos = [];
    foreach ($photosList as $p) {
        $trimmed = trim($p);
        if (empty($trimmed)) continue;
        if (is_numeric($trimmed)) {
            $parsedPhotos[] = uploaded_asset($trimmed);
        } else {
            $parsedPhotos[] = static_asset($trimmed);
        }
    }
    if (empty($parsedPhotos)) {
        $parsedPhotos = [
            static_asset('assets/img/custom/story_couple.jpg'),
            static_asset('assets/img/custom/story_wedding_reception.jpg'),
            static_asset('assets/img/custom/story_wedding_rings.jpg')
        ];
    }

    $marriageYear = $happy_story->created_at ? $happy_story->created_at->format('Y') : date('Y');
    $partnerName = !empty($happy_story->partner_name) ? $happy_story->partner_name : translate('Life Partner');

    $displayRelated = (isset($related_stories) && $related_stories->count() > 0)
        ? $related_stories
        : \App\Models\HappyStory::where('approved', 1)->where('id', '!=', $happy_story->id)->take(3)->get();
@endphp

<div class="story-details-wrap">

    <!-- 1. Header & Breadcrumbs -->
    <nav class="story-breadcrumb-nav" aria-label="breadcrumb">
        <div class="container">
            <ul class="story-breadcrumb-list">
                <li>
                    <a href="{{ route('home') }}">{{ translate('Home') }}</a>
                </li>
                <li class="story-breadcrumb-separator">
                    <i class="las la-angle-right"></i>
                </li>
                <li>
                    <a href="{{ route('happy_stories') }}">{{ translate('Success Stories') }}</a>
                </li>
                <li class="story-breadcrumb-separator">
                    <i class="las la-angle-right"></i>
                </li>
                <li class="story-breadcrumb-current" aria-current="page">
                    {{ $happy_story->title }}
                </li>
            </ul>
        </div>
    </nav>

    <!-- 2. Story Hero Banner -->
    <header class="story-hero-section">
        <div class="container">
            <div class="text-center max-w-800px mx-auto">
                <div class="story-hero-badge">
                    <i class="las la-heart"></i>
                    <span>{{ translate('True Success Story') }}</span>
                </div>
                <h1 class="story-hero-title">
                    {{ $happy_story->title }}
                </h1>
                <div class="story-hero-meta-row justify-content-center">
                    <span class="story-meta-pill">
                        <i class="las la-ring"></i>
                        <span>{{ translate('Married in ') . $marriageYear }}</span>
                    </span>
                    <span class="story-meta-pill">
                        <i class="las la-map-marker-alt"></i>
                        <span>{{ translate('Dhaka, Bangladesh') }}</span>
                    </span>
                    <span class="story-meta-pill verified">
                        <i class="las la-check-circle"></i>
                        <span>{{ translate('Verified Match') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Wedding Photo Showcase -->
    <section class="story-photo-showcase-section">
        <div class="container">
            @if(count($parsedPhotos) >= 3)
                <div class="story-gallery-grid">
                    <div class="story-gallery-main">
                        <img src="{{ $parsedPhotos[0] }}" alt="{{ $happy_story->title }} - Wedding Hero" class="story-gallery-img">
                    </div>
                    <div class="story-gallery-side">
                        <div class="story-gallery-sub">
                            <img src="{{ $parsedPhotos[1] }}" alt="{{ $happy_story->title }} - Wedding Reception" class="story-gallery-img">
                        </div>
                        <div class="story-gallery-sub">
                            <img src="{{ $parsedPhotos[2] }}" alt="{{ $happy_story->title }} - Wedding Ceremony" class="story-gallery-img">
                        </div>
                    </div>
                </div>
            @elseif(count($parsedPhotos) == 2)
                <div class="row gutters-15">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="story-gallery-sub h-420px">
                            <img src="{{ $parsedPhotos[0] }}" alt="{{ $happy_story->title }}" class="story-gallery-img">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="story-gallery-sub h-420px">
                            <img src="{{ $parsedPhotos[1] }}" alt="{{ $happy_story->title }}" class="story-gallery-img">
                        </div>
                    </div>
                </div>
            @else
                <div class="story-gallery-single">
                    <img src="{{ $parsedPhotos[0] }}" alt="{{ $happy_story->title }}" class="story-gallery-img">
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Editorial Story Body -->
    <section class="story-editorial-section py-4">
        <div class="container">
            <div class="story-editorial-container">

                <!-- Lead Paragraph Intro -->
                <p class="story-editorial-lead">
                    {{ translate('A heartfelt union brought together by shared values, family blessings, and sincere intentions on Active Matrimonial.') }}
                </p>

                <!-- Highlighted Quote Callout -->
                <blockquote class="story-quote-card">
                    <div class="story-quote-icon">&ldquo;</div>
                    <p class="story-quote-text">
                        {{ Str::limit(strip_tags($happy_story->details), 180) }}
                    </p>
                    <footer class="story-quote-author">
                        &mdash; {{ $happy_story->title }}
                    </footer>
                </blockquote>

                <!-- Narrative Body Text -->
                <div class="story-editorial-body">
                    {!! $happy_story->details !!}
                </div>

                <!-- Video Showcase (if provided) -->
                @if(!empty($happy_story->video_link))
                    <div class="story-video-card">
                        <div class="story-video-card-header">
                            <i class="las la-video"></i>
                            <span>{{ translate('Watch Their Story') }}</span>
                        </div>
                        <div class="embed-responsive embed-responsive-16by9">
                            @if($happy_story->video_provider == 'youtube' && isset(explode('=', $happy_story->video_link)[1]))
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $happy_story->video_link)[1] }}" allowfullscreen></iframe>
                            @elseif($happy_story->video_provider == 'dailymotion' && isset(explode('video/', $happy_story->video_link)[1]))
                                <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $happy_story->video_link)[1] }}" allowfullscreen></iframe>
                            @elseif($happy_story->video_provider == 'vimeo' && isset(explode('vimeo.com/', $happy_story->video_link)[1]))
                                <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $happy_story->video_link)[1] }}" frameborder="0" allowfullscreen></iframe>
                            @else
                                <iframe class="embed-responsive-item" src="{{ $happy_story->video_link }}" allowfullscreen></iframe>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- 5. Pre-Footer Conversion CTA Banner -->
                <aside class="story-conversion-banner">
                    <div class="story-cta-badge">
                        <i class="las la-star"></i>
                        <span>{{ translate('Your Story Could Be Next') }}</span>
                    </div>
                    <h2 class="story-cta-title">
                        {{ translate('Ready to Write Your Own Love Story?') }}
                    </h2>
                    <p class="story-cta-subtitle">
                        {{ translate('Connect with genuine, verified prospective life partners who share your beliefs, family culture, and future ambitions.') }}
                    </p>
                    <a href="{{ route('register') }}" class="story-cta-btn">
                        <span>{{ translate('Create Your Free Profile') }}</span>
                        <i class="las la-arrow-right"></i>
                    </a>
                </aside>

            </div>
        </div>
    </section>

    <!-- 6. Related Stories Section -->
    @if(isset($displayRelated) && count($displayRelated) > 0)
        <section class="story-related-section">
            <div class="container">
                <div class="story-related-header">
                    <div class="story-related-eyebrow">{{ translate('MORE SUCCESS STORIES') }}</div>
                    <h2 class="story-related-title">{{ translate('Couples Who Found Their Forever') }}</h2>
                </div>
                <div class="row">
                    @foreach($displayRelated as $relStory)
                        @php
                            $relPhotos = !empty($relStory->photos) ? explode(',', $relStory->photos) : [];
                            $relPhoto = isset($relPhotos[0]) ? (is_numeric($relPhotos[0]) ? uploaded_asset($relPhotos[0]) : static_asset($relPhotos[0])) : static_asset('assets/img/custom/story_couple.jpg');
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-4">
                            <a href="{{ route('story_details', $relStory->id) }}" class="story-related-card">
                                <div class="story-related-media">
                                    <img src="{{ $relPhoto }}" alt="{{ $relStory->title }}" loading="lazy">
                                </div>
                                <div class="story-related-content">
                                    <h3 class="story-related-couple">{{ $relStory->title }}</h3>
                                    <div class="story-related-meta">
                                        <i class="las la-ring"></i>
                                        <span>{{ $relStory->created_at ? translate('Married in ') . $relStory->created_at->format('Y') : translate('Verified Match') }}</span>
                                    </div>
                                    <p class="story-related-snippet">
                                        {{ Str::limit(strip_tags($relStory->details), 95) }}
                                    </p>
                                    <span class="story-related-link">
                                        <span>{{ translate('Read Their Story') }}</span>
                                        <i class="las la-arrow-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>
@endsection

@section('modal')
    @include('modals.login_modal')
    @include('modals.package_update_alert_modal')
@endsection
