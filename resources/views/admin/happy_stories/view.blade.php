Taho@extends('admin.layouts.app')
@section('content')
@php
    $raw_video_link = $happy_story->video_link ?? '';
    $embed_url = null;
    if (!empty($raw_video_link)) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $raw_video_link, $ytMatch)) {
            $embed_url = 'https://www.youtube.com/embed/' . $ytMatch[1];
        } elseif (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/i', $raw_video_link, $driveMatch)) {
            $embed_url = 'https://drive.google.com/file/d/' . $driveMatch[1] . '/preview';
        } elseif (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/i', $raw_video_link, $driveMatch2)) {
            $embed_url = 'https://drive.google.com/file/d/' . $driveMatch2[1] . '/preview';
        }
    }
@endphp
<div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{ translate('Story Details') }}</h5>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
                <div class="col-8">
                    <span class="h6">{{ $happy_story->title }}</span>
                </div>
                <div class="col-4 text-right">
                    <span>
                      {{ translate('Posted By:').' '.($happy_story->user ? $happy_story->user->first_name.' '.$happy_story->user->last_name : '') }}
                    </span>
                </div>
            </div>

            <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height mt-4" data-arrows="true" data-dots="true" data-autoplay="true">
                @if ($happy_story->photos != null)
                    @foreach (explode(',', $happy_story->photos) as $key => $photo)
                        <div class="carousel-box">
                            <img class="d-block w-100 lazyload rounded h-200px h-lg-380px img-fit" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($photo) }}" alt="{{ $key }} offer">
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="mt-3 text-muted">{{ translate('Post Time:').' '.$happy_story->created_at }}</div>
            <div class="overflow-hidden mt-4">@php echo $happy_story->details; @endphp</div>

            @if(!empty($raw_video_link))
            <div class="p-4">
                <div class="embed-responsive embed-responsive-16by9 rounded overflow-hidden">
                    @if ($embed_url)
                        <iframe class="embed-responsive-item" src="{{ $embed_url }}" allowfullscreen></iframe>
                    @elseif ($happy_story->video_provider == 'dailymotion' && isset(explode('video/', $raw_video_link)[1]))
                        <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $raw_video_link)[1] }}" allowfullscreen></iframe>
                    @elseif ($happy_story->video_provider == 'vimeo' && isset(explode('vimeo.com/', $raw_video_link)[1]))
                        <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $raw_video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    @else
                        <video class="embed-responsive-item" controls playsinline>
                            <source src="{{ $raw_video_link }}" type="video/mp4">
                        </video>
                    @endif
                </div>
            </div>
            @endif
          </div>
        </div>
    </div>
</div>

@endsection