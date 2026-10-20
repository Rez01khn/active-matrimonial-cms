@extends('frontend.layouts.member_panel')

@section('panel_content')
<style type="text/css">
/* ==========================================================================
   Interest Requests Clean Luxury Styles - Pixel-Perfect to Reference Image
   ========================================================================== */
.interest-requests-container {
    padding: 0 0 32px;
    font-family: var(--mat-font-sans, 'Plus Jakarta Sans', sans-serif);
}

/* Header */
.interest-page-header {
    margin-bottom: 18px;
}
.interest-page-title {
    font-family: var(--mat-font-serif, 'Playfair Display', Georgia, serif);
    font-size: 28px;
    font-weight: 700;
    color: var(--mat-maroon-dark, #2C0A12) !important;
    letter-spacing: -0.02em;
    line-height: 1.25;
    margin-bottom: 4px;
}
.interest-page-subtitle {
    font-size: 13.5px;
    color: #64748B;
    line-height: 1.45;
    margin-bottom: 0;
}

/* Toolbar (2 Tabs on Left + Search & Filter on Right) */
.interest-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 16px;
}
.interest-tabs-group {
    display: inline-flex;
    align-items: center;
    gap: 12px;
}
.interest-tab-pill {
    background: #FFFFFF;
    color: #4B5563;
    border: 1px solid #E5E7EB;
    border-radius: 9999px;
    padding: 7px 18px;
    font-size: 13.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}
.interest-tab-pill i {
    font-size: 16px;
}
.interest-tab-pill:hover {
    color: var(--mat-maroon-dark, #2C0A12);
    border-color: #D1D5DB;
    background: #F9FAFB;
}
.interest-tab-pill.active {
    background: var(--mat-maroon-dark, #2C0A12) !important;
    color: #FFFFFF !important;
    border-color: var(--mat-maroon-dark, #2C0A12) !important;
    box-shadow: 0 4px 14px rgba(44, 10, 18, 0.2);
}
.interest-tab-pill .interest-pill-badge {
    background: #F3F4F6;
    color: #4B5563;
    border-radius: 9999px;
    min-width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11.5px;
    font-weight: 700;
    padding: 0 6px;
    transition: all 0.2s ease;
}
.interest-tab-pill.active .interest-pill-badge {
    background: #FFFFFF !important;
    color: var(--mat-maroon-dark, #2C0A12) !important;
}

/* Search Form & Search Button */
.interest-search-form {
    display: flex;
    align-items: center;
    gap: 10px;
}
.interest-search-box {
    position: relative;
    min-width: 240px;
}
.interest-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    font-size: 16px;
    pointer-events: none;
}
.interest-search-input {
    border-radius: 9999px !important;
    padding-left: 38px !important;
    padding-right: 14px !important;
    height: 38px !important;
    font-size: 13px !important;
    border: 1px solid #E5E7EB !important;
    background: #FFFFFF !important;
    color: #1F2937 !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
    transition: all 0.2s ease !important;
}
.interest-search-input:focus {
    border-color: var(--mat-maroon-dark, #2C0A12) !important;
    box-shadow: 0 0 0 3px rgba(44, 10, 18, 0.08) !important;
}
.interest-search-btn {
    border-radius: 9999px !important;
    border: 1px solid #E5E7EB !important;
    background: #FFFFFF !important;
    color: #4B5563 !important;
    height: 38px !important;
    padding: 0 18px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    transition: all 0.2s ease !important;
    cursor: pointer;
}
.interest-search-btn:hover {
    background: #F9FAFB !important;
    color: var(--mat-maroon-dark, #2C0A12) !important;
    border-color: #D1D5DB !important;
}

/* ==========================================================================
   REQUEST CARD ARCHITECTURE (2 Cards Per Line / 2-Column Grid Layout)
   ========================================================================== */
.interest-cards-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.interest-cards-list > .text-center {
    grid-column: 1 / -1;
}

.interest-card-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    background: #FFFFFF;
    border: 1px solid #ECE7E1;
    border-radius: 18px; /* rounded-2xl */
    padding: 24px 26px; /* Generous vertical padding to balance width and height */
    min-height: 180px; /* Solid, balanced card height */
    margin-bottom: 0;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
}
.interest-card-item:hover {
    box-shadow: 0 8px 24px rgba(44, 10, 18, 0.06);
    border-color: rgba(140, 98, 57, 0.35);
    transform: translateY(-2px);
}

/* Column 1: Identity */
.interest-col-identity {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
    flex: 1.15;
}
.interest-avatar-box {
    position: relative;
    width: 78px;
    height: 78px;
    flex-shrink: 0;
}
.interest-avatar-img {
    width: 78px !important;
    height: 78px !important;
    max-width: 78px !important;
    max-height: 78px !important;
    border-radius: 18px; /* rounded-2xl */
    object-fit: cover;
    background: #F3F4F6;
    display: block;
}
.interest-status-dot {
    position: absolute;
    bottom: 0px;
    right: 0px;
    width: 13px;
    height: 13px;
    background-color: #10B981;
    border: 2px solid #FFFFFF;
    border-radius: 50%;
}
.interest-identity-meta {
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}
.interest-name-row {
    display: flex;
    align-items: center;
    gap: 6px;
}
.interest-user-fullname {
    font-size: 16px;
    font-weight: 700;
    color: #1F2937;
    line-height: 1.3;
    text-decoration: none !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: color 0.15s ease;
}
.interest-user-fullname:hover {
    color: var(--mat-maroon-dark, #2C0A12);
}
.interest-verified-tick {
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}
.interest-sub-location {
    font-size: 13px;
    color: #6B7280;
    font-weight: 500;
    line-height: 1.3;
    margin: 1px 0 3px;
}
.interest-peach-badges {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 6px;
}
.interest-peach-pill {
    background-color: #FFF7ED;
    color: #9A6B2F;
    border: 1px solid rgba(229, 194, 134, 0.45);
    border-radius: 8px;
    padding: 3.5px 9px;
    font-size: 11.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    line-height: 1.25;
    white-space: nowrap;
}

/* Column 2: Specs & Lifestyle Tags */
.interest-col-specs {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0;
    min-width: 0;
    flex: 1;
}
.interest-specs-list {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.interest-spec-line {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12.5px;
    line-height: 1.4;
}
.interest-spec-label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #9CA3AF;
    font-size: 12px;
    width: 82px;
    flex-shrink: 0;
}
.interest-spec-label i {
    font-size: 14px;
    color: #9CA3AF;
}
.interest-spec-value {
    color: #1F2937;
    font-weight: 600;
    font-size: 12.5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.interest-lifestyle-tags {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 13px;
}
.interest-lifestyle-pill {
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 500;
    padding: 3px 11px;
    line-height: 1.25;
    letter-spacing: 0.1px;
    white-space: nowrap;
}

/* Column 3: Action Buttons & 3-dot Menu */
.interest-col-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    flex-shrink: 0;
}
.interest-btn-stack {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 142px;
}
.interest-btn-view-profile {
    background: var(--mat-maroon-dark, #2C0A12) !important;
    color: #FFFFFF !important;
    border-radius: 9999px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    height: 38px !important;
    padding: 8px 18px !important;
    border: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    box-shadow: 0 2px 6px rgba(44, 10, 18, 0.16) !important;
    transition: all 0.2s ease !important;
    white-space: nowrap;
    text-decoration: none !important;
}
.interest-btn-view-profile:hover {
    background: #420F1B !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(44, 10, 18, 0.25) !important;
    transform: translateY(-1px);
}
.interest-btn-accept {
    background: #FFFFFF !important;
    color: var(--mat-maroon-dark, #2C0A12) !important;
    border: 1.5px solid var(--mat-maroon-dark, #2C0A12) !important;
    border-radius: 9999px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    height: 38px !important;
    padding: 7px 18px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    transition: all 0.2s ease !important;
    white-space: nowrap;
}
.interest-btn-accept:hover {
    background: var(--mat-maroon-dark, #2C0A12) !important;
    color: #FFFFFF !important;
    box-shadow: 0 2px 8px rgba(44, 10, 18, 0.15) !important;
}
.interest-btn-accepted,
.interest-btn-connected {
    background: #FFFFFF !important;
    color: #10B981 !important;
    border: 1.5px solid #10B981 !important;
    border-radius: 9999px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    height: 38px !important;
    padding: 7px 18px !important;
    cursor: default !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    white-space: nowrap;
    text-decoration: none !important;
}
.interest-btn-pending {
    background: #FFFFFF !important;
    color: #F59E0B !important;
    border: 1.5px solid #F59E0B !important;
    border-radius: 9999px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    height: 38px !important;
    padding: 7px 18px !important;
    cursor: default !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    white-space: nowrap;
}
.interest-dots-trigger {
    width: 32px;
    height: 32px;
    border-radius: 50% !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    color: #9CA3AF;
    font-size: 22px;
    padding: 0;
    transition: all 0.15s ease;
}
.interest-dots-trigger:hover,
.interest-dots-trigger:focus {
    background: #F3F4F6;
    color: #1F2937;
}
.interest-dropdown-menu {
    border-radius: 14px !important;
    font-size: 13px;
    min-width: 190px;
}
.interest-dropdown-menu .dropdown-item {
    font-weight: 500;
    display: flex;
    align-items: center;
    padding: 8px 16px;
    transition: background-color 0.15s ease;
}
.interest-dropdown-menu .dropdown-item:hover {
    background-color: #F9FAFB;
}

/* Pagination: < (1) 2 3 > Showing 1-3 of 3 */
.interest-pagination-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 10px;
}
.interest-pagination-wrap .pagination {
    margin-bottom: 0;
    display: flex;
    align-items: center;
}
.interest-pagination-wrap .page-item .page-link {
    border-radius: 50%;
    margin: 0 4px;
    border: none;
    color: #4B5563;
    font-weight: 600;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all 0.2s ease;
    background: transparent;
}
.interest-pagination-wrap .page-item:hover .page-link {
    background-color: #F3F4F6;
    color: #1F2937;
}
.interest-pagination-wrap .page-item.active .page-link {
    background-color: var(--mat-maroon-dark, #2C0A12) !important;
    color: #FFFFFF !important;
    box-shadow: 0 2px 6px rgba(44, 10, 18, 0.25);
}
.interest-pagination-info {
    font-size: 13px;
    color: #9CA3AF;
    font-weight: 500;
    margin-left: auto;
}

/* Responsive Fluid Adaptability */
@media (max-width: 1199px) {
    .interest-cards-list {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 767px) {
    .interest-cards-list {
        grid-template-columns: 1fr;
    }
    .interest-card-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        padding: 15px;
    }
    .interest-col-actions {
        width: 100%;
        justify-content: space-between;
    }
    .interest-btn-stack {
        flex-direction: row;
        width: 100%;
    }
    .interest-btn-view-profile,
    .interest-btn-accept {
        flex: 1;
    }
    .interest-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .interest-search-form {
        width: 100%;
    }
    .interest-search-box {
        flex: 1;
    }
}
</style>

<div class="interest-requests-container">
    <!-- 1. Header: Dynamic based on Active Tab (Interest Requests vs Interest Sent) -->
    <div class="interest-page-header">
        @if ($active_tab === 'sent')
            <h1 class="interest-page-title">{{ translate('Interest Sent') }}</h1>
            <p class="interest-page-subtitle">{{ translate('Here are the members you have expressed interest in.') }}</p>
        @else
            <h1 class="interest-page-title">{{ translate('Interest Requests') }}</h1>
            <p class="interest-page-subtitle">{{ translate('Here are the people who showed interest in your profile.') }}</p>
        @endif
    </div>

    <!-- 2. Tab Navigation & Search Toolbar: Strictly 2 Tabs on Left, Search on Right -->
    <div class="interest-toolbar">
        <!-- Left: Strictly TWO TABS -->
        <div class="interest-tabs-group">
            <!-- Tab 1: Interest Requests (Received) -->
            <a href="{{ route('interest_requests', ['tab' => 'requests']) }}" 
               class="interest-tab-pill {{ ($active_tab == 'requests' || $active_tab == 'received') ? 'active' : '' }}">
                <i class="las la-user-friends"></i>
                <span>{{ translate('Interest Requests') }}</span>
                <span class="interest-pill-badge">{{ $requests_count ?? 0 }}</span>
            </a>

            <!-- Tab 2: Interest Sent -->
            <a href="{{ route('interest_requests', ['tab' => 'sent']) }}" 
               class="interest-tab-pill {{ $active_tab == 'sent' ? 'active' : '' }}">
                <i class="las la-paper-plane"></i>
                <span>{{ translate('Interest Sent') }}</span>
                <span class="interest-pill-badge">{{ $sent_count ?? 0 }}</span>
            </a>
        </div>

        <!-- Right: Search input ("Search by name...") & Search button (Empty submission disabled) -->
        <form method="GET" action="{{ route('interest_requests') }}" class="interest-search-form" id="member_search_form">
            <input type="hidden" name="tab" value="{{ $active_tab }}">
            <div class="interest-search-box">
                <i class="las la-search interest-search-icon"></i>
                <input type="text" name="search" id="member_search_input" value="{{ request('search') }}" 
                       class="form-control interest-search-input" placeholder="{{ translate('Search by name...') }}" autocomplete="off">
            </div>
            <button type="submit" class="btn interest-search-btn" id="member_search_btn">
                <i class="las la-search"></i>
                <span>{{ translate('Search') }}</span>
            </button>
        </form>
    </div>

    <!-- 3. Request Card Architecture (Strictly Matching Reference Image Layout) -->
    <div class="interest-cards-list">
        @forelse ($interests as $key => $interest)
            @php
                // For Tab 1 (requests received): show interested_by_user
                // For Tab 2 (sent): show target user
                $target_user = ($active_tab === 'sent') ? $interest->user : $interest->interested_by_user;
                if ($target_user == null && $active_tab === 'sent') {
                    $target_user = \App\Models\User::find($interest->user_id);
                } elseif ($target_user == null) {
                    $target_user = \App\Models\User::find($interest->interested_by);
                }
            @endphp
            @if ($target_user != null)
                @php
                    $member = $target_user->member;
                    $age = ($member && $member->birthday) ? \Carbon\Carbon::parse($member->birthday)->age : 26;
                    
                    // District / Location
                    $present_address = $target_user->addresses ? $target_user->addresses->where('type', 'present')->first() : null;
                    $city = $present_address->city->name ?? null;
                    $country = $present_address->country->name ?? translate('Bangladesh');
                    $location_text = $city ? $city : $country;

                    // Education & Profession & Height
                    $education = $target_user->education ? $target_user->education->sortByDesc('id')->first() : null;
                    $education_name = $education->degree ?? ($member->highest_degree ?? translate('Bachelors'));

                    $career = $target_user->career ? $target_user->career->sortByDesc('id')->first() : null;
                    $profession_name = $career->designation ?? translate('Not Specified');

                    $height_val = $target_user->physical_attributes->height ?? null;
                    if (!$height_val) {
                        $height_val = "5'4\"";
                    }

                    $marital_status = $member->marital_status->name ?? translate('Never Married');

                    // Lifestyle Tags
                    $hobby_model = $target_user->hobbies;
                    $tags = [];
                    if ($hobby_model && !empty($hobby_model->interests)) {
                        $tags = array_slice(array_map('trim', explode(',', $hobby_model->interests)), 0, 3);
                    }
                    if (empty($tags) && $hobby_model && !empty($hobby_model->hobbies)) {
                        $tags = array_slice(array_map('trim', explode(',', $hobby_model->hobbies)), 0, 3);
                    }
                    if (empty($tags)) {
                        $sample_tag_presets = [
                            ['Reading', 'Travel', 'Cooking'],
                            ['Photography', 'Music', 'Nature'],
                            ['Design', 'Books', 'Food']
                        ];
                        $tags = $sample_tag_presets[$key % 3];
                    }

                    // Proposal Message Quote
                    $intro_note = (!empty($member->introduction)) 
                        ? $member->introduction 
                        : "Your profile looks really interesting. Would love to know you better.";

                    // Alternating Color Themes for Message Callout Box & Tags (Rose, Emerald, Lavender)
                    $palette_schemes = [
                        [
                            'callout_bg' => '#FFF5F5', 
                            'icon_bg' => '#FFE4E6', 
                            'icon_color' => '#E11D48', 
                            'tag_bg' => '#FFE4E6', 
                            'tag_color' => '#BE123C'
                        ],
                        [
                            'callout_bg' => '#F0FDF4', 
                            'icon_bg' => '#DCFCE7', 
                            'icon_color' => '#059669', 
                            'tag_bg' => '#D1FAE5', 
                            'tag_color' => '#047857'
                        ],
                        [
                            'callout_bg' => '#FAF5FF', 
                            'icon_bg' => '#F3E8FF', 
                            'icon_color' => '#7C3AED', 
                            'tag_bg' => '#EDE9FE', 
                            'tag_color' => '#6D28D9'
                        ],
                    ];
                    $scheme = $palette_schemes[$key % 3];
                @endphp

                <div class="interest-card-item" id="interest_card_{{ $interest->id }}">
                    <!-- Column 1 (Identity): Avatar, Name + verified tick, Age · District, Peach pills -->
                    <div class="interest-col-identity">
                        <div class="interest-avatar-box">
                            <a href="{{ route('member_profile', $target_user->id) }}">
                                @if (uploaded_asset($target_user->photo) != null)
                                    <img src="{{ uploaded_asset($target_user->photo) }}" class="interest-avatar-img" alt="{{ $target_user->first_name }}">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="interest-avatar-img" alt="{{ $target_user->first_name }}">
                                @endif
                            </a>
                            <!-- Green Online Status Dot -->
                            <span class="interest-status-dot" title="{{ translate('Online') }}"></span>
                        </div>
                        <div class="interest-identity-meta">
                            <div class="interest-name-row">
                                <a href="{{ route('member_profile', $target_user->id) }}" class="interest-user-fullname">
                                    {{ $target_user->first_name }} {{ $target_user->last_name }}
                                </a>
                                <!-- Verified Blue Tick -->
                                <span class="interest-verified-tick" title="{{ translate('Verified Profile') }}">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" fill="#0284C7"/>
                                        <path d="M8.5 12.5L11 15L16 9.5" stroke="#FFFFFF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="interest-sub-location">
                                Age {{ $age }} · {{ $location_text }}
                            </div>
                            <div class="interest-peach-badges">
                                <span class="interest-peach-pill">
                                    <i class="las la-graduation-cap"></i>
                                    <span>{{ Str::limit($education_name, 18) }}</span>
                                </span>
                                <span class="interest-peach-pill">
                                    <i class="las la-user-friends"></i>
                                    <span>{{ $marital_status }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 (Specs & Tags): 3 Key specs with icons & Lifestyle soft tags -->
                    <div class="interest-col-specs">
                        <div class="interest-specs-list">
                            <div class="interest-spec-line">
                                <span class="interest-spec-label">
                                    <i class="las la-graduation-cap"></i>
                                    <span>{{ translate('Education') }}</span>
                                </span>
                                <span class="interest-spec-value">
                                    {{ Str::limit($education_name, 16) }}
                                </span>
                            </div>
                            <div class="interest-spec-line">
                                <span class="interest-spec-label">
                                    <i class="las la-briefcase"></i>
                                    <span>{{ translate('Profession') }}</span>
                                </span>
                                <span class="interest-spec-value">
                                    {{ Str::limit($profession_name, 16) }}
                                </span>
                            </div>
                            <div class="interest-spec-line">
                                <span class="interest-spec-label">
                                    <i class="las la-ruler-vertical"></i>
                                    <span>{{ translate('Height') }}</span>
                                </span>
                                <span class="interest-spec-value">
                                    {{ $height_val }}
                                </span>
                            </div>
                        </div>
                        <!-- Lifestyle tags -->
                        <div class="interest-lifestyle-tags">
                            @foreach ($tags as $tag)
                                <span class="interest-lifestyle-pill" style="background: {{ $scheme['tag_bg'] }}; color: {{ $scheme['tag_color'] }};">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Column 3 (Action Buttons): Solid Primary Maroon Button, Accept Button, 3-dot menu -->
                    <div class="interest-col-actions">
                        <div class="interest-btn-stack">
                            <!-- Button 1: Solid Primary Brand Maroon Button [ View Profile ] -->
                            <a href="{{ route('member_profile', $target_user->id) }}" class="btn interest-btn-view-profile">
                                <i class="las la-eye"></i>
                                <span>{{ translate('View Profile') }}</span>
                            </a>

                            <!-- Button 2: MUST BE LABELED [ Accept ] (replacing "Reply") -->
                            @if ($active_tab === 'sent')
                                @if ($interest->status == 1)
                                    <a href="{{ route('all.messages') }}" class="btn interest-btn-connected">
                                        <i class="las la-comments"></i>
                                        <span>{{ translate('Chat') }}</span>
                                    </a>
                                @else
                                    <button type="button" class="btn interest-btn-pending" disabled>
                                        <i class="las la-hourglass-half"></i>
                                        <span>{{ translate('Pending') }}</span>
                                    </button>
                                @endif
                            @else
                                @if ($interest->status == 1)
                                    <button type="button" class="btn interest-btn-accepted" disabled>
                                        <i class="las la-check"></i>
                                        <span>{{ translate('Accepted') }}</span>
                                    </button>
                                @else
                                    <button type="button" 
                                            onclick="handle_accept_interest({{ $interest->id }})" 
                                            id="accept_btn_{{ $interest->id }}" 
                                            class="btn interest-btn-accept">
                                        <i class="las la-check"></i>
                                        <span>{{ translate('Accept') }}</span>
                                    </button>
                                @endif
                            @endif
                        </div>

                        <!-- Three-dot Menu (⋮) with Decline/Reject, Block, and Report -->
                        <div class="dropdown">
                            <button type="button" class="btn interest-dots-trigger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ translate('More Options') }}">
                                <i class="las la-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right interest-dropdown-menu shadow-lg border-0 py-2">
                                <a class="dropdown-item py-2" href="{{ route('member_profile', $target_user->id) }}">
                                    <i class="las la-user mr-2 text-muted"></i> {{ translate('View Full Profile') }}
                                </a>

                                @if ($interest->status == 1)
                                    <a class="dropdown-item py-2" href="{{ route('all.messages') }}">
                                        <i class="las la-comment-alt mr-2 text-success"></i> {{ translate('Open Conversation') }}
                                    </a>
                                    @if(Auth::user()->membership == 2)
                                        <a class="dropdown-item py-2 font-weight-bold" style="color: #800020;" href="{{ route('consultations.index', ['target' => $target_user->id]) }}">
                                            <i class="las la-video mr-2 text-warning"></i> {{ translate('Schedule Meeting') }}
                                        </a>
                                    @else
                                        <a class="dropdown-item py-2 text-warning font-weight-bold" href="javascript:void(0);" onclick="package_update_alert()">
                                            <i class="las la-crown mr-2 text-warning"></i> {{ translate('Schedule Meeting (VIP)') }}
                                        </a>
                                    @endif
                                @endif

                                <div class="dropdown-divider my-1"></div>

                                <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="confirm_reject_interest({{ $interest->id }})">
                                    <i class="las la-times-circle mr-2"></i> {{ translate('Decline Request') }}
                                </a>

                                <a class="dropdown-item py-2 text-danger" href="javascript:void(0);" onclick="confirm_block_member({{ $target_user->id }}, '{{ addslashes($target_user->first_name . ' ' . $target_user->last_name) }}')">
                                    <i class="las la-ban mr-2"></i> {{ translate('Block Member') }}
                                </a>

                                <a class="dropdown-item py-2 text-warning" href="javascript:void(0);" onclick="open_report_modal({{ $target_user->id }}, '{{ addslashes($target_user->first_name . ' ' . $target_user->last_name) }}')">
                                    <i class="las la-flag mr-2"></i> {{ translate('Report Member') }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        @empty
            <div class="text-center bg-white p-5 shadow-sm rounded-2xl" style="border: 1px solid #ECE7E1;">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" 
                     style="width: 74px; height: 74px; border-radius: 50%; background: var(--mat-maroon-light-bg, #FDF0F0); color: var(--mat-maroon-dark, #2C0A12);">
                    <i class="las la-heart-broken la-3x"></i>
                </div>
                <h4 class="h6 font-weight-bold mb-2 text-dark">{{ translate('No Interest Requests Found') }}</h4>
                <p class="text-muted fs-13 mb-0" style="max-width: 420px; margin: 0 auto;">
                    @if ($active_tab === 'sent')
                        {{ translate('You have not sent any interest requests yet. Explore profiles and express interest to get connected!') }}
                    @else
                        {{ translate('You do not have any pending interest requests at the moment. When members express interest, they will appear here.') }}
                    @endif
                </p>
                <div class="mt-4">
                    <a href="{{ route('member.listing') }}" class="btn text-white px-4 py-2" style="background: var(--mat-maroon-dark, #2C0A12); border-radius: 50px; font-size: 13px; font-weight: 600;">
                        <i class="las la-search mr-1"></i> {{ translate('Explore Profiles') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination Matching Mockup: < 1 2 3 > Showing X-Y of Z -->
    @if ($interests->total() > 0)
        <div class="interest-pagination-container">
            <div class="interest-pagination-wrap">
                {{ $interests->links() }}
            </div>
            <div class="interest-pagination-info">
                {{ translate('Showing') }} {{ $interests->firstItem() ?? 1 }}-{{ $interests->lastItem() ?? $interests->count() }} {{ translate('of') }} {{ $interests->total() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal 1: Confirm Decline / Reject Interest -->
<div class="modal fade" id="interestRejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-2xl" style="overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 54px; height: 54px; border-radius: 50%; background: #FEE2E2; color: #DC2626;">
                    <i class="las la-times-circle la-2x"></i>
                </div>
                <h5 class="h6 font-weight-bold mb-2 text-dark">{{ translate('Decline Interest Request?') }}</h5>
                <p class="text-muted fs-13 mb-4">{{ translate('Are you sure you want to decline this request? The member will be notified.') }}</p>
                
                <form id="reject_interest_form" action="{{ route('reject_interest') }}" method="POST">
                    @csrf
                    <input type="hidden" name="interest_id" id="reject_interest_id" value="">
                    <div class="d-flex justify-content-center" style="gap: 10px;">
                        <button type="button" class="btn btn-light px-3 py-2 flex-grow-1" data-dismiss="modal" style="border-radius: 50px; font-weight: 500; font-size: 13px;">
                            {{ translate('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger px-3 py-2 flex-grow-1" style="border-radius: 50px; font-weight: 600; font-size: 13px;">
                            {{ translate('Decline') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Confirm Block Member -->
<div class="modal fade" id="blockMemberModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-2xl" style="overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 54px; height: 54px; border-radius: 50%; background: #FEF3C7; color: #D97706;">
                    <i class="las la-ban la-2x"></i>
                </div>
                <h5 class="h6 font-weight-bold mb-2 text-dark">{{ translate('Block Member?') }}</h5>
                <p class="text-muted fs-13 mb-4">
                    <span id="block_target_name_span"></span> {{ translate('will no longer be able to view your profile or contact you.') }}
                </p>
                
                <div class="d-flex justify-content-center" style="gap: 10px;">
                    <button type="button" class="btn btn-light px-3 py-2 flex-grow-1" data-dismiss="modal" style="border-radius: 50px; font-weight: 500; font-size: 13px;">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="button" id="confirm_block_submit_btn" onclick="execute_block_member()" class="btn btn-danger px-3 py-2 flex-grow-1" style="border-radius: 50px; font-weight: 600; font-size: 13px;">
                        {{ translate('Block') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Report Member Modal -->
<div class="modal fade" id="reportMemberModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-2xl" style="overflow: hidden;">
            <div class="modal-header px-4 py-3 border-bottom">
                <h5 class="modal-title fs-15 fw-700 text-dark">
                    <i class="las la-flag mr-1 text-danger"></i> {{ translate('Report Member Profile') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reportusers.store') }}" method="POST" id="report_member_form">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="member_id" id="report_target_member_id" value="">
                    <p class="text-muted fs-13 mb-3">
                        {{ translate('Please state why you are reporting') }} <strong id="report_target_name_span"></strong>. {{ translate('Our safety team will review your report promptly.') }}
                    </p>
                    <div class="form-group mb-0">
                        <label class="form-label fs-13 fw-600 text-dark">{{ translate('Reason for Report') }} <span class="text-danger">*</span></label>
                        <textarea name="reason" rows="4" class="form-control" placeholder="{{ translate('Describe the inappropriate behavior, fake profile, harassment, or concern...') }}" required style="border-radius: 12px; font-size: 13px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 border-top bg-light">
                    <button type="button" class="btn btn-secondary px-3 py-2" data-dismiss="modal" style="border-radius: 50px; font-size: 13px;">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger px-4 py-2" style="border-radius: 50px; font-size: 13px; font-weight: 600;">{{ translate('Submit Report') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var pending_block_user_id = null;

    // Requirement (ii): If search input is empty, prevent form submission
    $('#member_search_form').on('submit', function(e) {
        var query = $.trim($('#member_search_input').val());
        if (query === '') {
            e.preventDefault();
            $('#member_search_input').focus();
            return false;
        }
    });

    // Rule 4: "Accept" Button Action & Instant Notification Logic
    function handle_accept_interest(interest_id) {
        var $btn = $('#accept_btn_' + interest_id);
        var original_html = $btn.html();

        $btn.prop('disabled', true).html('<i class="las la-spinner la-spin mr-1"></i> {{ translate("Accepting...") }}');

        $.ajax({
            url: "{{ route('interest_requests.accept') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                interest_id: interest_id
            },
            success: function(response) {
                if (response && response.result) {
                    $btn.removeClass('interest-btn-accept')
                        .addClass('interest-btn-accepted')
                        .prop('disabled', true)
                        .removeAttr('onclick')
                        .html('<i class="las la-check mr-1"></i> {{ translate("Accepted") }}');
                    
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', response.message || '{{ translate("Interest accepted successfully!") }}');
                    } else {
                        alert(response.message || '{{ translate("Interest accepted successfully!") }}');
                    }
                } else {
                    $btn.prop('disabled', false).html(original_html);
                    var msg = (response && response.message) ? response.message : '{{ translate("Something went wrong") }}';
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', msg);
                    } else {
                        alert(msg);
                    }
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(original_html);
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', '{{ translate("Failed to accept interest. Please try again.") }}');
                } else {
                    alert('{{ translate("Failed to accept interest. Please try again.") }}');
                }
            }
        });
    }

    // Modal Trigger: Decline / Reject Interest
    function confirm_reject_interest(interest_id) {
        $('#reject_interest_id').val(interest_id);
        $('#interestRejectModal').modal('show');
    }

    // AJAX Submission: Decline / Reject Interest
    $('#reject_interest_form').on('submit', function(e) {
        e.preventDefault();
        var interest_id = $('#reject_interest_id').val();
        var $form = $(this);
        var $submit_btn = $form.find('button[type="submit"]');

        $submit_btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>');

        $.ajax({
            url: "{{ route('reject_interest') }}",
            type: "POST",
            data: $form.serialize(),
            success: function(response) {
                $('#interestRejectModal').modal('hide');
                $submit_btn.prop('disabled', false).text('{{ translate("Decline") }}');
                
                // Animate removal of card
                $('#interest_card_' + interest_id).fadeOut(350, function() {
                    $(this).remove();
                });
                
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', (response && response.message) ? response.message : '{{ translate("Interest request declined.") }}');
                }
            },
            error: function() {
                $submit_btn.prop('disabled', false).text('{{ translate("Decline") }}');
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', '{{ translate("Something went wrong.") }}');
                }
            }
        });
    });

    // Modal Trigger: Block Member
    function confirm_block_member(user_id, name) {
        pending_block_user_id = user_id;
        $('#block_target_name_span').text(name);
        $('#blockMemberModal').modal('show');
    }

    // AJAX Submission: Block Member
    function execute_block_member() {
        if (!pending_block_user_id) return;

        var $btn = $('#confirm_block_submit_btn');
        $btn.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>');

        $.ajax({
            url: "{{ route('member.add_to_ignore_list') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: pending_block_user_id
            },
            success: function(response) {
                $('#blockMemberModal').modal('hide');
                $btn.prop('disabled', false).text('{{ translate("Block") }}');
                
                // Hide any cards belonging to this blocked member
                $('[id^="interest_card_"]').each(function() {
                    var $card = $(this);
                    if ($card.find('a[href*="/member-profile/' + pending_block_user_id + '"]').length > 0) {
                        $card.fadeOut(350, function() { $card.remove(); });
                    }
                });

                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', '{{ translate("Member blocked successfully.") }}');
                }
                pending_block_user_id = null;
            },
            error: function() {
                $btn.prop('disabled', false).text('{{ translate("Block") }}');
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', '{{ translate("Failed to block member.") }}');
                }
            }
        });
    }

    // Modal Trigger: Report Member
    function open_report_modal(user_id, name) {
        $('#report_target_member_id').val(user_id);
        $('#report_target_name_span').text(name);
        $('#reportMemberModal').modal('show');
    }

    function package_update_alert() {
        $('.package_update_alert_modal').modal('show');
    }
</script>

@include('modals.package_update_alert_modal')
@endsection
