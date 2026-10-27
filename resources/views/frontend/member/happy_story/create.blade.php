@php
    $user = Auth::user();
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

    $existing_media_type = (!empty($happy_story) && !empty($happy_story->video_link)) ? 'video' : 'photo';
    $existing_photo_url = null;
    if (!empty($happy_story) && !empty($happy_story->photos)) {
        $first_photo = trim(explode(',', $happy_story->photos)[0]);
        $existing_photo_url = is_numeric($first_photo) ? uploaded_asset($first_photo) : static_asset($first_photo);
    }
    $existing_video_link = (!empty($happy_story) && in_array($happy_story->video_provider, ['youtube', 'drive', 'link']))
        ? $happy_story->video_link
        : '';
@endphp

<style>
    .hs-form-card {
        background: #ffffff;
        border: 1px solid #f0e6df;
        border-radius: 18px;
        box-shadow: 0 6px 24px rgba(59, 23, 33, 0.04);
        overflow: hidden;
    }
    .hs-form-header {
        background: linear-gradient(135deg, #3b1721 0%, #562332 100%);
        color: #ffffff;
        padding: 24px 28px;
    }
    .hs-eyebrow-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 999px;
        background: rgba(243, 213, 165, 0.16);
        border: 1px solid rgba(243, 213, 165, 0.3);
        color: #f3d5a5;
        font-size: 11.5px;
        font-weight: 600;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        line-height: 1.2;
        white-space: nowrap;
    }
    .hs-profile-badge-bar {
        background: #fdf8f4;
        border: 1px solid #efe2d6;
        border-radius: 12px;
        padding: 14px 18px;
    }
    .hs-type-selector {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .hs-type-option {
        border: 2px solid #ebe0d6;
        border-radius: 14px;
        padding: 18px;
        cursor: pointer;
        transition: all 0.22s ease;
        background: #fffdfb;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
    }
    .hs-type-option:hover {
        border-color: #c59b6d;
        background: #fdf8f3;
    }
    .hs-type-option.active {
        border-color: #3b1721;
        background: #fdf6f0;
        box-shadow: 0 4px 14px rgba(59, 23, 33, 0.08);
    }
    .hs-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f5ebe1;
        color: #3b1721;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
        transition: all 0.22s ease;
    }
    .hs-type-option.active .hs-type-icon {
        background: #3b1721;
        color: #f3d5a5;
    }
    .hs-upload-dropzone {
        border: 2px dashed #d8c5b4;
        border-radius: 14px;
        background: #fffcf9;
        padding: 26px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        height: calc(100% - 30px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .hs-upload-dropzone:hover {
        border-color: #3b1721;
        background: #fdf7f2;
    }
    .hs-video-link-card {
        border: 1px solid #eadcd0;
        border-radius: 14px;
        background: #fffcf9;
        padding: 22px 20px;
        height: calc(100% - 30px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .hs-submit-btn {
        background: #3b1721;
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 12px 32px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
    }
    .hs-submit-btn:hover {
        background: #52202e;
        color: #ffffff;
        transform: translateY(-1px);
    }
    @media (max-width: 575px) {
        .hs-type-selector {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="hs-form-card mb-4">
    <!-- Top Header -->
    <div class="hs-form-header d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <div class="hs-eyebrow-pill mb-2">
                <i class="las la-heart"></i>
                <span>{{ translate('LOVE STORIES THAT INSPIRE') }}</span>
            </div>
            <h4 class="font-serif fw-700 mb-1 text-white" style="font-size: 22px;">
                {{ !empty($happy_story) ? translate('Update Your Happy Story') : translate('Share Your Happy Story') }}
            </h4>
            <p class="mb-0 text-white-50 fs-13">
                {{ translate('Your name and location are automatically linked from your profile.') }}
            </p>
        </div>
        @if(!empty($happy_story))
            <a href="{{ route('happy_story.member') }}" class="btn btn-sm rounded-pill px-3 py-1 mt-2 mt-sm-0" style="background: rgba(255,255,255,0.15); color: #fff;">
                <i class="las la-arrow-left mr-1"></i> {{ translate('Back to Preview') }}
            </a>
        @endif
    </div>

    <div class="card-body p-4 p-md-5">
        <!-- Dynamic Profile Info Bar -->
        <div class="hs-profile-badge-bar mb-4 d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-sm-0">
                <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #3b1721; color: #f3d5a5;">
                    <i class="las la-user-check fs-20"></i>
                </div>
                <div>
                    <div class="fs-12 text-muted">{{ translate('Couple Display Name (Auto-linked with your profile)') }}</div>
                    <div class="fw-700 fs-16" style="color: #3b1721;" id="coupleNameLivePreview">
                        {{ $user->first_name }} &amp; <span id="partnerNameLiveSpan">{{ old('partner_name', $happy_story->partner_name ?? translate('Partner Name')) }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center text-muted fs-13">
                <span class="mr-3"><i class="las la-map-marker-alt text-danger mr-1"></i> {{ $user_location }}</span>
                <span><i class="las la-ring text-warning mr-1"></i> {{ translate('Verified Match') }}</span>
            </div>
        </div>

        <form action="{{ route('happy_story.store') }}" method="POST" enctype="multipart/form-data" id="happyStoryMemberForm">
            @csrf

            <!-- 1. Partner Name -->
            <div class="form-group mb-4">
                <label class="fw-600 fs-14 mb-2" style="color: #3b1721;">
                    {{ translate('Partner Name') }} <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control form-control-lg rounded-lg"
                       name="partner_name"
                       id="partnerNameInput"
                       value="{{ old('partner_name', $happy_story->partner_name ?? '') }}"
                       placeholder="{{ translate('e.g. Tasmia / Nusrat / Rahim') }}"
                       required>
                @error('partner_name')
                    <small class="form-text text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- 2. Story / Quote -->
            <div class="form-group mb-4">
                <label class="fw-600 fs-14 mb-2" style="color: #3b1721;">
                    {{ translate('Your Story') }} <span class="text-danger">*</span>
                </label>
                <textarea name="details"
                          rows="4"
                          class="form-control rounded-lg"
                          placeholder="{{ translate('Write a short inspiring quote or story about how you met on our platform...') }}"
                          required>{{ old('details', isset($happy_story) ? strip_tags($happy_story->details) : '') }}</textarea>
                <small class="text-muted d-block mt-1">
                    {{ translate('Example: "We met on this platform, and now we\'re building our forever."') }}
                </small>
                @error('details')
                    <small class="form-text text-danger mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- 3. Select Media Type (Photo vs Video) -->
            <div class="form-group mb-4">
                <label class="fw-600 fs-14 mb-2 d-block" style="color: #3b1721;">
                    {{ translate('What would you like to upload?') }} <span class="text-danger">*</span>
                </label>

                <input type="hidden" name="media_type" id="selectedMediaType" value="{{ old('media_type', $existing_media_type) }}">

                <div class="hs-type-selector">
                    <!-- Option A: Photo Story -->
                    <div class="hs-type-option {{ old('media_type', $existing_media_type) === 'photo' ? 'active' : '' }}"
                         id="typeOptionPhoto"
                         onclick="selectStoryMediaType('photo')">
                        <div class="hs-type-icon">
                            <i class="las la-camera"></i>
                        </div>
                        <div>
                            <div class="fw-700 fs-15" style="color: #3b1721;">{{ translate('Upload Photo') }}</div>
                            <div class="fs-12 text-muted">{{ translate('Displays as a Text & Photo Story card') }}</div>
                        </div>
                    </div>

                    <!-- Option B: Video Story -->
                    <div class="hs-type-option {{ old('media_type', $existing_media_type) === 'video' ? 'active' : '' }}"
                         id="typeOptionVideo"
                         onclick="selectStoryMediaType('video')">
                        <div class="hs-type-icon">
                            <i class="las la-video"></i>
                        </div>
                        <div>
                            <div class="fw-700 fs-15" style="color: #3b1721;">{{ translate('Upload Video') }}</div>
                            <div class="fs-12 text-muted">{{ translate('Displays as a playable Video Story card') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4A. Photo Upload Box (Shown when Photo is selected) -->
            <div id="photoUploadSection" class="mb-4" style="{{ old('media_type', $existing_media_type) === 'video' ? 'display: none;' : '' }}">
                <label class="fw-600 fs-14 mb-2" style="color: #3b1721;">
                    {{ translate('Upload Couple Photo') }} <span class="text-danger">*</span>
                </label>
                <div class="hs-upload-dropzone" onclick="document.getElementById('storyPhotoFileInput').click()">
                    <input type="file"
                           name="photo_file"
                           id="storyPhotoFileInput"
                           accept="image/jpeg,image/png,image/webp,image/jpg"
                           style="display: none;"
                           onchange="previewSelectedPhoto(this)">
                    <div id="photoUploadPlaceholder" style="{{ $existing_photo_url ? 'display:none;' : '' }}">
                        <i class="las la-cloud-upload-alt mb-2" style="font-size: 40px; color: #8C6239;"></i>
                        <div class="fw-600 fs-15" style="color: #3b1721;">{{ translate('Click to choose your couple photo') }}</div>
                        <div class="fs-12 text-muted mt-1">{{ translate('Supports JPG, PNG, WEBP (High quality landscape or portrait photo)') }}</div>
                    </div>
                    <div id="photoUploadPreviewWrap" style="{{ $existing_photo_url ? '' : 'display:none;' }}">
                        <img id="photoUploadPreviewImg"
                             src="{{ $existing_photo_url ?? '' }}"
                             alt="Couple Preview"
                             style="max-height: 220px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <div class="mt-2 fs-12 fw-600" style="color: #8C6239;">
                            <i class="las la-sync mr-1"></i> {{ translate('Click to change photo') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4B. Video Upload OR Video Link Section (Shown when Video is selected) -->
            <div id="videoUploadSection" class="mb-4" style="{{ old('media_type', $existing_media_type) === 'video' ? '' : 'display: none;' }}">
                <div class="row align-items-stretch">
                    <!-- Direct Video File Upload -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="fw-600 fs-14 mb-2" style="color: #3b1721;">
                            {{ translate('Upload Story Video File') }}
                        </label>
                        <div class="hs-upload-dropzone" onclick="document.getElementById('storyVideoFileInput').click()">
                            <input type="file"
                                   name="video_file"
                                   id="storyVideoFileInput"
                                   accept="video/mp4,video/webm,video/quicktime"
                                   style="display: none;"
                                   onchange="previewSelectedVideo(this)">
                            <div id="videoUploadPlaceholder">
                                <i class="las la-film mb-2" style="font-size: 40px; color: #8C6239;"></i>
                                <div class="fw-600 fs-15" style="color: #3b1721;">{{ translate('Click to select your video file') }}</div>
                                <div class="fs-12 text-muted mt-1">{{ translate('Supports MP4, WebM, MOV from your device') }}</div>
                            </div>
                            <div id="videoUploadPreviewWrap" style="display: none;">
                                <video id="videoUploadPreviewPlayer" controls style="max-height: 160px; max-width: 100%; border-radius: 10px;"></video>
                                <div class="mt-2 fs-12 fw-600" id="videoSelectedFileName" style="color: #3b1721;"></div>
                                <div class="fs-12" style="color: #8C6239;">
                                    <i class="las la-sync mr-1"></i> {{ translate('Click to choose a different video') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OR Video Link (Google Drive / YouTube) -->
                    <div class="col-md-6">
                        <label class="fw-600 fs-14 mb-2" style="color: #3b1721;">
                            {{ translate('Or Video Link (Google Drive / YouTube)') }}
                        </label>
                        <div class="hs-video-link-card">
                            <div class="d-flex align-items-center mb-2">
                                <div class="mr-2 d-flex align-items-center justify-content-center rounded-circle" style="width: 34px; height: 34px; background: #f5ebe1; color: #8C6239;">
                                    <i class="las la-link fs-18"></i>
                                </div>
                                <div>
                                    <div class="fw-600 fs-14" style="color: #3b1721;">{{ translate('Paste YouTube or Google Drive Link') }}</div>
                                    <div class="fs-12 text-muted">{{ translate('Share link from YouTube or Google Drive') }}</div>
                                </div>
                            </div>
                            <input type="url"
                                   name="video_link"
                                   id="storyVideoLinkInput"
                                   class="form-control rounded-lg mt-2"
                                   value="{{ old('video_link', $existing_video_link) }}"
                                   placeholder="{{ translate('https://www.youtube.com/watch?v=... or https://drive.google.com/...') }}">
                            <small class="text-muted mt-2 d-block fs-12">
                                <i class="lab la-youtube text-danger mr-1"></i> {{ translate('YouTube') }} &nbsp;|&nbsp;
                                <i class="lab la-google-drive text-primary mr-1"></i> {{ translate('Google Drive share link') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                <button type="submit" class="hs-submit-btn">
                    <i class="las la-paper-plane mr-1"></i>
                    {{ !empty($happy_story) ? translate('Update Story') : translate('Publish Story') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live update couple name preview
    document.addEventListener('DOMContentLoaded', function () {
        var partnerInput = document.getElementById('partnerNameInput');
        var partnerSpan = document.getElementById('partnerNameLiveSpan');
        if (partnerInput && partnerSpan) {
            partnerInput.addEventListener('input', function () {
                partnerSpan.textContent = this.value.trim() !== '' ? this.value.trim() : '{{ translate("Partner Name") }}';
            });
        }
    });

    function selectStoryMediaType(type) {
        document.getElementById('selectedMediaType').value = type;
        var photoOpt = document.getElementById('typeOptionPhoto');
        var videoOpt = document.getElementById('typeOptionVideo');
        var photoSec = document.getElementById('photoUploadSection');
        var videoSec = document.getElementById('videoUploadSection');

        if (type === 'video') {
            videoOpt.classList.add('active');
            photoOpt.classList.remove('active');
            videoSec.style.display = '';
            photoSec.style.display = 'none';
        } else {
            photoOpt.classList.add('active');
            videoOpt.classList.remove('active');
            photoSec.style.display = '';
            videoSec.style.display = 'none';
        }
    }

    function previewSelectedPhoto(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.getElementById('photoUploadPreviewImg');
                var wrap = document.getElementById('photoUploadPreviewWrap');
                var placeholder = document.getElementById('photoUploadPlaceholder');
                if (img && wrap && placeholder) {
                    img.src = e.target.result;
                    wrap.style.display = '';
                    placeholder.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function previewSelectedVideo(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var url = URL.createObjectURL(file);
            var player = document.getElementById('videoUploadPreviewPlayer');
            var wrap = document.getElementById('videoUploadPreviewWrap');
            var placeholder = document.getElementById('videoUploadPlaceholder');
            var fileNameEl = document.getElementById('videoSelectedFileName');

            if (player && wrap && placeholder) {
                player.src = url;
                wrap.style.display = '';
                placeholder.style.display = 'none';
                if (fileNameEl) {
                    fileNameEl.textContent = file.name;
                }
            }
        }
    }
</script>
