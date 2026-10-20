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

    <!-- Matched Profiles 4-Column Grid -->
    <div class="row gutters-15">
        @forelse ($matchedProfiles as $matchedProfile)
            @php
                $candidate = $matchedProfile->user;
            @endphp
            @if ($candidate != null)
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4" id="matched_card_{{ $candidate->id }}">
                    <div class="match-profile-card h-100 d-flex flex-column position-relative shadow-sm">
                        <!-- Card Top Bar: Match Badge & Quick Actions -->
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge px-2.5 py-1 text-white font-weight-bold d-inline-flex align-items-center"
                                  style="background: linear-gradient(135deg, #800020 0%, #A31D3F 100%); border-radius: 9999px; font-size: 11px; letter-spacing: 0.3px; box-shadow: 0 2px 6px rgba(128,0,32,0.25);">
                                <i class="las la-fire-alt mr-1 font-size-12"></i> {{ $matchedProfile->match_percentage }}% {{ translate('Match') }}
                            </span>
                            <div class="d-flex align-items-center">
                                <!-- Quick Heart: Express Interest -->
                                <button type="button"
                                        onclick="express_interest({{ $candidate->id }})"
                                        id="interest_btn_{{ $candidate->id }}"
                                        class="btn btn-icon btn-circle btn-xs mr-1 shadow-none"
                                        style="width: 30px; height: 30px; background: {{ in_array($candidate->id, $expressed_interest_ids) ? 'rgba(16,185,129,0.1)' : 'rgba(128,0,32,0.07)' }}; color: {{ in_array($candidate->id, $expressed_interest_ids) ? '#10B981' : '#800020' }};"
                                        title="{{ in_array($candidate->id, $expressed_interest_ids) ? translate('Interest Expressed') : translate('Express Interest') }}">
                                    <i class="las la-heart font-size-15"></i>
                                </button>

                                <!-- Quick Bookmark: Shortlist -->
                                <button type="button"
                                        onclick="{{ in_array($candidate->id, $shortlisted_ids) ? 'remove_shortlist('.$candidate->id.')' : 'do_shortlist('.$candidate->id.')' }}"
                                        id="shortlist_btn_{{ $candidate->id }}"
                                        class="btn btn-icon btn-circle btn-xs mr-1 shadow-none"
                                        style="width: 30px; height: 30px; background: {{ in_array($candidate->id, $shortlisted_ids) ? 'rgba(212,175,55,0.15)' : 'rgba(100,116,139,0.08)' }}; color: {{ in_array($candidate->id, $shortlisted_ids) ? '#D4AF37' : '#64748B' }};"
                                        title="{{ in_array($candidate->id, $shortlisted_ids) ? translate('Shortlisted') : translate('Add to Shortlist') }}">
                                    <i class="{{ in_array($candidate->id, $shortlisted_ids) ? 'las la-bookmark font-size-15' : 'lar la-bookmark font-size-15' }}"></i>
                                </button>

                                <!-- 3-Dots Quick Dropdown Menu -->
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-icon btn-circle btn-xs btn-light shadow-none" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 30px; height: 30px; background: #f8f9fa;">
                                        <i class="las la-ellipsis-v text-muted font-size-15"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 py-2" style="border-radius: 12px; font-size: 13px; min-width: 210px; z-index: 1050;">
                                        <a class="dropdown-item py-2" href="{{ route('member_profile', $candidate->id) }}">
                                            <i class="las la-user-circle mr-2 font-size-16" style="color: #800020;"></i> {{ translate('View Full Profile') }}
                                        </a>
                                        <a class="dropdown-item py-2" href="{{ route('consultations.index') }}">
                                            <i class="las la-calendar-check mr-2 font-size-16 text-success"></i> {{ translate('Book Arranged Meeting') }}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item py-2" href="javascript:void(0);" onclick="ignore_member({{ $candidate->id }})">
                                            <i class="las la-ban mr-2 font-size-16 text-muted"></i> {{ translate('Ignore Profile') }}
                                        </a>
                                        <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="report_member({{ $candidate->id }}, '{{ addslashes($candidate->first_name . ' ' . $candidate->last_name) }}')">
                                            <i class="las la-flag mr-2 font-size-16 text-danger"></i> {{ translate('Report Member') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Candidate Landscape Photo (16:10 Ratio) -->
                        <div class="card-img-wrap position-relative mb-2.5 overflow-hidden" style="border-radius: 12px; height: 180px; background: #FAF5F0;">
                            <a href="{{ route('member_profile', $candidate->id) }}" class="d-block w-100 h-100">
                                @php
                                    $photo_src = static_asset('assets/img/avatar-place.png');
                                    if(uploaded_asset($candidate->photo) != null){
                                        $photo_src = uploaded_asset($candidate->photo);
                                    }
                                @endphp
                                <img src="{{ $photo_src }}"
                                     alt="{{ $candidate->first_name }}"
                                     class="w-100 h-100 card-profile-img"
                                     style="object-fit: cover; object-position: top; transition: transform 0.35s ease;"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            </a>
                            <!-- Online Indicator Badge -->
                            <span class="position-absolute d-inline-flex align-items-center text-white"
                                  style="bottom: 8px; left: 8px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); padding: 2px 8px; border-radius: 12px; font-size: 10.5px; font-weight: 500; letter-spacing: 0.2px;">
                                <span class="d-inline-block rounded-circle mr-1.5" style="width: 6.5px; height: 6.5px; background: #10B981; box-shadow: 0 0 5px #10B981;"></span>
                                {{ translate('Online') }}
                            </span>
                        </div>

                        <!-- Profile Specs & Info -->
                        <div class="d-flex align-items-baseline justify-content-between mb-1.5">
                            <a href="{{ route('member_profile', $candidate->id) }}"
                               class="font-weight-bold text-truncate mr-2 candidate-name"
                               style="color: #2C0A12; font-size: 15px; text-decoration: none;"
                               title="{{ $candidate->first_name . ' ' . $candidate->last_name }}">
                                {{ $candidate->first_name . ' ' . $candidate->last_name }}
                            </a>
                            <span class="small font-weight-600 text-muted text-nowrap" style="font-size: 11px;">#{{ $candidate->code }}</span>
                        </div>

                        <div class="candidate-specs mb-2.5">
                            @php
                                $age = optional($candidate->member)->birthday ? \Carbon\Carbon::parse($candidate->member->birthday)->age : null;
                                $present_address = $candidate->addresses->where('type', 'present')->first();
                                $cityName = optional($present_address)->city ? $present_address->city->name : null;
                                $stateName = optional($present_address)->state ? $present_address->state->name : null;
                                $countryName = optional($present_address)->country ? $present_address->country->name : null;
                                $locationText = $cityName ?? ($stateName ?? ($countryName ?? translate('Not Specified')));

                                $education = $candidate->education->first();
                                $educationText = $education ? $education->degree : translate('Not Specified');

                                $career = $candidate->career->first();
                                $careerText = $career ? ($career->designation . ($career->company ? ' at ' . $career->company : '')) : translate('Not Specified');
                            @endphp
                            <!-- Age & Location -->
                            <div class="text-truncate text-muted small mb-1" style="font-size: 12px;" title="{{ $age ? $age . ' ' . translate('Yrs') . ' • ' : '' }}{{ $locationText }}">
                                <i class="las la-map-marker-alt mr-1 font-size-13" style="color: #800020;"></i>
                                <span>{{ $age ? $age . ' ' . translate('Yrs') : '' }}{{ $age ? ' • ' : '' }}{{ $locationText }}</span>
                            </div>
                            <!-- Education Degree -->
                            <div class="text-truncate text-muted small mb-1" style="font-size: 12px;" title="{{ $educationText }}">
                                <i class="las la-graduation-cap mr-1 font-size-13" style="color: #800020;"></i>
                                <span>{{ $educationText }}</span>
                            </div>
                            <!-- Career / Profession -->
                            <div class="text-truncate text-muted small mb-2" style="font-size: 12px;" title="{{ $careerText }}">
                                <i class="las la-briefcase mr-1 font-size-13" style="color: #800020;"></i>
                                <span>{{ $careerText }}</span>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="row gutters-5 mt-auto pt-2 border-top" style="border-color: #F3F1EC !important;">
                            <div class="col-6">
                                @if(in_array($candidate->id, $expressed_interest_ids))
                                    <button type="button"
                                            class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center"
                                            style="font-size: 11.5px; height: 34px; background: rgba(16,185,129,0.1); color: #10B981; border: 1px solid rgba(16,185,129,0.25);"
                                            disabled>
                                        <i class="las la-check mr-1 font-size-13"></i> {{ translate('Sent') }}
                                    </button>
                                @else
                                    <button type="button"
                                            onclick="express_interest({{ $candidate->id }})"
                                            id="bottom_interest_btn_{{ $candidate->id }}"
                                            class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold text-white d-inline-flex align-items-center justify-content-center shadow-none"
                                            style="font-size: 11.5px; height: 34px; background: linear-gradient(135deg, #800020 0%, #9B1C3B 100%); border: none; box-shadow: 0 2px 6px rgba(128,0,32,0.2); transition: all 0.2s;"
                                            onmouseover="this.style.opacity='0.92';"
                                            onmouseout="this.style.opacity='1';">
                                        <i class="las la-heart mr-1 font-size-13"></i> {{ translate('Send Interest') }}
                                    </button>
                                @endif
                            </div>
                            <div class="col-6">
                                <a href="{{ route('member_profile', $candidate->id) }}"
                                   class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center"
                                   style="font-size: 11.5px; height: 34px; color: #800020; border: 1.5px solid #800020; background: transparent; transition: all 0.2s;"
                                   onmouseover="this.style.background='rgba(128,0,32,0.06)';"
                                   onmouseout="this.style.background='transparent';">
                                    {{ translate('View Profile') }}
                                </a>
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

    <style>
        .match-profile-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #ECE7E1;
            padding: 14px;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .match-profile-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(128, 0, 32, 0.08) !important;
            border-color: #E2D3CA !important;
        }
        .match-profile-card:hover .card-profile-img {
            transform: scale(1.04);
        }
        .candidate-name:hover {
            color: #800020 !important;
        }
    </style>
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

        var quickBtn = $("#interest_btn_" + id);
        var bottomBtn = $("#bottom_interest_btn_" + id);

        quickBtn.removeAttr("onclick");
        bottomBtn.removeAttr("onclick").prop("disabled", true);

        $.post('{{ route('express-interest.store') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (data) {
            if (data == 1 || (data && data.result)) {
                // Update quick button
                quickBtn.css({
                    "background": "rgba(16,185,129,0.1)",
                    "color": "#10B981"
                }).attr("title", "{{ translate('Interest Expressed') }}");

                // Update bottom button
                bottomBtn.replaceWith(
                    '<button type="button" class="btn btn-sm btn-block text-nowrap rounded-pill font-weight-bold d-inline-flex align-items-center justify-content-center" style="font-size: 11.5px; height: 34px; background: rgba(16,185,129,0.1); color: #10B981; border: 1px solid rgba(16,185,129,0.25);" disabled>' +
                    '<i class="las la-check mr-1 font-size-13"></i> {{ translate('Sent') }}' +
                    '</button>'
                );

                AIZ.plugins.notify('success', '{{ translate('Interest expressed successfully!') }}');
            } else if (data && data.status === 'upgrade_required') {
                $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
                $('#package_alert_text').text(data.message || "{{ translate('Please upgrade to a premium package to express interest.') }}");
                $('.package_update_alert_modal').modal('show');
                quickBtn.attr("onclick", "express_interest(" + id + ")");
                bottomBtn.attr("onclick", "express_interest(" + id + ")").prop("disabled", false);
            } else {
                quickBtn.attr("onclick", "express_interest(" + id + ")");
                bottomBtn.attr("onclick", "express_interest(" + id + ")").prop("disabled", false);
                AIZ.plugins.notify('danger', (data && data.message) ? data.message : '{{ translate('Something went wrong') }}');
            }
        }).fail(function () {
            quickBtn.attr("onclick", "express_interest(" + id + ")");
            bottomBtn.attr("onclick", "express_interest(" + id + ")").prop("disabled", false);
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        });
    }

    // Shortlist / Bookmark
    function do_shortlist(id) {
        var btn = $("#shortlist_btn_" + id);
        btn.removeAttr("onclick");

        $.post('{{ route('member.add_to_shortlist') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (res) {
            if (res == 1) {
                btn.css({
                    "background": "rgba(212,175,55,0.15)",
                    "color": "#D4AF37"
                }).attr({
                    "title": "{{ translate('Shortlisted') }}",
                    "onclick": "remove_shortlist(" + id + ")"
                });
                btn.find('i').removeClass('lar la-bookmark').addClass('las la-bookmark');
                AIZ.plugins.notify('success', '{{ translate('Profile added to shortlist.') }}');
            } else {
                btn.attr("onclick", "do_shortlist(" + id + ")");
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        }).fail(function () {
            btn.attr("onclick", "do_shortlist(" + id + ")");
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        });
    }

    function remove_shortlist(id) {
        var btn = $("#shortlist_btn_" + id);
        btn.removeAttr("onclick");

        $.post('{{ route('member.remove_from_shortlist') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function (res) {
            if (res == 1) {
                btn.css({
                    "background": "rgba(100,116,139,0.08)",
                    "color": "#64748B"
                }).attr({
                    "title": "{{ translate('Add to Shortlist') }}",
                    "onclick": "do_shortlist(" + id + ")"
                });
                btn.find('i').removeClass('las la-bookmark').addClass('lar la-bookmark');
                AIZ.plugins.notify('success', '{{ translate('Profile removed from shortlist.') }}');
            } else {
                btn.attr("onclick", "remove_shortlist(" + id + ")");
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        }).fail(function () {
            btn.attr("onclick", "remove_shortlist(" + id + ")");
            AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
        });
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
