@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $auth_id = Auth::id();
    // Cache expressed interest and shortlist IDs to avoid N+1 queries in view
    $expressed_interest_ids = \App\Models\ExpressInterest::where('interested_by', $auth_id)->pluck('user_id')->toArray();
    $received_interest_map = \App\Models\ExpressInterest::where('user_id', $auth_id)->pluck('status', 'interested_by')->toArray();
    $shortlisted_ids = \App\Models\Shortlist::where('shortlisted_by', $auth_id)->pluck('user_id')->toArray();
@endphp

<style>
    .viewer-profile-card {
        background: #ffffff;
        border: 1px solid #ECE7E1;
        border-radius: 16px;
        padding: 14px;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .viewer-profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(59, 23, 33, 0.08);
        border-color: #DFD3C3;
    }
    .viewer-card-img-wrap {
        position: relative;
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        background: #FAF5F0;
    }
    .viewer-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.35s ease;
    }
    .viewer-profile-card:hover .viewer-card-img {
        transform: scale(1.05);
    }
    .viewer-pill-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        line-height: 1.2;
        white-space: nowrap;
    }
</style>

<!-- Header & Counter -->
<div class="mb-4">
    <ul class="breadcrumb bg-transparent p-0 mb-2 small">
        <li class="breadcrumb-item opacity-50">
            <a class="text-reset" href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a>
        </li>
        <li class="text-dark fw-600 breadcrumb-item active">
            {{ translate('Profile Viewers') }}
        </li>
    </ul>
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h1 class="h4 font-weight-bold mb-1 font-serif" style="color: #3b1721; letter-spacing: -0.3px;">
                {{ translate('Profile Viewers') }}
            </h1>
            <p class="text-muted small mb-0">
                {{ translate('Members who recently visited your profile. Reach out to compatible candidates who showed interest.') }}
            </p>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="d-inline-flex align-items-center px-3 py-1.5 rounded-pill fw-600 fs-13" style="background: rgba(59, 23, 33, 0.08); color: #3b1721; border: 1px solid rgba(59, 23, 33, 0.12);">
                <i class="las la-eye text-primary mr-1.5 fs-16"></i>
                <span>{{ $profileViewers->total() }}</span>&nbsp;{{ translate('Total Visits') }}
            </span>
        </div>
    </div>
</div>

<!-- Profile Boost Banner -->
<div class="card mb-4 border-0 shadow-sm rounded-xl overflow-hidden" style="background: linear-gradient(135deg, #fdf8f3 0%, #faede1 100%); border: 1px solid #ebd9c8 !important;">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; background: #3b1721; color: #f3d5a5; flex-shrink: 0;">
                    <i class="las la-chart-line fs-24"></i>
                </div>
                <div>
                    <h5 class="fw-700 fs-15 mb-0" style="color: #3b1721;">{{ translate('Want to get 3x more profile views?') }}</h5>
                    <p class="text-muted fs-13 mb-0">
                        {{ translate('Upload high-quality gallery photos and keep your biodata updated so more members discover you.') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('profile_settings') }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-600 text-white" style="background: #3b1721;">
                <i class="las la-user-edit mr-1"></i> {{ translate('Complete Profile') }}
            </a>
        </div>
    </div>
</div>

@if ($profileViewers->count() > 0)
    <!-- Profile Viewers 3-4 Column Grid -->
    <div class="row gutters-15">
        @foreach ($profileViewers as $key => $profileViewer)
            @php
                $candidate = $profileViewer->profileViewer;
            @endphp
            @if ($candidate != null)
                @php
                    $candidate_age = !empty($candidate->member->birthday) ? \Carbon\Carbon::parse($candidate->member->birthday)->age : null;
                    $candidate_height = $candidate->physical_attributes->height ?? null;
                    $candidate_profession = ($candidate->career && $candidate->career->first()) ? $candidate->career->first()->designation : null;
                    
                    $candidate_city = null;
                    $candidate_country = null;
                    if ($candidate->addresses && $candidate->addresses->first()) {
                        $c_addr = $candidate->addresses->first();
                        $candidate_city = $c_addr->city->name ?? null;
                        $candidate_country = $c_addr->country->name ?? null;
                    }
                    $candidate_location = trim(($candidate_city ? $candidate_city . ', ' : '') . ($candidate_country ?? ''));

                    $gender_val = optional($candidate->member)->gender ?? 1;
                    $default_avatar = ($gender_val == 2)
                        ? static_asset('assets/img/female-avatar-place.png')
                        : static_asset('assets/img/avatar-place.png');

                    $photo_src = $default_avatar;
                    if (!empty($candidate->photo) && uploaded_asset($candidate->photo) != null) {
                        $photo_src = uploaded_asset($candidate->photo);
                    }

                    $has_sent_interest = in_array($candidate->id, $expressed_interest_ids);
                    $received_interest_status = $received_interest_map[$candidate->id] ?? null;
                    $is_shortlisted = in_array($candidate->id, $shortlisted_ids);
                    $view_time_text = $profileViewer->created_at ? $profileViewer->created_at->diffForHumans() : translate('Recently');
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4" id="viewer_card_{{ $candidate->id }}">
                    <div class="viewer-profile-card h-100 d-flex flex-column position-relative shadow-sm">
                        
                        <!-- Top Bar: Viewed Badge & Shortlist Action -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="viewer-pill-badge" style="background: rgba(59, 23, 33, 0.08); color: #3b1721; border: 1px solid rgba(59, 23, 33, 0.12);">
                                <i class="las la-clock mr-1 text-warning fs-12"></i> {{ $view_time_text }}
                            </span>
                            
                            <div class="d-flex align-items-center">
                                <!-- Shortlist Bookmark Button -->
                                <button type="button"
                                        id="shortlist_btn_{{ $candidate->id }}"
                                        onclick="{{ $is_shortlisted ? 'toggle_shortlist('.$candidate->id.', false)' : 'toggle_shortlist('.$candidate->id.', true)' }}"
                                        class="btn btn-icon btn-circle btn-xs shadow-none"
                                        style="width: 30px; height: 30px; background: {{ $is_shortlisted ? 'rgba(212,175,55,0.15)' : 'rgba(100,116,139,0.08)' }}; color: {{ $is_shortlisted ? '#D4AF37' : '#64748B' }};"
                                        title="{{ $is_shortlisted ? translate('Shortlisted') : translate('Add to shortlist') }}">
                                    <i class="{{ $is_shortlisted ? 'las la-bookmark fs-15' : 'lar la-bookmark fs-15' }}"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Candidate Photo Wrap (Clean, No Broken Text Inside) -->
                        <div class="viewer-card-img-wrap mb-2.5">
                            <a href="{{ route('member_profile', $candidate->id) }}" class="d-block w-100 h-100">
                                <img src="{{ $photo_src }}"
                                     alt="{{ $candidate->first_name }}"
                                     class="viewer-card-img"
                                     onerror="this.onerror=null;this.src='{{ $default_avatar }}';">
                            </a>
                            @if($candidate->code)
                                <span class="position-absolute d-inline-flex align-items-center text-white"
                                      style="bottom: 8px; left: 8px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); padding: 2px 8px; border-radius: 12px; font-size: 10.5px; font-weight: 500;">
                                    #{{ $candidate->code }}
                                </span>
                            @endif
                        </div>

                        <!-- Name & Verified Check -->
                        <div class="d-flex align-items-baseline justify-content-between mb-1.5">
                            <a href="{{ route('member_profile', $candidate->id) }}"
                               class="font-weight-bold text-truncate mr-2"
                               style="color: #2C0A12; font-size: 15px; text-decoration: none;"
                               title="{{ $candidate->first_name . ' ' . $candidate->last_name }}">
                                {{ $candidate->first_name . ' ' . $candidate->last_name }}
                            </a>
                            <i class="las la-check-circle text-success fs-15 flex-shrink-0" title="{{ translate('Verified Profile') }}"></i>
                        </div>

                        <!-- Specs: Age, Height, Religion -->
                        <div class="candidate-specs mb-3 flex-grow-1" style="min-height: 52px;">
                            <div class="text-truncate text-muted small mb-1" style="font-size: 12px;">
                                <i class="las la-user mr-1" style="color: #800020;"></i>
                                <span>{{ $candidate_age ? $candidate_age . ' ' . translate('Yrs') : '' }}</span>
                                @if($candidate_height)
                                    <span>&middot; {{ $candidate_height }}</span>
                                @endif
                                @if(!empty($candidate->spiritual_backgrounds->religion->name))
                                    <span>&middot; {{ $candidate->spiritual_backgrounds->religion->name }}</span>
                                @endif
                            </div>

                            @if(!empty($candidate_location))
                                <div class="text-truncate text-muted small mb-1" style="font-size: 12px;" title="{{ $candidate_location }}">
                                    <i class="las la-map-marker-alt mr-1" style="color: #800020;"></i>
                                    <span>{{ $candidate_location }}</span>
                                </div>
                            @endif

                            @if($candidate_profession)
                                <div class="text-truncate text-muted small" style="font-size: 12px;" title="{{ $candidate_profession }}">
                                    <i class="las la-briefcase mr-1" style="color: #800020;"></i>
                                    <span>{{ $candidate_profession }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Bottom Action Buttons: Separated with mt-auto & clean border-top wrapper -->
                        <div class="mt-auto pt-2.5" style="border-top: 1px solid #F3F1EC;">
                            <div class="row gutters-5">
                                <div class="col-6">
                                    <a href="{{ route('member_profile', $candidate->id) }}"
                                       class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center"
                                       style="font-size: 11.5px; height: 34px; color: #800020; border: 1.5px solid #800020; background: transparent; transition: all 0.2s;"
                                       onmouseover="this.style.background='rgba(128,0,32,0.06)';"
                                       onmouseout="this.style.background='transparent';">
                                        {{ translate('View Profile') }}
                                    </a>
                                </div>

                                <div class="col-6">
                                    @if ($has_sent_interest)
                                        <button type="button"
                                                class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center"
                                                style="font-size: 11.5px; height: 34px; background: rgba(16,185,129,0.1); color: #10B981; border: 1px solid rgba(16,185,129,0.25);"
                                                disabled>
                                            <i class="las la-check mr-1 fs-13"></i> {{ translate('Sent') }}
                                        </button>
                                    @elseif (!is_null($received_interest_status) && $received_interest_status == 0)
                                        <a href="{{ route('interest_requests') }}"
                                           class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center"
                                           style="font-size: 11.5px; height: 34px; background: rgba(59,23,33,0.08); color: #3b1721; border: 1px solid rgba(59,23,33,0.2);">
                                            <i class="las la-reply mr-1 fs-13"></i> {{ translate('Respond') }}
                                        </a>
                                    @else
                                        <button type="button"
                                                id="interest_btn_{{ $candidate->id }}"
                                                onclick="express_interest({{ $candidate->id }})"
                                                class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold text-white d-inline-flex align-items-center justify-content-center shadow-none"
                                                style="font-size: 11.5px; height: 34px; background: linear-gradient(135deg, #800020 0%, #9B1C3B 100%); border: none; box-shadow: 0 2px 6px rgba(128,0,32,0.2); transition: all 0.2s;"
                                                onmouseover="this.style.opacity='0.92';"
                                                onmouseout="this.style.opacity='1';"
                                                title="{{ translate('Express Interest') }}">
                                            <i class="las la-heart mr-1 fs-13"></i> {{ translate('Send Interest') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="aiz-pagination aiz-pagination-center mt-3 mb-4">
        {{ $profileViewers->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="card border-0 shadow-sm rounded-2xl p-5 text-center my-4" style="border: 1px solid #ECE7E1 !important; background: #ffffff;">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #FAF5F0; color: #8C6239; font-size: 32px;">
            <i class="las la-eye"></i>
        </div>
        <h4 class="font-serif fw-700 mb-2" style="color: #3b1721;">{{ translate('No Profile Viewers Yet') }}</h4>
        <p class="text-muted mx-auto mb-4" style="max-width: 440px; font-size: 14px;">
            {{ translate('Complete your profile and add photos to your gallery to attract more views and connection requests.') }}
        </p>
        <div class="d-flex justify-content-center" style="gap: 12px;">
            <a href="{{ route('profile_settings') }}" class="btn rounded-pill px-4 py-2 fw-600 text-white" style="background: #3b1721;">
                <i class="las la-user-edit mr-1"></i> {{ translate('Edit Profile') }}
            </a>
            <a href="{{ route('my_matched_profiles') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-600">
                <i class="las la-search mr-1"></i> {{ translate('Browse Matches') }}
            </a>
        </div>
    </div>
@endif

@endsection

@section('modal')
  @include('modals.confirm_modal')
  @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">
    // Shortlist toggle handler
    function toggle_shortlist(id, actionAdd) {
        var url = actionAdd ? '{{ route('member.add_to_shortlist') }}' : '{{ route('member.remove_from_shortlist') }}';
        var btn = $('#shortlist_btn_' + id);

        $.post(url, {
            _token: '{{ csrf_token() }}',
            id: id
        }, function(data) {
            if (data == 1) {
                if (actionAdd) {
                    btn.attr('onclick', 'toggle_shortlist(' + id + ', false)')
                       .css({'background': 'rgba(212,175,55,0.15)', 'color': '#D4AF37'})
                       .find('i').removeClass('lar').addClass('las');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Added to shortlist") }}');
                    }
                } else {
                    btn.attr('onclick', 'toggle_shortlist(' + id + ', true)')
                       .css({'background': 'rgba(100,116,139,0.08)', 'color': '#64748B'})
                       .find('i').removeClass('las').addClass('lar');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Removed from shortlist") }}');
                    }
                }
            } else {
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                }
            }
        });
    }

    // Express Interest
    var is_paid_user = {{ is_paid_member(Auth::id()) ? 'true' : 'false' }};

    function express_interest(id) {
        if (!is_paid_user) {
            $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
            $('#package_alert_text').text("{{ translate('Please upgrade to a premium package to express interest.') }}");
            $('.package_update_alert_modal').modal('show');
            return;
        }

        var btn = $("#interest_btn_" + id);
        btn.prop("disabled", true).html('<i class="las la-spinner la-spin mr-1"></i>');

        $.post('{{ route('express-interest.store') }}',
            {
                _token: '{{ csrf_token() }}',
                id: id
            },
            function (data) {
                if (data == 1 || (data && data.result)) {
                    btn.removeClass('text-white')
                       .addClass('btn-soft-success')
                       .css('background', '')
                       .prop('disabled', true)
                       .html('<i class="las la-heart mr-1 text-success fs-14"></i> {{ translate("Sent") }}');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Interest expressed successfully!") }}');
                    }
                } else if (data && data.status === 'upgrade_required') {
                    btn.prop("disabled", false).html('<i class="las la-heart mr-1 fs-14"></i> {{ translate("Connect") }}');
                    $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
                    $('#package_alert_text').text(data.message || "{{ translate('Please upgrade to a premium package to express interest.') }}");
                    $('.package_update_alert_modal').modal('show');
                } else {
                    btn.prop("disabled", false).html('<i class="las la-heart mr-1 fs-14"></i> {{ translate("Connect") }}');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', (data && data.message) ? data.message : '{{ translate("Something went wrong") }}');
                    }
                }
            }
        ).fail(function() {
            btn.prop("disabled", false).html('<i class="las la-heart mr-1 fs-14"></i> {{ translate("Connect") }}');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
            }
        });
    }

    function package_update_alert(){
        $('.package_update_alert_modal').modal('show');
    }
</script>
@endsection
