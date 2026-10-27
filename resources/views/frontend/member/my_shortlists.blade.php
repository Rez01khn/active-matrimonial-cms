@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $auth_id = Auth::id();
@endphp

<!-- Header & Counter -->
<div class="mb-4">
    <ul class="breadcrumb bg-transparent p-0 mb-2 small">
        <li class="breadcrumb-item opacity-50">
            <a class="text-reset" href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a>
        </li>
        <li class="text-dark fw-600 breadcrumb-item active">
            {{ translate('Shortlisted Profiles') }}
        </li>
    </ul>
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h1 class="h4 font-weight-bold mb-1 font-serif" style="color: #3b1721; letter-spacing: -0.3px;">
                {{ translate('Shortlisted Profiles') }}
            </h1>
            <p class="text-muted small mb-0">
                {{ translate('Keep track of profiles you are interested in and connect with them when you are ready.') }}
            </p>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="d-inline-flex align-items-center px-3 py-1 rounded-pill fw-600 fs-13" style="background: rgba(59, 23, 33, 0.08); color: #3b1721; border: 1px solid rgba(59, 23, 33, 0.12);">
                <i class="las la-bookmark text-warning mr-1 fs-16"></i>
                <span id="shortlist_counter_total">{{ $shortlists->total() }}</span>&nbsp;{{ translate('Saved Profiles') }}
            </span>
        </div>
    </div>
</div>

@if ($shortlists->count() > 0)
    <!-- Shortlisted Profiles 4-Column Horizontal Cards Grid -->
    <div class="row gutters-15" id="shortlist_cards_container">
        @foreach ($shortlists as $key => $shortlist)
            @php
                $candidate = $shortlist->user;
            @endphp
            @if ($candidate != null)
                @php
                    $candidate_member = $candidate->member;
                    $candidate_address = $candidate->addresses ? $candidate->addresses->first() : null;
                    $candidate_career = $candidate->career ? $candidate->career->first() : null;
                    $candidate_education = $candidate->education ? $candidate->education->first() : null;

                    // Calculate Age
                    $age = null;
                    if ($candidate_member && !empty($candidate_member->birthday)) {
                        try {
                            $age = \Carbon\Carbon::parse($candidate_member->birthday)->age;
                        } catch (\Exception $e) {
                            $age = null;
                        }
                    }

                    // Location text (e.g. "Sylhet", "Dhaka")
                    $location_city_or_state = 'Bangladesh';
                    if ($candidate_address) {
                        if ($candidate_address->city && !empty($candidate_address->city->name)) {
                            $location_city_or_state = $candidate_address->city->name;
                        } elseif ($candidate_address->state && !empty($candidate_address->state->name)) {
                            $location_city_or_state = $candidate_address->state->name;
                        } elseif ($candidate_address->country && !empty($candidate_address->country->name)) {
                            $location_city_or_state = $candidate_address->country->name;
                        }
                    }

                    // Education text
                    $education_text = ($candidate_education && !empty($candidate_education->degree)) 
                        ? $candidate_education->degree 
                        : translate('Graduate');

                    // Career text
                    $career_text = ($candidate_career && !empty($candidate_career->designation)) 
                        ? $candidate_career->designation 
                        : translate('Professional');

                    // Time text
                    $time_text = $shortlist->created_at 
                        ? $shortlist->created_at->diffForHumans() 
                        : translate('Recently');

                    // Photo URL & Fallback
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
                @endphp

                <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-3" id="shortlisted_member_{{ $candidate->id }}">
                    <div class="shortlist-ref-card">
                        
                        <!-- Left: Photo with Green Online Dot -->
                        <div class="shortlist-ref-photo-wrap">
                            <a href="{{ route('member_profile', $candidate->id) }}" class="d-block w-100 h-100">
                                <img src="{{ $avatar_url }}" alt="{{ $candidate->first_name }} {{ $candidate->last_name }}"
                                     class="shortlist-ref-photo"
                                     onerror="this.onerror=null;this.src='{{ $default_avatar }}';">
                            </a>
                            <span class="shortlist-ref-status-dot" title="{{ translate('Online') }}"></span>
                        </div>

                        <!-- Right: Details & Actions -->
                        <div class="shortlist-ref-info">
                            <!-- Line 1: Name & Time -->
                            <div class="cand-name-row d-flex align-items-center justify-content-between gap-1">
                                <h4 class="cand-name text-truncate mr-1" title="{{ $candidate->first_name }} {{ $candidate->last_name }}">
                                    <a href="{{ route('member_profile', $candidate->id) }}">
                                        {{ $candidate->first_name }} {{ $candidate->last_name }}
                                    </a>
                                </h4>
                                <span class="cand-time flex-shrink-0">
                                    <i class="lar la-calendar"></i>
                                    <span>{{ $time_text }}</span>
                                </span>
                            </div>

                            <!-- Line 2: Age • Location -->
                            <div class="cand-meta-location text-truncate">
                                {{ $age ? 'Age ' . $age : translate('Age N/A') }} &bull; {{ $location_city_or_state }}
                            </div>

                            <!-- Line 3 & 4: Education & Profession -->
                            <div class="cand-specs">
                                <div class="cand-spec-line" title="{{ $education_text }}">
                                    <i class="las la-graduation-cap"></i>
                                    <span class="text-truncate">{{ $education_text }}</span>
                                </div>
                                <div class="cand-spec-line" title="{{ $career_text }}">
                                    <i class="las la-briefcase"></i>
                                    <span class="text-truncate">{{ $career_text }}</span>
                                </div>
                            </div>

                            <!-- Line 5: Actions (View Profile & Heart/Remove Button) -->
                            <div class="cand-actions">
                                <a href="{{ route('member_profile', $candidate->id) }}" class="btn-ref-view">
                                    <i class="las la-eye"></i>
                                    <span>{{ translate('View Profile') }}</span>
                                </a>

                                <button type="button" class="btn-ref-fav"
                                    title="{{ translate('Remove from shortlist') }}"
                                    onclick="remove_shortlist({{ $candidate->id }})"
                                    onmouseenter="var i = this.querySelector('i'); if(i) { i.className = 'las la-trash-alt fs-14 text-danger'; }"
                                    onmouseleave="var i = this.querySelector('i'); if(i) { i.className = 'lar la-heart fs-14'; }">
                                    <i class="lar la-heart fs-14"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="aiz-pagination aiz-pagination-center mt-3 mb-4">
        {{ $shortlists->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="card border-0 shadow-sm rounded-2xl p-5 text-center my-4" style="border: 1px solid #ECE7E1 !important; background: #ffffff; border-radius: 16px;">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: #FAF5F0; color: #8C6239; font-size: 32px;">
            <i class="las la-bookmark"></i>
        </div>
        <h4 class="font-serif fw-700 mb-2" style="color: #3b1721;">{{ translate('No Shortlisted Profiles Yet') }}</h4>
        <p class="text-muted mx-auto mb-4" style="max-width: 440px; font-size: 14px;">
            {{ translate('When you find someone compatible, save them to your shortlist so you can easily review and connect with them later.') }}
        </p>
        <div>
            <a href="{{ route('member.listing') }}" class="btn rounded-pill px-4 py-2 fw-600 text-white" style="background: #3b1721;">
                <i class="las la-user-friends mr-1"></i> {{ translate('Explore Matched Profiles') }}
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
    function remove_shortlist(id) {
        $.post('{{ route('member.remove_from_shortlist') }}',
            {
                _token: '{{ csrf_token() }}',
                id: id
            },
            function (data) {
                if (data == 1) {
                    var el = $("#shortlisted_member_" + id);
                    el.fadeOut(300, function() {
                        $(this).remove();
                        var cnt = parseInt($('#shortlist_counter_total').text());
                        if (!isNaN(cnt) && cnt > 0) {
                            $('#shortlist_counter_total').text(cnt - 1);
                        }
                        if ($('#shortlist_cards_container [id^="shortlisted_member_"]').length === 0) {
                            location.reload();
                        }
                    });
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Removed from shortlist successfully") }}');
                    }
                } else {
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', '{{ translate("Something went wrong") }}');
                    }
                }
            }
        );
    }
</script>
@endsection
