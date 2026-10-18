@extends('frontend.layouts.member_panel')

@section('panel_content')
<style>
    .gallery-dashboard-wrap {
        font-family: inherit;
    }
    .gallery-top-title {
        color: #1c1917;
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .gallery-stat-card {
        background: #ffffff;
        border: 1px solid #ede8e3;
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
    }
    .gallery-stat-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(0,0,0,0.04);
        transform: translateY(-1px);
    }
    .gallery-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .gallery-panel-card {
        background: #ffffff;
        border: 1px solid #ede8e3;
        border-radius: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 1199.98px) {
        .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 767.98px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }
    @media (max-width: 420px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
    }
    .gallery-photo-card {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e7e2dc;
        background: #faf8f5;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .gallery-photo-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .gallery-photo-thumb-wrap {
        position: relative;
        width: 100%;
        padding-top: 115%; /* portrait ratio */
        background: #f1ede8;
        overflow: hidden;
        cursor: pointer;
    }
    .gallery-photo-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .gallery-photo-card:hover .gallery-photo-img {
        transform: scale(1.04);
    }
    .gallery-primary-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #2b111e 0%, #4a1525 100%);
        color: #fef3c7;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 9px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        z-index: 2;
    }
    .gallery-primary-badge i {
        color: #f59e0b;
        font-size: 13px;
    }
    .gallery-menu-trigger {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(4px);
        border: none;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #334155;
        font-size: 16px;
        cursor: pointer;
        opacity: 0.85;
        transition: all 0.2s ease;
        z-index: 3;
    }
    .gallery-menu-trigger:hover {
        background: #ffffff;
        opacity: 1;
        transform: scale(1.08);
        color: #0f172a;
    }
    .gallery-photo-footer {
        padding: 8px 10px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1ece6;
        gap: 6px;
    }
    .gallery-vis-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #059669;
        background: #ecfdf5;
        padding: 2px 7px;
        border-radius: 9999px;
        line-height: 1.3;
    }
    .gallery-primary-pill {
        font-size: 11px;
        font-weight: 700;
        color: #92400e;
        background: #fef3c7;
        padding: 2px 8px;
        border-radius: 9999px;
        line-height: 1.3;
    }
    .gallery-icon-btn {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid transparent;
        color: #64748b;
        font-size: 14px;
        transition: all 0.15s ease;
        padding: 0;
        cursor: pointer;
    }
    .gallery-icon-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .gallery-icon-btn.btn-delete:hover {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .gallery-icon-btn.btn-primary-set:hover {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }
    /* Add Photos Placeholder Slot */
    .gallery-add-slot {
        border: 2px dashed #e2d6cf;
        border-radius: 14px;
        background: #faf8f5;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px 14px;
        cursor: pointer;
        transition: all 0.25s ease;
        min-height: 210px;
    }
    .gallery-add-slot:hover {
        border-color: #2b111e;
        background: #f5efe9;
        transform: translateY(-2px);
    }
    .gallery-add-slot.dragover {
        border-color: #2b111e;
        background: #f5ede6;
        transform: scale(1.02);
        box-shadow: 0 0 0 4px rgba(43, 17, 30, 0.1);
    }
    .gallery-add-slot-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #b47d53;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 10px;
        box-shadow: 0 4px 10px rgba(180, 125, 83, 0.28);
        transition: transform 0.2s ease;
    }
    .gallery-add-slot:hover .gallery-add-slot-circle {
        transform: scale(1.1);
        background: #2b111e;
    }
    /* Drag & Drop Upload Zone */
    .gallery-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        background: #faf8f6;
        padding: 28px 16px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    .gallery-dropzone.dragover {
        border-color: #2b111e;
        background: #f5ede6;
        box-shadow: 0 0 0 4px rgba(43, 17, 30, 0.08);
    }
    .btn-maroon-pill {
        background: #2b111e;
        color: #ffffff !important;
        border: none;
        padding: 9px 20px;
        border-radius: 9999px;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(43, 17, 30, 0.22);
    }
    .btn-maroon-pill:hover {
        background: #4a1525;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(43, 17, 30, 0.3);
    }
    .guideline-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding-bottom: 14px;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1ece6;
    }
    .guideline-item:last-child {
        padding-bottom: 0;
        margin-bottom: 0;
        border-bottom: none;
    }
    .guideline-icon-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #fbf5f2;
        color: #9b2c2c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .upload-progress-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(2px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 14px;
    }

    /* Exact ON / OFF Capsule Button matching reference image */
    .gallery-onoff-switch {
        position: relative;
        display: inline-block;
        width: 72px;
        height: 36px;
        cursor: pointer;
        user-select: none;
        vertical-align: middle;
        margin-bottom: 0;
    }
    .gallery-onoff-switch input {
        opacity: 0;
        width: 0;
        height: 0;
        position: absolute;
    }
    .gallery-onoff-slider {
        position: absolute;
        inset: 0;
        background: #f1f5f9;
        border: 2.5px solid #cbd5e1;
        border-radius: 9999px;
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 4px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    .gallery-onoff-knob {
        position: absolute;
        top: 2.5px;
        left: 3.5px;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: radial-gradient(circle at 35% 35%, #ffffff 0%, #e2e8f0 100%);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.28), 0 0 0 1px rgba(0, 0, 0, 0.06);
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
    }
    .gallery-onoff-slider .onoff-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        line-height: 1;
        z-index: 1;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .gallery-onoff-slider .on-label {
        color: #ffffff;
        padding-left: 9px;
        opacity: 0;
        transform: scale(0.85);
    }
    .gallery-onoff-slider .off-label {
        color: #0f172a;
        padding-right: 7px;
        opacity: 1;
        margin-left: auto;
        transform: scale(1);
    }

    /* Checked (ON State) */
    .gallery-onoff-switch input:checked + .gallery-onoff-slider {
        background: #0f172a;
        border-color: #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
    }
    .gallery-onoff-switch input:checked + .gallery-onoff-slider .gallery-onoff-knob {
        left: calc(100% - 28.5px);
        background: radial-gradient(circle at 35% 35%, #ffffff 0%, #f1f5f9 100%);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.35);
    }
    .gallery-onoff-switch input:checked + .gallery-onoff-slider .on-label {
        opacity: 1;
        transform: scale(1);
    }
    .gallery-onoff-switch input:checked + .gallery-onoff-slider .off-label {
        opacity: 0;
        transform: scale(0.85);
    }

    /* Hover & Disabled */
    .gallery-onoff-switch:hover .gallery-onoff-slider {
        transform: scale(1.03);
    }
    .gallery-onoff-switch input:disabled + .gallery-onoff-slider {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="gallery-dashboard-wrap">
    {{-- Breadcrumb & Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1 fs-12">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">{{ translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item active text-dark font-weight-medium" aria-current="page">{{ translate('Photo Gallery') }}</li>
                </ol>
            </nav>
            <h1 class="h2 gallery-top-title font-serif mb-1">{{ translate('My Photo Gallery') }}</h1>
            <p class="text-muted fs-14 mb-0">{{ translate('Manage the photos that help others get to know you.') }}</p>
        </div>
        <div class="mt-2 mt-md-0">
            <div class="p-2 px-3 rounded-pill bg-white border border-stone-200/80 shadow-xs d-inline-flex align-items-center" style="border-radius: 9999px;">
                <span class="mr-2 text-primary" style="font-size: 18px;"><i class="las la-shield-alt"></i></span>
                <div class="text-left">
                    <span class="text-muted d-block line-height-1" style="font-size: 10.5px; letter-spacing: 0.5px; text-transform: uppercase;">{{ translate('Photo Visibility') }}</span>
                    <span class="fw-700 text-dark" style="font-size: 12.5px;" id="header-privacy-label">
                        @if($gallery_privacy == 'all')
                            {{ translate('Visible to all members') }}
                        @else
                            {{ translate('Visible to approved members') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 3 Metric Cards --}}
    <div class="row gutters-15 mb-4">
        {{-- Metric 1: Uploads Remaining --}}
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="gallery-stat-card d-flex align-items-center">
                <span class="gallery-stat-icon mr-3" style="background: #fdf2f8; color: #9d174d;">
                    <i class="las la-cloud-upload-alt"></i>
                </span>
                <div>
                    <h3 class="h3 fw-700 font-serif mb-0 text-dark line-height-1">{{ $remaining_uploads }}</h3>
                    <span class="text-muted fs-13">{{ translate('uploads remaining') }}</span>
                </div>
            </div>
        </div>

        {{-- Metric 2: Photos Uploaded --}}
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="gallery-stat-card d-flex align-items-center">
                <span class="gallery-stat-icon mr-3" style="background: #ecfeff; color: #0891b2;">
                    <i class="las la-images"></i>
                </span>
                <div>
                    <h3 class="h3 fw-700 font-serif mb-0 text-dark line-height-1" id="uploaded-count-label">{{ $uploaded_count }}</h3>
                    <span class="text-muted fs-13">{{ translate('photos uploaded') }}</span>
                </div>
            </div>
        </div>

        {{-- Metric 3: Profile Photo Status --}}
        <div class="col-md-4">
            <div class="gallery-stat-card d-flex align-items-center">
                <span class="gallery-stat-icon mr-3" style="background: #fefce8; color: #b45309;">
                    <i class="las la-user-circle"></i>
                </span>
                <div>
                    <h5 class="fs-14 fw-700 mb-0 text-dark">{{ translate('Profile photo') }}</h5>
                    @if($is_profile_photo_set)
                        <span class="text-success fw-600 fs-13 d-inline-flex align-items-center">
                            {{ translate('Set') }} <i class="las la-check-circle ml-1" style="font-size: 15px;"></i>
                        </span>
                    @else
                        <span class="text-warning fw-600 fs-13">{{ translate('Not set') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content: Left (Your Photos) + Right (Sidebar Upload & Guidelines) --}}
    <div class="row gutters-15">
        {{-- Left Column: Your Photos Card --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="gallery-panel-card p-4">
                {{-- Card Header with Title and Sorting --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-2 border-bottom border-stone-100">
                    <div class="d-flex align-items-start mr-2">
                        <span class="mr-3 mt-1 text-primary" style="font-size: 22px;">
                            <i class="las la-photo-video"></i>
                        </span>
                        <div>
                            <h4 class="fs-17 fw-700 text-dark mb-1 font-serif">{{ translate('Your Photos') }}</h4>
                            <p class="text-muted fs-13 mb-0">{{ translate('Upload and manage your photos. You can set a primary photo and control visibility.') }}</p>
                        </div>
                    </div>
                    <div class="mt-2 mt-sm-0 d-flex align-items-center">
                        @if ($remaining_uploads > 0)
                            <button type="button" class="btn-maroon-pill btn-sm mr-3" onclick="triggerFileInput()">
                                <i class="las la-plus-circle"></i> {{ translate('Upload Photo') }}
                            </button>
                        @endif
                        <form method="GET" action="{{ route('gallery-image_index') }}" id="gallery-sort-form" class="form-inline">
                            <label class="text-muted fs-12 mr-2 mb-0">{{ translate('Sort by') }}</label>
                            <select name="sort" class="form-control form-control-sm rounded-pill px-3 fs-12 border-stone-200" onchange="document.getElementById('gallery-sort-form').submit();" style="height: 32px; background: #faf8f5;">
                                <option value="newest" @if($sort == 'newest') selected @endif>{{ translate('Newest') }}</option>
                                <option value="oldest" @if($sort == 'oldest') selected @endif>{{ translate('Oldest') }}</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Photo Grid --}}
                <div class="gallery-grid" id="gallery-items-container">
                    @php $user_photo_id = Auth::user()->photo; @endphp
                    @forelse ($gallery_images as $key => $gallery_image)
                        @php 
                            $is_primary = (!empty($user_photo_id) && $user_photo_id == $gallery_image->image);
                            $img_url = uploaded_asset($gallery_image->image);
                        @endphp
                        <div class="gallery-photo-card" id="gallery-card-{{ $gallery_image->id }}">
                            {{-- Primary Crown Badge --}}
                            @if ($is_primary)
                                <span class="gallery-primary-badge" id="primary-badge-{{ $gallery_image->id }}">
                                    <i class="las la-crown"></i> {{ translate('Profile Photo') }}
                                </span>
                            @endif

                            {{-- Dropdown Menu Trigger --}}
                            <div class="dropdown">
                                <button type="button" class="gallery-menu-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ translate('Options') }}">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2 fs-13" style="border-radius: 12px;">
                                    <a class="dropdown-item py-2 d-flex align-items-center" href="javascript:void(0);" onclick="openLightbox('{{ $img_url }}', '{{ $key }}')">
                                        <i class="las la-search-plus mr-2 text-primary fs-16"></i> {{ translate('View Fullscreen') }}
                                    </a>
                                    @if (!$is_primary)
                                        <a class="dropdown-item py-2 d-flex align-items-center btn-make-primary" href="javascript:void(0);" onclick="setAsPrimaryPhoto({{ $gallery_image->id }})">
                                            <i class="las la-crown mr-2 text-warning fs-16"></i> {{ translate('Set as Profile Photo') }}
                                        </a>
                                    @endif
                                    <div class="dropdown-divider my-1"></div>
                                    <a class="dropdown-item py-2 d-flex align-items-center text-danger" href="javascript:void(0);" onclick="confirmDeletePhoto('{{ route('gallery_image.destroy', $gallery_image->id) }}', {{ $gallery_image->id }})">
                                        <i class="las la-trash-alt mr-2 fs-16"></i> {{ translate('Delete Photo') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Thumbnail Image Container --}}
                            <div class="gallery-photo-thumb-wrap" onclick="openLightbox('{{ $img_url }}', '{{ $key }}')">
                                <img src="{{ $img_url }}" alt="{{ translate('Gallery photo') }}" class="gallery-photo-img" loading="lazy" onerror="this.src='{{ static_asset('assets/img/avatar-place.png') }}'">
                            </div>

                            {{-- Card Footer Actions --}}
                            <div class="gallery-photo-footer">
                                <span class="gallery-vis-pill">
                                    <i class="las la-eye"></i> {{ translate('Visible') }}
                                </span>
                                <div class="d-flex align-items-center">
                                    @if ($is_primary)
                                        <span class="gallery-primary-pill" id="primary-pill-{{ $gallery_image->id }}">
                                            {{ translate('Primary') }}
                                        </span>
                                    @else
                                        <button type="button" class="gallery-icon-btn btn-primary-set mr-1" onclick="setAsPrimaryPhoto({{ $gallery_image->id }})" title="{{ translate('Make Profile Photo') }}">
                                            <i class="lar la-star"></i>
                                        </button>
                                        <button type="button" class="gallery-icon-btn btn-delete" onclick="confirmDeletePhoto('{{ route('gallery_image.destroy', $gallery_image->id) }}', {{ $gallery_image->id }})" title="{{ translate('Delete') }}">
                                            <i class="las la-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse

                    {{-- Add Photos Placeholder Card (Always in grid if remaining uploads > 0) --}}
                    @if ($remaining_uploads > 0)
                        <div class="gallery-add-slot" id="gallery-add-slot" onclick="triggerFileInput()" role="button" tabindex="0">
                            <div id="add-slot-content">
                                <span class="gallery-add-slot-circle">
                                    <i class="las la-plus"></i>
                                </span>
                                <span class="fw-700 text-dark fs-14 mb-1 d-block">{{ translate('Add photos') }}</span>
                                <span class="text-muted fs-11" style="line-height: 1.3;">{{ translate('Click or drag photos here') }}<br>{{ translate('JPG or PNG • Max 5 MB') }}</span>
                            </div>
                            <div id="add-slot-loading" class="d-none">
                                <div class="spinner-border text-primary mb-2" role="status" style="width: 2rem; height: 2rem;"></div>
                                <span class="fw-700 text-dark fs-12 d-block">{{ translate('Uploading...') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Pagination if more than 24 photos --}}
                @if ($gallery_images->hasPages())
                    <div class="mt-4 pt-3 border-top border-stone-100 d-flex justify-content-center">
                        {{ $gallery_images->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Photo Guidelines + Privacy --}}
        <div class="col-lg-4">
            {{-- Card 1: Photo Guidelines --}}
            <div class="gallery-panel-card p-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="text-warning mr-2" style="font-size: 20px;"><i class="las la-lightbulb"></i></span>
                    <h5 class="fs-15 fw-700 text-dark mb-0 font-serif">{{ translate('Photo Guidelines') }}</h5>
                </div>

                {{-- Guideline 1 --}}
                <div class="guideline-item">
                    <span class="guideline-icon-circle"><i class="las la-calendar-check"></i></span>
                    <div>
                        <h6 class="fs-13 fw-700 text-dark mb-0">{{ translate('Use recent photos') }}</h6>
                        <span class="text-muted fs-12" style="line-height: 1.4;">{{ translate('Photos from the last 6-12 months work best.') }}</span>
                    </div>
                </div>

                {{-- Guideline 2 --}}
                <div class="guideline-item">
                    <span class="guideline-icon-circle"><i class="las la-portrait"></i></span>
                    <div>
                        <h6 class="fs-13 fw-700 text-dark mb-0">{{ translate('Keep images clear') }}</h6>
                        <span class="text-muted fs-12" style="line-height: 1.4;">{{ translate('Make sure your face is well lit and clearly visible.') }}</span>
                    </div>
                </div>

                {{-- Guideline 3 --}}
                <div class="guideline-item">
                    <span class="guideline-icon-circle"><i class="las la-user-shield"></i></span>
                    <div>
                        <h6 class="fs-13 fw-700 text-dark mb-0">{{ translate('Avoid sharing private details') }}</h6>
                        <span class="text-muted fs-12" style="line-height: 1.4;">{{ translate('Do not share phone numbers, ID cards or personal sensitive info.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Gallery Privacy --}}
            <div class="gallery-panel-card p-4">
                <div class="d-flex align-items-center mb-1">
                    <span class="text-primary mr-2" style="font-size: 20px;"><i class="las la-shield-alt"></i></span>
                    <h5 class="fs-15 fw-700 text-dark mb-0 font-serif">{{ translate('Gallery Privacy') }}</h5>
                </div>
                <p class="text-muted fs-12 mb-3">{{ translate('Control who can view your photos.') }}</p>

                <div class="p-3 border border-stone-200/80 d-flex align-items-center justify-content-between" style="background: #faf8f5; border-radius: 14px;">
                    <div class="d-flex align-items-center mr-3">
                        <span class="gallery-privacy-icon-wrap mr-2" id="privacy-icon-indicator" style="width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: {{ $gallery_privacy == 'only_me' ? '#f0fdf4' : '#fef2f2' }}; color: {{ $gallery_privacy == 'only_me' ? '#16a34a' : '#dc2626' }}; font-size: 19px;">
                            <i class="{{ $gallery_privacy == 'only_me' ? 'las la-user-lock' : 'las la-globe-americas' }}" id="privacy-icon-el"></i>
                        </span>
                        <div>
                            <span class="fs-13 fw-700 text-dark d-block line-height-1 mb-1" id="privacy-status-text">
                                {{ $gallery_privacy == 'only_me' ? translate('Visible to approved members') : translate('Visible to all members') }}
                            </span>
                            <span class="text-muted fs-11" id="privacy-sub-text">
                                {{ $gallery_privacy == 'only_me' ? translate('Approval request required') : translate('Visible to all members') }}
                            </span>
                        </div>
                    </div>
                        <label class="gallery-onoff-switch" title="{{ translate('Toggle Gallery Privacy') }}">
                            <input type="checkbox" id="gallery-privacy-switch" {{ $gallery_privacy == 'only_me' ? 'checked' : '' }} onchange="toggleGalleryPrivacy(this.checked)">
                            <span class="gallery-onoff-slider">
                                <span class="onoff-label on-label">ON</span>
                                <span class="gallery-onoff-knob"></span>
                                <span class="onoff-label off-label">OFF</span>
                            </span>
                        </label>
                </div>
                <small class="text-muted fs-11 d-block mt-2" id="privacy-footer-note">
                    @if ($gallery_privacy == 'only_me')
                        <i class="las la-lock mr-1 text-success"></i> {{ translate('Photos are locked. Other members must send a request to view them.') }}
                    @else
                        <i class="las la-unlock mr-1 text-danger"></i> {{ translate('Photos are open. All members can view your gallery.') }}
                    @endif
                </small>
            </div>
        </div>
    </div>

    {{-- Hidden Direct Upload Form --}}
    <form id="direct-upload-form" action="{{ route('gallery-image_store') }}" method="POST" enctype="multipart/form-data" class="d-none">
        @csrf
        <input type="file" id="direct-file-input" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" onchange="handleDirectFiles(this.files)">
    </form>
</div>
@endsection

@section('modal')
{{-- Ultra-Modern Theatrical Fullscreen Lightbox --}}
<div class="modern-lightbox" id="modernGalleryLightbox" role="dialog" aria-modal="true">
    {{-- Header Bar --}}
    <div class="modern-lightbox-header">
        <div class="modern-lightbox-counter-pill">
            <i class="las la-images mr-1 text-warning"></i>
            <span id="modern-lightbox-counter">1 / 1</span>
        </div>
        <div class="modern-lightbox-actions">
            <button type="button" class="modern-lightbox-btn-set-primary" id="lightboxMakePrimaryBtn" onclick="setPrimaryFromLightbox()">
                <i class="las la-crown"></i>
                <span>{{ translate('Set as Profile Photo') }}</span>
            </button>
            <button type="button" class="modern-lightbox-close-circle" onclick="closeModernLightbox()" title="{{ translate('Close (Esc)') }}">
                <i class="las la-times"></i>
            </button>
        </div>
    </div>

    {{-- Center Stage with Floating Image & Nav Arrows --}}
    <div class="modern-lightbox-stage" onclick="handleStageBackdropClick(event)">
        <button type="button" class="modern-lightbox-nav-arrow prev" id="modernLightboxPrevBtn" onclick="navigateModernLightbox(-1)" title="{{ translate('Previous') }}">
            <i class="las la-chevron-left"></i>
        </button>

        <div class="modern-lightbox-img-wrap" id="modernLightboxImgWrap">
            <img src="" id="modernLightboxImage" class="modern-lightbox-img" alt="{{ translate('Gallery photo preview') }}">
        </div>

        <button type="button" class="modern-lightbox-nav-arrow next" id="modernLightboxNextBtn" onclick="navigateModernLightbox(1)" title="{{ translate('Next') }}">
            <i class="las la-chevron-right"></i>
        </button>
    </div>

    {{-- Bottom Thumbnails Strip --}}
    <div class="modern-lightbox-bottom">
        <div class="modern-lightbox-thumbs" id="modernLightboxThumbsList">
            {{-- Thumbnails generated dynamically by JS --}}
        </div>
    </div>
</div>

<style>
/* Ultra-Modern Fullscreen Lightbox */
.modern-lightbox {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(10, 10, 15, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    display: none;
    flex-direction: column;
    justify-content: space-between;
    opacity: 0;
    transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    user-select: none;
}
.modern-lightbox.active {
    display: flex;
    opacity: 1;
}
.modern-lightbox-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 30px;
    z-index: 10;
}
.modern-lightbox-counter-pill {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #f8fafc;
    font-size: 13.5px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 9999px;
    backdrop-filter: blur(8px);
    display: inline-flex;
    align-items: center;
}
.modern-lightbox-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.modern-lightbox-btn-set-primary {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    color: #ffffff;
    border: none;
    padding: 9px 18px;
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
}
.modern-lightbox-btn-set-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(217, 119, 6, 0.5);
    filter: brightness(1.08);
}
.modern-lightbox-btn-set-primary.is-already-primary {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.4);
    color: #f59e0b;
    box-shadow: none;
    cursor: default;
}
.modern-lightbox-close-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none !important;
}
.modern-lightbox-close-circle:hover {
    background: #ef4444;
    border-color: #ef4444;
    transform: rotate(90deg) scale(1.08);
    color: #ffffff;
}
.modern-lightbox-stage {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 10px 80px;
    overflow: hidden;
}
.modern-lightbox-img-wrap {
    max-height: 75vh;
    max-width: 86vw;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.modern-lightbox-img {
    max-height: 75vh;
    max-width: 86vw;
    object-fit: contain;
    border-radius: 16px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.12);
    transition: opacity 0.25s ease, transform 0.25s ease;
}
.modern-lightbox-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.18);
    color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    cursor: pointer;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition: all 0.2s ease;
    z-index: 10;
    outline: none !important;
}
.modern-lightbox-nav-arrow:hover {
    background: #ffffff;
    color: #0f172a;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}
.modern-lightbox-nav-arrow.prev {
    left: 28px;
}
.modern-lightbox-nav-arrow.next {
    right: 28px;
}
@media (max-width: 767.98px) {
    .modern-lightbox-nav-arrow {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    .modern-lightbox-nav-arrow.prev { left: 10px; }
    .modern-lightbox-nav-arrow.next { right: 10px; }
    .modern-lightbox-stage { padding: 10px 40px; }
    .modern-lightbox-header { padding: 14px 18px; }
    .modern-lightbox-btn-set-primary span { display: none; }
}
/* Bottom Thumbnails Strip */
.modern-lightbox-bottom {
    padding: 16px 20px 24px;
    display: flex;
    justify-content: center;
    z-index: 10;
}
.modern-lightbox-thumbs {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 14px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 9999px;
    backdrop-filter: blur(10px);
    max-width: 90vw;
    overflow-x: auto;
}
.modern-lightbox-thumb-item {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    opacity: 0.55;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.modern-lightbox-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.modern-lightbox-thumb-item:hover {
    opacity: 0.9;
    transform: scale(1.08);
}
.modern-lightbox-thumb-item.active {
    opacity: 1;
    border-color: #f59e0b;
    transform: scale(1.15);
    box-shadow: 0 0 14px rgba(245, 158, 11, 0.6);
}
</style>

{{-- Confirm Delete Modal --}}
<div class="modal fade" id="image_delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content text-center p-4 border-0 shadow-lg" style="border-radius: 18px;">
            <div class="size-60px rounded-circle mx-auto bg-soft-danger d-flex align-items-center justify-content-center mb-3">
                <i class="las la-trash-alt la-2x text-danger"></i>
            </div>
            <h5 class="fs-16 fw-700 text-dark mb-1">{{ translate('Delete Photo?') }}</h5>
            <p class="text-muted fs-13 mb-3">{{ translate('Are you sure you want to remove this photo from your gallery?') }}</p>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <button type="button" class="btn btn-light rounded-pill px-3 fs-13 mr-2" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 fs-13" id="confirm-delete-action-btn">{{ translate('Yes, Delete') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var allGalleryData = [
        @foreach($gallery_images as $key => $g)
        {
            id: {{ $g->id }},
            url: '{{ uploaded_asset($g->image) }}',
            is_primary: {{ (!empty($user_photo_id) && $user_photo_id == $g->image) ? 'true' : 'false' }}
        },
        @endforeach
    ];
    var currentLightboxIndex = 0;
    var currentDeleteUrl = '';
    var currentDeleteCardId = 0;

    function triggerFileInput() {
        document.getElementById('direct-file-input').click();
    }

    // Drag and Drop listeners on Add Slot & Gallery Grid
    var addSlot = document.getElementById('gallery-add-slot');
    var galleryGrid = document.getElementById('gallery-items-container');
    var dropTargets = [addSlot, galleryGrid].filter(Boolean);

    dropTargets.forEach(function(target) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            target.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (addSlot) addSlot.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            target.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (addSlot) addSlot.classList.remove('dragover');
            }, false);
        });

        target.addEventListener('drop', function(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            handleDirectFiles(files);
        }, false);
    });

    function handleDirectFiles(files) {
        if (!files || files.length === 0) return;

        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        for (var i = 0; i < files.length; i++) {
            formData.append('photos[]', files[i]);
        }

        // Show slot loading spinner
        if ($('#gallery-add-slot').length) {
            $('#add-slot-content').addClass('d-none');
            $('#add-slot-loading').removeClass('d-none');
        }
        AIZ.plugins.notify('info', '{{ translate("Uploading photos, please wait...") }}');

        $.ajax({
            url: '{{ route("gallery-image_store") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if ($('#gallery-add-slot').length) {
                    $('#add-slot-content').removeClass('d-none');
                    $('#add-slot-loading').addClass('d-none');
                }
                if (response.success) {
                    AIZ.plugins.notify('success', response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                } else {
                    AIZ.plugins.notify('danger', response.message || '{{ translate("Upload failed.") }}');
                }
            },
            error: function(xhr) {
                if ($('#gallery-add-slot').length) {
                    $('#add-slot-content').removeClass('d-none');
                    $('#add-slot-loading').addClass('d-none');
                }
                var msg = '{{ translate("Upload failed.") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                AIZ.plugins.notify('danger', msg);
            }
        });
    }

    // Toggle Gallery Privacy (only_me vs all) via AJAX
    function toggleGalleryPrivacy(isChecked) {
        var newPrivacy = isChecked ? 'only_me' : 'all';
        var $switch = $('#gallery-privacy-switch');
        $switch.prop('disabled', true);

        $.ajax({
            url: '{{ route("gallery_image.update_privacy") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                privacy: newPrivacy
            },
            success: function(response) {
                $switch.prop('disabled', false);
                if (response.success) {
                    if (response.privacy === 'only_me') {
                        // ON State: Green notification
                        AIZ.plugins.notify('success', '{{ translate("Visibility On") }}');

                        $('#privacy-status-text').text('{{ translate("Visible to approved members") }}');
                        $('#privacy-sub-text').text('{{ translate("Approval request required") }}');
                        $('#header-privacy-label').text('{{ translate("Visible to approved members") }}');
                        $('#privacy-icon-indicator').css({'background': '#f0fdf4', 'color': '#16a34a'});
                        $('#privacy-icon-el').attr('class', 'las la-user-lock');
                        $('#privacy-footer-note').html('<i class="las la-lock mr-1 text-success"></i> {{ translate("Photos are locked. Other members must send a request to view them.") }}');
                    } else {
                        // OFF State: Red notification
                        AIZ.plugins.notify('danger', '{{ translate("Visibility Off") }}');

                        $('#privacy-status-text').text('{{ translate("Visible to all members") }}');
                        $('#privacy-sub-text').text('{{ translate("Visible to all members") }}');
                        $('#header-privacy-label').text('{{ translate("Visible to all members") }}');
                        $('#privacy-icon-indicator').css({'background': '#fef2f2', 'color': '#dc2626'});
                        $('#privacy-icon-el').attr('class', 'las la-globe-americas');
                        $('#privacy-footer-note').html('<i class="las la-unlock mr-1 text-danger"></i> {{ translate("Photos are open. All members can view your gallery.") }}');
                    }
                } else {
                    AIZ.plugins.notify('danger', '{{ translate("Failed to update privacy.") }}');
                    $switch.prop('checked', !isChecked);
                }
            },
            error: function() {
                $switch.prop('disabled', false);
                AIZ.plugins.notify('danger', '{{ translate("Failed to update privacy.") }}');
                $switch.prop('checked', !isChecked);
            }
        });
    }

    // Set as Primary Profile Photo via AJAX
    function setAsPrimaryPhoto(galleryImageId) {
        $.ajax({
            url: '{{ route("gallery_image.set_profile_photo") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                gallery_image_id: galleryImageId
            },
            success: function(response) {
                if (response.success) {
                    AIZ.plugins.notify('success', response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 500);
                } else {
                    AIZ.plugins.notify('danger', response.message || '{{ translate("Could not set as profile photo.") }}');
                }
            },
            error: function(xhr) {
                var msg = '{{ translate("Error setting profile photo.") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                AIZ.plugins.notify('danger', msg);
            }
        });
    }

    function setPrimaryFromLightbox() {
        if (allGalleryData.length === 0) return;
        var currentItem = allGalleryData[currentLightboxIndex];
        if (currentItem && !currentItem.is_primary) {
            setAsPrimaryPhoto(currentItem.id);
        }
    }

    // Confirm Delete
    function confirmDeletePhoto(url, cardId) {
        currentDeleteUrl = url;
        currentDeleteCardId = cardId;
        $('#image_delete_modal').modal('show');
    }

    $('#confirm-delete-action-btn').on('click', function() {
        if (!currentDeleteUrl) return;

        var $btn = $(this);
        $btn.prop('disabled', true).text('{{ translate("Deleting...") }}');

        $.ajax({
            url: currentDeleteUrl,
            type: 'GET',
            success: function(response) {
                $('#image_delete_modal').modal('hide');
                $btn.prop('disabled', false).text('{{ translate("Yes, Delete") }}');
                AIZ.plugins.notify('success', '{{ translate("Image deleted successfully.") }}');
                
                $('#gallery-card-' + currentDeleteCardId).fadeOut(300, function() {
                    $(this).remove();
                    // update count
                    var currentCount = parseInt($('#uploaded-count-label').text()) || 0;
                    if (currentCount > 0) {
                        $('#uploaded-count-label').text(currentCount - 1);
                    }
                });
            },
            error: function() {
                $('#image_delete_modal').modal('hide');
                $btn.prop('disabled', false).text('{{ translate("Yes, Delete") }}');
                AIZ.plugins.notify('danger', '{{ translate("Failed to delete image.") }}');
            }
        });
    });

    // --- Ultra-Modern Theatrical Lightbox Controller ---
    function openLightbox(url, index) {
        currentLightboxIndex = parseInt(index) || 0;
        buildLightboxThumbnails();
        updateModernLightboxView();

        var $box = $('#modernGalleryLightbox');
        $box.css('display', 'flex');
        setTimeout(function() {
            $box.addClass('active');
        }, 10);

        $('body').css('overflow', 'hidden');
    }

    function closeModernLightbox() {
        var $box = $('#modernGalleryLightbox');
        $box.removeClass('active');
        setTimeout(function() {
            $box.css('display', 'none');
            $('body').css('overflow', '');
        }, 250);
    }

    function handleStageBackdropClick(e) {
        // If clicked on backdrop itself (not on the image or navigation buttons)
        if (e.target.classList.contains('modern-lightbox-stage') || e.target.classList.contains('modern-lightbox-img-wrap')) {
            closeModernLightbox();
        }
    }

    function navigateModernLightbox(dir) {
        if (allGalleryData.length === 0) return;
        currentLightboxIndex = (currentLightboxIndex + dir + allGalleryData.length) % allGalleryData.length;
        updateModernLightboxView();
    }

    function buildLightboxThumbnails() {
        var $thumbsContainer = $('#modernLightboxThumbsList');
        $thumbsContainer.empty();

        if (allGalleryData.length <= 1) {
            $('.modern-lightbox-bottom').hide();
            return;
        } else {
            $('.modern-lightbox-bottom').show();
        }

        allGalleryData.forEach(function(item, idx) {
            var $th = $('<div class="modern-lightbox-thumb-item" data-index="' + idx + '"><img src="' + item.url + '" alt="thumb"></div>');
            $th.on('click', function() {
                currentLightboxIndex = idx;
                updateModernLightboxView();
            });
            $thumbsContainer.append($th);
        });
    }

    function updateModernLightboxView() {
        if (allGalleryData.length === 0) return;
        var currentItem = allGalleryData[currentLightboxIndex];

        // Fade effect
        var $img = $('#modernLightboxImage');
        $img.css({ 'opacity': 0.3, 'transform': 'scale(0.98)' });
        
        setTimeout(function() {
            $img.attr('src', currentItem.url);
            $img.css({ 'opacity': 1, 'transform': 'scale(1)' });
        }, 150);

        // Counter
        $('#modern-lightbox-counter').text((currentLightboxIndex + 1) + ' / ' + allGalleryData.length);

        // Update Make Profile Photo button
        var $primaryBtn = $('#lightboxMakePrimaryBtn');
        if (currentItem.is_primary) {
            $primaryBtn.addClass('is-already-primary').html('<i class="las la-check-circle"></i> <span>{{ translate("Current Profile Photo") }}</span>');
        } else {
            $primaryBtn.removeClass('is-already-primary').html('<i class="las la-crown"></i> <span>{{ translate("Set as Profile Photo") }}</span>');
        }

        // Nav arrow visibility
        if (allGalleryData.length <= 1) {
            $('#modernLightboxPrevBtn, #modernLightboxNextBtn').hide();
        } else {
            $('#modernLightboxPrevBtn, #modernLightboxNextBtn').show();
        }

        // Active thumbnail highlighting
        $('.modern-lightbox-thumb-item').removeClass('active');
        $('.modern-lightbox-thumb-item[data-index="' + currentLightboxIndex + '"]').addClass('active');
    }

    // Keyboard Shortcuts (Esc, Left, Right)
    $(document).on('keydown', function(e) {
        if ($('#modernGalleryLightbox').hasClass('active')) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeModernLightbox();
            } else if (e.key === 'ArrowLeft' || e.keyCode === 37) {
                navigateModernLightbox(-1);
            } else if (e.key === 'ArrowRight' || e.keyCode === 39) {
                navigateModernLightbox(1);
            }
        }
    });
</script>
@endsection
