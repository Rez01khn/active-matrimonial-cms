@php
    $user = $happy_story->user ?? Auth::user();
    $couple_name = $user->first_name . ' & ' . ($happy_story->partner_name ?: translate('Partner'));

    $user_city = 'Dhaka';
    $user_country = 'Bangladesh';
    if ($user->addresses && $user->addresses->first()) {
        $addr = $user->addresses->first();
        if ($addr->city && !empty($addr->city->name)) {
            $user_city = $addr->city->name;
        }
        if ($addr->country && !empty($addr->country->name)) {
            $user_country = $addr->country->name;
        }
    }
    $user_location = $user_city . ', ' . $user_country;

    $is_video = !empty($happy_story->video_link);
    $raw_video_link = $happy_story->video_link ?? '';
    $embed_url = null;
    $youtube_thumb = null;

    if ($is_video) {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $raw_video_link, $ytMatch)) {
            $embed_url = 'https://www.youtube.com/embed/' . $ytMatch[1];
            $youtube_thumb = 'https://img.youtube.com/vi/' . $ytMatch[1] . '/hqdefault.jpg';
        } elseif (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/i', $raw_video_link, $driveMatch)) {
            $embed_url = 'https://drive.google.com/file/d/' . $driveMatch[1] . '/preview';
        } elseif (preg_match('/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/i', $raw_video_link, $driveMatch2)) {
            $embed_url = 'https://drive.google.com/file/d/' . $driveMatch2[1] . '/preview';
        }
    }

    $photo_url = $youtube_thumb ?: static_asset('assets/img/custom/story_couple.jpg');
    if (!empty($happy_story->photos)) {
        $first_photo = trim(explode(',', $happy_story->photos)[0]);
        $photo_url = is_numeric($first_photo) ? uploaded_asset($first_photo) : static_asset($first_photo);
    } elseif (!$youtube_thumb && !empty($user->photo)) {
        $photo_url = uploaded_asset($user->photo);
    }
@endphp

<div class="card border-0 shadow-sm mb-4" style="border-radius: 18px; border: 1px solid #f0e6df !important; overflow: hidden;">
    <div class="card-header bg-white py-3 px-4 d-flex flex-wrap align-items-center justify-content-between" style="border-bottom: 1px solid #f2e8e0;">
        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
            <h5 class="font-serif fw-700 mb-0" style="color: #3b1721; font-size: 20px;">
                {{ translate('Your Happy Story Preview') }}
            </h5>
            @if($happy_story->approved == 1)
                <span class="d-inline-flex align-items-center px-3 py-1 rounded-pill fw-600 fs-12" style="background: #e6f7ee; color: #16794b;">
                    <i class="las la-check-circle mr-1"></i> {{ translate('Approved & Live') }}
                </span>
            @else
                <span class="d-inline-flex align-items-center px-3 py-1 rounded-pill fw-600 fs-12" style="background: #fff6e5; color: #b7791f;">
                    <i class="las la-clock mr-1"></i> {{ translate('Pending Admin Approval') }}
                </span>
            @endif
        </div>
        <a href="{{ route('happy_story.member', ['edit' => 1]) }}" class="btn btn-sm rounded-pill px-3 py-1 fw-600 mt-2 mt-sm-0" style="background: #3b1721; color: #fff;">
            <i class="las la-edit mr-1"></i> {{ translate('Edit Story') }}
        </a>
    </div>

    <div class="card-body p-4 p-md-5" style="background: #fdfaf7;">
        <div class="mx-auto" style="max-width: 680px; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 8px 28px rgba(59, 23, 33, 0.07); border: 1px solid #f0e5dc;">
            <!-- Media Area -->
            <div class="position-relative" style="height: 360px; background: #1e1115; overflow: hidden;">
                <span class="position-absolute px-3 py-1 rounded-pill fw-600 fs-12 d-inline-flex align-items-center"
                      style="top: 16px; left: 16px; z-index: 3; background: rgba(255,255,255,0.94); color: #3b1721; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                    @if($is_video)
                        <i class="las la-video mr-1"></i> {{ translate('Video Story') }}
                    @else
                        <i class="las la-file-alt mr-1"></i> {{ translate('Text Story') }}
                    @endif
                </span>

                @if($is_video)
                    @if($embed_url)
                        <iframe src="{{ $embed_url }}" style="width: 100%; height: 100%; border: 0;" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <video controls playsinline poster="{{ $photo_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="{{ $happy_story->video_link }}" type="video/mp4">
                        </video>
                    @endif
                @else
                    <img src="{{ $photo_url }}" alt="{{ $couple_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>

            <!-- Card Body Matching Frontend Design -->
            <div class="p-4">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <h3 class="font-serif fw-700 mb-1" style="color: #2b1118; font-size: 22px;">
                            {{ $couple_name }}
                        </h3>
                        <div class="text-muted fs-13 mb-1">
                            <i class="las la-map-marker-alt mr-1"></i> {{ $user_location }}
                        </div>
                        <div class="text-muted fs-13">
                            <i class="las la-ring mr-1"></i> {{ translate('Married') }} &middot; {{ $happy_story->created_at ? $happy_story->created_at->diffForHumans(null, true) : translate('1 Year') }}
                        </div>
                    </div>
                    <div class="col-md-7" style="border-left: 2px solid #f0e4da;">
                        <p class="fst-italic text-muted mb-0 pl-md-3" style="font-size: 14.5px; line-height: 1.65;">
                            &ldquo;{{ strip_tags($happy_story->details) }}&rdquo;
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
