@extends('frontend.layouts.member_panel')

@section('panel_content')
    <!-- Breadcrumb & Header -->
    <div class="mb-4">
        <ul class="breadcrumb bg-transparent p-0 mb-2 small">
            <li class="breadcrumb-item opacity-50">
                <a class="text-reset" href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a>
            </li>
            <li class="text-dark fw-600 breadcrumb-item">
                <a class="text-reset" href="{{ route('my_matched_profiles') }}">{{ translate('Matched Profiles') }}</a>
            </li>
        </ul>
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h1 class="h4 font-weight-bold mb-1" style="color: #2C0A12; font-family: 'Playfair Display', Georgia, serif; letter-spacing: -0.3px;">
                    {{ translate('Matched Profiles') }}
                </h1>
                <p class="text-muted small mb-0">
                    {{ translate('Discover highly compatible profiles handpicked based on your partner preferences.') }}
                </p>
            </div>
            <div class="mt-2 mt-md-0">
                @if($canRefresh)
                    <a href="{{ route('match.refresh') }}"
                       class="btn btn-sm btn-outline-primary d-inline-flex align-items-center shadow-sm refresh-btn-pulse"
                       style="border-radius: 9999px; border-color: #800020; color: #800020; font-weight: 600; padding: 7px 18px; transition: all 0.2s;"
                       onmouseover="this.style.background='#800020'; this.style.color='#ffffff';"
                       onmouseout="this.style.background='transparent'; this.style.color='#800020';"
                       title="{{ translate('Recalculate and refresh matches with latest preferences') }}">
                        <i class="las la-sync mr-1.5 font-size-16"></i> {{ translate('Refresh Matches') }}
                    </a>
                @else
                    <button type="button"
                            class="btn btn-sm btn-soft-secondary d-inline-flex align-items-center"
                            style="border-radius: 9999px; font-weight: 500; padding: 7px 18px;"
                            disabled
                            title="{{ translate('You can refresh matches after 30 minutes') }} ({{ $nextRefresh ? $nextRefresh->diffForHumans() : '' }})">
                        <i class="las la-history mr-1.5 font-size-16"></i> {{ translate('Cooldown Active') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Card Container -->
    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 16px; border: 1px solid #ECE7E1 !important; background: #ffffff;">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('my_matched_profiles') }}" method="GET" id="matched_filter_form">
                <!-- Search & Filter Controls -->
                <div class="row gutters-10">
                    <!-- Search Input -->
                    <div class="col-12 col-lg-4 mb-3">
                        <div class="position-relative">
                            <i class="las la-search position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 18px; z-index: 2;"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="{{ translate('Search by name, ID, or keyword...') }}"
                                   style="padding-left: 42px; border-radius: 10px; height: 42px; border-color: #E2E8F0; font-size: 13.5px;">
                        </div>
                    </div>

                    <!-- Age Range -->
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <select name="age_range" class="form-control aiz-selectpicker" data-live-search="false" title="{{ translate('Age Range') }}" style="border-radius: 10px; height: 42px;">
                            <option value="all" {{ !request('age_range') || request('age_range') == 'all' ? 'selected' : '' }}>{{ translate('All Ages') }}</option>
                            <option value="18-25" {{ request('age_range') == '18-25' ? 'selected' : '' }}>18 - 25</option>
                            <option value="26-30" {{ request('age_range') == '26-30' ? 'selected' : '' }}>26 - 30</option>
                            <option value="31-35" {{ request('age_range') == '31-35' ? 'selected' : '' }}>31 - 35</option>
                            <option value="36-40" {{ request('age_range') == '36-40' ? 'selected' : '' }}>36 - 40</option>
                            <option value="41+" {{ request('age_range') == '41+' ? 'selected' : '' }}>41+</option>
                        </select>
                    </div>

                    <!-- Religion -->
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <select name="religion" class="form-control aiz-selectpicker" data-live-search="true" title="{{ translate('Religion') }}" style="border-radius: 10px; height: 42px;">
                            <option value="all" {{ !request('religion') || request('religion') == 'all' ? 'selected' : '' }}>{{ translate('All Religions') }}</option>
                            @foreach($religions as $rel)
                                <option value="{{ $rel->id }}" {{ request('religion') == $rel->id ? 'selected' : '' }}>{{ $rel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <select name="location" class="form-control aiz-selectpicker" data-live-search="true" title="{{ translate('Location') }}" style="border-radius: 10px; height: 42px;">
                            <option value="all" {{ !request('location') || request('location') == 'all' ? 'selected' : '' }}>{{ translate('All Locations') }}</option>
                            @if(isset($states) && count($states) > 0)
                                <optgroup label="{{ translate('Divisions / States') }}">
                                    @foreach($states as $st)
                                        <option value="{{ $st->id }}" {{ request('location') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if(isset($countries) && count($countries) > 0)
                                <optgroup label="{{ translate('Countries') }}">
                                    @foreach($countries as $cnt)
                                        <option value="{{ $cnt->id }}" {{ request('location') == $cnt->id ? 'selected' : '' }}>{{ $cnt->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    <!-- Education -->
                    <div class="col-6 col-md-4 col-lg-2 mb-3">
                        <select name="education" class="form-control aiz-selectpicker" data-live-search="true" title="{{ translate('Education') }}" style="border-radius: 10px; height: 42px;">
                            <option value="all" {{ !request('education') || request('education') == 'all' ? 'selected' : '' }}>{{ translate('All Education') }}</option>
                            <option value="BSc" {{ request('education') == 'BSc' ? 'selected' : '' }}>B.Sc / Engineering</option>
                            <option value="MBBS" {{ request('education') == 'MBBS' ? 'selected' : '' }}>MBBS / Medical</option>
                            <option value="MBA" {{ request('education') == 'MBA' ? 'selected' : '' }}>MBA / Masters</option>
                            <option value="BBA" {{ request('education') == 'BBA' ? 'selected' : '' }}>BBA / Business</option>
                            <option value="Diploma" {{ request('education') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                        </select>
                    </div>
                </div>

                <!-- Bottom Filter Row: Marital Status & Action Buttons (Search + Clear Filters) -->
                <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 border-top" style="border-color: #F1EFEA !important;">
                    <div class="d-flex align-items-center mb-2 mb-sm-0" style="min-width: 260px;">
                        <label class="small text-muted font-weight-600 mb-0 mr-2 text-nowrap">{{ translate('Marital Status:') }}</label>
                        <select name="marital_status" class="form-control form-control-sm aiz-selectpicker" style="border-radius: 8px;">
                            <option value="all" {{ !request('marital_status') || request('marital_status') == 'all' ? 'selected' : '' }}>{{ translate('All Statuses') }}</option>
                            @foreach($marital_statuses as $ms)
                                <option value="{{ $ms->id }}" {{ request('marital_status') == $ms->id ? 'selected' : '' }}>{{ $ms->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Explicit Search & Clear Filter Action Buttons Group -->
                    <div class="d-flex align-items-center">
                        <button type="submit"
                                class="btn btn-primary d-inline-flex align-items-center justify-content-center px-4 mr-2 shadow-sm"
                                style="background: linear-gradient(135deg, #800020 0%, #9E1C3D 100%); border: none; border-radius: 9999px; font-weight: 600; height: 38px; font-size: 13.5px; transition: transform 0.15s, box-shadow 0.15s;"
                                onmouseover="this.style.boxShadow='0 4px 12px rgba(128,0,32,0.35)';"
                                onmouseout="this.style.boxShadow='none';">
                            <i class="las la-search mr-1.5 font-size-16"></i> {{ translate('Search') }}
                        </button>
                        <a href="{{ route('my_matched_profiles') }}"
                           class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3"
                           style="border-radius: 9999px; height: 38px; color: #800020; border-color: rgba(128,0,32,0.3); background: #FAF7F5; font-weight: 500; font-size: 13.5px; transition: all 0.2s;"
                           onmouseover="this.style.background='rgba(128,0,32,0.08)';"
                           onmouseout="this.style.background='#FAF7F5';">
                            <i class="las la-sync-alt mr-1 font-size-15"></i> {{ translate('Clear Filters') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Match Stats & Sort Toolbar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 px-1">
        <div class="d-flex align-items-center mb-2 mb-sm-0">
            <span class="badge badge-inline px-3 py-2 font-weight-bold d-inline-flex align-items-center shadow-none"
                  style="background: rgba(128,0,32,0.07); color: #800020 !important; border-radius: 20px; font-size: 13px; border: 1px solid rgba(128,0,32,0.12);">
                <i class="las la-user-check mr-1.5 font-size-16" style="color: #800020;"></i>
                {{ $matchedProfiles->total() }} {{ translate('Matched Profiles Found') }}
            </span>
        </div>
        <div class="d-flex align-items-center">
            <span class="small text-muted font-weight-600 mr-2 text-nowrap">{{ translate('Sort by:') }}</span>
            <div style="width: 175px;">
                <select name="sort" class="form-control form-control-sm aiz-selectpicker" style="border-radius: 8px;" onchange="updateSortFilter(this.value)">
                    <option value="match_high" {{ request('sort') == 'match_high' || !request('sort') ? 'selected' : '' }}>{{ translate('Highest Match') }}</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ translate('Newest First') }}</option>
                    <option value="match_low" {{ request('sort') == 'match_low' ? 'selected' : '' }}>{{ translate('Lowest Match') }}</option>
                    <option value="age_asc" {{ request('sort') == 'age_asc' ? 'selected' : '' }}>{{ translate('Age: Young to Old') }}</option>
                    <option value="age_desc" {{ request('sort') == 'age_desc' ? 'selected' : '' }}>{{ translate('Age: Old to Young') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Matched Profiles 3-Column Grid -->
    <div class="row gutters-15">
        @forelse ($matchedProfiles as $matchedProfile)
            @php
                $candidate = $matchedProfile->user;
            @endphp
            @if ($candidate != null)
                @php
                    $candidate_member = $candidate->member;
                    $candidate_address = $candidate->addresses ? $candidate->addresses->first() : null;
                    $candidate_career = $candidate->career ? $candidate->career->first() : null;
                    $candidate_education = $candidate->education ? $candidate->education->first() : null;
                    $candidate_family = $candidate->families;

                    // Calculate Age
                    $age = null;
                    if ($candidate_member && !empty($candidate_member->birthday)) {
                        try {
                            $age = \Carbon\Carbon::parse($candidate_member->birthday)->age;
                        } catch (\Exception $e) {
                            $age = null;
                        }
                    }

                    // Location text
                    $location_parts = [];
                    if ($candidate_address) {
                        if ($candidate_address->city && !empty($candidate_address->city->name)) {
                            $location_parts[] = $candidate_address->city->name;
                        }
                        if ($candidate_address->state && !empty($candidate_address->state->name)) {
                            $location_parts[] = $candidate_address->state->name;
                        } elseif ($candidate_address->country && !empty($candidate_address->country->name)) {
                            $location_parts[] = $candidate_address->country->name;
                        }
                    }
                    $location_text = !empty($location_parts) ? implode(', ', $location_parts) : translate('Bangladesh');

                    // Education text
                    $education_text = ($candidate_education && !empty($candidate_education->degree)) 
                        ? $candidate_education->degree 
                        : translate('Graduate');

                    // Career text
                    $career_text = ($candidate_career && !empty($candidate_career->designation)) 
                        ? $candidate_career->designation 
                        : translate('Professional');

                    // Family text
                    $family_text = ($candidate_family && !empty($candidate_family->family_class)) 
                        ? $candidate_family->family_class 
                        : translate('Respectable Family');

                    // Photo URL
                    $gender_val = $candidate_member ? $candidate_member->gender : 1;
                    $default_avatar = ($gender_val == 2)
                        ? static_asset('assets/img/female-avatar-place.png')
                        : static_asset('assets/img/avatar-place.png');

                    $avatar_url = null;
                    if (!empty($candidate->photo)) {
                        $avatar_url = is_numeric($candidate->photo) ? uploaded_asset($candidate->photo) : static_asset($candidate->photo);
                    }
                    if (empty($avatar_url)) {
                        $avatar_url = $default_avatar;
                    }

                    $has_sent_interest = in_array($candidate->id, $expressed_interest_ids);
                    $is_shortlisted = in_array($candidate->id, $shortlisted_ids);
                @endphp

                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-4" id="matched_card_{{ $candidate->id }}">
                    <div class="match-profile-card">
                        
                        <!-- Photo Section (Framed with padding, 205px Height) -->
                        <div class="match-card-photo-wrap">
                            <div class="match-card-photo-inner">
                                <a href="{{ route('member_profile', $candidate->id) }}" class="d-block w-100 h-100">
                                    <img src="{{ $avatar_url }}" alt="{{ $candidate->first_name }} {{ $candidate->last_name }}"
                                        class="match-card-photo"
                                        onerror="this.onerror=null;this.src='{{ $default_avatar }}';">
                                </a>

                                <!-- Floating Top-Right Circular Button: Shortlist Heart -->
                                <button type="button" class="match-card-fav-btn"
                                    id="shortlist_btn_{{ $candidate->id }}"
                                    title="{{ $is_shortlisted ? translate('Shortlisted') : translate('Add to Wishlist') }}"
                                    onclick="toggle_shortlist({{ $candidate->id }}, this)">
                                    <i class="{{ $is_shortlisted ? 'las la-heart text-danger' : 'lar la-heart' }} fs-18"></i>
                                </button>

                                <!-- Floating Verified Profile Badge on Bottom-Left of Photo -->
                                @if($candidate->approved == 1)
                                    <div class="match-card-verified-badge">
                                        <i class="las la-check-circle fs-13"></i>
                                        <span>{{ translate('Verified Profile') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="match-card-body">
                            <!-- Name & Age Row -->
                            <div class="match-card-title-row">
                                <h3 class="match-card-name text-truncate mr-2">
                                    <a href="{{ route('member_profile', $candidate->id) }}" class="text-truncate d-block">
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </a>
                                </h3>
                                @if($age)
                                    <span class="match-card-age flex-shrink-0">{{ $age }}</span>
                                @endif
                            </div>

                            <!-- 4 Meta List Items with Gold Icons -->
                            <ul class="match-meta-list">
                                <li class="match-meta-item">
                                    <i class="las la-map-marker-alt"></i>
                                    <span class="text-truncate">{{ $location_text }}</span>
                                </li>
                                <li class="match-meta-item">
                                    <i class="las la-graduation-cap"></i>
                                    <span class="text-truncate">{{ $education_text }}</span>
                                </li>
                                <li class="match-meta-item">
                                    <i class="las la-briefcase"></i>
                                    <span class="text-truncate">{{ $career_text }}</span>
                                </li>
                                <li class="match-meta-item">
                                    <i class="las la-users"></i>
                                    <span class="text-truncate">{{ $family_text }}</span>
                                </li>
                            </ul>

                            <!-- Bottom Actions: View Profile & Interest -->
                            <div class="match-card-actions">
                                <a href="{{ route('member_profile', $candidate->id) }}" class="btn btn-match-view">
                                    {{ translate('View Profile') }}
                                </a>

                                <button type="button"
                                    id="interest_btn_{{ $candidate->id }}"
                                    class="btn btn-match-interest {{ $has_sent_interest ? 'btn-match-interest-sent' : '' }}"
                                    onclick="express_interest({{ $candidate->id }})"
                                    {{ $has_sent_interest ? 'disabled' : '' }}>
                                    <i class="{{ $has_sent_interest ? 'las la-heart' : 'lar la-heart' }} fs-14"></i>
                                    <span id="interest_text_{{ $candidate->id }}">{{ $has_sent_interest ? translate('Interest Sent') : translate('Interest') }}</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <div class="text-center py-5 px-3 bg-white shadow-sm" style="border: 1px solid #ECE7E1; border-radius: 16px;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 72px; height: 72px; background: rgba(128,0,32,0.06); color: #800020;">
                        <i class="las la-user-friends font-size-36"></i>
                    </div>
                    <h4 class="h5 font-weight-bold mb-2" style="color: #2C0A12; font-family: 'Playfair Display', Georgia, serif;">
                        {{ translate('No Matched Profiles Found') }}
                    </h4>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 500px;">
                        {{ translate('We could not find profiles matching your current filters. Try relaxing some filter criteria or recalculate your profile matches.') }}
                    </p>
                    <div class="d-inline-flex">
                        <a href="{{ route('my_matched_profiles') }}" class="btn btn-primary px-4 rounded-pill font-weight-bold mr-2" style="background: #800020; border-color: #800020;">
                            <i class="las la-sync mr-1"></i> {{ translate('Reset All Filters') }}
                        </a>
                        @if($canRefresh)
                            <a href="{{ route('match.refresh') }}" class="btn btn-outline-primary px-4 rounded-pill font-weight-bold" style="color: #800020; border-color: #800020;">
                                <i class="las la-sync-alt mr-1"></i> {{ translate('Refresh Matches') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="aiz-pagination mt-4 d-flex justify-content-center">
        {{ $matchedProfiles->links() }}
    </div>
@endsection

@section('modal')
    @include('modals.confirm_modal')
    @include('modals.package_update_alert_modal')

    <!-- Ignore Member Modal -->
    <div class="modal fade ignore_member_modal" id="ignore_member_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content text-center p-3" style="border-radius: 16px;">
                <div class="modal-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 56px; height: 56px; background: rgba(239,68,68,0.1); color: #EF4444;">
                        <i class="las la-ban font-size-30"></i>
                    </div>
                    <h5 class="font-weight-bold mb-1" style="color: #2C0A12;">{{ translate('Ignore Profile?') }}</h5>
                    <p class="text-muted small mb-4">
                        {{ translate('This member will be removed from your matched list and searches.') }}
                    </p>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-3 mr-2" data-dismiss="modal">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="button" id="confirm_ignore_btn" class="btn btn-danger rounded-pill px-4 font-weight-bold">
                            {{ translate('Yes, Ignore') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Member Modal -->
    <div class="modal fade report_member_modal" id="report_member_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-2" style="border-radius: 16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold" style="color: #2C0A12;">
                        <i class="las la-flag text-danger mr-1.5 font-size-20"></i>
                        {{ translate('Report Member') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('reportusers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="member_id" id="report_member_id">
                    <div class="modal-body pt-2">
                        <p class="text-muted small mb-3">
                            {{ translate('Report profile of') }} <strong id="report_member_name" class="text-dark"></strong>. {{ translate('Our moderation team investigates all reports promptly.') }}
                        </p>
                        <div class="form-group mb-3">
                            <label class="small font-weight-600 text-dark">{{ translate('Reason for Report') }} <span class="text-danger">*</span></label>
                            <select name="reason" class="form-control" required style="border-radius: 8px;">
                                <option value="">{{ translate('Select a reason...') }}</option>
                                <option value="Fake or Misleading Profile">{{ translate('Fake or Misleading Profile') }}</option>
                                <option value="Inappropriate Photo or Content">{{ translate('Inappropriate Photo or Content') }}</option>
                                <option value="Harassment or Offensive Behavior">{{ translate('Harassment or Offensive Behavior') }}</option>
                                <option value="Already Married or Ineligible">{{ translate('Already Married or Ineligible') }}</option>
                                <option value="Financial Scam or Commercial">{{ translate('Financial Scam or Commercial') }}</option>
                                <option value="Other Policy Violation">{{ translate('Other Policy Violation') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-3" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 font-weight-bold">{{ translate('Submit Report') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    var is_paid_user = {{ is_paid_member(Auth::id()) ? 'true' : 'false' }};

    function updateSortFilter(val) {
        var form = $('#matched_filter_form');
        $('<input>').attr({
            type: 'hidden',
            name: 'sort',
            value: val
        }).appendTo(form);
        form.submit();
    }

    // Express Interest
    function express_interest(id) {
        if (!is_paid_user) {
            $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
            $('#package_alert_text').text("{{ translate('Please upgrade to a premium package to express interest in matched profiles.') }}");
            $('.package_update_alert_modal').modal('show');
            return;
        }

        var $btn = $("#interest_btn_" + id);
        var $text = $("#interest_text_" + id);
        var originalContent = $btn.html();

        $btn.prop("disabled", true);
        $text.text("{{ translate('Sending...') }}");

        $.post('{{ route('express-interest.store') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (data) {
            if (data == 1 || (data && data.result)) {
                $btn.css({
                    'background-color': '#718096',
                    'cursor': 'default'
                })
                .prop('disabled', true)
                .removeAttr('onclick')
                .html('<i class="las la-heart fs-14"></i> <span>{{ translate("Interest Sent") }}</span>');

                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', '{{ translate("Interest expressed successfully!") }}');
                }
            } else if (data && data.status === 'upgrade_required') {
                $btn.prop('disabled', false).html(originalContent);
                $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
                $('#package_alert_text').text(data.message || "{{ translate('Please upgrade to a premium package to express interest.') }}");
                $('.package_update_alert_modal').modal('show');
            } else {
                $btn.prop('disabled', false).html(originalContent);
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', (data && data.message) ? data.message : '{{ translate("Something went wrong") }}');
                }
            }
        }).fail(function () {
            $btn.prop('disabled', false).html(originalContent);
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
            }
        });
    }

    // Toggle Shortlist
    function toggle_shortlist(id, btn) {
        var $btn = $(btn);
        var isShortlisted = $btn.find('i').hasClass('las');

        if (isShortlisted) {
            $.post('{{ route('member.remove_from_shortlist') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function (res) {
                if (res == 1) {
                    $btn.attr('title', '{{ translate("Add to Wishlist") }}')
                        .find('i').removeClass('las text-danger').addClass('lar');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Removed from shortlist") }}');
                    }
                } else {
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                }
            });
        } else {
            $.post('{{ route('member.add_to_shortlist') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function (res) {
                if (res == 1) {
                    $btn.attr('title', '{{ translate("Shortlisted") }}')
                        .find('i').removeClass('lar').addClass('las text-danger');
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Added to shortlist") }}');
                    }
                } else {
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                }
            });
        }
    }

    // Ignore Profile
    function ignore_member(id) {
        $('.ignore_member_modal').modal('show');
        $('#confirm_ignore_btn').off('click').on('click', function () {
            do_ignore(id);
        });
    }

    function do_ignore(id) {
        $.post('{{ route('member.add_to_ignore_list') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (res) {
            if (res == 1) {
                $('.ignore_member_modal').modal('hide');
                $('#matched_card_' + id).fadeOut(350, function () {
                    $(this).remove();
                });
                AIZ.plugins.notify('success', '{{ translate('Profile ignored successfully.') }}');
            } else {
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        }).fail(function () {
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        });
    }

    // Report Profile
    function report_member(id, name) {
        $('#report_member_id').val(id);
        $('#report_member_name').text(name);
        $('.report_member_modal').modal('show');
    }
</script>
@endsection
