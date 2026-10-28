@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $auth_user = Auth::user();
    $member = $member ?? $auth_user;
    
    // Addresses
    $present_address = $member->addresses ? $member->addresses->where('type', 'present')->first() : null;
    $permanent_address = $member->addresses ? $member->addresses->where('type', 'permanent')->first() : null;
    
    $present_country_id = optional($present_address)->country_id ?? "";
    $present_state_id = optional($present_address)->state_id ?? "";
    $present_city_id = optional($present_address)->city_id ?? "";
    $present_postal_code = optional($present_address)->postal_code ?? "";
    $present_home_road = optional($present_address)->home_road ?? "";
    
    $permanent_country_id = optional($permanent_address)->country_id ?? "";
    $permanent_state_id = optional($permanent_address)->state_id ?? "";
    $permanent_city_id = optional($permanent_address)->city_id ?? "";
    $permanent_postal_code = optional($permanent_address)->postal_code ?? "";
    $permanent_home_road = optional($permanent_address)->home_road ?? "";
    
    // Education & Career
    $educations = $member->education ?? collect();
    $highest_education = $educations->where('is_highest_degree', 1)->first() ?? $educations->first();
    
    $careers = $member->career ?? collect();
    $current_career = $careers->where('present', 1)->first() ?? $careers->first();
    
    // Known languages
    $raw_known_lang = optional($member->member)->known_languages;
    $known_lang_ids = !empty($raw_known_lang) ? json_decode($raw_known_lang, true) : [];
    if (is_array($known_lang_ids) && !empty($known_lang_ids)) {
        $known_languages_names = \App\Models\MemberLanguage::whereIn('id', (array)$known_lang_ids)->pluck('name')->toArray();
        $known_languages_display = implode(', ', $known_languages_names);
    } else {
        $known_languages_display = !empty($raw_known_lang) ? $raw_known_lang : 'English';
    }
    
    // Spiritual & Social
    $member_religion_id = optional($member->spiritual_backgrounds)->religion_id ?? "";
    $member_caste_id = optional($member->spiritual_backgrounds)->caste_id ?? "";
    $member_sub_caste_id = optional($member->spiritual_backgrounds)->sub_caste_id ?? "";
    
    // Partner Expectations Helper Lookups
    $pe = $member->partner_expectations;
    $pe_residence_country_name = (!empty($pe) && !empty($pe->residence_country_id)) ? optional($countries->where('id', $pe->residence_country_id)->first())->name : '';
    $pe_preferred_country_name = (!empty($pe) && !empty($pe->preferred_country_id)) ? optional($countries->where('id', $pe->preferred_country_id)->first())->name : '';
    $pe_preferred_state_name = (!empty($pe) && !empty($pe->preferred_state_id)) ? optional(\App\Models\State::find($pe->preferred_state_id))->name : '';
    // Refined Profile completeness calculation across 13 sections
    // 1. Basic Info: Name, Gender, Birthday, and Contact
    $is_c1_completed = !empty($member->first_name) && (!empty($member->phone) || !empty($member->email)) && !empty(optional($member->member)->gender) && !empty(optional($member->member)->birthday);

    // 2. Personal Details: Height and at least one physical attribute (weight, complexion, blood group)
    $is_c2_completed = !empty(optional($member->physical_attributes)->height) && (!empty(optional($member->physical_attributes)->weight) || !empty(optional($member->physical_attributes)->complexion) || !empty(optional($member->physical_attributes)->blood_group));

    // 3. Religion: Religion ID and Sect / Caste
    $is_c3_completed = !empty(optional($member->spiritual_backgrounds)->religion_id) && !empty(optional($member->spiritual_backgrounds)->caste_id);

    // 4. Career: Has career with profession status, job type, company, or designation
    $curr_c = $careers->first();
    $is_c4_completed = $careers->count() > 0 && (!empty(optional($curr_c)->profession_status) || !empty(optional($curr_c)->designation) || !empty(optional($curr_c)->company) || !empty(optional($curr_c)->occupation));

    // 5. Education: Has education records with degree
    $is_c5_completed = $educations->count() > 0 && !empty(optional($highest_education)->degree);

    // 6. Present Address: City/District and Home/Road or Postal Code
    $is_c6_completed = !empty($present_address) && !empty($present_address->city_id) && (!empty($present_address->home_road) || !empty($present_address->postal_code));

    // 7. Permanent Address: City/District and Home/Road or Postal Code
    $is_c7_completed = !empty($permanent_address) && !empty($permanent_address->city_id) && (!empty($permanent_address->home_road) || !empty($permanent_address->postal_code));

    // 8. Family: Father and Mother details
    $is_c8_completed = !empty(optional($member->families)->father) && !empty(optional($member->families)->mother);

    // 9. Residency: Country of residence and immigration or permanent country
    $is_c9_completed = !empty(optional($member->recidency)->recidency_country_id) && (!empty(optional($member->recidency)->birth_country_id) || !empty(optional($member->recidency)->immigration_status));

    // 10. Lifestyle: Diet and Living arrangements
    $is_c10_completed = !empty(optional($member->lifestyles)->diet) && !empty(optional($member->lifestyles)->living_with);

    // 11. Language: Mother tongue and known languages
    $is_c11_completed = !empty(optional($member->member)->mothere_tongue) && !empty(optional($member->member)->known_languages);

    // 12. Hobbies: Hobbies or Interests
    $is_c12_completed = !empty(optional($member->hobbies)->hobbies) || !empty(optional($member->hobbies)->interests);

    // 13. Additional Information: Introduction and Partner Expectations
    $is_c13_completed = !empty(optional($member->member)->introduction) && !empty(optional($member->partner_expectations)->general);

    $profile_sections_meta = [
        [
            'index' => 1,
            'id' => 'basic_info',
            'card_id' => 'card-1',
            'modal_id' => '#modal-edit-basic-info',
            'name' => translate('Basic Information'),
            'icon' => 'las la-user',
            'is_completed' => (bool)$is_c1_completed,
            'missing_hint' => translate('Full name, date of birth, gender, or phone/email missing.'),
        ],
        [
            'index' => 2,
            'id' => 'personal_details',
            'card_id' => 'card-2',
            'modal_id' => '#modal-edit-personal-details',
            'name' => translate('Personal Details'),
            'icon' => 'las la-id-card',
            'is_completed' => (bool)$is_c2_completed,
            'missing_hint' => translate('Height, weight, complexion or blood group missing.'),
        ],
        [
            'index' => 3,
            'id' => 'religion',
            'card_id' => 'card-12',
            'modal_id' => '#modal-edit-spiritual',
            'name' => translate('Religion'),
            'icon' => 'las la-place-of-worship',
            'is_completed' => (bool)$is_c3_completed,
            'missing_hint' => translate('Religion or Sect / Mazhab / Caste not selected.'),
        ],
        [
            'index' => 4,
            'id' => 'career',
            'card_id' => 'card-5',
            'modal_id' => '#modal-edit-career',
            'name' => translate('Career'),
            'icon' => 'las la-briefcase',
            'is_completed' => (bool)$is_c4_completed,
            'missing_hint' => translate('Profession status, job sector or occupation missing.'),
        ],
        [
            'index' => 5,
            'id' => 'education',
            'card_id' => 'card-4',
            'modal_id' => '#modal-add-education',
            'name' => translate('Education'),
            'icon' => 'las la-graduation-cap',
            'is_completed' => (bool)$is_c5_completed,
            'missing_hint' => translate('Degree, institute or passing year details missing.'),
        ],
        [
            'index' => 6,
            'id' => 'present_address',
            'card_id' => 'card-3',
            'modal_id' => '#modal-edit-contact-details',
            'name' => translate('Present Address'),
            'icon' => 'las la-map-marker',
            'is_completed' => (bool)$is_c6_completed,
            'missing_hint' => translate('City, district or home/road address missing.'),
        ],
        [
            'index' => 7,
            'id' => 'permanent_address',
            'card_id' => 'card-13',
            'modal_id' => '#modal-edit-permanent-address',
            'name' => translate('Permanent Address'),
            'icon' => 'las la-map-marker-alt',
            'is_completed' => (bool)$is_c7_completed,
            'missing_hint' => translate('City, district or home/road address missing.'),
        ],
        [
            'index' => 8,
            'id' => 'family',
            'card_id' => 'card-9',
            'modal_id' => '#modal-edit-family',
            'name' => translate('Family Information'),
            'icon' => 'las la-users',
            'is_completed' => (bool)$is_c8_completed,
            'missing_hint' => translate('Father’s or Mother’s information missing.'),
        ],
        [
            'index' => 9,
            'id' => 'residency',
            'card_id' => 'card-11',
            'modal_id' => '#modal-edit-residency',
            'name' => translate('Residency Information'),
            'icon' => 'las la-home',
            'is_completed' => (bool)$is_c9_completed,
            'missing_hint' => translate('Country of residence or immigration details missing.'),
        ],
        [
            'index' => 10,
            'id' => 'lifestyle',
            'card_id' => 'card-10',
            'modal_id' => '#modal-edit-lifestyle',
            'name' => translate('Lifestyle & Preferences'),
            'icon' => 'las la-cocktail',
            'is_completed' => (bool)$is_c10_completed,
            'missing_hint' => translate('Diet preference or living arrangements missing.'),
        ],
        [
            'index' => 11,
            'id' => 'language',
            'card_id' => 'card-7',
            'modal_id' => '#modal-edit-language',
            'name' => translate('Language'),
            'icon' => 'las la-language',
            'is_completed' => (bool)$is_c11_completed,
            'missing_hint' => translate('Mother tongue or known languages missing.'),
        ],
        [
            'index' => 12,
            'id' => 'hobbies',
            'card_id' => 'card-8',
            'modal_id' => '#modal-edit-hobbies',
            'name' => translate('Hobbies & Interests'),
            'icon' => 'las la-heart',
            'is_completed' => (bool)$is_c12_completed,
            'missing_hint' => translate('Hobbies or personal interests not added yet.'),
        ],
        [
            'index' => 13,
            'id' => 'additional',
            'card_id' => 'card-14',
            'modal_id' => '#modal-edit-additional-info',
            'name' => translate('Additional Information'),
            'icon' => 'las la-file-alt',
            'is_completed' => (bool)$is_c13_completed,
            'missing_hint' => translate('Self-introduction or partner expectations missing.'),
        ],
    ];

    $total_sections = count($profile_sections_meta);
    $completed_sections = count(array_filter($profile_sections_meta, fn($item) => $item['is_completed']));
    $incomplete_sections = $total_sections - $completed_sections;
    $profile_completion_percentage = (int)round(($completed_sections / $total_sections) * 100);

    // Partner Expectations Completeness Calculation
    $is_pe_basic_completed = !empty(optional($pe)->height) || !empty(optional($pe)->weight) || !empty(optional($pe)->marital_status_id) || !empty(optional($pe)->complexion) || !empty(optional($pe)->body_type);
    $is_pe_religion_completed = !empty(optional($pe)->religion_id) || !empty(optional($pe)->language_id) || !empty(optional($pe)->family_value_id);
    $is_pe_education_completed = !empty(optional($pe)->education) || !empty(optional($pe)->profession);
    $is_pe_location_completed = !empty(optional($pe)->residence_country_id) || !empty(optional($pe)->preferred_country_id);
    $is_pe_lifestyle_completed = !empty(optional($pe)->diet) || !empty(optional($pe)->smoking_acceptable) || !empty(optional($pe)->drinking_acceptable);
    $is_pe_general_completed = !empty(optional($pe)->general);

    $partner_pref_sections_meta = [
        [
            'index' => 1,
            'id' => 'pref_basic',
            'card_id' => 'card-pref-basic',
            'modal_id' => '#modal-edit-pref-basic',
            'tab' => 'basic',
            'name' => translate('Basic & Physical Requirements'),
            'icon' => 'las la-heart',
            'is_completed' => (bool)$is_pe_basic_completed,
            'missing_hint' => translate('Preferred height, weight, marital status, or complexion not specified.'),
        ],
        [
            'index' => 2,
            'id' => 'pref_religion',
            'card_id' => 'card-pref-religion',
            'modal_id' => '#modal-edit-pref-religion',
            'tab' => 'religion',
            'name' => translate('Religion & Community Preferences'),
            'icon' => 'las la-place-of-worship',
            'is_completed' => (bool)$is_pe_religion_completed,
            'missing_hint' => translate('Preferred religion, sect / caste, language, or family values not selected.'),
        ],
        [
            'index' => 3,
            'id' => 'pref_education',
            'card_id' => 'card-pref-education',
            'modal_id' => '#modal-edit-pref-education',
            'tab' => 'education',
            'name' => translate('Education & Profession Preferences'),
            'icon' => 'las la-graduation-cap',
            'is_completed' => (bool)$is_pe_education_completed,
            'missing_hint' => translate('Minimum education qualification or preferred profession missing.'),
        ],
        [
            'index' => 4,
            'id' => 'pref_location',
            'card_id' => 'card-pref-location',
            'modal_id' => '#modal-edit-pref-location',
            'tab' => 'location',
            'name' => translate('Location Preferences'),
            'icon' => 'las la-map-marker-alt',
            'is_completed' => (bool)$is_pe_location_completed,
            'missing_hint' => translate('Preferred residence country or preferred location not chosen.'),
        ],
        [
            'index' => 5,
            'id' => 'pref_lifestyle',
            'card_id' => 'card-pref-lifestyle',
            'modal_id' => '#modal-edit-pref-lifestyle',
            'tab' => 'lifestyle',
            'name' => translate('Lifestyle & Habits'),
            'icon' => 'las la-glass-martini-alt',
            'is_completed' => (bool)$is_pe_lifestyle_completed,
            'missing_hint' => translate('Diet, smoking, or drinking preferences not selected.'),
        ],
        [
            'index' => 6,
            'id' => 'pref_general',
            'card_id' => 'card-pref-general',
            'modal_id' => '#modal-edit-pref-general',
            'tab' => 'general',
            'name' => translate('Expectations Note'),
            'icon' => 'las la-comment-dots',
            'is_completed' => (bool)$is_pe_general_completed,
            'missing_hint' => translate('Brief note about your desired life partner is not written yet.'),
        ],
    ];

    $total_pref_sections = count($partner_pref_sections_meta);
    $completed_pref_sections = count(array_filter($partner_pref_sections_meta, fn($item) => $item['is_completed']));
    $incomplete_pref_sections = $total_pref_sections - $completed_pref_sections;
    $partner_pref_completion_percentage = (int)round(($completed_pref_sections / $total_pref_sections) * 100);

    // Marital Status & Children check
    $ms_name = strtolower(optional(optional($member->member)->marital_status)->name ?? (string)optional($member->member)->marital_status);
    $ms_id = optional($member->member)->marital_status_id;
    $is_never_married = empty($ms_name) || str_contains($ms_name, 'never married') || $ms_id == 1;
    $salary_val = optional($member->member)->annual_salary_range_id;
@endphp

<style>
    .profile-completeness-pill {
        cursor: pointer !important;
        user-select: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(16, 185, 129, 0.35);
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        padding: 8px 18px;
        border-radius: 9999px;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.08);
    }
    .profile-completeness-pill:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.22);
        border-color: #10b981;
        background-color: #f0fdf4;
    }
    .profile-completeness-pill:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }
    .checklist-item-card {
        transition: all 0.2s ease;
    }
    .checklist-item-card:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        background-color: #ffffff !important;
    }
    .checklist-filter-group .btn {
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .checklist-filter-group .btn.active {
        background-color: #059669 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
    }
    .checklist-filter-group .btn.active i {
        color: #ffffff !important;
    }
    .checklist-item-card.is-hidden {
        display: none !important;
    }
    .circular-chart {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        max-height: 100%;
    }
    .circle-bg {
        fill: none;
        stroke: #e2e8f0;
        stroke-width: 3.2;
    }
    .circle {
        fill: none;
        stroke-width: 3.2;
        stroke-linecap: round;
        transition: stroke-dasharray 0.6s ease;
    }
    .profile-nav-tabs-pill-wrap {
        display: inline-flex;
        align-items: center;
        background: #fdfbf9;
        border: 1px solid #ebdcd5;
        padding: 5px;
        border-radius: 9999px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }
    .profile-nav-pill-btn {
        border: none;
        outline: none !important;
        background: transparent;
        color: #4a1928;
        font-family: inherit;
        font-size: 14.5px;
        font-weight: 600;
        padding: 8px 24px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
        transition: none; /* User rule: active color thakbe button e hover e color lagbe na */
    }
    .profile-nav-pill-btn:hover {
        /* User instruction: active color thakbe button e hover e color lagbe na */
        background: transparent;
        color: inherit;
    }
    .profile-nav-pill-btn.active {
        background: #2b111e !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(43, 17, 30, 0.28);
    }
    .profile-nav-pill-btn.active:hover {
        background: #2b111e !important;
        color: #ffffff !important;
    }
    .profile-nav-pill-btn i {
        font-size: 16px;
        color: inherit;
    }
    .partner-pref-tabs .nav-link {
        color: #64748b;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    .partner-pref-tabs .nav-link.active {
        background: #2b111e !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(43, 17, 30, 0.2);
    }
    .profile-data-col-grid.cols-3 {
        grid-template-columns: repeat(3, 1fr) !important;
    }
    .profile-data-col-grid.cols-5 {
        grid-template-columns: repeat(5, 1fr) !important;
    }
    @media (max-width: 991.98px) {
        .profile-data-col-grid.cols-5 {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    @media (max-width: 767.98px) {
        .profile-data-col-grid.cols-5,
        .profile-data-col-grid.cols-3 {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    @media (max-width: 575.98px) {
        .profile-data-col-grid.cols-5,
        .profile-data-col-grid.cols-3,
        .profile-data-col-grid.cols-2 {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<!-- Profile Page Header & Completeness Status Bar -->
<div class="profile-page-header-wrap">
    <div class="profile-header-left">
        <div class="profile-nav-tabs-pill-wrap">
            <button type="button" class="profile-nav-pill-btn active" id="btn-tab-my-profile" onclick="switchProfileTab('my-profile')">
                <i class="las la-user"></i>
                <span>{{ translate('My Profile') }}</span>
            </button>
            <button type="button" class="profile-nav-pill-btn ml-1" id="btn-tab-partner-preferences" onclick="switchProfileTab('partner-preferences')">
                <i class="lar la-heart"></i>
                <span>{{ translate('Partner Preferences') }}</span>
            </button>
        </div>
    </div>
    <div class="profile-header-right">
        <div class="profile-completeness-pill" role="button" tabindex="0" data-toggle="modal" data-target="#modal-profile-completion-checklist" title="{{ translate('Click to see profile completion checklist') }}" style="cursor: pointer;">
            <span class="profile-completeness-badge">
                <span class="profile-completeness-check-icon">
                    @if($profile_completion_percentage == 100)
                        &#10004;
                    @else
                        <i class="las la-tasks" style="font-size: 11px;"></i>
                    @endif
                </span>
                <span id="profile-status-bar-scope-label">{{ translate('Profile') }}</span> <span id="profile-percent-label">{{ $profile_completion_percentage }}%</span> {{ translate('Complete') }}
            </span>
            <div class="profile-completeness-progress-track">
                <svg class="profile-completeness-svg" width="90" height="7">
                    <rect width="100%" height="7" rx="3.5" class="profile-completeness-svg-track" />
                    <rect id="profile-completeness-fill" width="{{ $profile_completion_percentage }}%" height="7" rx="3.5" class="profile-completeness-svg-fill" style="transition: width 0.4s ease-in-out;" />
                </svg>
            </div>
            <span class="profile-completeness-click-hint" style="color: #059669; font-size: 13px; display: inline-flex; align-items: center; margin-left: 2px;">
                <i class="las la-chevron-right"></i>
            </span>
        </div>
    </div>
</div>

<div id="view-my-profile">

@php
    $profile_avatar_url = null;
    if (!empty($member->photo)) {
        $profile_avatar_url = is_numeric($member->photo) ? uploaded_asset($member->photo) : static_asset($member->photo);
    }
    if (empty($profile_avatar_url)) {
        $g_val = optional($member->member)->gender ?? 1;
        $profile_avatar_url = ($g_val == 2)
            ? static_asset('assets/img/female-avatar-place.png')
            : static_asset('assets/img/avatar-place.png');
    }
@endphp

<!-- Profile Identity Hero with Direct Photo Upload -->
<div class="mb-4 p-4 rounded-2xl bg-white border border-stone-200/80 shadow-sm d-flex flex-wrap align-items-center justify-content-between" style="border-radius: 18px; gap: 16px;">
    <div class="d-flex align-items-center" style="gap: 18px;">
        <div class="position-relative" style="cursor: pointer;" data-toggle="modal" data-target="#modal-update-profile-photo-main" title="{{ translate('Click to change profile photo') }}">
            <img src="{{ $profile_avatar_url }}" alt="{{ $member->first_name }}" class="rounded-circle border shadow-sm" style="width: 76px; height: 76px; object-fit: cover; border-width: 3px !important; border-color: var(--mat-border, #ECE7E1) !important;" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
            <button type="button" class="position-absolute bottom-0 right-0 btn btn-xs btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 26px; height: 26px; padding: 0; background: var(--mat-maroon, #4A1521); border: 2px solid #FFFFFF;" data-toggle="modal" data-target="#modal-update-profile-photo-main" title="{{ translate('Change Photo') }}">
                <i class="las la-camera fs-13 text-white"></i>
            </button>
        </div>
        <div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                <h4 class="fw-700 mb-0 font-serif" style="color: var(--mat-maroon-dark, #2C0A12); font-size: 20px;">
                    {{ $member->first_name }} {{ $member->last_name }}
                </h4>
                @if($member->approved == 1)
                    <span class="badge badge-inline px-2 py-0.5 fs-11 fw-600 text-success rounded-pill" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.28);">
                        <i class="las la-check-circle mr-1"></i>{{ translate('Verified') }}
                    </span>
                @endif
            </div>
            <div class="text-muted fs-13 mt-1 d-flex align-items-center flex-wrap" style="gap: 12px;">
                <span><strong class="text-dark">{{ translate('Member ID') }}:</strong> #{{ $member->code ?? 'ID-'.$member->id }}</span>
                <span>•</span>
                <span><strong class="text-dark">{{ translate('Membership') }}:</strong> {{ is_paid_member($member->id) ? (($member->member && $member->member->package) ? $member->member->package->name : translate('Premium Member')) : translate('Free Plan') }}</span>
            </div>
        </div>
    </div>
    <div>
        <button type="button" class="btn text-white rounded-pill px-4 py-2 fs-13 fw-600 d-inline-flex align-items-center shadow-sm" style="background: var(--mat-maroon, #4A1521); gap: 6px;" data-toggle="modal" data-target="#modal-update-profile-photo-main">
            <i class="las la-camera fs-16" style="color: var(--mat-gold, #C5A059);"></i>
            <span>{{ translate('Change Profile Photo') }}</span>
        </button>
    </div>
</div>

<div class="profile-biodata-grid">

    {{-- ROW 1 (2 Columns): Card 1 & Card 2 --}}
    <div class="profile-biodata-row">
        {{-- Card 1: 1. Basic Information --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-1" data-completed="{{ $is_c1_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-user"></i></span>
                    <h3 class="profile-card-title font-serif">1. {{ translate('Basic Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-basic-info">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Full Name') }}</span>
                    <span class="profile-field-value" data-field="full_name">{{ trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) ?: '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Gender') }}</span>
                    <span class="profile-field-value" data-field="gender">{{ (optional($member->member)->gender ?? 1) == 1 ? translate('Male') : translate('Female') }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Date Of Birth') }}</span>
                    <span class="profile-field-value" data-field="birthday">{{ !empty(optional($member->member)->birthday) ? date('d M Y', strtotime($member->member->birthday)) : '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Phone Number') }}</span>
                    <span class="profile-field-value" data-field="phone">{{ $member->phone ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Email') }}</span>
                    <span class="profile-field-value" data-field="email">{{ $member->email ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Marital Status') }}</span>
                    <span class="profile-field-value" data-field="marital_status">{{ !empty(optional(optional($member->member)->marital_status)->name) ? optional(optional($member->member)->marital_status)->name : (!empty(optional($member->member)->marital_status) ? ucwords(str_replace('_', ' ', optional($member->member)->marital_status)) : 'Never Married') }}</span>
                </div>
                <div class="profile-field-item card-children-field @if($is_never_married) d-none @endif" id="card-children-item">
                    <span class="profile-field-label">{{ translate('Number Of Children') }}</span>
                    <span class="profile-field-value" data-field="children">{{ optional($member->member)->children ?? 0 }}</span>
                </div>
                <div class="profile-field-item @if(empty($salary_val)) d-none @endif" id="card-salary-item">
                    <span class="profile-field-label">{{ translate('Annual Salary Range') }}</span>
                    <span class="profile-field-value" data-field="annual_salary_range">{{ !empty($salary_val) ? (is_numeric($salary_val) ? number_format($salary_val) : $salary_val) : '' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 2: 2. Personal Details (Merged with Physical Attributes) --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-2" data-completed="{{ $is_c2_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-id-card"></i></span>
                    <h3 class="profile-card-title font-serif">2. {{ translate('Personal Details') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-personal-details">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Age') }}</span>
                    <span class="profile-field-value" data-field="age">{{ !empty(optional($member->member)->birthday) ? \Carbon\Carbon::parse($member->member->birthday)->age . ' ' . translate('Years') : '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Height') }}</span>
                    <span class="profile-field-value" data-field="height">{{ !empty(optional($member->physical_attributes)->height) ? $member->physical_attributes->height . ' ft' : '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Weight') }}</span>
                    <span class="profile-field-value" data-field="weight">{{ !empty(optional($member->physical_attributes)->weight) ? $member->physical_attributes->weight . ' kg' : '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Body Type') }}</span>
                    <span class="profile-field-value" data-field="body_type">{{ optional($member->physical_attributes)->body_type ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Complexion') }}</span>
                    <span class="profile-field-value" data-field="complexion">{{ optional($member->physical_attributes)->complexion ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Blood Group') }}</span>
                    <span class="profile-field-value" data-field="blood_group">{{ optional($member->physical_attributes)->blood_group ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Disability') }}</span>
                    <span class="profile-field-value" data-field="disability">{{ (optional($member->physical_attributes)->disability) ? $member->physical_attributes->disability : 'None' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 2 (2 Columns): Card 3 (Spiritual & Social Background) & Card 4 (Career) --}}
    <div class="profile-biodata-row">
        {{-- Card 3: 3. Religion --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-12" data-completed="{{ $is_c3_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-place-of-worship"></i></span>
                    <h3 class="profile-card-title font-serif">3. {{ translate('Religion') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-spiritual">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            @php
                $currentReligionName = optional(optional($member->spiritual_backgrounds)->religion)->name ?? 'Islam';
                $isIslam = (strtolower($currentReligionName) == 'islam' || empty($member->spiritual_backgrounds));
                $isHindu = (strtolower($currentReligionName) == 'hinduism');
                $sectOrCasteName = optional(optional($member->spiritual_backgrounds)->caste)->name;
                $subName = optional(optional($member->spiritual_backgrounds)->sub_caste)->name;
            @endphp
            <div class="profile-data-col-grid cols-3" id="card-spiritual-grid">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Religion') }}</span>
                    <span class="profile-field-value" data-field="religion">{{ $currentReligionName }}</span>
                </div>
                <div class="profile-field-item" id="card-caste-field">
                    <span class="profile-field-label" id="card-caste-label">{{ $isIslam ? translate('Sect / Mazhab') : ($isHindu ? translate('Caste') : translate('Denomination / Sect')) }}</span>
                    <span class="profile-field-value" data-field="caste">{{ $sectOrCasteName ?? ($isIslam ? 'Sunni' : '—') }}</span>
                </div>
                <div class="profile-field-item" id="card-subcaste-field" style="{{ (!$isHindu && empty($subName)) ? 'display: none;' : '' }}">
                    <span class="profile-field-label" id="card-subcaste-label">{{ $isIslam ? translate('Sub-Sect') : translate('Sub-Caste / Gotra') }}</span>
                    <span class="profile-field-value" data-field="sect">{{ $subName ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Family Values') }}</span>
                    <span class="profile-field-value" data-field="family_values">{{ optional(optional($member->spiritual_backgrounds)->family_value)->name ?? 'Traditional' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 4: 4. Career --}}
        @php
            $c_prof = optional($current_career)->profession_status ?? '';
            if (empty($c_prof) && (!empty(optional($current_career)->designation) || !empty(optional($current_career)->company))) {
                $c_prof = 'Job Holder';
            }
        @endphp
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-5" data-completed="{{ $is_c4_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-briefcase"></i></span>
                    <h3 class="profile-card-title font-serif">4. {{ translate('Career') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-career">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3" id="card-career-grid">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Employment / Profession Status') }}</span>
                    <span class="profile-field-value" data-field="profession_status">{{ !empty($c_prof) ? translate($c_prof) : '—' }}</span>
                </div>
                <div class="profile-field-item @if(empty(optional($current_career)->job_type)) d-none @endif" id="card-career-job-type-item">
                    <span class="profile-field-label">{{ translate('Job Type / Sector') }}</span>
                    <span class="profile-field-value" data-field="job_type">{{ optional($current_career)->job_type ?? '—' }}</span>
                </div>
                <div class="profile-field-item @if(empty(optional($current_career)->occupation) || in_array($c_prof, ['Not Employed', 'Homemaker'])) d-none @endif" id="card-career-occupation-item">
                    <span class="profile-field-label">{{ translate('Occupation (পেশা)') }}</span>
                    <span class="profile-field-value" data-field="occupation">{{ optional($current_career)->occupation ?? '—' }}</span>
                </div>
                <div class="profile-field-item @if(empty(optional($current_career)->company) || in_array($c_prof, ['Not Employed', 'Homemaker'])) d-none @endif" id="card-career-company-item">
                    <span class="profile-field-label" id="card-career-company-label">{{ $c_prof == 'Student' ? translate('Institution / University') : translate('Company / Organization') }}</span>
                    <span class="profile-field-value" data-field="company">{{ optional($current_career)->company ?? '—' }}</span>
                </div>
                <div class="profile-field-item @if(empty(optional($current_career)->designation) || in_array($c_prof, ['Not Employed', 'Homemaker'])) d-none @endif" id="card-career-designation-item">
                    <span class="profile-field-label" id="card-career-designation-label">{{ $c_prof == 'Student' ? translate('Department / Subject') : translate('Designation (পদবি)') }}</span>
                    <span class="profile-field-value" data-field="designation">{{ optional($current_career)->designation ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3 (Full-Width Card): Card 5 (Education) --}}
    <div class="profile-biodata-row full-width">
        {{-- Card 5: 5. Education --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-4" data-completed="{{ $is_c5_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-graduation-cap"></i></span>
                    <h3 class="profile-card-title font-serif">5. {{ translate('Education') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-add-education">
                    <i class="las la-plus"></i> {{ translate('Add Degree') }}
                </button>
            </div>
            <div class="profile-education-list" id="education-card-list">
                @php $educations = $member->education ?? collect(); @endphp
                @forelse($educations as $edu)
                    <div class="profile-education-item py-3 @if(!$loop->first) border-top border-stone-100 @endif" id="edu-item-{{ $edu->id }}">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge badge-inline badge-soft-primary font-weight-bold px-3 py-1 text-sm rounded-pill">
                                {{ $edu->degree }}
                            </span>
                            <div class="education-actions">
                                <button type="button" class="btn btn-sm btn-icon btn-circle btn-light edit-edu-btn mr-1" 
                                    data-id="{{ $edu->id }}"
                                    data-degree="{{ $edu->degree }}"
                                    data-institution="{{ $edu->institution }}"
                                    data-subject="{{ $edu->subject }}"
                                    data-result="{{ $edu->result }}"
                                    data-end="{{ $edu->end }}"
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-pen text-primary"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-circle btn-light delete-edu-btn" 
                                    data-id="{{ $edu->id }}"
                                    data-url="{{ route('education_destroy', $edu->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash text-danger"></i>
                                </button>
                            </div>
                        </div>
                        <div class="profile-data-col-grid cols-5">
                            <div class="profile-field-item">
                                <span class="profile-field-label">{{ translate('Degree') }}</span>
                                <span class="profile-field-value edu-degree-val">{{ $edu->degree ?? '—' }}</span>
                            </div>
                            <div class="profile-field-item">
                                <span class="profile-field-label">{{ translate('Subject') }}</span>
                                <span class="profile-field-value edu-subject-val">{{ $edu->subject ?? '—' }}</span>
                            </div>
                            <div class="profile-field-item">
                                <span class="profile-field-label">{{ translate('Institution') }}</span>
                                <span class="profile-field-value edu-institution-val">{{ $edu->institution ?? '—' }}</span>
                            </div>
                            <div class="profile-field-item">
                                <span class="profile-field-label">{{ translate('Passing Year') }}</span>
                                <span class="profile-field-value edu-end-val">{{ $edu->end ?? '—' }}</span>
                            </div>
                            <div class="profile-field-item">
                                <span class="profile-field-label">{{ translate('CGPA / Result') }}</span>
                                <span class="profile-field-value edu-result-val">{{ $edu->result ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="las la-graduation-cap la-3x text-muted mb-2 opacity-50"></i>
                        <p class="mb-2">{{ translate('No education details added yet.') }}</p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-toggle="modal" data-target="#modal-add-education">
                            <i class="las la-plus mr-1"></i> {{ translate('Add Degree') }}
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ROW 4 (2 Columns): Card 6 (Present Address) & Card 7 (Permanent Address) --}}
    <div class="profile-biodata-row">
        {{-- Card 6: 6. Present Address --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-3" data-completed="{{ $is_c6_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-map-marker"></i></span>
                    <h3 class="profile-card-title font-serif">6. {{ translate('Present Address') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-contact-details">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Country') }}</span>
                    <span class="profile-field-value" data-field="country">{{ optional(optional($present_address)->country)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('State / Division') }}</span>
                    <span class="profile-field-value" data-field="state">{{ optional(optional($present_address)->state)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('City / District') }}</span>
                    <span class="profile-field-value" data-field="city">{{ optional(optional($present_address)->city)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Postal Code') }}</span>
                    <span class="profile-field-value" data-field="postal_code">{{ optional($present_address)->postal_code ?? '—' }}</span>
                </div>
                <div class="profile-field-item" style="grid-column: span 2;">
                    <span class="profile-field-label">{{ translate('Home no and road or lane number') }}</span>
                    <span class="profile-field-value" data-field="home_road">{{ optional($present_address)->home_road ?? '—' }}</span>
                </div>
                <div class="profile-field-item" style="grid-column: 1 / -1;">
                    <span class="profile-field-label">{{ translate('Present Address') }}</span>
                    <span class="profile-field-value" data-field="present_address">
                        {{ (optional($present_address)->home_road ? optional($present_address)->home_road . ', ' : '') . (optional(optional($present_address)->city)->name ? optional($present_address->city)->name . ', ' : '') . (optional(optional($present_address)->state)->name ? optional($present_address->state)->name . ', ' : '') . (optional(optional($present_address)->country)->name ?? '—') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 7: 7. Permanent Address --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-13" data-completed="{{ $is_c7_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-map-marker-alt"></i></span>
                    <h3 class="profile-card-title font-serif">7. {{ translate('Permanent Address') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-permanent-address">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Country') }}</span>
                    <span class="profile-field-value" data-field="country">{{ optional(optional($permanent_address)->country)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Division / State') }}</span>
                    <span class="profile-field-value" data-field="division">{{ optional(optional($permanent_address)->state)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('District / City') }}</span>
                    <span class="profile-field-value" data-field="district">{{ optional(optional($permanent_address)->city)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Postal Code') }}</span>
                    <span class="profile-field-value" data-field="postal_code">{{ optional($permanent_address)->postal_code ?? '—' }}</span>
                </div>
                <div class="profile-field-item" style="grid-column: span 2;">
                    <span class="profile-field-label">{{ translate('Home no and road or lane number') }}</span>
                    <span class="profile-field-value" data-field="home_road">{{ optional($permanent_address)->home_road ?? '—' }}</span>
                </div>
                <div class="profile-field-item" style="grid-column: 1 / -1;">
                    <span class="profile-field-label">{{ translate('Permanent Address') }}</span>
                    <span class="profile-field-value" data-field="address">
                        {{ (optional($permanent_address)->home_road ? optional($permanent_address)->home_road . ', ' : '') . (optional(optional($permanent_address)->city)->name ? optional($permanent_address->city)->name . ', ' : '') . (optional(optional($permanent_address)->state)->name ? optional($permanent_address->state)->name . ', ' : '') . (optional(optional($permanent_address)->country)->name ?? '—') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 5 (2 Columns): Card 8 (Family Information) & Card 9 (Residency Information) --}}
    <div class="profile-biodata-row">
        {{-- Card 8: 8. Family Information --}}
        @php
            $sibling_data = !empty(optional($member->families)->about_siblings) ? json_decode($member->families->about_siblings, true) : [];
        @endphp
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-9" data-completed="{{ $is_c8_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-users"></i></span>
                    <h3 class="profile-card-title font-serif">8. {{ translate('Family Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-family">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate("Father's Name") }}</span>
                    <span class="profile-field-value" data-field="father">{{ optional($member->families)->father ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate("Father's Occupation") }}</span>
                    <span class="profile-field-value" data-field="father_occupation">{{ optional($member->families)->father_occupation ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate("Mother's Name") }}</span>
                    <span class="profile-field-value" data-field="mother">{{ optional($member->families)->mother ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate("Mother's Occupation") }}</span>
                    <span class="profile-field-value" data-field="mother_occupation">{{ optional($member->families)->mother_occupation ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Number of Brothers') }}</span>
                    <span class="profile-field-value" data-field="no_of_brothers">{{ isset(optional($member->families)->no_of_brothers) ? $member->families->no_of_brothers : '0' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Number of Sisters') }}</span>
                    <span class="profile-field-value" data-field="no_of_sisters">{{ isset(optional($member->families)->no_of_sisters) ? $member->families->no_of_sisters : '0' }}</span>
                </div>
                <div class="profile-field-item @if(empty($sibling_data['brother_status']) || (optional($member->families)->no_of_brothers ?? 0) <= 0) d-none @endif" id="card-brother-status-field">
                    <span class="profile-field-label">{{ translate("Brother's Profession") }}</span>
                    <span class="profile-field-value" data-field="brother_profession">
                        {{ ($sibling_data['brother_status'] ?? '') }}{{ !empty($sibling_data['brother_detail']) ? ' (' . $sibling_data['brother_detail'] . ')' : '' }}
                    </span>
                </div>
                <div class="profile-field-item @if(empty($sibling_data['sister_status']) || (optional($member->families)->no_of_sisters ?? 0) <= 0) d-none @endif" id="card-sister-status-field">
                    <span class="profile-field-label">{{ translate("Sister's Profession") }}</span>
                    <span class="profile-field-value" data-field="sister_profession">
                        {{ ($sibling_data['sister_status'] ?? '') }}{{ !empty($sibling_data['sister_detail']) ? ' (' . $sibling_data['sister_detail'] . ')' : '' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 9: 9. Residency Information --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-11" data-completed="{{ $is_c9_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-home"></i></span>
                    <h3 class="profile-card-title font-serif">9. {{ translate('Residency Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-residency">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Current Residence (Country)') }}</span>
                    <span class="profile-field-value" data-field="current_residence">{{ $countries->firstWhere('id', optional($member->recidency)->recidency_country_id)->name ?? (optional(optional($present_address)->country)->name ?? '—') }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Permanent Residence (Birth Country)') }}</span>
                    <span class="profile-field-value" data-field="permanent_residence">{{ $countries->firstWhere('id', optional($member->recidency)->birth_country_id)->name ?? (optional(optional($permanent_address)->country)->name ?? '—') }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Grow-up Country') }}</span>
                    <span class="profile-field-value" data-field="growup_country">{{ $countries->firstWhere('id', optional($member->recidency)->growup_country_id)->name ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Immigration Status') }}</span>
                    <span class="profile-field-value" data-field="immigration_status">{{ optional($member->recidency)->immigration_status ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 6 (2 Columns): Card 10 (Lifestyle & Preferences) & Card 11 (Language) --}}
    <div class="profile-biodata-row">
        {{-- Card 10: 10. Lifestyle & Preferences --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-10" data-completed="{{ $is_c10_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-cocktail"></i></span>
                    <h3 class="profile-card-title font-serif">10. {{ translate('Lifestyle & Preferences') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-lifestyle">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Smoking') }}</span>
                    <span class="profile-field-value" data-field="smoke">{{ !empty(optional($member->lifestyles)->smoke) ? ucfirst($member->lifestyles->smoke) : 'No' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Drinking') }}</span>
                    <span class="profile-field-value" data-field="drink">{{ !empty(optional($member->lifestyles)->drink) ? ucfirst($member->lifestyles->drink) : 'No' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Food Preference / Diet') }}</span>
                    <span class="profile-field-value" data-field="diet">{{ !empty(optional($member->lifestyles)->diet) ? ($member->lifestyles->diet == 'yes' ? 'Vegetarian' : 'Non-Vegetarian') : 'Non-Vegetarian' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Living With') }}</span>
                    <span class="profile-field-value" data-field="living_with">{{ optional($member->lifestyles)->living_with ?? 'Family' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 11: 11. Language --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-7" data-completed="{{ $is_c11_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-language"></i></span>
                    <h3 class="profile-card-title font-serif">11. {{ translate('Language') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-language">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-3">
                <div class="profile-field-item">
                    <span class="profile-field-label">Mother Tongue</span>
                    <span class="profile-field-value" data-field="mother_tongue">{{ !empty(optional(optional($member->member)->mothereTongue)->name) ? optional(optional($member->member)->mothereTongue)->name : (!empty(optional($member->member)->mothere_tongue) ? optional($member->member)->mothere_tongue : 'Bengali') }}</span>
                    <small class="text-muted mt-1">{{ translate('Native') }}</small>
                </div>
                <div class="profile-field-item" style="grid-column: span 2;">
                    <span class="profile-field-label">{{ translate('Known Languages') }}</span>
                    <span class="profile-field-value" data-field="known_languages">
                        {{ $known_languages_display }}
                    </span>
                    <small class="text-muted mt-1">{{ translate('Fluent') }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 7 (2 Columns): Card 12 (Hobbies & Interests) & Card 13 (Additional Information) --}}
    <div class="profile-biodata-row">
        {{-- Card 12: 12. Hobbies & Interests --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-8" data-completed="{{ $is_c12_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-heart"></i></span>
                    <h3 class="profile-card-title font-serif">12. {{ translate('Hobbies & Interests') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-hobbies">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Hobbies') }}</span>
                    <span class="profile-field-value" data-field="hobbies" style="white-space: pre-line;">{{ optional($member->hobbies)->hobbies ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Interests') }}</span>
                    <span class="profile-field-value" data-field="interests" style="white-space: pre-line;">{{ optional($member->hobbies)->interests ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 13: 13. Additional Information --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-14" data-completed="{{ $is_c13_completed ? 1 : 0 }}">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-file-alt"></i></span>
                    <h3 class="profile-card-title font-serif">13. {{ translate('Additional Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-additional-info">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-1">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('About Yourself') }}</span>
                    <div class="profile-field-value serif-bio" data-field="about_yourself">
                        {{ optional($member->member)->introduction ?? 'I am a simple, honest and family oriented person. I love to explore new places, learn new things and build a happy life with my partner.' }}
                    </div>
                </div>
                <div class="profile-field-item mt-3">
                    <span class="profile-field-label">{{ translate('Expectations from Partner') }}</span>
                    <div class="profile-field-value serif-bio" data-field="expectations">
                        {{ optional($member->partner_expectations)->general ?? 'I am looking for a kind, understanding and supportive partner who values family and keeps a positive mindset towards life.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

{{-- PARTNER PREFERENCES FULL PAGE VIEW --}}
<div id="view-partner-preferences" class="d-none">
    <div class="profile-biodata-grid">
        {{-- PREF ROW 1 (2 Columns): Basic & Physical + Religion & Community --}}
        <div class="profile-biodata-row">
            {{-- Card 1: 1. Basic & Physical Requirements --}}
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-basic" data-completed="{{ $is_pe_basic_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-heart"></i></span>
                        <h3 class="profile-card-title font-serif">1. {{ translate('Basic & Physical Requirements') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-basic">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-data-col-grid cols-3">
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Min Height') }}</span>
                        <span class="profile-field-value" data-field="pref_height">{{ !empty(optional($pe)->height) ? optional($pe)->height . ' ft' : '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Max Weight') }}</span>
                        <span class="profile-field-value" data-field="pref_weight">{{ !empty(optional($pe)->weight) ? optional($pe)->weight . ' kg' : '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Marital Status') }}</span>
                        <span class="profile-field-value" data-field="pref_marital_status">{{ optional(optional($pe)->marital_status)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Children Acceptable') }}</span>
                        <span class="profile-field-value" data-field="pref_children">{{ !empty(optional($pe)->children_acceptable) ? (optional($pe)->children_acceptable == 'dose_not_matter' ? translate('Does Not Matter') : ucfirst(optional($pe)->children_acceptable)) : '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Complexion') }}</span>
                        <span class="profile-field-value" data-field="pref_complexion">{{ optional($pe)->complexion ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Body Type') }}</span>
                        <span class="profile-field-value" data-field="pref_body_type">{{ optional($pe)->body_type ?: '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: 2. Religion & Community Preferences --}}
            @php
                $pe_rel_name = strtolower(optional(optional($pe)->religion)->name ?? '');
                $pe_rel_id = optional($pe)->religion_id;
                $pe_is_hindu = ($pe_rel_name === 'hinduism' || $pe_rel_id == 2);
                $pe_is_islam = ($pe_rel_name === 'islam' || $pe_rel_id == 1);
                $pe_is_christian = ($pe_rel_name === 'christianity' || $pe_rel_id == 3);
                $pe_is_buddhist = ($pe_rel_name === 'buddhism' || $pe_rel_id == 4);

                $caste_display_label = translate('Sect / Mazhab / Caste');
                if ($pe_is_islam) $caste_display_label = translate('Sect / Mazhab');
                elseif ($pe_is_hindu) $caste_display_label = translate('Caste');
                elseif ($pe_is_christian) $caste_display_label = translate('Denomination');
                elseif ($pe_is_buddhist) $caste_display_label = translate('Sect / Community');
            @endphp
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-religion" data-completed="{{ $is_pe_religion_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-place-of-worship"></i></span>
                        <h3 class="profile-card-title font-serif">2. {{ translate('Religion & Community Preferences') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-religion">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-data-col-grid cols-3">
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Religion') }}</span>
                        <span class="profile-field-value" data-field="pref_religion">{{ optional(optional($pe)->religion)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item" id="card-pref-caste-item">
                        <span class="profile-field-label" id="card-pref-caste-label">{{ $caste_display_label }}</span>
                        <span class="profile-field-value" data-field="pref_caste">{{ optional(optional($pe)->caste)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item @if(!$pe_is_hindu) d-none @endif" id="card-pref-subcaste-item">
                        <span class="profile-field-label">{{ translate('Sub-caste / Gotra') }}</span>
                        <span class="profile-field-value" data-field="pref_sub_caste">{{ optional(optional($pe)->sub_caste)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Mother Tongue / Language') }}</span>
                        <span class="profile-field-value" data-field="pref_language">{{ optional(optional($pe)->member_language)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Family Value') }}</span>
                        <span class="profile-field-value" data-field="pref_family_value">{{ optional(optional($pe)->family_value)->name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Personal Values') }}</span>
                        <span class="profile-field-value" data-field="pref_personal_value">{{ optional($pe)->personal_value ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item @if(!$pe_is_hindu) d-none @endif" id="card-pref-manglik-item">
                        <span class="profile-field-label">{{ translate('Manglik') }}</span>
                        <span class="profile-field-value" data-field="pref_manglik">{{ !empty(optional($pe)->manglik) ? (optional($pe)->manglik == 'dose_not_matter' ? translate('Does Not Matter') : ucfirst(optional($pe)->manglik)) : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREF ROW 2 (2 Columns): Education & Profession + Location Preferences --}}
        <div class="profile-biodata-row">
            {{-- Card 3: 3. Education & Profession Preferences --}}
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-education" data-completed="{{ $is_pe_education_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-graduation-cap"></i></span>
                        <h3 class="profile-card-title font-serif">3. {{ translate('Education & Profession Preferences') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-education">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-data-col-grid cols-2">
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Minimum Education') }}</span>
                        <span class="profile-field-value" data-field="pref_education">{{ optional($pe)->education ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Profession / Career') }}</span>
                        <span class="profile-field-value" data-field="pref_profession">{{ optional($pe)->profession ?: '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 4: 4. Location & Residence Preferences --}}
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-location" data-completed="{{ $is_pe_location_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-map-marker-alt"></i></span>
                        <h3 class="profile-card-title font-serif">4. {{ translate('Location & Residence Preferences') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-location">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-data-col-grid cols-3">
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Residence Country') }}</span>
                        <span class="profile-field-value" data-field="pref_residence_country">{{ $pe_residence_country_name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Preferred Country') }}</span>
                        <span class="profile-field-value" data-field="pref_preferred_country">{{ $pe_preferred_country_name ?: '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Preferred State / Division') }}</span>
                        <span class="profile-field-value" data-field="pref_preferred_state">{{ $pe_preferred_state_name ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PREF ROW 3 (2 Columns): Lifestyle & Habits + General Expectations Note --}}
        <div class="profile-biodata-row">
            {{-- Card 5: 5. Lifestyle & Habits --}}
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-lifestyle" data-completed="{{ $is_pe_lifestyle_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-glass-martini-alt"></i></span>
                        <h3 class="profile-card-title font-serif">5. {{ translate('Lifestyle & Habits') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-lifestyle">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-data-col-grid cols-3">
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Diet Preference') }}</span>
                        <span class="profile-field-value" data-field="pref_diet">{{ !empty(optional($pe)->diet) ? (optional($pe)->diet == 'dose_not_matter' ? translate('Does Not Matter') : (optional($pe)->diet == 'non_veg' ? translate('Non-Vegetarian') : (optional($pe)->diet == 'veg' ? translate('Vegetarian') : translate('Both')))) : '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Smoking Acceptable') }}</span>
                        <span class="profile-field-value" data-field="pref_smoking">{{ !empty(optional($pe)->smoking_acceptable) ? (optional($pe)->smoking_acceptable == 'dose_not_matter' ? translate('Does Not Matter') : ucfirst(optional($pe)->smoking_acceptable)) : '—' }}</span>
                    </div>
                    <div class="profile-field-item">
                        <span class="profile-field-label">{{ translate('Drinking Acceptable') }}</span>
                        <span class="profile-field-value" data-field="pref_drinking">{{ !empty(optional($pe)->drinking_acceptable) ? (optional($pe)->drinking_acceptable == 'dose_not_matter' ? translate('Does Not Matter') : ucfirst(optional($pe)->drinking_acceptable)) : '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 6: 6. General Expectations & Note --}}
            <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-pref-general" data-completed="{{ $is_pe_general_completed ? 1 : 0 }}">
                <div class="profile-biodata-card-header">
                    <div class="profile-card-title-wrap">
                        <span class="profile-card-icon-badge"><i class="las la-comment-dots"></i></span>
                        <h3 class="profile-card-title font-serif">6. {{ translate('Expectations Note') }}</h3>
                    </div>
                    <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-pref-general">
                        <i class="las la-pen"></i> {{ translate('Edit') }}
                    </button>
                </div>
                <div class="profile-field-item mt-2">
                    <span class="profile-field-label">{{ translate('What kind of life partner you are looking for') }}</span>
                    <div class="profile-field-value serif-bio mt-1" data-field="pref_general">
                        {{ optional($pe)->general ?: translate('I am looking for an understanding, caring and supportive partner with good family values.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('frontend.member.profile.modals')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')

    <!-- Update Profile Photo Modal -->
    <div class="modal fade" id="modal-update-profile-photo-main" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-2xl border-0 overflow-hidden shadow-lg" style="border-radius: 18px;">
                <div class="modal-header py-3 px-4 border-0" style="background: var(--mat-maroon, #4A1521); color: #ffffff;">
                    <h5 class="modal-title font-serif fw-700 fs-16 mb-0 text-white d-flex align-items-center">
                        <i class="las la-camera mr-2" style="color: var(--mat-gold, #C5A059); font-size: 20px;"></i>
                        {{ translate('Update Profile Photo') }}
                    </h5>
                    <button type="button" class="close p-0 m-0 text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('member.profile_photo_update') }}" method="POST" id="form-update-profile-photo-main" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 text-center">
                        <div class="mb-4 d-inline-block position-relative">
                            <img id="avatar_preview_img_main" src="{{ $profile_avatar_url }}" class="rounded-circle border shadow-sm" style="width: 140px; height: 140px; object-fit: cover; border-width: 3px !important; border-color: var(--mat-border, #ECE7E1) !important;" alt="{{ $member->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        </div>

                        <div class="p-4 rounded-xl border border-dashed text-center mb-2" style="background: #FAF8F5; border-color: #CBD5E1; cursor: pointer;" onclick="document.getElementById('profile_photo_input_main_file').click();">
                            <i class="las la-cloud-upload-alt fs-36 text-muted mb-1 d-block"></i>
                            <span class="fs-14 fw-600 text-dark d-block">{{ translate('Click here to choose a photo') }}</span>
                        </div>

                        <input type="file" name="photo" id="profile_photo_input_main_file" accept="image/jpeg,image/png,image/webp,image/jpg,image/gif" class="d-none" onchange="previewMainProfilePhoto(event)">
                    </div>
                    <div class="modal-footer bg-light py-2.5 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4 fs-13 fw-600" data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="button" class="btn text-white rounded-pill px-4 fs-13 fw-600 shadow-sm" id="btn-save-main-photo" onclick="submitProfilePhotoUpload()" style="background: var(--mat-maroon, #4A1521); cursor: pointer;">
                            <i class="las la-check mr-1"></i> {{ translate('Upload & Save Photo') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    var currentActiveScope = 'my-profile';
    var profilePercentage = {{ $profile_completion_percentage }};
    var prefPercentage = {{ $partner_pref_completion_percentage }};
    var profileTotalSections = {{ $total_sections }};
    var prefTotalSections = {{ $total_pref_sections }};

    // Bangladesh Degrees & Subjects Dynamic Mapping
    var degreeSubjectsMap = {
        'engineering': {
            label: '{{ translate("Engineering & Technology") }}',
            subjects: [
                'Computer Science & Engineering (CSE)',
                'Electrical & Electronic Engineering (EEE)',
                'Civil Engineering (CE)',
                'Mechanical Engineering (ME)',
                'Software Engineering (SWE)',
                'Information Technology (IT)',
                'Information & Communication Technology (ICT)',
                'Textile Engineering',
                'Chemical Engineering',
                'Industrial & Production Engineering (IPE)',
                'Biomedical Engineering',
                'Aeronautical & Aerospace Engineering',
                'Materials & Metallurgical Engineering (MME)',
                'Naval Architecture & Marine Engineering (NAME)',
                'Architecture (B.Arch)',
                'Urban & Regional Planning (URP)',
                'Water Resources Engineering (WRE)',
                'Petroleum & Mining Engineering (PME)',
                'Electronics & Communication Engineering (ECE)',
                'Mechatronics Engineering',
                'Nuclear Engineering',
                'Food Engineering & Technology',
                'Agricultural Engineering',
                'Automobile Engineering',
                'Environmental Engineering',
                'Leather Engineering',
                'Glass & Ceramic Engineering'
            ]
        },
        'science': {
            label: '{{ translate("General & Applied Science") }}',
            subjects: [
                'Physics',
                'Applied Physics & Electronics',
                'Chemistry',
                'Applied Chemistry & Chemical Engineering',
                'Mathematics',
                'Applied Mathematics',
                'Statistics',
                'Computer Science',
                'Biochemistry & Molecular Biology',
                'Microbiology',
                'Genetic Engineering & Biotechnology (GEB)',
                'Pharmacy',
                'Botany',
                'Zoology',
                'Geography & Environment',
                'Environmental Science',
                'Soil, Water & Environment',
                'Geology',
                'Oceanography',
                'Fisheries',
                'Agriculture (B.Sc.Ag)',
                'Forestry',
                'Food Science & Technology',
                'Nutrition & Food Science',
                'Psychology'
            ]
        },
        'medical': {
            label: '{{ translate("Medical, Dental & Allied Health") }}',
            subjects: [
                'General Medicine & Surgery (MBBS)',
                'Dentistry / Dental Surgery (BDS)',
                'Pharmacy (B.Pharm)',
                'Nursing Science',
                'Physiotherapy (BPT)',
                'Veterinary Medicine (DVM)',
                'Public Health (MPH)',
                'Medical Laboratory Technology',
                'Radiology & Imaging',
                'Cardiology',
                'Pediatrics',
                'Gynecology & Obstetrics',
                'General Surgery',
                'Internal Medicine',
                'Ophthalmology',
                'ENT',
                'Dermatology',
                'Anesthesiology'
            ]
        },
        'business': {
            label: '{{ translate("Business & Commerce") }}',
            subjects: [
                'Accounting & Information Systems (AIS)',
                'Finance',
                'Finance & Banking',
                'Marketing',
                'Management',
                'Human Resource Management (HRM)',
                'International Business (IB)',
                'Management Information Systems (MIS)',
                'Banking & Insurance',
                'Tourism & Hospitality Management',
                'Supply Chain Management',
                'General Business Administration',
                'Commerce'
            ]
        },
        'social_science': {
            label: '{{ translate("Social Sciences") }}',
            subjects: [
                'Economics',
                'Political Science',
                'Sociology',
                'Public Administration',
                'International Relations (IR)',
                'Mass Communication & Journalism (MCJ)',
                'Anthropology',
                'Social Work / Welfare',
                'Peace & Conflict Studies',
                'Development Studies',
                'Women & Gender Studies',
                'Criminology',
                'Population Sciences'
            ]
        },
        'arts': {
            label: '{{ translate("Arts & Humanities") }}',
            subjects: [
                'English Literature & Linguistics',
                'Bangla (Bengali)',
                'History',
                'Islamic History & Culture',
                'Philosophy',
                'Islamic Studies',
                'Arabic Language & Literature',
                'Linguistics',
                'Fine Arts',
                'Music',
                'Theater & Performance Studies',
                'Archaeology',
                'Urdu',
                'Sanskrit & Pali',
                'World Religions & Culture'
            ]
        },
        'law': {
            label: '{{ translate("Law") }}',
            subjects: [
                'General Law',
                'Commercial & Corporate Law',
                'International Law',
                'Criminal Law',
                'Human Rights Law',
                'Constitutional Law'
            ]
        },
        'school': {
            label: '{{ translate("School / Higher Secondary") }}',
            subjects: [
                'Science',
                'Business Studies / Commerce',
                'Humanities / Arts',
                'General',
                'Vocational / Technical',
                'BM (Business Management)'
            ]
        },
        'diploma': {
            label: '{{ translate("Polytechnic & Technical Diploma") }}',
            subjects: [
                'Computer Technology',
                'Electrical Technology',
                'Civil Technology',
                'Mechanical Technology',
                'Electronics Technology',
                'Textile Technology',
                'Architecture Technology',
                'Automobile Technology',
                'Medical Technology (Laboratory)',
                'Medical Technology (Dental)',
                'Medical Technology (Radiology)',
                'Nursing & Midwifery',
                'Pharmacy',
                'Agriculture Technology',
                'Fisheries Technology',
                'Refrigeration & Air Conditioning (RAC)'
            ]
        },
        'madrasah': {
            label: '{{ translate("Madrasah & Islamic Education") }}',
            subjects: [
                'Hadith & Islamic Studies',
                'Tafseer',
                'Fiqh',
                'Arabic Literature (Adab)',
                'Dawah & Islamic Culture',
                'Hifzul Quran',
                'General Islamic Studies'
            ]
        }
    };

    function getCategoryForDegree(degree) {
        if (!degree) return 'all';
        degree = degree.trim();
        
        // Engineering
        if (degree.indexOf('Engg') !== -1 || degree.indexOf('Engineering') !== -1) {
            return 'engineering';
        }
        // Medical & Dental
        if (degree === 'MBBS' || degree === 'BDS' || degree === 'MD / MS' || degree === 'FCPS' || degree === 'MPH' || degree === 'B.Pharm' || degree === 'M.Pharm' || degree.indexOf('Nursing') !== -1) {
            return 'medical';
        }
        // Business & Commerce
        if (degree === 'BBA' || degree.indexOf('B.Com') !== -1 || degree === 'MBA' || degree === 'EMBA' || degree === 'MBM' || degree === 'M.Com' || degree === 'BBS') {
            return 'business';
        }
        // Social Science (Economics, etc.)
        if (degree.indexOf('B.S.S') !== -1 || degree === 'M.S.S') {
            return 'social_science';
        }
        // Arts & Humanities
        if (degree.indexOf('B.A') !== -1 || degree === 'M.A' || degree === 'B.F.A' || degree === 'B.Ed' || degree === 'M.Ed') {
            return 'arts';
        }
        // Science
        if (degree.indexOf('B.Sc') !== -1 || degree === 'M.Sc') {
            return 'science';
        }
        // Law
        if (degree.indexOf('LL.B') !== -1 || degree === 'LL.M') {
            return 'law';
        }
        // Diploma
        if (degree.indexOf('Diploma') !== -1) {
            return 'diploma';
        }
        // School / Higher Secondary
        if (degree === 'SSC' || degree === 'HSC' || degree.indexOf('Dakhil') !== -1 || degree.indexOf('Alim') !== -1 || degree.indexOf('O-Level') !== -1 || degree.indexOf('A-Level') !== -1 || degree.indexOf('Vocational') !== -1 || degree.indexOf('BM') !== -1) {
            return 'school';
        }
        // Madrasah
        if (degree === 'Fazil' || degree === 'Kamil' || degree.indexOf('Dawra') !== -1 || degree.indexOf('Hafiz') !== -1) {
            return 'madrasah';
        }
        
        return 'all';
    }

    function populateSubjectsForDegree(degree, $subjectSelect, selectedSubject) {
        var category = getCategoryForDegree(degree);
        $subjectSelect.empty();
        $subjectSelect.append('<option value="">{{ translate("Select Subject") }}</option>');
        
        if (category === 'all') {
            $.each(degreeSubjectsMap, function(catKey, catObj) {
                var $optgroup = $('<optgroup>').attr('label', catObj.label);
                $.each(catObj.subjects, function(i, subj) {
                    var $opt = $('<option>').val(subj).text(subj);
                    if (selectedSubject && selectedSubject === subj) {
                        $opt.prop('selected', true);
                    }
                    $optgroup.append($opt);
                });
                $subjectSelect.append($optgroup);
            });
        } else {
            var catObj = degreeSubjectsMap[category];
            if (catObj && catObj.subjects) {
                $.each(catObj.subjects, function(i, subj) {
                    var $opt = $('<option>').val(subj).text(subj);
                    if (selectedSubject && selectedSubject === subj) {
                        $opt.prop('selected', true);
                    }
                    $subjectSelect.append($opt);
                });
            }
            if (category === 'science' && degreeSubjectsMap['engineering']) {
                var $engGroup = $('<optgroup>').attr('label', '{{ translate("Engineering Subjects") }}');
                $.each(degreeSubjectsMap['engineering'].subjects, function(i, subj) {
                    var $opt = $('<option>').val(subj).text(subj);
                    if (selectedSubject && selectedSubject === subj) {
                        $opt.prop('selected', true);
                    }
                    $engGroup.append($opt);
                });
                $subjectSelect.append($engGroup);
            }
        }
        
        var $otherOpt = $('<option value="Other">{{ translate("Other / Custom Subject") }}</option>');
        if (selectedSubject && selectedSubject === 'Other') {
            $otherOpt.prop('selected', true);
        }
        $subjectSelect.append($otherOpt);
        
        if (selectedSubject && selectedSubject !== 'Other' && $subjectSelect.find('option[value="' + selectedSubject + '"]').length === 0) {
            $subjectSelect.prepend(new Option(selectedSubject, selectedSubject, true, true));
        } else if (selectedSubject) {
            $subjectSelect.val(selectedSubject);
        }
        
        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
            AIZ.plugins.bootstrapSelect('refresh');
        }
    }

    $(document).ready(function() {
        // Initial live sync for Profile completeness & Partner Preferences completeness
        recalculateProfileCompleteness();
        recalculatePartnerPrefCompleteness();

        // Top tab switcher buttons
        $(document).on('click', '#btn-tab-my-profile', function(e) {
            e.preventDefault();
            switchProfileTab('my-profile');
        });

        $(document).on('click', '#btn-tab-partner-preferences', function(e) {
            e.preventDefault();
            switchProfileTab('partner-preferences');
        });

        // Triggering section edit from checklist modal for Profile sections
        $(document).on('click', '.btn-trigger-section-edit', function(e) {
            e.preventDefault();
            var targetModal = $(this).data('target');
            if (!targetModal) return;

            var $checklistModal = $('#modal-profile-completion-checklist');
            if ($checklistModal.hasClass('show')) {
                $checklistModal.one('hidden.bs.modal', function() {
                    $(targetModal).modal('show');
                }).modal('hide');
            } else {
                $(targetModal).modal('show');
            }
        });

        // Triggering section edit from checklist modal for Partner Preference sections
        $(document).on('click', '.btn-trigger-pref-edit', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            var $checklistModal = $('#modal-profile-completion-checklist');
            if ($checklistModal.hasClass('show')) {
                $checklistModal.one('hidden.bs.modal', function() {
                    openPrefModalTab(tab);
                }).modal('hide');
            } else {
                openPrefModalTab(tab);
            }
        });

        // Checklist filter tabs
        $(document).on('click', '.checklist-filter-btn', function(e) {
            e.preventDefault();
            $('.checklist-filter-btn').removeClass('active');
            $(this).addClass('active');
            var filter = $(this).data('filter');
            applyChecklistFilter(filter);
        });

        // Ensure current filter is reapplied when checklist modal opens
        $('#modal-profile-completion-checklist').on('show.bs.modal', function() {
            if (currentActiveScope === 'partner-preferences') {
                recalculatePartnerPrefCompleteness();
            } else {
                recalculateProfileCompleteness();
            }
            var currentFilter = $('.checklist-filter-btn.active').data('filter') || 'all';
            applyChecklistFilter(currentFilter);
        });

        // Init dependent address selects
        if ($('#present_country_id').length) {
            get_states_by_country_for_present();
        }
        if ($('#permanent_country_id').length) {
            get_states_by_country_for_permanent();
        }
        if ($('#modal_member_religion_id').length) {
            handleReligionFields();
            get_castes_by_religion();
        }

        $('#modal-edit-spiritual').on('shown.bs.modal', function() {
            handleReligionFields();
        });

        // Partner Preference Dynamic Triggers
        $(document).on('change', '#pref_partner_religion_id', function() {
            get_pref_castes_by_religion();
        });
        $(document).on('change', '#pref_partner_caste_id', function() {
            get_pref_sub_castes_by_caste();
        });
        $(document).on('change', '#pref_partner_country_id', function() {
            get_pref_states_by_country();
        });

        $('#modal-edit-pref-religion').on('shown.bs.modal', function() {
            handlePrefReligionFields();
            if ($('#pref_partner_religion_id').val()) {
                get_pref_castes_by_religion();
            }
        });

        $('#modal-edit-pref-location').on('shown.bs.modal', function() {
            if ($('#pref_partner_country_id').val() && $('#pref_partner_state_id option').length <= 1) {
                get_pref_states_by_country();
            }
        });

        if ($('#pref_partner_religion_id').length) {
            handlePrefReligionFields();
        }

        // Change triggers
        $('#present_country_id').on('change', function() {
            get_states_by_country_for_present();
        });
        $('#present_state_id').on('change', function() {
            get_cities_by_state_for_present();
        });
        $('#permanent_country_id').on('change', function() {
            get_states_by_country_for_permanent();
        });
        $('#permanent_state_id').on('change', function() {
            get_cities_by_state_for_permanent();
        });
        $('#modal_member_religion_id').on('change', function() {
            get_castes_by_religion();
        });
        $('#modal_member_caste_id').on('change', function() {
            get_sub_castes_by_caste();
        });

        // Marital Status dynamic children toggle
        function handleMaritalStatusChange() {
            var $select = $('#modal-basic-marital-status');
            if (!$select.length) return;
            var text = $select.find('option:selected').text().trim().toLowerCase();
            var val = $select.val();
            var isNever = (text.indexOf('never married') !== -1 || val == '1' || text === '');
            if (isNever) {
                $('#group-number-of-children').addClass('d-none');
            } else {
                $('#group-number-of-children').removeClass('d-none');
            }
        }

        $(document).on('change', '#modal-basic-marital-status', function() {
            handleMaritalStatusChange();
        });

        $('#modal-edit-basic-info').on('shown.bs.modal', function() {
            handleMaritalStatusChange();
        });

        // Dynamic Family Modal Events
        $(document).on('change', '#family_father_sector', function() {
            var sec = $(this).val();
            if (sec === 'Deceased') {
                $('#family_father_position_group').addClass('d-none');
                $('#family_father_position').val('');
            } else {
                $('#family_father_position_group').removeClass('d-none');
            }
        });

        $(document).on('change', '#family_mother_sector', function() {
            var sec = $(this).val();
            if (sec === 'Homemaker' || sec === 'Deceased' || !sec) {
                $('#family_mother_position_group').addClass('d-none');
                $('#family_mother_position').val('');
            } else {
                $('#family_mother_position_group').removeClass('d-none');
            }
        });

        $(document).on('change', '#family_no_of_brothers', function() {
            var num = parseInt($(this).val()) || 0;
            if (num > 0) {
                $('#family_brother_dynamic_wrap').removeClass('d-none');
            } else {
                $('#family_brother_dynamic_wrap').addClass('d-none');
                $('#family_brother_status').val('');
                $('#family_brother_detail').val('');
                $('#family_brother_detail_group').addClass('d-none');
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            }
        });

        $(document).on('change', '#family_brother_status', function() {
            var val = $(this).val();
            if (!val) {
                $('#family_brother_detail_group').addClass('d-none');
            } else if (val === 'Student') {
                $('#family_brother_detail_group').removeClass('d-none');
                $('#family_brother_detail_label').text('{{ translate("School / College / University") }}');
                $('#family_brother_detail').attr('placeholder', '{{ translate("e.g. Dhaka University, CSE") }}');
            } else if (val === 'Job Holder') {
                $('#family_brother_detail_group').removeClass('d-none');
                $('#family_brother_detail_label').text('{{ translate("Company & Designation") }}');
                $('#family_brother_detail').attr('placeholder', '{{ translate("e.g. Software Engineer at XYZ Ltd.") }}');
            } else if (val === 'Business') {
                $('#family_brother_detail_group').removeClass('d-none');
                $('#family_brother_detail_label').text('{{ translate("Business Details") }}');
                $('#family_brother_detail').attr('placeholder', '{{ translate("e.g. Retail business, Dhaka") }}');
            } else {
                $('#family_brother_detail_group').removeClass('d-none');
                $('#family_brother_detail_label').text('{{ translate("Details / Activity") }}');
                $('#family_brother_detail').attr('placeholder', '{{ translate("Enter details") }}');
            }
        });

        $(document).on('change', '#family_no_of_sisters', function() {
            var num = parseInt($(this).val()) || 0;
            if (num > 0) {
                $('#family_sister_dynamic_wrap').removeClass('d-none');
            } else {
                $('#family_sister_dynamic_wrap').addClass('d-none');
                $('#family_sister_status').val('');
                $('#family_sister_detail').val('');
                $('#family_sister_detail_group').addClass('d-none');
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            }
        });

        $(document).on('change', '#family_sister_status', function() {
            var val = $(this).val();
            if (!val || val === 'Homemaker') {
                $('#family_sister_detail_group').addClass('d-none');
            } else if (val === 'Student') {
                $('#family_sister_detail_group').removeClass('d-none');
                $('#family_sister_detail_label').text('{{ translate("School / College / University") }}');
                $('#family_sister_detail').attr('placeholder', '{{ translate("e.g. Eden College, BBA") }}');
            } else if (val === 'Job Holder') {
                $('#family_sister_detail_group').removeClass('d-none');
                $('#family_sister_detail_label').text('{{ translate("Company & Designation") }}');
                $('#family_sister_detail').attr('placeholder', '{{ translate("e.g. Bank Officer, Dhaka") }}');
            } else if (val === 'Business') {
                $('#family_sister_detail_group').removeClass('d-none');
                $('#family_sister_detail_label').text('{{ translate("Business Details") }}');
                $('#family_sister_detail').attr('placeholder', '{{ translate("e.g. Boutique Shop owner") }}');
            } else {
                $('#family_sister_detail_group').removeClass('d-none');
                $('#family_sister_detail_label').text('{{ translate("Details / Activity") }}');
                $('#family_sister_detail').attr('placeholder', '{{ translate("Enter details") }}');
            }
        });

        // Dynamic Career Modal Events
        $(document).on('change', '#career_profession_status', function() {
            var status = $(this).val();
            $('#career_job_holder_fields, #career_business_fields, #career_student_fields, #career_other_fields, #career_empty_notice').addClass('d-none');
            if (status === 'Job Holder') {
                $('#career_job_holder_fields').removeClass('d-none');
            } else if (status === 'Business') {
                $('#career_business_fields').removeClass('d-none');
            } else if (status === 'Student') {
                $('#career_student_fields').removeClass('d-none');
            } else if (status === 'Freelancer' || status === 'Other') {
                $('#career_other_fields').removeClass('d-none');
            } else if (status === 'Not Employed' || status === 'Homemaker') {
                $('#career_empty_notice').removeClass('d-none');
            }
        });

        // AJAX Form Submission for All Modals
        $(document).on('submit', '.profile-ajax-form', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $modal = $form.closest('.modal');
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.html();
            var cardId = $form.data('card');

            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> {{ translate("Saving...") }}');

            var formData = new FormData(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $submitBtn.prop('disabled', false).html(originalText);
                    $modal.modal('hide');

                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('success', '{{ translate("Changes saved successfully!") }}');
                    }

                    if (cardId === 'card-4') {
                        setTimeout(function() {
                            location.reload();
                        }, 400);
                        return;
                    }

                    // Update DOM values directly
                    updateCardFromFormData(cardId, $form);
                },
                error: function(xhr) {
                    $submitBtn.prop('disabled', false).html(originalText);
                    var msg = '{{ translate("Something went wrong. Please check your inputs.") }}';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                        AIZ.plugins.notify('danger', msg);
                    } else {
                        alert(msg);
                    }
                }
            });
        });

        // Edit Education Button Handler
        $(document).on('click', '.edit-edu-btn', function() {
            var id = $(this).data('id');
            var degree = $(this).data('degree');
            var institution = $(this).data('institution');
            var subject = $(this).data('subject');
            var result = $(this).data('result');
            var end = $(this).data('end');

            $('#edit_education_id').val(id);
            if (degree && $('#edit_education_degree option[value="' + degree + '"]').length === 0) {
                $('#edit_education_degree').append(new Option(degree, degree, true, true));
            }
            $('#edit_education_degree').val(degree);
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }

            // Populate dependent subjects and select current subject
            populateSubjectsForDegree(degree, $('#edit_education_subject'), subject);
            $('#edit_custom_subject').addClass('d-none').val('');

            $('#edit_education_institution').val(institution);
            $('#edit_education_result').val(result);
            $('#edit_education_end').val(end);

            var updateUrl = '{{ url("/education/update") }}/' + id;
            $('#form-edit-education').attr('action', updateUrl);

            $('#modal-edit-education').modal('show');
        });

        // Degree change in Add Education modal
        $('#add_education_degree').on('change', function() {
            var deg = $(this).val();
            populateSubjectsForDegree(deg, $('#add_education_subject'), '');
            $('#add_custom_subject').addClass('d-none').val('');
        });

        // Degree change in Edit Education modal
        $('#edit_education_degree').on('change', function() {
            var deg = $(this).val();
            populateSubjectsForDegree(deg, $('#edit_education_subject'), '');
            $('#edit_custom_subject').addClass('d-none').val('');
        });

        // Custom subject toggling for Other option
        $(document).on('change', '.education-subject-select', function() {
            var val = $(this).val();
            var $customInput = $(this).closest('.form-group').find('input[name="custom_subject"]');
            if (val === 'Other') {
                $customInput.removeClass('d-none').focus();
            } else {
                $customInput.addClass('d-none').val('');
            }
        });

        // Init Add Education subjects on modal open if degree is selected
        $('#modal-add-education').on('shown.bs.modal', function() {
            var currentDeg = $('#add_education_degree').val();
            if (currentDeg && $('#add_education_subject option').length <= 1) {
                populateSubjectsForDegree(currentDeg, $('#add_education_subject'), '');
            }
        });

        // Delete Education Button Handler
        $(document).on('click', '.delete-edu-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url');

            if (confirm('{{ translate("Are you sure you want to delete this education record?") }}')) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#edu-item-' + id).slideUp(300, function() {
                            $(this).remove();
                            recalculateProfileCompleteness();
                            if ($('#education-card-list .profile-education-item').length === 0) {
                                location.reload();
                            }
                        });
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('success', '{{ translate("Education record deleted successfully!") }}');
                        }
                    },
                    error: function() {
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('danger', '{{ translate("Failed to delete education record.") }}');
                        } else {
                            alert('{{ translate("Failed to delete education record.") }}');
                        }
                    }
                });
            }
        });
    });

    // Dynamic DOM updates without page refresh
    function updateCardFromFormData(cardId, $form) {
        var $card = $('#' + cardId);
        if (!$card.length) return;

        var isCompleted = false;

        if (cardId === 'card-1') {
            var firstName = ($form.find('input[name="first_name"]').val() || '').trim();
            var lastName = ($form.find('input[name="last_name"]').val() || '').trim();
            $card.find('[data-field="full_name"]').text((firstName || lastName) ? (firstName + ' ' + lastName).trim() : '—');
            
            var genderVal = $form.find('select[name="gender"]').val();
            $card.find('[data-field="gender"]').text(genderVal == '1' ? '{{ translate("Male") }}' : (genderVal == '2' ? '{{ translate("Female") }}' : '—'));
            
            var dob = ($form.find('input[name="date_of_birth"]').val() || '').trim();
            $card.find('[data-field="birthday"]').text(dob || '—');
            $('[data-field="birthday"]').text(dob || '—');
            
            var phone = ($form.find('input[name="phone"]').val() || '').trim();
            $card.find('[data-field="phone"]').text(phone || '—');
            
            var email = ($form.find('input[name="email"]').val() || '').trim();
            $card.find('[data-field="email"]').text(email || '—');
            
            var maritalText = $form.find('select[name="marital_status"] option:selected').text();
            var maritalVal = $form.find('select[name="marital_status"]').val();
            $card.find('[data-field="marital_status"]').text((maritalVal && maritalText.indexOf('Choose') === -1) ? maritalText : '—');
            
            var isNever = (maritalText.toLowerCase().indexOf('never married') !== -1 || maritalVal == '1');
            if (isNever) {
                $('#card-children-item').addClass('d-none');
            } else {
                $('#card-children-item').removeClass('d-none');
                var childrenVal = $form.find('input[name="children"]').val() || '0';
                $card.find('[data-field="children"]').text(childrenVal);
            }
            var salaryVal = $form.find('input[name="annual_salary_range"]').val();
            if (salaryVal !== undefined && salaryVal !== null && salaryVal.toString().trim() !== '') {
                var formattedSalary = !isNaN(salaryVal) ? Number(salaryVal).toLocaleString() : salaryVal;
                $card.find('[data-field="annual_salary_range"]').text(formattedSalary);
                $('#card-salary-item').removeClass('d-none');
            } else {
                $('#card-salary-item').addClass('d-none');
                $card.find('[data-field="annual_salary_range"]').text('');
            }

            // Completed only if first name, phone/email, gender, and birthday exist
            isCompleted = !!(firstName && (phone || email) && genderVal && dob);

        } else if (cardId === 'card-2') {
            var height = ($form.find('input[name="height"]').val() || '').trim();
            var weight = ($form.find('input[name="weight"]').val() || '').trim();
            var bodyType = ($form.find('input[name="body_type"]').val() || '').trim();
            var complexion = ($form.find('input[name="complexion"]').val() || '').trim();
            var bloodGroup = ($form.find('input[name="blood_group"]').val() || '').trim();
            var disability = ($form.find('input[name="disability"]').val() || '').trim();

            $('[data-field="height"]').text(height ? (height + ' ft') : '—');
            $('[data-field="weight"]').text(weight ? (weight + ' kg') : '—');
            $('[data-field="body_type"]').text(bodyType || '—');
            $('[data-field="complexion"]').text(complexion || '—');
            $('[data-field="blood_group"]').text(bloodGroup || '—');
            $('[data-field="disability"]').text(disability || 'None');

            isCompleted = !!(height && (weight || complexion || bloodGroup));

        } else if (cardId === 'card-3') {
            var countryText = $form.find('select[name="present_country_id"] option:selected').text();
            var countryVal = $form.find('select[name="present_country_id"]').val();
            var stateText = $form.find('select[name="present_state_id"] option:selected').text();
            var stateVal = $form.find('select[name="present_state_id"]').val();
            var cityText = $form.find('select[name="present_city_id"] option:selected').text();
            var cityVal = $form.find('select[name="present_city_id"]').val();
            var postalCode = ($form.find('input[name="present_postal_code"]').val() || '').trim();
            var homeRoad = ($form.find('input[name="present_home_road"]').val() || '').trim();

            $card.find('[data-field="country"]').text((countryVal && countryText !== '{{ translate("Select Country") }}') ? countryText : '—');
            $card.find('[data-field="state"]').text((stateVal && stateText !== '{{ translate("Select State") }}') ? stateText : '—');
            $card.find('[data-field="city"]').text((cityVal && cityText !== '{{ translate("Select City") }}') ? cityText : '—');
            $card.find('[data-field="postal_code"]').text(postalCode || '—');
            $card.find('[data-field="home_road"]').text(homeRoad || '—');

            var fullAddress = (homeRoad ? homeRoad + ', ' : '') +
                              (cityVal && cityText !== '{{ translate("Select City") }}' ? cityText + ', ' : '') +
                              (stateVal && stateText !== '{{ translate("Select State") }}' ? stateText + ', ' : '') +
                              (countryVal && countryText !== '{{ translate("Select Country") }}' ? countryText : '');
            $card.find('[data-field="present_address"]').text(fullAddress || '—');

            isCompleted = !!(cityVal && (homeRoad || postalCode));

        } else if (cardId === 'card-4') {
            var degree = ($form.find('input[name="degree"]').val() || '').trim();
            var institution = ($form.find('input[name="institution"]').val() || '').trim();
            var startYear = ($form.find('input[name="education_start"]').val() || '').trim();
            var passingYear = ($form.find('input[name="education_end"]').val() || '').trim();
            if (degree) $card.find('[data-field="highest_degree"]').text(degree);
            if (institution) $card.find('[data-field="institution"]').text(institution);
            if (startYear) $card.find('[data-field="start_year"]').text(startYear);
            if (passingYear) $card.find('[data-field="passing_year"]').text(passingYear);

            isCompleted = !!degree;

        } else if (cardId === 'card-5') {
            var status = $form.find('select[name="profession_status"]').val() || '';
            var jobType = '';
            var occupation = '';
            var company = '';
            var designation = '';

            if (status === 'Job Holder') {
                jobType = ($form.find('select[name="job_type"]').val() || '').trim();
                occupation = ($form.find('input[name="occupation"]').val() || '').trim();
                company = ($form.find('input[name="company"]').val() || '').trim();
                designation = ($form.find('input[name="designation"]').val() || '').trim();
            } else if (status === 'Business') {
                jobType = ($form.find('input[name="business_job_type"]').val() || '').trim();
                occupation = ($form.find('input[name="business_occupation"]').val() || '').trim();
                company = ($form.find('input[name="business_company"]').val() || '').trim();
                designation = ($form.find('input[name="business_designation"]').val() || '').trim();
            } else if (status === 'Student') {
                occupation = 'Student';
                company = ($form.find('input[name="student_institution"]').val() || '').trim();
                designation = ($form.find('input[name="student_subject"]').val() || '').trim();
            } else if (status === 'Freelancer' || status === 'Other') {
                occupation = ($form.find('input[name="other_occupation"]').val() || '').trim();
                company = ($form.find('input[name="other_company"]').val() || '').trim();
            } else if (status === 'Not Employed' || status === 'Homemaker') {
                occupation = status;
            }

            $card.find('[data-field="profession_status"]').text(status || '—');

            if (jobType) {
                $('#card-career-job-type-item').removeClass('d-none');
                $card.find('[data-field="job_type"]').text(jobType);
            } else {
                $('#card-career-job-type-item').addClass('d-none');
                $card.find('[data-field="job_type"]').text('—');
            }

            if (occupation && status !== 'Not Employed' && status !== 'Homemaker') {
                $('#card-career-occupation-item').removeClass('d-none');
                $card.find('[data-field="occupation"]').text(occupation);
            } else {
                $('#card-career-occupation-item').addClass('d-none');
                $card.find('[data-field="occupation"]').text('—');
            }

            if (company && status !== 'Not Employed' && status !== 'Homemaker') {
                $('#card-career-company-item').removeClass('d-none');
                $('#card-career-company-label').text(status === 'Student' ? '{{ translate("Institution / University") }}' : '{{ translate("Company / Organization") }}');
                $card.find('[data-field="company"]').text(company);
            } else {
                $('#card-career-company-item').addClass('d-none');
                $card.find('[data-field="company"]').text('—');
            }

            if (designation && status !== 'Not Employed' && status !== 'Homemaker') {
                $('#card-career-designation-item').removeClass('d-none');
                $('#card-career-designation-label').text(status === 'Student' ? '{{ translate("Department / Subject") }}' : '{{ translate("Designation (পদবি)") }}');
                $card.find('[data-field="designation"]').text(designation);
            } else {
                $('#card-career-designation-item').addClass('d-none');
                $card.find('[data-field="designation"]').text('—');
            }

            isCompleted = !!(status && (status === 'Not Employed' || status === 'Homemaker' || status === 'Student' || jobType || occupation || company || designation));

        } else if (cardId === 'card-7') {
            var motherTongue = ($form.find('[name="mothere_tongue"]').val() || '').trim();
            var knownLangs = ($form.find('[name="known_languages"]').val() || '').trim();
            $card.find('[data-field="mother_tongue"]').text(motherTongue || '—');
            $card.find('[data-field="known_languages"]').text(knownLangs || '—');

            isCompleted = !!(motherTongue && knownLangs);

        } else if (cardId === 'card-8') {
            var hobbies = ($form.find('[name="hobbies"]').val() || '').trim();
            var interests = ($form.find('[name="interests"]').val() || '').trim();
            $card.find('[data-field="hobbies"]').text(hobbies || '—');
            $card.find('[data-field="interests"]').text(interests || '—');

            isCompleted = !!(hobbies || interests);

        } else if (cardId === 'card-9') {
            var father = ($form.find('input[name="father"]').val() || '').trim();
            var fatherSec = $form.find('select[name="father_sector"]').val() || '';
            var fatherPos = ($form.find('input[name="father_position"]').val() || '').trim();
            var fatherOcc = (fatherSec && fatherPos) ? (fatherSec + ' - ' + fatherPos) : (fatherSec || fatherPos || ($form.find('input[name="father_occupation"]').val() || '').trim());

            var mother = ($form.find('input[name="mother"]').val() || '').trim();
            var motherSec = $form.find('select[name="mother_sector"]').val() || '';
            var motherPos = ($form.find('input[name="mother_position"]').val() || '').trim();
            var motherOcc = (motherSec && motherPos && motherSec.toLowerCase() !== 'homemaker') ? (motherSec + ' - ' + motherPos) : (motherSec || motherPos || ($form.find('input[name="mother_occupation"]').val() || '').trim());

            var brothers = parseInt($form.find('select[name="no_of_brothers"]').val()) || 0;
            var sisters = parseInt($form.find('select[name="no_of_sisters"]').val()) || 0;
            var broStatus = $form.find('select[name="brother_status"]').val() || '';
            var broDetail = ($form.find('input[name="brother_detail"]').val() || '').trim();
            var sisStatus = $form.find('select[name="sister_status"]').val() || '';
            var sisDetail = ($form.find('input[name="sister_detail"]').val() || '').trim();

            $card.find('[data-field="father"]').text(father || '—');
            $card.find('[data-field="father_occupation"]').text(fatherOcc || '—');
            $card.find('[data-field="mother"]').text(mother || '—');
            $card.find('[data-field="mother_occupation"]').text(motherOcc || '—');
            $card.find('[data-field="no_of_brothers"]').text(brothers);
            $card.find('[data-field="no_of_sisters"]').text(sisters);

            if (brothers > 0 && broStatus) {
                $('#card-brother-status-field').removeClass('d-none');
                $card.find('[data-field="brother_profession"]').text(broStatus + (broDetail ? ' (' + broDetail + ')' : ''));
            } else {
                $('#card-brother-status-field').addClass('d-none');
            }

            if (sisters > 0 && sisStatus) {
                $('#card-sister-status-field').removeClass('d-none');
                $card.find('[data-field="sister_profession"]').text(sisStatus + (sisDetail ? ' (' + sisDetail + ')' : ''));
            } else {
                $('#card-sister-status-field').addClass('d-none');
            }

            isCompleted = !!(father && mother);

        } else if (cardId === 'card-10') {
            var smoke = $form.find('select[name="smoke"]').val();
            var drink = $form.find('select[name="drink"]').val();
            var diet = $form.find('select[name="diet"]').val();
            var livingWith = ($form.find('input[name="living_with"]').val() || '').trim();
            $card.find('[data-field="smoke"]').text(smoke ? (smoke.charAt(0).toUpperCase() + smoke.slice(1)) : '—');
            $card.find('[data-field="drink"]').text(drink ? (drink.charAt(0).toUpperCase() + drink.slice(1)) : '—');
            $card.find('[data-field="diet"]').text(diet ? (diet === 'yes' ? 'Vegetarian' : 'Non-Vegetarian') : '—');
            $card.find('[data-field="living_with"]').text(livingWith || '—');

            isCompleted = !!(diet && livingWith);

        } else if (cardId === 'card-11') {
            var resCountry = $form.find('select[name="recidency_country_id"] option:selected').text();
            var resCountryVal = $form.find('select[name="recidency_country_id"]').val();
            var permCountry = $form.find('select[name="birth_country_id"] option:selected').text();
            var permCountryVal = $form.find('select[name="birth_country_id"]').val();
            var growCountry = $form.find('select[name="growup_country_id"] option:selected').text();
            var growCountryVal = $form.find('select[name="growup_country_id"]').val();
            var immStatus = ($form.find('input[name="immigration_status"]').val() || '').trim();

            $card.find('[data-field="current_residence"]').text((resCountryVal && resCountry !== '{{ translate("Select Country") }}') ? resCountry : '—');
            $card.find('[data-field="permanent_residence"]').text((permCountryVal && permCountry !== '{{ translate("Select Country") }}') ? permCountry : '—');
            $card.find('[data-field="growup_country"]').text((growCountryVal && growCountry !== '{{ translate("Select Country") }}') ? growCountry : '—');
            $card.find('[data-field="immigration_status"]').text(immStatus || '—');

            isCompleted = !!(resCountryVal && (permCountryVal || immStatus));

        } else if (cardId === 'card-12') {
            var religion = $form.find('select[name="member_religion_id"] option:selected').text();
            var relVal = $form.find('select[name="member_religion_id"]').val();
            var caste = $form.find('select[name="member_caste_id"] option:selected').text();
            var casteVal = $form.find('select[name="member_caste_id"]').val();
            var subCaste = $form.find('select[name="member_sub_caste_id"] option:selected').text();
            var fv = $form.find('select[name="family_value_id"] option:selected').text();
            var fvVal = $form.find('select[name="family_value_id"]').val();

            $card.find('[data-field="religion"]').text((relVal && religion !== '{{ translate("Select Religion") }}') ? religion : '—');
            $card.find('[data-field="family_values"]').text((fvVal && fv !== '{{ translate("Select One") }}') ? fv : '—');

            var relLower = (religion || '').toLowerCase();
            if (relLower.indexOf('islam') !== -1) {
                $('#card-caste-field').show();
                $('#card-caste-label').text('{{ translate("Sect / Mazhab") }}');
                $card.find('[data-field="caste"]').text((casteVal && caste.indexOf('Select') === -1) ? caste : '—');
                if (subCaste && subCaste.indexOf('Select') === -1 && subCaste.trim() !== '') {
                    $('#card-subcaste-field').show();
                    $('#card-subcaste-label').text('{{ translate("Sub-Sect") }}');
                    $card.find('[data-field="sect"]').text(subCaste);
                } else {
                    $('#card-subcaste-field').hide();
                }
            } else if (relLower.indexOf('hindu') !== -1) {
                $('#card-caste-field').show();
                $('#card-caste-label').text('{{ translate("Caste") }}');
                $card.find('[data-field="caste"]').text((casteVal && caste.indexOf('Select') === -1) ? caste : '—');
                $('#card-subcaste-field').show();
                $('#card-subcaste-label').text('{{ translate("Sub-Caste / Gotra") }}');
                $card.find('[data-field="sect"]').text((subCaste && subCaste.indexOf('Select') === -1) ? subCaste : '—');
            } else {
                $('#card-caste-field').show();
                $('#card-caste-label').text('{{ translate("Denomination / Sect") }}');
                $card.find('[data-field="caste"]').text((casteVal && caste.indexOf('Select') === -1) ? caste : '—');
                $('#card-subcaste-field').hide();
            }

            isCompleted = !!(relVal && casteVal);

        } else if (cardId === 'card-13') {
            var permCountry = $form.find('select[name="permanent_country_id"] option:selected').text();
            var permCountryVal = $form.find('select[name="permanent_country_id"]').val();
            var permState = $form.find('select[name="permanent_state_id"] option:selected').text();
            var permStateVal = $form.find('select[name="permanent_state_id"]').val();
            var permCity = $form.find('select[name="permanent_city_id"] option:selected').text();
            var permCityVal = $form.find('select[name="permanent_city_id"]').val();
            var permPostal = ($form.find('input[name="permanent_postal_code"]').val() || '').trim();
            var permHomeRoad = ($form.find('input[name="permanent_home_road"]').val() || '').trim();

            $card.find('[data-field="country"]').text((permCountryVal && permCountry !== '{{ translate("Select Country") }}') ? permCountry : '—');
            $card.find('[data-field="division"]').text((permStateVal && permState !== '{{ translate("Select State") }}') ? permState : '—');
            $card.find('[data-field="district"]').text((permCityVal && permCity !== '{{ translate("Select City") }}') ? permCity : '—');
            $card.find('[data-field="postal_code"]').text(permPostal || '—');
            $card.find('[data-field="home_road"]').text(permHomeRoad || '—');

            var permFullAddress = (permHomeRoad ? permHomeRoad + ', ' : '') +
                                  (permCityVal && permCity !== '{{ translate("Select City") }}' ? permCity + ', ' : '') +
                                  (permStateVal && permState !== '{{ translate("Select State") }}' ? permState + ', ' : '') +
                                  (permCountryVal && permCountry !== '{{ translate("Select Country") }}' ? permCountry : '');
            $card.find('[data-field="address"]').text(permFullAddress || '—');

            isCompleted = !!(permCityVal && (permHomeRoad || permPostal));

        } else if (cardId === 'card-14') {
            var intro = ($form.find('textarea[name="introduction"]').val() || '').trim();
            var expectations = ($form.find('textarea[name="expectations"]').val() || '').trim();
            $card.find('[data-field="about_yourself"]').text(intro || '—');
            $card.find('[data-field="expectations"]').text(expectations || '—');

            isCompleted = !!(intro && expectations);

        } else if (cardId === 'card-pref-basic') {
            var heightVal = ($form.find('input[name="partner_height"]').val() || '').trim();
            $('[data-field="pref_height"]').text(heightVal ? (heightVal + ' ft') : '—');

            var weightVal = ($form.find('input[name="partner_weight"]').val() || '').trim();
            $('[data-field="pref_weight"]').text(weightVal ? (weightVal + ' kg') : '—');

            var msVal = $form.find('select[name="partner_marital_status"]').val();
            var msText = $form.find('select[name="partner_marital_status"] option:selected').text();
            $('[data-field="pref_marital_status"]').text((msVal && msText.indexOf('Choose') === -1 && msText.indexOf('Select') === -1) ? msText : '—');

            var childrenVal = $form.find('select[name="partner_children_acceptable"]').val();
            $('[data-field="pref_children"]').text(childrenVal ? (childrenVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : childrenVal.charAt(0).toUpperCase() + childrenVal.slice(1)) : '—');

            var compVal = ($form.find('input[name="pertner_complexion"]').val() || '').trim();
            $('[data-field="pref_complexion"]').text(compVal || '—');

            var bodyVal = ($form.find('input[name="partner_body_type"]').val() || '').trim();
            $('[data-field="pref_body_type"]').text(bodyVal || '—');

            var hasBasic = !!(heightVal || weightVal || msVal || compVal || bodyVal);
            $('#card-pref-basic').attr('data-completed', hasBasic ? '1' : '0');
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-pref-religion') {
            var relVal = $form.find('select[name="partner_religion_id"]').val();
            var relText = $form.find('select[name="partner_religion_id"] option:selected').text().trim();
            $('[data-field="pref_religion"]').text((relVal && relText.indexOf('Select') === -1 && relText.indexOf('Any') === -1) ? relText : '—');

            var relLower = (relText || '').toLowerCase();
            var isHindu = (relLower.indexOf('hindu') !== -1 || relVal == '2');
            var isIslam = (relLower.indexOf('islam') !== -1 || relVal == '1');
            var isChristian = (relLower.indexOf('christian') !== -1 || relVal == '3');
            var isBuddhist = (relLower.indexOf('buddh') !== -1 || relVal == '4');

            // Dynamic label and visibility on the card
            if (isIslam) {
                $('#card-pref-caste-label').text('{{ translate("Sect / Mazhab") }}');
                $('#card-pref-subcaste-item').addClass('d-none');
                $('#card-pref-manglik-item').addClass('d-none');
            } else if (isHindu) {
                $('#card-pref-caste-label').text('{{ translate("Caste") }}');
                $('#card-pref-subcaste-item').removeClass('d-none');
                $('#card-pref-manglik-item').removeClass('d-none');
            } else if (isChristian) {
                $('#card-pref-caste-label').text('{{ translate("Denomination") }}');
                $('#card-pref-subcaste-item').addClass('d-none');
                $('#card-pref-manglik-item').addClass('d-none');
            } else if (isBuddhist) {
                $('#card-pref-caste-label').text('{{ translate("Sect / Community") }}');
                $('#card-pref-subcaste-item').addClass('d-none');
                $('#card-pref-manglik-item').addClass('d-none');
            } else {
                $('#card-pref-caste-label').text('{{ translate("Sect / Mazhab / Caste") }}');
                $('#card-pref-subcaste-item').addClass('d-none');
                $('#card-pref-manglik-item').addClass('d-none');
            }

            var casteVal = $form.find('select[name="partner_caste_id"]').val();
            var casteText = $form.find('select[name="partner_caste_id"] option:selected').text();
            $('[data-field="pref_caste"]').text((casteVal && casteText.indexOf('Any') === -1 && casteText.indexOf('Select') === -1) ? casteText : '—');

            var subCasteVal = $form.find('select[name="partner_sub_caste_id"]').val();
            var subCasteText = $form.find('select[name="partner_sub_caste_id"] option:selected').text();
            $('[data-field="pref_sub_caste"]').text((subCasteVal && subCasteText.indexOf('Any') === -1 && subCasteText.indexOf('Select') === -1 && isHindu) ? subCasteText : '—');

            var langVal = $form.find('select[name="language_id"]').val();
            var langText = $form.find('select[name="language_id"] option:selected').text();
            $('[data-field="pref_language"]').text((langVal && langText.indexOf('Select') === -1 && langText.indexOf('Any') === -1) ? langText : '—');

            var famVal = $form.find('select[name="family_value_id"]').val();
            var famText = $form.find('select[name="family_value_id"] option:selected').text();
            $('[data-field="pref_family_value"]').text((famVal && famText.indexOf('Select') === -1 && famText.indexOf('Any') === -1) ? famText : '—');

            var pVal = ($form.find('input[name="partner_personal_value"]').val() || '').trim();
            $('[data-field="pref_personal_value"]').text(pVal || '—');

            var manglikVal = $form.find('select[name="partner_manglik"]').val();
            $('[data-field="pref_manglik"]').text((manglikVal && isHindu) ? (manglikVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : manglikVal.charAt(0).toUpperCase() + manglikVal.slice(1)) : '—');

            var hasRel = !!(relVal || langVal || famVal || pVal || (isHindu && manglikVal));
            $('#card-pref-religion').attr('data-completed', hasRel ? '1' : '0');
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-pref-education') {
            var eduVal = ($form.find('input[name="pertner_education"]').val() || '').trim();
            $('[data-field="pref_education"]').text(eduVal || '—');

            var profVal = ($form.find('input[name="partner_profession"]').val() || '').trim();
            $('[data-field="pref_profession"]').text(profVal || '—');

            var hasEdu = !!(eduVal || profVal);
            $('#card-pref-education').attr('data-completed', hasEdu ? '1' : '0');
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-pref-location') {
            var resCVal = $form.find('select[name="residence_country_id"]').val();
            var resCText = $form.find('select[name="residence_country_id"] option:selected').text();
            $('[data-field="pref_residence_country"]').text((resCVal && resCText.indexOf('Select') === -1) ? resCText : '—');

            var prefCVal = $form.find('select[name="partner_country_id"]').val();
            var prefCText = $form.find('select[name="partner_country_id"] option:selected').text();
            $('[data-field="pref_preferred_country"]').text((prefCVal && prefCText.indexOf('Select') === -1) ? prefCText : '—');

            var prefSVal = $form.find('select[name="partner_state_id"]').val();
            var prefSText = $form.find('select[name="partner_state_id"] option:selected').text();
            $('[data-field="pref_preferred_state"]').text((prefSVal && prefSText.indexOf('Any') === -1 && prefSText.indexOf('Select') === -1) ? prefSText : '—');

            var hasLoc = !!(resCVal || prefCVal || prefSVal);
            $('#card-pref-location').attr('data-completed', hasLoc ? '1' : '0');
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-pref-lifestyle') {
            var dietVal = $form.find('select[name="partner_diet"]').val();
            $('[data-field="pref_diet"]').text(dietVal ? (dietVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : (dietVal === 'non_veg' ? '{{ translate("Non-Vegetarian") }}' : (dietVal === 'veg' ? '{{ translate("Vegetarian") }}' : '{{ translate("Both") }}'))) : '—');

            var smokeVal = $form.find('select[name="smoking_acceptable"]').val();
            $('[data-field="pref_smoking"]').text(smokeVal ? (smokeVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : smokeVal.charAt(0).toUpperCase() + smokeVal.slice(1)) : '—');

            var drinkVal = $form.find('select[name="drinking_acceptable"]').val();
            $('[data-field="pref_drinking"]').text(drinkVal ? (drinkVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : drinkVal.charAt(0).toUpperCase() + drinkVal.slice(1)) : '—');

            var hasLife = !!(dietVal || smokeVal || drinkVal);
            $('#card-pref-lifestyle').attr('data-completed', hasLife ? '1' : '0');
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-pref-general') {
            var genText = ($form.find('textarea[name="general"]').val() || '').trim();
            $('[data-field="pref_general"]').text(genText || '—');
            $('#card-14').find('[data-field="expectations"]').text(genText || '—');

            var hasGen = !!(genText && genText.length > 0);
            $('#card-pref-general').attr('data-completed', hasGen ? '1' : '0');

            // Also synchronize Profile Card 14 completeness
            var currentIntro = ($('#card-14 [data-field="about_yourself"]').text() || '').trim();
            var isCard14Done = !!(currentIntro && currentIntro !== '—' && hasGen);
            $('#card-14').attr('data-completed', isCard14Done ? '1' : '0');

            recalculateProfileCompleteness();
            recalculatePartnerPrefCompleteness();
            return;

        } else if (cardId === 'card-partner-pref') {
            var genText = ($form.find('textarea[name="general"]').val() || '').trim();
            $('[data-field="pref_general"]').text(genText || '—');
            $('#card-14').find('[data-field="expectations"]').text(genText || '—');

            var heightVal = ($form.find('input[name="partner_height"]').val() || '').trim();
            $('[data-field="pref_height"]').text(heightVal ? (heightVal + ' ft') : '—');

            var weightVal = ($form.find('input[name="partner_weight"]').val() || '').trim();
            $('[data-field="pref_weight"]').text(weightVal ? (weightVal + ' kg') : '—');

            var msVal = $form.find('select[name="partner_marital_status"]').val();
            var msText = $form.find('select[name="partner_marital_status"] option:selected').text();
            $('[data-field="pref_marital_status"]').text((msVal && msText.indexOf('Choose') === -1) ? msText : '—');

            var childrenVal = $form.find('select[name="partner_children_acceptable"]').val();
            $('[data-field="pref_children"]').text(childrenVal ? (childrenVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : childrenVal.charAt(0).toUpperCase() + childrenVal.slice(1)) : '—');

            var compVal = ($form.find('input[name="pertner_complexion"]').val() || '').trim();
            $('[data-field="pref_complexion"]').text(compVal || '—');

            var bodyVal = ($form.find('input[name="partner_body_type"]').val() || '').trim();
            $('[data-field="pref_body_type"]').text(bodyVal || '—');

            var relVal = $form.find('select[name="partner_religion_id"]').val();
            var relText = $form.find('select[name="partner_religion_id"] option:selected').text();
            $('[data-field="pref_religion"]').text((relVal && relText.indexOf('Select') === -1) ? relText : '—');

            var casteVal = $form.find('select[name="partner_caste_id"]').val();
            var casteText = $form.find('select[name="partner_caste_id"] option:selected').text();
            $('[data-field="pref_caste"]').text((casteVal && casteText.indexOf('Any') === -1) ? casteText : '—');

            var subCasteVal = $form.find('select[name="partner_sub_caste_id"]').val();
            var subCasteText = $form.find('select[name="partner_sub_caste_id"] option:selected').text();
            $('[data-field="pref_sub_caste"]').text((subCasteVal && subCasteText.indexOf('Any') === -1) ? subCasteText : '—');

            var langVal = $form.find('select[name="language_id"]').val();
            var langText = $form.find('select[name="language_id"] option:selected').text();
            $('[data-field="pref_language"]').text((langVal && langText.indexOf('Select') === -1) ? langText : '—');

            var famVal = $form.find('select[name="family_value_id"]').val();
            var famText = $form.find('select[name="family_value_id"] option:selected').text();
            $('[data-field="pref_family_value"]').text((famVal && famText.indexOf('Select') === -1) ? famText : '—');

            var pVal = ($form.find('input[name="partner_personal_value"]').val() || '').trim();
            $('[data-field="pref_personal_value"]').text(pVal || '—');

            var eduVal = ($form.find('input[name="pertner_education"]').val() || '').trim();
            $('[data-field="pref_education"]').text(eduVal || '—');

            var profVal = ($form.find('input[name="partner_profession"]').val() || '').trim();
            $('[data-field="pref_profession"]').text(profVal || '—');

            var resCVal = $form.find('select[name="residence_country_id"]').val();
            var resCText = $form.find('select[name="residence_country_id"] option:selected').text();
            $('[data-field="pref_residence_country"]').text((resCVal && resCText.indexOf('Select') === -1) ? resCText : '—');

            var prefCVal = $form.find('select[name="partner_country_id"]').val();
            var prefCText = $form.find('select[name="partner_country_id"] option:selected').text();
            $('[data-field="pref_preferred_country"]').text((prefCVal && prefCText.indexOf('Select') === -1) ? prefCText : '—');

            var prefSVal = $form.find('select[name="partner_state_id"]').val();
            var prefSText = $form.find('select[name="partner_state_id"] option:selected').text();
            $('[data-field="pref_preferred_state"]').text((prefSVal && prefSText.indexOf('Any') === -1) ? prefSText : '—');

            var dietVal = $form.find('select[name="partner_diet"]').val();
            $('[data-field="pref_diet"]').text(dietVal ? (dietVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : (dietVal === 'non_veg' ? '{{ translate("Non-Vegetarian") }}' : (dietVal === 'veg' ? '{{ translate("Vegetarian") }}' : '{{ translate("Both") }}'))) : '—');

            var smokeVal = $form.find('select[name="smoking_acceptable"]').val();
            $('[data-field="pref_smoking"]').text(smokeVal ? (smokeVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : smokeVal.charAt(0).toUpperCase() + smokeVal.slice(1)) : '—');

            var drinkVal = $form.find('select[name="drinking_acceptable"]').val();
            $('[data-field="pref_drinking"]').text(drinkVal ? (drinkVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : drinkVal.charAt(0).toUpperCase() + drinkVal.slice(1)) : '—');

            var manglikVal = $form.find('select[name="partner_manglik"]').val();
            $('[data-field="pref_manglik"]').text(manglikVal ? (manglikVal === 'dose_not_matter' ? '{{ translate("Does Not Matter") }}' : manglikVal.charAt(0).toUpperCase() + manglikVal.slice(1)) : '—');

            // Partner Preferences individual cards completeness
            var hasBasic = !!(heightVal || weightVal || msVal || compVal || bodyVal);
            $('#card-pref-basic').attr('data-completed', hasBasic ? '1' : '0');

            var hasRel = !!(relVal || langVal || famVal);
            $('#card-pref-religion').attr('data-completed', hasRel ? '1' : '0');

            var hasEdu = !!(eduVal || profVal);
            $('#card-pref-education').attr('data-completed', hasEdu ? '1' : '0');

            var hasLoc = !!(resCVal || prefCVal);
            $('#card-pref-location').attr('data-completed', hasLoc ? '1' : '0');

            var hasLife = !!(dietVal || smokeVal || drinkVal);
            $('#card-pref-lifestyle').attr('data-completed', hasLife ? '1' : '0');

            var hasGen = !!(genText && genText.length > 0);
            $('#card-pref-general').attr('data-completed', hasGen ? '1' : '0');

            // Also synchronize Profile Card 14 completeness
            var currentIntro = ($('#card-14 [data-field="about_yourself"]').text() || '').trim();
            var isCard14Done = !!(currentIntro && currentIntro !== '—' && hasGen);
            $('#card-14').attr('data-completed', isCard14Done ? '1' : '0');

            recalculateProfileCompleteness();
            recalculatePartnerPrefCompleteness();
            return;
        }

        // Dynamically assign 1 if completed, or 0 if emptied / incomplete!
        $card.attr('data-completed', isCompleted ? '1' : '0');

        // Dynamically recalculate and animate status bar without reloading page
        recalculateProfileCompleteness();
    }

    function applyChecklistFilter(filter) {
        var $activeChecklist = (currentActiveScope === 'partner-preferences') ? $('#checklist-view-preferences') : $('#checklist-view-profile');
        $activeChecklist.find('.checklist-item-card').each(function() {
            var status = $(this).attr('data-status');
            if (filter === 'all' || !filter) {
                $(this).removeClass('is-hidden d-none');
            } else if (filter === status) {
                $(this).removeClass('is-hidden d-none');
            } else {
                $(this).addClass('is-hidden d-none');
            }
        });
    }

    // Live recalculation of profile completeness percentage across all 13 sections
    function recalculateProfileCompleteness() {
        var totalSections = 13;
        var completedCards = ['card-1', 'card-2', 'card-12', 'card-5', 'card-4', 'card-3', 'card-13', 'card-9', 'card-11', 'card-10', 'card-7', 'card-8', 'card-14'];
        var count = 0;
        completedCards.forEach(function(cid) {
            var isDone = ($('#' + cid).attr('data-completed') === '1');
            if (isDone) {
                count++;
            }
            // Update row in checklist modal
            var $row = $('#checklist-row-' + cid);
            if ($row.length) {
                if (isDone) {
                    $row.attr('data-status', 'completed');
                    $row.find('.checklist-item-icon-box').removeClass('bg-soft-warning text-warning').addClass('bg-soft-success text-success');
                    $row.find('.checklist-status-badge').removeClass('badge-soft-warning').addClass('badge-soft-success')
                        .html('<i class="las la-check-circle mr-1"></i> {{ translate("Completed") }}');
                    $row.find('.checklist-hint-text').addClass('d-none');
                    $row.find('.btn-trigger-section-edit').removeClass('btn-primary').addClass('btn-light text-secondary')
                        .html('<i class="las la-pen mr-1"></i> {{ translate("Edit") }}');
                } else {
                    $row.attr('data-status', 'incomplete');
                    $row.find('.checklist-item-icon-box').removeClass('bg-soft-success text-success').addClass('bg-soft-warning text-warning');
                    $row.find('.checklist-status-badge').removeClass('badge-soft-success').addClass('badge-soft-warning')
                        .html('<i class="las la-exclamation-circle mr-1"></i> {{ translate("Incomplete") }}');
                    $row.find('.checklist-hint-text').removeClass('d-none');
                    $row.find('.btn-trigger-section-edit').removeClass('btn-light text-secondary').addClass('btn-primary')
                        .html('<i class="las la-plus-circle mr-1"></i> {{ translate("Fill Up") }}');
                }
            }
        });

        var percent = Math.min(100, Math.round((count / totalSections) * 100));
        var missingCount = totalSections - count;
        profilePercentage = percent;

        if (currentActiveScope === 'my-profile') {
            $('#profile-percent-label').text(percent + '%');
            $('#profile-completeness-fill').attr('width', percent + '%').css('width', percent + '%');

            if (percent === 100) {
                $('.profile-completeness-check-icon').html('&#10004;');
            } else {
                $('.profile-completeness-check-icon').html('<i class="las la-tasks" style="font-size:11px;"></i>');
            }

            $('#checklist-percent-val').text(percent + '%');
            $('#checklist-percent-headline').text(percent + '%');
            $('#checklist-circular-fill').attr('stroke-dasharray', percent + ', 100');
            $('#checklist-hero-progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
            $('#checklist-completed-count').text(count);
            $('#checklist-missing-count').text(missingCount);
            $('#tab-completed-count').text(count);
            $('#tab-missing-count').text(missingCount);
            $('#tab-all-count').text(totalSections);
            $('#checklist-progress-text').text(count + '/' + totalSections + ' {{ translate("Sections") }}');
        }

        var currentFilter = $('.checklist-filter-btn.active').data('filter') || 'all';
        applyChecklistFilter(currentFilter);
    }

    // Live recalculation of partner preferences completeness percentage across all 6 sections
    function recalculatePartnerPrefCompleteness() {
        var completedCards = ['card-pref-basic', 'card-pref-religion', 'card-pref-education', 'card-pref-location', 'card-pref-lifestyle', 'card-pref-general'];
        var count = 0;
        completedCards.forEach(function(cid) {
            var isDone = ($('#' + cid).attr('data-completed') === '1');
            if (isDone) count++;

            var $row = $('#checklist-row-' + cid);
            if ($row.length) {
                if (isDone) {
                    $row.attr('data-status', 'completed');
                    $row.find('.checklist-item-icon-box').removeClass('bg-soft-warning text-warning').addClass('bg-soft-success text-success');
                    $row.find('.checklist-status-badge').removeClass('badge-soft-warning').addClass('badge-soft-success')
                        .html('<i class="las la-check-circle mr-1"></i> {{ translate("Completed") }}');
                    $row.find('.checklist-hint-text').addClass('d-none');
                    $row.find('.btn-trigger-pref-edit, .btn-trigger-section-edit').removeClass('btn-primary').addClass('btn-light text-secondary')
                        .html('<i class="las la-pen mr-1"></i> {{ translate("Edit") }}');
                } else {
                    $row.attr('data-status', 'incomplete');
                    $row.find('.checklist-item-icon-box').removeClass('bg-soft-success text-success').addClass('bg-soft-warning text-warning');
                    $row.find('.checklist-status-badge').removeClass('badge-soft-success').addClass('badge-soft-warning')
                        .html('<i class="las la-exclamation-circle mr-1"></i> {{ translate("Incomplete") }}');
                    $row.find('.checklist-hint-text').removeClass('d-none');
                    $row.find('.btn-trigger-pref-edit, .btn-trigger-section-edit').removeClass('btn-light text-secondary').addClass('btn-primary')
                        .html('<i class="las la-plus-circle mr-1"></i> {{ translate("Fill Up") }}');
                }
            }
        });

        var percent = Math.min(100, Math.round((count / prefTotalSections) * 100));
        var missingCount = prefTotalSections - count;
        prefPercentage = percent;

        if (currentActiveScope === 'partner-preferences') {
            $('#profile-percent-label').text(percent + '%');
            $('#profile-completeness-fill').attr('width', percent + '%').css('width', percent + '%');

            if (percent === 100) {
                $('.profile-completeness-check-icon').html('&#10004;');
            } else {
                $('.profile-completeness-check-icon').html('<i class="las la-tasks" style="font-size:11px;"></i>');
            }

            $('#checklist-percent-val').text(percent + '%');
            $('#checklist-percent-headline').text(percent + '%');
            $('#checklist-circular-fill').attr('stroke-dasharray', percent + ', 100');
            $('#checklist-hero-progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
            $('#checklist-completed-count').text(count);
            $('#checklist-missing-count').text(missingCount);
            $('#tab-completed-count').text(count);
            $('#tab-missing-count').text(missingCount);
            $('#tab-all-count').text(prefTotalSections);
            $('#checklist-progress-text').text(count + '/' + prefTotalSections + ' {{ translate("Sections") }}');
        }

        var currentFilter = $('.checklist-filter-btn.active').data('filter') || 'all';
        applyChecklistFilter(currentFilter);
    }

    // Ajax dependent location dropdowns
    function get_states_by_country_for_present() {
        var country_id = $('#present_country_id').val();
        if (!country_id) return;
        $.post('{{ route('states.get_state_by_country') }}', {
            _token: '{{ csrf_token() }}',
            country_id: country_id
        }, function(data) {
            $('#present_state_id').html('<option value="">{{ translate("Select State") }}</option>');
            for (var i = 0; i < data.length; i++) {
                $('#present_state_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            if ('{{ $present_state_id }}') {
                $('#present_state_id').val('{{ $present_state_id }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            get_cities_by_state_for_present();
        });
    }

    function get_cities_by_state_for_present() {
        var state_id = $('#present_state_id').val();
        if (!state_id) return;
        $.post('{{ route('cities.get_cities_by_state') }}', {
            _token: '{{ csrf_token() }}',
            state_id: state_id
        }, function(data) {
            $('#present_city_id').html('<option value="">{{ translate("Select City") }}</option>');
            for (var i = 0; i < data.length; i++) {
                $('#present_city_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            if ('{{ $present_city_id }}') {
                $('#present_city_id').val('{{ $present_city_id }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }

    function get_states_by_country_for_permanent() {
        var country_id = $('#permanent_country_id').val();
        if (!country_id) return;
        $.post('{{ route('states.get_state_by_country') }}', {
            _token: '{{ csrf_token() }}',
            country_id: country_id
        }, function(data) {
            $('#permanent_state_id').html('<option value="">{{ translate("Select State") }}</option>');
            for (var i = 0; i < data.length; i++) {
                $('#permanent_state_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            if ('{{ $permanent_state_id }}') {
                $('#permanent_state_id').val('{{ $permanent_state_id }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            get_cities_by_state_for_permanent();
        });
    }

    function get_cities_by_state_for_permanent() {
        var state_id = $('#permanent_state_id').val();
        if (!state_id) return;
        $.post('{{ route('cities.get_cities_by_state') }}', {
            _token: '{{ csrf_token() }}',
            state_id: state_id
        }, function(data) {
            $('#permanent_city_id').html('<option value="">{{ translate("Select City") }}</option>');
            for (var i = 0; i < data.length; i++) {
                $('#permanent_city_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            if ('{{ $permanent_city_id }}') {
                $('#permanent_city_id').val('{{ $permanent_city_id }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }

    function handleReligionFields() {
        var $relSelect = $('#modal_member_religion_id');
        if (!$relSelect.length) return;
        var relText = $relSelect.find('option:selected').text().trim().toLowerCase();
        var relVal = $relSelect.val();

        if (relText === 'islam' || relVal == '1') {
            // Islam: Only show Sect / Mazhab (Sunni, Shia, etc.), Hide Sub-Sect
            $('#group-member-caste').removeClass('d-none');
            $('#group-member-sub-caste').addClass('d-none');
            $('#label-member-caste').text('{{ translate("Sect / Mazhab") }}');
            $('#modal_member_sub_caste_id').val('');
        } else if (relText === 'hinduism' || relVal == '2') {
            // Hinduism: Show Caste and Sub-Caste / Gotra
            $('#group-member-caste').removeClass('d-none');
            $('#group-member-sub-caste').removeClass('d-none');
            $('#label-member-caste').text('{{ translate("Caste") }}');
            $('#label-member-sub-caste').text('{{ translate("Sub-Caste / Gotra") }}');
        } else if (relText === 'christianity' || relVal == '3') {
            // Christianity: Show Denomination, Hide Sub-caste
            $('#group-member-caste').removeClass('d-none');
            $('#group-member-sub-caste').addClass('d-none');
            $('#label-member-caste').text('{{ translate("Denomination") }}');
            $('#modal_member_sub_caste_id').val('');
        } else if (relText === 'buddhism' || relVal == '4') {
            // Buddhism: Show Sect / Community, Hide Sub-caste
            $('#group-member-caste').removeClass('d-none');
            $('#group-member-sub-caste').addClass('d-none');
            $('#label-member-caste').text('{{ translate("Sect / Community") }}');
            $('#modal_member_sub_caste_id').val('');
        } else {
            $('#group-member-caste').addClass('d-none');
            $('#group-member-sub-caste').addClass('d-none');
        }
    }

    function get_castes_by_religion() {
        handleReligionFields();
        var religion_id = $('#modal_member_religion_id').val();
        if (!religion_id) {
            $('#modal_member_caste_id').empty().append('<option value="">{{ translate("Select Option") }}</option>');
            $('#modal_member_sub_caste_id').empty().append('<option value="">{{ translate("Select Option") }}</option>');
            return;
        }
        $.post('{{ route('castes.get_caste_by_religion') }}', {
            _token: '{{ csrf_token() }}',
            religion_id: religion_id
        }, function(data) {
            var relText = $('#modal_member_religion_id option:selected').text().trim().toLowerCase();
            var placeholder = '{{ translate("Select Option") }}';
            if (relText === 'islam') placeholder = '{{ translate("Select Sect / Mazhab") }}';
            else if (relText === 'hinduism') placeholder = '{{ translate("Select Caste") }}';
            else if (relText === 'christianity') placeholder = '{{ translate("Select Denomination") }}';
            else if (relText === 'buddhism') placeholder = '{{ translate("Select Sect") }}';

            $('#modal_member_caste_id').html('<option value="">' + placeholder + '</option>');
            for (var i = 0; i < data.length; i++) {
                $('#modal_member_caste_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            if ('{{ $member_caste_id }}') {
                $('#modal_member_caste_id').val('{{ $member_caste_id }}');
            }
            get_sub_castes_by_caste();
        });
    }

    function get_sub_castes_by_caste() {
        var relText = $('#modal_member_religion_id option:selected').text().trim().toLowerCase();
        if (relText === 'islam' || relText === 'christianity' || relText === 'buddhism') {
            $('#group-member-sub-caste').addClass('d-none');
            $('#modal_member_sub_caste_id').val('');
            return;
        }

        var caste_id = $('#modal_member_caste_id').val();
        if (!caste_id) {
            $('#modal_member_sub_caste_id').html('<option value="">{{ translate("Select Sub Caste / Gotra") }}</option>');
            return;
        }
        $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}', {
            _token: '{{ csrf_token() }}',
            caste_id: caste_id
        }, function(data) {
            var placeholder = '{{ translate("Select Sub Caste / Gotra") }}';

            if (data && data.length > 0) {
                $('#group-member-sub-caste').removeClass('d-none');
                $('#modal_member_sub_caste_id').html('<option value="">' + placeholder + '</option>');
                for (var i = 0; i < data.length; i++) {
                    $('#modal_member_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                if ('{{ $member_sub_caste_id }}') {
                    $('#modal_member_sub_caste_id').val('{{ $member_sub_caste_id }}');
                }
            } else {
                if (relText === 'islam' || relText === 'christianity' || relText === 'buddhism') {
                    $('#group-member-sub-caste').addClass('d-none');
                }
                $('#modal_member_sub_caste_id').html('<option value="">' + placeholder + '</option>');
            }
        });
    }

    // Partner Expectations Dynamic Handlers
    function handlePrefReligionFields() {
        var $relSelect = $('#pref_partner_religion_id');
        if (!$relSelect.length) return;
        var relText = $relSelect.find('option:selected').text().trim().toLowerCase();
        var relVal = $relSelect.val();

        if (relText === 'islam' || relVal == '1') {
            $('#group-pref-partner-caste').removeClass('d-none');
            $('#group-pref-partner-sub-caste').addClass('d-none');
            $('#group-pref-partner-manglik').addClass('d-none');
            $('#label-pref-partner-caste').text('{{ translate("Sect / Mazhab") }}');
            $('#pref_partner_sub_caste_id').val('');
            $('#pref_partner_manglik').val('');
        } else if (relText === 'hinduism' || relVal == '2') {
            $('#group-pref-partner-caste').removeClass('d-none');
            $('#group-pref-partner-sub-caste').removeClass('d-none');
            $('#group-pref-partner-manglik').removeClass('d-none');
            $('#label-pref-partner-caste').text('{{ translate("Caste") }}');
            $('#label-pref-partner-sub-caste').text('{{ translate("Sub-Caste / Gotra") }}');
        } else if (relText === 'christianity' || relVal == '3') {
            $('#group-pref-partner-caste').removeClass('d-none');
            $('#group-pref-partner-sub-caste').addClass('d-none');
            $('#group-pref-partner-manglik').addClass('d-none');
            $('#label-pref-partner-caste').text('{{ translate("Denomination") }}');
            $('#pref_partner_sub_caste_id').val('');
            $('#pref_partner_manglik').val('');
        } else if (relText === 'buddhism' || relVal == '4') {
            $('#group-pref-partner-caste').removeClass('d-none');
            $('#group-pref-partner-sub-caste').addClass('d-none');
            $('#group-pref-partner-manglik').addClass('d-none');
            $('#label-pref-partner-caste').text('{{ translate("Sect / Community") }}');
            $('#pref_partner_sub_caste_id').val('');
            $('#pref_partner_manglik').val('');
        } else {
            $('#group-pref-partner-caste').removeClass('d-none');
            $('#group-pref-partner-sub-caste').addClass('d-none');
            $('#group-pref-partner-manglik').addClass('d-none');
            $('#label-pref-partner-caste').text('{{ translate("Sect / Mazhab / Caste") }}');
            $('#pref_partner_sub_caste_id').val('');
            $('#pref_partner_manglik').val('');
        }

        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
            AIZ.plugins.bootstrapSelect('refresh');
        }
    }

    function get_pref_castes_by_religion() {
        handlePrefReligionFields();
        var religion_id = $('#pref_partner_religion_id').val();
        if (!religion_id) {
            $('#pref_partner_caste_id').html('<option value="">{{ translate("Any Option") }}</option>');
            $('#pref_partner_sub_caste_id').html('<option value="">{{ translate("Any Option") }}</option>');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            return;
        }
        $.post('{{ route('castes.get_caste_by_religion') }}', {
            _token: '{{ csrf_token() }}',
            religion_id: religion_id
        }, function(data) {
            var relText = $('#pref_partner_religion_id option:selected').text().trim().toLowerCase();
            var placeholder = '{{ translate("Any Option") }}';
            if (relText === 'islam' || religion_id == 1) placeholder = '{{ translate("Any Sect / Mazhab") }}';
            else if (relText === 'hinduism' || religion_id == 2) placeholder = '{{ translate("Any Caste") }}';
            else if (relText === 'christianity' || religion_id == 3) placeholder = '{{ translate("Any Denomination") }}';
            else if (relText === 'buddhism' || religion_id == 4) placeholder = '{{ translate("Any Sect") }}';

            $('#pref_partner_caste_id').html('<option value="">' + placeholder + '</option>');
            for (var i = 0; i < data.length; i++) {
                $('#pref_partner_caste_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            @php $p_caste_id_val = optional($member->partner_expectations)->caste_id ?? ''; @endphp
            if ('{{ $p_caste_id_val }}') {
                $('#pref_partner_caste_id').val('{{ $p_caste_id_val }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            get_pref_sub_castes_by_caste();
        });
    }

    function get_pref_sub_castes_by_caste() {
        var relText = $('#pref_partner_religion_id option:selected').text().trim().toLowerCase();
        var relVal = $('#pref_partner_religion_id').val();
        if (relText !== 'hinduism' && relVal != '2') {
            $('#group-pref-partner-sub-caste').addClass('d-none');
            $('#pref_partner_sub_caste_id').val('');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            return;
        }

        var caste_id = $('#pref_partner_caste_id').val();
        if (!caste_id) {
            $('#pref_partner_sub_caste_id').html('<option value="">{{ translate("Any Option") }}</option>');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            return;
        }
        $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}', {
            _token: '{{ csrf_token() }}',
            caste_id: caste_id
        }, function(data) {
            if (data && data.length > 0) {
                $('#group-pref-partner-sub-caste').removeClass('d-none');
                $('#pref_partner_sub_caste_id').html('<option value="">{{ translate("Any Sub-Caste / Gotra") }}</option>');
                for (var i = 0; i < data.length; i++) {
                    $('#pref_partner_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                @php $p_sub_caste_id_val = optional($member->partner_expectations)->sub_caste_id ?? ''; @endphp
                if ('{{ $p_sub_caste_id_val }}') {
                    $('#pref_partner_sub_caste_id').val('{{ $p_sub_caste_id_val }}');
                }
            } else {
                $('#group-pref-partner-sub-caste').addClass('d-none');
                $('#pref_partner_sub_caste_id').html('<option value="">{{ translate("Any Option") }}</option>');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }

    function get_pref_states_by_country() {
        var country_id = $('#pref_partner_country_id').val();
        if (!country_id) {
            $('#pref_partner_state_id').html('<option value="">{{ translate("Any State / Division") }}</option>');
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
            return;
        }
        $.post('{{ route('states.get_state_by_country') }}', {
            _token: '{{ csrf_token() }}',
            country_id: country_id
        }, function(data) {
            $('#pref_partner_state_id').html('<option value="">{{ translate("Any State / Division") }}</option>');
            for (var i = 0; i < data.length; i++) {
                $('#pref_partner_state_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
            }
            @php $p_state_id_val = optional($member->partner_expectations)->preferred_state_id ?? ''; @endphp
            if ('{{ $p_state_id_val }}') {
                $('#pref_partner_state_id').val('{{ $p_state_id_val }}');
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }

    function updateStatusBarDisplay(scope) {
        currentActiveScope = scope;
        if (scope === 'partner-preferences') {
            $('#profile-status-bar-scope-label').text('{{ translate("Preferences") }}');
            $('#profile-percent-label').text(prefPercentage + '%');
            $('#profile-completeness-fill').attr('width', prefPercentage + '%').css('width', prefPercentage + '%');
            if (prefPercentage === 100) {
                $('.profile-completeness-check-icon').html('&#10004;');
            } else {
                $('.profile-completeness-check-icon').html('<i class="las la-tasks" style="font-size:11px;"></i>');
            }
            $('#profileCompletionChecklistTitle').text('{{ translate("Partner Preferences Completion Checklist") }}');
            $('#profileCompletionChecklistSubtitle').text('{{ translate("Complete missing partner requirements to help find your ideal match.") }}');
            $('#checklist-view-profile').addClass('d-none');
            $('#checklist-view-preferences').removeClass('d-none');
            $('#checklist-percent-val').text(prefPercentage + '%');
            $('#checklist-percent-headline').text(prefPercentage + '%');
            $('#checklist-circular-fill').attr('stroke-dasharray', prefPercentage + ', 100');
            $('#checklist-hero-progress-bar').css('width', prefPercentage + '%').attr('aria-valuenow', prefPercentage);
            var completedCount = $('#checklist-view-preferences .checklist-item-card[data-status="completed"]').length;
            var totalCount = prefTotalSections;
            var missingCount = totalCount - completedCount;
            $('#checklist-completed-count').text(completedCount);
            $('#checklist-missing-count').text(missingCount);
            $('#tab-completed-count').text(completedCount);
            $('#tab-missing-count').text(missingCount);
            $('#tab-all-count').text(totalCount);
            $('#checklist-progress-text').text(completedCount + '/' + totalCount + ' {{ translate("Sections") }}');
        } else {
            $('#profile-status-bar-scope-label').text('{{ translate("Profile") }}');
            $('#profile-percent-label').text(profilePercentage + '%');
            $('#profile-completeness-fill').attr('width', profilePercentage + '%').css('width', profilePercentage + '%');
            if (profilePercentage === 100) {
                $('.profile-completeness-check-icon').html('&#10004;');
            } else {
                $('.profile-completeness-check-icon').html('<i class="las la-tasks" style="font-size:11px;"></i>');
            }
            $('#profileCompletionChecklistTitle').text('{{ translate("Profile Completion Checklist") }}');
            $('#profileCompletionChecklistSubtitle').text('{{ translate("Complete missing information to make your biodata 100% complete.") }}');
            $('#checklist-view-preferences').addClass('d-none');
            $('#checklist-view-profile').removeClass('d-none');
            $('#checklist-percent-val').text(profilePercentage + '%');
            $('#checklist-percent-headline').text(profilePercentage + '%');
            $('#checklist-circular-fill').attr('stroke-dasharray', profilePercentage + ', 100');
            $('#checklist-hero-progress-bar').css('width', profilePercentage + '%').attr('aria-valuenow', profilePercentage);
            var completedCount = $('#checklist-view-profile .checklist-item-card[data-status="completed"]').length;
            var totalCount = profileTotalSections;
            var missingCount = totalCount - completedCount;
            $('#checklist-completed-count').text(completedCount);
            $('#checklist-missing-count').text(missingCount);
            $('#tab-completed-count').text(completedCount);
            $('#tab-missing-count').text(missingCount);
            $('#tab-all-count').text(totalCount);
            $('#checklist-progress-text').text(completedCount + '/' + totalCount + ' {{ translate("Sections") }}');
        }
        var currentFilter = $('.checklist-filter-btn.active').data('filter') || 'all';
        applyChecklistFilter(currentFilter);
    }

    function switchProfileTab(tabName) {
        if (tabName === 'partner-preferences') {
            $('#btn-tab-my-profile').removeClass('active');
            $('#btn-tab-partner-preferences').addClass('active');
            $('#view-my-profile').addClass('d-none');
            $('#view-partner-preferences').removeClass('d-none');
            updateStatusBarDisplay('partner-preferences');
            if (window.history && window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.set('tab', 'partner_preferences');
                window.history.replaceState(null, '', url.toString());
            }
        } else {
            $('#btn-tab-partner-preferences').removeClass('active');
            $('#btn-tab-my-profile').addClass('active');
            $('#view-partner-preferences').addClass('d-none');
            $('#view-my-profile').removeClass('d-none');
            updateStatusBarDisplay('my-profile');
            if (window.history && window.history.replaceState) {
                var url = new URL(window.location.href);
                url.searchParams.delete('tab');
                window.history.replaceState(null, '', url.toString());
            }
        }
    }

    function openPrefModalTab(tabName) {
        var targetTabId = 'tab-pref-basic';
        if (tabName === 'religion') {
            targetTabId = 'tab-pref-religion';
        } else if (tabName === 'career' || tabName === 'education' || tabName === 'location') {
            targetTabId = 'tab-pref-career';
        } else if (tabName === 'lifestyle' || tabName === 'general') {
            targetTabId = 'tab-pref-lifestyle';
        }

        // Activate corresponding pill nav link
        $('.partner-pref-tabs .nav-link').removeClass('active');
        $('#' + targetTabId + '-btn').addClass('active');

        // Activate corresponding tab pane inside modal
        $('#partnerPrefTabsContent .tab-pane').removeClass('show active');
        $('#' + targetTabId).addClass('show active');

        $('#modal-edit-partner-expectations').modal('show');
    }

    // Attach to global window scope so inline onclick attributes never fail
    window.switchProfileTab = switchProfileTab;
    window.openPrefModalTab = openPrefModalTab;
    window.applyChecklistFilter = applyChecklistFilter;
    window.updateStatusBarDisplay = updateStatusBarDisplay;
    window.recalculateProfileCompleteness = recalculateProfileCompleteness;
    window.recalculatePartnerPrefCompleteness = recalculatePartnerPrefCompleteness;

    function previewMainProfilePhoto(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('avatar_preview_img_main');
                if (preview) {
                    preview.src = e.target.result;
                }
                var btn = document.getElementById('btn-save-main-photo');
                if (btn) {
                    btn.disabled = false;
                }
            };
            reader.readAsDataURL(file);
        }
    }
    window.previewMainProfilePhoto = previewMainProfilePhoto;

    function submitProfilePhotoUpload() {
        var fileInput = document.getElementById('profile_photo_input_main_file');
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('warning', '{{ translate("Please click and choose a photo first.") }}');
            } else {
                alert('{{ translate("Please click and choose a photo first.") }}');
            }
            return false;
        }
        $('#form-update-profile-photo-main').submit();
    }
    window.submitProfilePhotoUpload = submitProfilePhotoUpload;

    $(document).on('submit', '#form-update-profile-photo-main', function(e) {
        e.preventDefault();
        var fileInput = document.getElementById('profile_photo_input_main_file');
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            alert('{{ translate("Please select a photo first.") }}');
            return false;
        }

        var $btn = $('#btn-save-main-photo');
        var originalHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span> {{ translate("Uploading...") }}');

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
            },
            success: function(response) {
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('success', '{{ translate("Profile photo updated successfully!") }}');
                }
                setTimeout(function() {
                    window.location.reload();
                }, 350);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                var errorMsg = '{{ translate("Failed to upload photo. Please select a valid image (JPG, PNG, WEBP).") }}';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                    AIZ.plugins.notify('danger', errorMsg);
                } else {
                    alert(errorMsg);
                }
            }
        });
    });

    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'partner_preferences' || window.location.hash === '#partner-preferences') {
            switchProfileTab('partner-preferences');
        } else {
            updateStatusBarDisplay('my-profile');
        }
    });
</script>
@endsection
