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
    // Profile completeness calculation across 13 sections
    $total_sections = 13;
    $completed_sections = 0;
    if (!empty($member->first_name)) $completed_sections++;
    if (!empty(optional($member->member)->birthday)) $completed_sections++;
    if (!empty($member->phone) || !empty($present_address)) $completed_sections++;
    if ($educations->count() > 0) $completed_sections++;
    if ($careers->count() > 0) $completed_sections++;
    if (!empty(optional($member->member)->mothere_tongue)) $completed_sections++;
    if (!empty(optional($member->hobbies)->hobbies)) $completed_sections++;
    if (!empty(optional($member->families)->father_occupation)) $completed_sections++;
    if (!empty(optional($member->lifestyles)->diet)) $completed_sections++;
    if (!empty(optional($member->recidency)->recidency_country_id)) $completed_sections++;
    if (!empty(optional($member->spiritual_backgrounds)->religion_id)) $completed_sections++;
    if (!empty($permanent_address)) $completed_sections++;
    if (!empty(optional($member->member)->introduction) || !empty(optional($member->partner_expectations)->general)) $completed_sections++;

    $calculated_percentage = (int)round(($completed_sections / $total_sections) * 100);
    $profile_completion_percentage = $profile_completion_percentage ?? ($calculated_percentage > 0 ? $calculated_percentage : 88);

    // Marital Status & Children check
    $ms_name = strtolower(optional(optional($member->member)->marital_status)->name ?? (string)optional($member->member)->marital_status);
    $ms_id = optional($member->member)->marital_status_id;
    $is_never_married = empty($ms_name) || str_contains($ms_name, 'never married') || $ms_id == 1;
    $salary_val = optional($member->member)->annual_salary_range_id;
@endphp

<!-- Profile Page Header & Completeness Status Bar -->
<div class="profile-page-header-wrap">
    <div class="profile-header-left">
        <div class="profile-page-breadcrumb">
            <span>{{ translate('My Profile') }}</span>
            <span class="breadcrumb-separator">/</span>
            <span>{{ translate('Biodata') }}</span>
        </div>
        <h1 class="profile-page-title font-serif">
            {{ translate('My Profile') }}
        </h1>
        <p class="profile-page-subtitle">
            {{ translate('Review your information and update any section when needed.') }}
        </p>
    </div>
    <div class="profile-header-right">
        <div class="profile-completeness-pill">
            <span class="profile-completeness-badge">
                <span class="profile-completeness-check-icon">&#10004;</span>
                {{ translate('Profile') }} <span id="profile-percent-label">{{ $profile_completion_percentage ?? 88 }}%</span> {{ translate('Complete') }}
            </span>
            <div class="profile-completeness-progress-track">
                <svg class="profile-completeness-svg" width="90" height="7">
                    <rect width="100%" height="7" rx="3.5" class="profile-completeness-svg-track" />
                    <rect id="profile-completeness-fill" width="{{ $profile_completion_percentage ?? 88 }}%" height="7" rx="3.5" class="profile-completeness-svg-fill" style="transition: width 0.4s ease-in-out;" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="profile-biodata-grid">

    {{-- ROW 1 (2 Columns): Card 1 & Card 2 --}}
    <div class="profile-biodata-row">
        {{-- Card 1: 1. Basic Information --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-1">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-user"></i></span>
                    <h3 class="profile-card-title font-serif">1. {{ translate('Basic Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-basic-info">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-2">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-id-card"></i></span>
                    <h3 class="profile-card-title font-serif">2. {{ translate('Personal Details') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-personal-details">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
        {{-- Card 3: 3. Spiritual & Social Background --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-12">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-place-of-worship"></i></span>
                    <h3 class="profile-card-title font-serif">3. {{ translate('Spiritual & Social Background') }}</h3>
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
            <div class="profile-data-col-grid cols-2" id="card-spiritual-grid">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-5">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-briefcase"></i></span>
                    <h3 class="profile-card-title font-serif">4. {{ translate('Career') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-career">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Designation / Occupation') }}</span>
                    <span class="profile-field-value" data-field="designation">{{ optional($current_career)->designation ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Company Name') }}</span>
                    <span class="profile-field-value" data-field="company">{{ optional($current_career)->company ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Start Year') }}</span>
                    <span class="profile-field-value" data-field="start">{{ optional($current_career)->start ?? '—' }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('End Year / Status') }}</span>
                    <span class="profile-field-value" data-field="end">{{ !empty(optional($current_career)->end) ? $current_career->end : (optional($current_career)->present ? translate('Present') : '—') }}</span>
                </div>
                <div class="profile-field-item">
                    <span class="profile-field-label">{{ translate('Experience (Years)') }}</span>
                    <span class="profile-field-value" data-field="experience">{{ !empty(optional($current_career)->start) ? max(1, (int)date('Y') - (int)$current_career->start) . ' ' . translate('Years') : '—' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3 (Full-Width Card): Card 5 (Education) --}}
    <div class="profile-biodata-row full-width">
        {{-- Card 5: 5. Education --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-4">
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
                        <div class="profile-data-col-grid cols-3">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-3">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-map-marker"></i></span>
                    <h3 class="profile-card-title font-serif">6. {{ translate('Present Address') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-contact-details">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
                <div class="profile-field-item" style="grid-column: 1 / -1;">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-13">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-map-marker-alt"></i></span>
                    <h3 class="profile-card-title font-serif">7. {{ translate('Permanent Address') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-permanent-address">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
                <div class="profile-field-item" style="grid-column: 1 / -1;">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-9">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-users"></i></span>
                    <h3 class="profile-card-title font-serif">8. {{ translate('Family Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-family">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
            </div>
        </div>

        {{-- Card 9: 9. Residency Information --}}
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-11">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-home"></i></span>
                    <h3 class="profile-card-title font-serif">9. {{ translate('Residency Information') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-residency">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-10">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-cocktail"></i></span>
                    <h3 class="profile-card-title font-serif">10. {{ translate('Lifestyle & Preferences') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-lifestyle">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-7">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-language"></i></span>
                    <h3 class="profile-card-title font-serif">11. {{ translate('Language') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-language">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-2">
                <div class="profile-field-item">
                    <span class="profile-field-label">Mother Tongue</span>
                    <span class="profile-field-value" data-field="mother_tongue">{{ !empty(optional(optional($member->member)->mothereTongue)->name) ? optional(optional($member->member)->mothereTongue)->name : (!empty(optional($member->member)->mothere_tongue) ? optional($member->member)->mothere_tongue : 'Bengali') }}</span>
                    <small class="text-muted mt-1">{{ translate('Native') }}</small>
                </div>
                <div class="profile-field-item">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-8">
            <div class="profile-biodata-card-header">
                <div class="profile-card-title-wrap">
                    <span class="profile-card-icon-badge"><i class="las la-heart"></i></span>
                    <h3 class="profile-card-title font-serif">12. {{ translate('Hobbies & Interests') }}</h3>
                </div>
                <button type="button" class="profile-card-edit-btn" data-toggle="modal" data-target="#modal-edit-hobbies">
                    <i class="las la-pen"></i> {{ translate('Edit') }}
                </button>
            </div>
            <div class="profile-data-col-grid cols-1">
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
        <div class="profile-biodata-card rounded-2xl p-6 border border-stone-200/80 shadow-sm bg-white" id="card-14">
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
@endsection

@section('modal')
    @include('frontend.member.profile.modals')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
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

        if (cardId === 'card-1') {
            var firstName = $form.find('input[name="first_name"]').val();
            var lastName = $form.find('input[name="last_name"]').val();
            if (firstName || lastName) {
                $card.find('[data-field="full_name"]').text(firstName + ' ' + lastName);
            }
            var genderVal = $form.find('select[name="gender"]').val();
            if (genderVal) {
                $card.find('[data-field="gender"]').text(genderVal == '1' ? '{{ translate("Male") }}' : '{{ translate("Female") }}');
            }
            var dob = $form.find('input[name="date_of_birth"]').val();
            if (dob) {
                $card.find('[data-field="birthday"]').text(dob);
                $('[data-field="birthday"]').text(dob);
            }
            var phone = $form.find('input[name="phone"]').val();
            if (phone !== undefined) {
                $card.find('[data-field="phone"]').text(phone || '—');
            }
            var email = $form.find('input[name="email"]').val();
            if (email !== undefined) {
                $card.find('[data-field="email"]').text(email || '—');
            }
            var maritalText = $form.find('select[name="marital_status"] option:selected').text();
            var maritalVal = $form.find('select[name="marital_status"]').val();
            if (maritalText) {
                $card.find('[data-field="marital_status"]').text(maritalText);
            }
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
        } else if (cardId === 'card-2') {
            var height = $form.find('input[name="height"]').val();
            var weight = $form.find('input[name="weight"]').val();
            var bodyType = $form.find('input[name="body_type"]').val();
            var complexion = $form.find('input[name="complexion"]').val();
            var bloodGroup = $form.find('input[name="blood_group"]').val();
            var disability = $form.find('input[name="disability"]').val();

            if (height) $('[data-field="height"]').text(height + ' ft');
            if (weight) $('[data-field="weight"]').text(weight + ' kg');
            if (bodyType) $('[data-field="body_type"]').text(bodyType);
            if (complexion) $('[data-field="complexion"]').text(complexion);
            if (bloodGroup) $('[data-field="blood_group"]').text(bloodGroup);
            if (disability !== undefined) $('[data-field="disability"]').text(disability || 'None');
        } else if (cardId === 'card-3') {
            var countryText = $form.find('select[name="present_country_id"] option:selected').text();
            var stateText = $form.find('select[name="present_state_id"] option:selected').text();
            var cityText = $form.find('select[name="present_city_id"] option:selected').text();
            var postalCode = $form.find('input[name="present_postal_code"]').val();
            var homeRoad = $form.find('input[name="present_home_road"]').val();

            if (countryText && countryText !== '{{ translate("Select Country") }}') $card.find('[data-field="country"]').text(countryText);
            if (stateText && stateText !== '{{ translate("Select State") }}') $card.find('[data-field="state"]').text(stateText);
            if (cityText && cityText !== '{{ translate("Select City") }}') $card.find('[data-field="city"]').text(cityText);
            if (postalCode) $card.find('[data-field="postal_code"]').text(postalCode);
            if (homeRoad !== undefined) $card.find('[data-field="home_road"]').text(homeRoad || '—');

            var fullAddress = (homeRoad ? homeRoad + ', ' : '') +
                              (cityText && cityText !== '{{ translate("Select City") }}' ? cityText + ', ' : '') +
                              (stateText && stateText !== '{{ translate("Select State") }}' ? stateText + ', ' : '') +
                              (countryText && countryText !== '{{ translate("Select Country") }}' ? countryText : '');
            if (fullAddress) $card.find('[data-field="present_address"]').text(fullAddress);
        } else if (cardId === 'card-4') {
            var degree = $form.find('input[name="degree"]').val();
            var institution = $form.find('input[name="institution"]').val();
            var startYear = $form.find('input[name="education_start"]').val();
            var passingYear = $form.find('input[name="education_end"]').val();
            if (degree) $card.find('[data-field="highest_degree"]').text(degree);
            if (institution) $card.find('[data-field="institution"]').text(institution);
            if (startYear) $card.find('[data-field="start_year"]').text(startYear);
            if (passingYear) $card.find('[data-field="passing_year"]').text(passingYear);
        } else if (cardId === 'card-5') {
            var designation = $form.find('input[name="designation"]').val();
            var company = $form.find('input[name="company"]').val();
            var startYear = parseInt($form.find('input[name="career_start"]').val());
            var endYear = $form.find('input[name="career_end"]').val();
            if (designation) $card.find('[data-field="designation"]').text(designation);
            if (company) $card.find('[data-field="company"]').text(company);
            if (startYear) {
                $card.find('[data-field="start"]').text(startYear);
                var currentYear = new Date().getFullYear();
                $card.find('[data-field="experience"]').text(Math.max(1, currentYear - startYear) + ' {{ translate("Years") }}');
            }
            if (endYear) {
                $card.find('[data-field="end"]').text(endYear);
            }
        } else if (cardId === 'card-7') {
            var motherTongue = $form.find('[name="mothere_tongue"]').val();
            var knownLangs = $form.find('[name="known_languages"]').val();
            if (motherTongue !== undefined) {
                $card.find('[data-field="mother_tongue"]').text(motherTongue || '—');
            }
            if (knownLangs !== undefined) {
                $card.find('[data-field="known_languages"]').text(knownLangs || '—');
            }
        } else if (cardId === 'card-8') {
            var hobbies = $form.find('[name="hobbies"]').val();
            var interests = $form.find('[name="interests"]').val();
            if (hobbies !== undefined) $card.find('[data-field="hobbies"]').text(hobbies || '—');
            if (interests !== undefined) $card.find('[data-field="interests"]').text(interests || '—');
        } else if (cardId === 'card-9') {
            var father = $form.find('input[name="father"]').val();
            var fatherOcc = $form.find('input[name="father_occupation"]').val();
            var mother = $form.find('input[name="mother"]').val();
            var motherOcc = $form.find('input[name="mother_occupation"]').val();
            var brothers = $form.find('select[name="no_of_brothers"]').val();
            var sisters = $form.find('select[name="no_of_sisters"]').val();
            if (father) $card.find('[data-field="father"]').text(father);
            if (fatherOcc) $card.find('[data-field="father_occupation"]').text(fatherOcc);
            if (mother) $card.find('[data-field="mother"]').text(mother);
            if (motherOcc) $card.find('[data-field="mother_occupation"]').text(motherOcc);
            if (brothers !== undefined) $card.find('[data-field="no_of_brothers"]').text(brothers);
            if (sisters !== undefined) $card.find('[data-field="no_of_sisters"]').text(sisters);
        } else if (cardId === 'card-10') {
            var smoke = $form.find('select[name="smoke"]').val();
            var drink = $form.find('select[name="drink"]').val();
            var diet = $form.find('select[name="diet"]').val();
            var livingWith = $form.find('input[name="living_with"]').val();
            if (smoke) $card.find('[data-field="smoke"]').text(smoke.charAt(0).toUpperCase() + smoke.slice(1));
            if (drink) $card.find('[data-field="drink"]').text(drink.charAt(0).toUpperCase() + drink.slice(1));
            if (diet) $card.find('[data-field="diet"]').text(diet === 'yes' ? 'Vegetarian' : 'Non-Vegetarian');
            if (livingWith) $card.find('[data-field="living_with"]').text(livingWith);
        } else if (cardId === 'card-11') {
            var resCountry = $form.find('select[name="recidency_country_id"] option:selected').text();
            var permCountry = $form.find('select[name="birth_country_id"] option:selected').text();
            var growCountry = $form.find('select[name="growup_country_id"] option:selected').text();
            var immStatus = $form.find('input[name="immigration_status"]').val();
            if (resCountry && resCountry !== '{{ translate("Select Country") }}') $card.find('[data-field="current_residence"]').text(resCountry);
            if (permCountry && permCountry !== '{{ translate("Select Country") }}') $card.find('[data-field="permanent_residence"]').text(permCountry);
            if (growCountry && growCountry !== '{{ translate("Select Country") }}') $card.find('[data-field="growup_country"]').text(growCountry);
            if (immStatus) $card.find('[data-field="immigration_status"]').text(immStatus);
        } else if (cardId === 'card-12') {
            var religion = $form.find('select[name="member_religion_id"] option:selected').text();
            var caste = $form.find('select[name="member_caste_id"] option:selected').text();
            var subCaste = $form.find('select[name="member_sub_caste_id"] option:selected').text();
            var fv = $form.find('select[name="family_value_id"] option:selected').text();
            if (religion && religion !== '{{ translate("Select Religion") }}') $card.find('[data-field="religion"]').text(religion);
            if (fv && fv !== '{{ translate("Select One") }}') $card.find('[data-field="family_values"]').text(fv);

            var relLower = (religion || '').toLowerCase();
            if (relLower.indexOf('islam') !== -1) {
                $('#card-caste-field').show();
                $('#card-caste-label').text('{{ translate("Sect / Mazhab") }}');
                if (caste && caste.indexOf('Select') === -1) $card.find('[data-field="caste"]').text(caste);
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
                if (caste && caste.indexOf('Select') === -1) $card.find('[data-field="caste"]').text(caste);
                $('#card-subcaste-field').show();
                $('#card-subcaste-label').text('{{ translate("Sub-Caste / Gotra") }}');
                if (subCaste && subCaste.indexOf('Select') === -1) $card.find('[data-field="sect"]').text(subCaste);
            } else {
                $('#card-caste-field').show();
                $('#card-caste-label').text('{{ translate("Denomination / Sect") }}');
                if (caste && caste.indexOf('Select') === -1) $card.find('[data-field="caste"]').text(caste);
                $('#card-subcaste-field').hide();
            }
        } else if (cardId === 'card-13') {
            var permCountry = $form.find('select[name="permanent_country_id"] option:selected').text();
            var permState = $form.find('select[name="permanent_state_id"] option:selected').text();
            var permCity = $form.find('select[name="permanent_city_id"] option:selected').text();
            var permPostal = $form.find('input[name="permanent_postal_code"]').val();
            var permHomeRoad = $form.find('input[name="permanent_home_road"]').val();

            if (permCountry && permCountry !== '{{ translate("Select Country") }}') $card.find('[data-field="country"]').text(permCountry);
            if (permState && permState !== '{{ translate("Select State") }}') $card.find('[data-field="division"]').text(permState);
            if (permCity && permCity !== '{{ translate("Select City") }}') $card.find('[data-field="district"]').text(permCity);
            if (permPostal) $card.find('[data-field="postal_code"]').text(permPostal);
            if (permHomeRoad !== undefined) $card.find('[data-field="home_road"]').text(permHomeRoad || '—');

            var permFullAddress = (permHomeRoad ? permHomeRoad + ', ' : '') +
                                  (permCity && permCity !== '{{ translate("Select City") }}' ? permCity + ', ' : '') +
                                  (permState && permState !== '{{ translate("Select State") }}' ? permState + ', ' : '') +
                                  (permCountry && permCountry !== '{{ translate("Select Country") }}' ? permCountry : '');
            if (permFullAddress) $card.find('[data-field="address"]').text(permFullAddress);
        } else if (cardId === 'card-14') {
            var intro = $form.find('textarea[name="introduction"]').val();
            var expectations = $form.find('textarea[name="expectations"]').val();
            if (intro) $card.find('[data-field="about_yourself"]').text(intro);
            if (expectations !== undefined) $card.find('[data-field="expectations"]').text(expectations || '—');
        }

        // Dynamically recalculate and animate status bar without reloading page
        recalculateProfileCompleteness();
    }

    // Live recalculation of profile completeness percentage across all 13 sections
    function recalculateProfileCompleteness() {
        var totalSections = 13;
        var completed = 0;

        // 1. Basic Information (Card 1)
        var name = $('#card-1 [data-field="full_name"]').text().trim();
        if (name && name !== '—') completed++;

        // 2. Personal Details (Card 2)
        var height = $('#card-2 [data-field="height"]').text().trim();
        var age = $('#card-2 [data-field="age"]').text().trim();
        var weight = $('#card-2 [data-field="weight"]').text().trim();
        if ((height && height !== '—') || (age && age !== '—') || (weight && weight !== '—')) completed++;

        // 3. Spiritual & Social Background (Card 3 / #card-12)
        var rel = $('#card-12 [data-field="religion"]').text().trim();
        if (rel && rel !== '—') completed++;

        // 4. Career (Card 4 / #card-5)
        var des = $('#card-5 [data-field="designation"]').text().trim();
        var comp = $('#card-5 [data-field="company"]').text().trim();
        if ((des && des !== '—') || (comp && comp !== '—')) completed++;

        // 5. Education (Card 5 / #card-4)
        var hasEdu = $('#education-card-list .profile-education-item').length > 0 ||
                     ($('#card-4 [data-field="highest_degree"]').text().trim() !== '' && $('#card-4 [data-field="highest_degree"]').text().trim() !== '—');
        if (hasEdu) completed++;

        // 6. Present Address (Card 6 / #card-3)
        var pres = $('#card-3 [data-field="present_address"]').text().trim() || $('#card-3 [data-field="country"]').text().trim();
        if (pres && pres !== '—') completed++;

        // 7. Permanent Address (Card 7 / #card-13)
        var perm = $('#card-13 [data-field="address"]').text().trim() || $('#card-13 [data-field="country"]').text().trim();
        if (perm && perm !== '—') completed++;

        // 8. Family Information (Card 8 / #card-9)
        var father = $('#card-9 [data-field="father_occupation"]').text().trim() || $('#card-9 [data-field="father"]').text().trim();
        if (father && father !== '—') completed++;

        // 9. Residency Information (Card 9 / #card-11)
        var res = $('#card-11 [data-field="current_residence"]').text().trim();
        if (res && res !== '—') completed++;

        // 10. Lifestyle & Preferences (Card 10 / #card-10)
        var diet = $('#card-10 [data-field="diet"]').text().trim();
        if (diet && diet !== '—') completed++;

        // 11. Language (Card 11 / #card-7)
        var lang = $('#card-7 [data-field="mother_tongue"]').text().trim();
        if (lang && lang !== '—') completed++;

        // 12. Hobbies & Interests (Card 12 / #card-8)
        var hob = $('#card-8 [data-field="hobbies"]').text().trim();
        if (hob && hob !== '—') completed++;

        // 13. Additional Information (Card 13 / #card-14)
        var intro = $('#card-14 [data-field="about_yourself"]').text().trim();
        var exp = $('#card-14 [data-field="expectations"]').text().trim();
        if ((intro && intro !== '—') || (exp && exp !== '—')) completed++;

        var percent = Math.min(100, Math.round((completed / totalSections) * 100));

        // Dynamically update percentage label and progress track
        $('#profile-percent-label').text(percent + '%');
        $('#profile-completeness-fill').attr('width', percent + '%').css('width', percent + '%');
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
</script>
@endsection
