{{-- Profile Completion Checklist Modal --}}
@php
    $profile_sections_meta = $profile_sections_meta ?? [];
    $total_sections = count($profile_sections_meta) ?: 13;
    $completed_sections = $completed_sections ?? 0;
    $incomplete_sections = $incomplete_sections ?? ($total_sections - $completed_sections);
    $profile_completion_percentage = $profile_completion_percentage ?? 0;

    $partner_pref_sections_meta = $partner_pref_sections_meta ?? [];
    $total_pref_sections = count($partner_pref_sections_meta) ?: 6;
    $completed_pref_sections = $completed_pref_sections ?? 0;
    $incomplete_pref_sections = $incomplete_pref_sections ?? ($total_pref_sections - $completed_pref_sections);
    $partner_pref_completion_percentage = $partner_pref_completion_percentage ?? 0;
@endphp
<div class="modal fade profile-modal" id="modal-profile-completion-checklist" tabindex="-1" role="dialog" aria-labelledby="profileCompletionChecklistTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-bottom: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #ecfdf5; color: #059669; font-size: 22px; border: 1px solid rgba(16, 185, 129, 0.25);">
                        <i class="las la-tasks"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="profileCompletionChecklistTitle" style="font-size: 1.15rem;">
                            {{ translate('Profile Completion Checklist') }}
                        </h5>
                        <p class="mb-0 text-muted small" id="profileCompletionChecklistSubtitle">
                            {{ translate('Complete missing information to make your biodata 100% complete.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background-color: #f8fafc; max-height: calc(85vh - 140px); overflow-y: auto;">
                <!-- Hero Score Box -->
                <div class="completion-hero-card p-4 rounded-xl mb-4 bg-white border border-stone-200/80 shadow-sm" style="border-radius: 16px;">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="position-relative mr-3" style="width: 68px; height: 68px; flex-shrink: 0;">
                                <svg viewBox="0 0 36 36" class="circular-chart" style="display: block; width: 68px; height: 68px;">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="3.5" />
                                    <path id="checklist-circular-fill" class="circle" stroke-dasharray="{{ $profile_completion_percentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#10b981" stroke-width="3.5" stroke-linecap="round" style="transition: stroke-dasharray 0.5s ease;" />
                                </svg>
                                <div class="position-absolute d-flex align-items-center justify-content-center" style="top:0; left:0; right:0; bottom:0;">
                                    <span id="checklist-percent-val" class="font-weight-bold text-dark" style="font-size: 14px;">{{ $profile_completion_percentage }}%</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-weight-bold text-dark mb-1" style="font-size: 1.25rem;">
                                    <span id="checklist-percent-headline">{{ $profile_completion_percentage }}%</span> {{ translate('Complete') }}
                                </h4>
                                <div class="text-muted small">
                                    <span id="checklist-completed-count" class="text-success font-weight-bold">{{ $completed_sections }}</span> {{ translate('completed') }}, 
                                    <span id="checklist-missing-count" class="text-warning font-weight-bold">{{ $incomplete_sections }}</span> {{ translate('remaining') }}
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar Visual in Hero -->
                        <div style="min-width: 220px;">
                            <div class="d-flex justify-content-between small text-muted mb-1 font-weight-500">
                                <span>{{ translate('Overall Status') }}</span>
                                <span id="checklist-progress-text" class="text-dark font-weight-bold">{{ $completed_sections }}/{{ $total_sections }} {{ translate('Sections') }}</span>
                            </div>
                            <div class="progress" style="height: 9px; border-radius: 999px; background-color: #e2e8f0;">
                                <div id="checklist-hero-progress-bar" class="progress-bar" role="progressbar" style="width: {{ $profile_completion_percentage }}%; background: linear-gradient(90deg, #10b981 0%, #059669 100%); border-radius: 999px; transition: width 0.4s ease;" aria-valuenow="{{ $profile_completion_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                    <div class="btn-group btn-group-sm checklist-filter-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active checklist-filter-btn" data-filter="all">
                            {{ translate('All Sections') }} (<span id="tab-all-count">{{ $total_sections }}</span>)
                        </button>
                        <button type="button" class="btn btn-outline-warning checklist-filter-btn text-dark" data-filter="incomplete">
                            <i class="las la-exclamation-circle text-warning mr-1"></i> {{ translate('Missing Info') }} (<span id="tab-missing-count">{{ $incomplete_sections }}</span>)
                        </button>
                        <button type="button" class="btn btn-outline-success checklist-filter-btn" data-filter="completed">
                            <i class="las la-check-circle text-success mr-1"></i> {{ translate('Completed') }} (<span id="tab-completed-count">{{ $completed_sections }}</span>)
                        </button>
                    </div>
                    <span class="text-muted small">
                        <i class="las la-hand-point-right text-primary mr-1"></i> {{ translate('Click Fill Up to complete any section') }}
                    </span>
                </div>

                <!-- Sections Checklist Wrap -->
                <div class="checklist-items-wrap">
                    {{-- Profile Checklist Items --}}
                    <div id="checklist-view-profile">
                        @foreach($profile_sections_meta as $sec)
                        <div class="checklist-item-card p-3 mb-2 rounded-xl bg-white border border-stone-200/80 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between"
                            id="checklist-row-{{ $sec['card_id'] }}"
                            data-status="{{ $sec['is_completed'] ? 'completed' : 'incomplete' }}"
                            data-card="{{ $sec['card_id'] }}"
                            style="border-radius: 14px; transition: all 0.2s ease;">
                            
                            <div class="d-flex align-items-center mb-2 mb-sm-0 pr-sm-2">
                                <span class="checklist-item-icon-box mr-3 rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 {{ $sec['is_completed'] ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}" style="width: 42px; height: 42px; font-size: 20px;">
                                    <i class="{{ $sec['icon'] }}"></i>
                                </span>
                                <div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <h6 class="font-weight-bold text-dark mb-0 mr-2" style="font-size: 0.96rem;">
                                            {{ $sec['index'] }}. {{ $sec['name'] }}
                                        </h6>
                                        <span class="checklist-status-badge badge badge-inline rounded-pill {{ $sec['is_completed'] ? 'badge-soft-success' : 'badge-soft-warning' }}" style="font-size: 11px; font-weight: 600;">
                                            @if($sec['is_completed'])
                                                <i class="las la-check-circle mr-1"></i> {{ translate('Completed') }}
                                            @else
                                                <i class="las la-exclamation-circle mr-1"></i> {{ translate('Incomplete') }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="checklist-hint-text text-muted small mb-0 mt-1 {{ $sec['is_completed'] ? 'd-none' : '' }}" style="line-height: 1.35;">
                                        {{ $sec['missing_hint'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-shrink-0 mt-2 mt-sm-0 ml-sm-2">
                                <button type="button" class="btn btn-sm rounded-pill font-weight-bold px-3 btn-trigger-section-edit {{ $sec['is_completed'] ? 'btn-light text-secondary' : 'btn-primary' }}" 
                                    data-target="{{ $sec['modal_id'] }}"
                                    data-card="{{ $sec['card_id'] }}">
                                    @if($sec['is_completed'])
                                        <i class="las la-pen mr-1"></i> {{ translate('Edit') }}
                                    @else
                                        <i class="las la-plus-circle mr-1"></i> {{ translate('Fill Up') }}
                                    @endif
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Partner Preferences Checklist Items --}}
                    <div id="checklist-view-preferences" class="d-none">
                        @foreach($partner_pref_sections_meta as $psec)
                        <div class="checklist-item-card p-3 mb-2 rounded-xl bg-white border border-stone-200/80 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between"
                            id="checklist-row-{{ $psec['card_id'] }}"
                            data-status="{{ $psec['is_completed'] ? 'completed' : 'incomplete' }}"
                            data-card="{{ $psec['card_id'] }}"
                            data-tab="{{ $psec['tab'] }}"
                            style="border-radius: 14px; transition: all 0.2s ease;">
                            
                            <div class="d-flex align-items-center mb-2 mb-sm-0 pr-sm-2">
                                <span class="checklist-item-icon-box mr-3 rounded-circle d-inline-flex align-items-center justify-content-center flex-shrink-0 {{ $psec['is_completed'] ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}" style="width: 42px; height: 42px; font-size: 20px;">
                                    <i class="{{ $psec['icon'] }}"></i>
                                </span>
                                <div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <h6 class="font-weight-bold text-dark mb-0 mr-2" style="font-size: 0.96rem;">
                                            {{ $psec['index'] }}. {{ $psec['name'] }}
                                        </h6>
                                        <span class="checklist-status-badge badge badge-inline rounded-pill {{ $psec['is_completed'] ? 'badge-soft-success' : 'badge-soft-warning' }}" style="font-size: 11px; font-weight: 600;">
                                            @if($psec['is_completed'])
                                                <i class="las la-check-circle mr-1"></i> {{ translate('Completed') }}
                                            @else
                                                <i class="las la-exclamation-circle mr-1"></i> {{ translate('Incomplete') }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="checklist-hint-text text-muted small mb-0 mt-1 {{ $psec['is_completed'] ? 'd-none' : '' }}" style="line-height: 1.35;">
                                        {{ $psec['missing_hint'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-shrink-0 mt-2 mt-sm-0 ml-sm-2">
                                <button type="button" class="btn btn-sm rounded-pill font-weight-bold px-3 btn-trigger-section-edit {{ $psec['is_completed'] ? 'btn-light text-secondary' : 'btn-primary' }}" 
                                    data-target="{{ $psec['modal_id'] ?? ('#modal-edit-' . $psec['card_id']) }}"
                                    data-card="{{ $psec['card_id'] }}">
                                    @if($psec['is_completed'])
                                        <i class="las la-pen mr-1"></i> {{ translate('Edit') }}
                                    @else
                                        <i class="las la-plus-circle mr-1"></i> {{ translate('Fill Up') }}
                                    @endif
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    <span class="text-success font-weight-bold">&#10004;</span> {{ translate('All changes are saved instantly.') }}
                </div>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">
                    {{ translate('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Partner Preferences & Expectations --}}
@php
    $pe = $member->partner_expectations;
    $p_religion_id = optional($pe)->religion_id ?? '';
    $p_caste_id = optional($pe)->caste_id ?? '';
    $p_sub_caste_id = optional($pe)->sub_caste_id ?? '';
    $p_country_id = optional($pe)->preferred_country_id ?? '';
    $p_state_id = optional($pe)->preferred_state_id ?? '';
    $p_marital_status_id = optional($pe)->marital_status_id ?? '';
    $p_children = optional($pe)->children_acceptable ?? '';
    $p_language_id = optional($pe)->language_id ?? '';
    $p_family_val_id = optional($pe)->family_value_id ?? '';
    $p_diet = optional($pe)->diet ?? '';
    $p_smoke = optional($pe)->smoking_acceptable ?? '';
    $p_drink = optional($pe)->drinking_acceptable ?? '';
    $p_manglik = optional($pe)->manglik ?? '';
    $p_residence_country_id = optional($pe)->residence_country_id ?? '';

    $pe_rel_name = strtolower(optional(optional($pe)->religion)->name ?? '');
    $is_pref_islam = ($pe_rel_name === 'islam' || $p_religion_id == 1);
    $is_pref_hindu = ($pe_rel_name === 'hinduism' || $p_religion_id == 2);
    $is_pref_christian = ($pe_rel_name === 'christianity' || $p_religion_id == 3);
    $is_pref_buddhist = ($pe_rel_name === 'buddhism' || $p_religion_id == 4);
@endphp
{{-- 6 Modular Partner Preferences Edit Modals --}}

{{-- Modal Pref 1: Basic & Physical Requirements --}}
<div class="modal fade profile-modal" id="modal-edit-pref-basic" tabindex="-1" role="dialog" aria-labelledby="prefBasicModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border-bottom: 1px solid #ffe4e6;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #ffe4e6; color: #e11d48; font-size: 22px;">
                        <i class="las la-heart"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefBasicModalTitle" style="font-size: 1.15rem;">
                            {{ translate('1. Basic & Physical Requirements') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Set your preferences for partner height, weight, marital status, and physical attributes.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-basic">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Min Height (In Feet)') }}</label>
                            <input type="number" step="any" name="partner_height" value="{{ optional($pe)->height ?? '' }}" class="form-control" placeholder="{{ translate('e.g. 5.2') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Max Weight (In Kg)') }}</label>
                            <input type="number" step="any" name="partner_weight" value="{{ optional($pe)->weight ?? '' }}" class="form-control" placeholder="{{ translate('e.g. 65') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Marital Status') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_marital_status" data-live-search="true">
                                <option value="">{{ translate('Any Marital Status') }}</option>
                                @foreach ($marital_statuses as $marital_status)
                                    <option value="{{ $marital_status->id }}" @if($p_marital_status_id == $marital_status->id) selected @endif>{{ $marital_status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Children Acceptable?') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_children_acceptable">
                                <option value="">{{ translate('Select Option') }}</option>
                                <option value="no" @if($p_children == 'no') selected @endif>{{ translate('No') }}</option>
                                <option value="yes" @if($p_children == 'yes') selected @endif>{{ translate('Yes') }}</option>
                                <option value="dose_not_matter" @if($p_children == 'dose_not_matter') selected @endif>{{ translate('Does not matter') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Complexion') }}</label>
                            <select class="form-control aiz-selectpicker" name="pertner_complexion">
                                <option value="">{{ translate('Any Complexion') }}</option>
                                <option value="Very Fair" @if((optional($pe)->complexion ?? '') == 'Very Fair') selected @endif>{{ translate('Very Fair') }}</option>
                                <option value="Fair" @if((optional($pe)->complexion ?? '') == 'Fair') selected @endif>{{ translate('Fair') }}</option>
                                <option value="Wheatish" @if((optional($pe)->complexion ?? '') == 'Wheatish') selected @endif>{{ translate('Wheatish') }}</option>
                                <option value="Dark" @if((optional($pe)->complexion ?? '') == 'Dark') selected @endif>{{ translate('Dark') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Body Type') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_body_type">
                                <option value="">{{ translate('Any Body Type') }}</option>
                                <option value="Slim" @if((optional($pe)->body_type ?? '') == 'Slim') selected @endif>{{ translate('Slim') }}</option>
                                <option value="Average" @if((optional($pe)->body_type ?? '') == 'Average') selected @endif>{{ translate('Average') }}</option>
                                <option value="Athletic" @if((optional($pe)->body_type ?? '') == 'Athletic') selected @endif>{{ translate('Athletic') }}</option>
                                <option value="Heavy" @if((optional($pe)->body_type ?? '') == 'Heavy') selected @endif>{{ translate('Heavy') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pref 2: Religion & Community Preferences --}}
<div class="modal fade profile-modal" id="modal-edit-pref-religion" tabindex="-1" role="dialog" aria-labelledby="prefReligionModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #fff7ed 0%, #ffffff 100%); border-bottom: 1px solid #fed7aa;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #fff7ed; color: #ea580c; font-size: 22px;">
                        <i class="las la-place-of-worship"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefReligionModalTitle" style="font-size: 1.15rem;">
                            {{ translate('2. Religion & Community Preferences') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Specify preferred religion, sect, caste, language and values.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-religion">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Religion') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_religion_id" id="pref_partner_religion_id" data-live-search="true" onchange="get_pref_castes_by_religion()">
                                <option value="">{{ translate('Any Religion') }}</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" @if($p_religion_id == $religion->id) selected @endif>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="group-pref-partner-caste">
                            <label class="profile-field-label font-weight-600" id="label-pref-partner-caste">
                                @if($is_pref_islam) {{ translate('Sect / Mazhab') }}
                                @elseif($is_pref_hindu) {{ translate('Caste') }}
                                @elseif($is_pref_christian) {{ translate('Denomination') }}
                                @elseif($is_pref_buddhist) {{ translate('Sect / Community') }}
                                @else {{ translate('Sect / Mazhab / Caste') }}
                                @endif
                            </label>
                            <select class="form-control aiz-selectpicker" name="partner_caste_id" id="pref_partner_caste_id" data-live-search="true" onchange="get_pref_sub_castes_by_caste()">
                                <option value="">{{ translate('Any Option') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group @if(!$is_pref_hindu) d-none @endif" id="group-pref-partner-sub-caste">
                            <label class="profile-field-label font-weight-600" id="label-pref-partner-sub-caste">{{ translate('Sub-Caste / Gotra') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_sub_caste_id" id="pref_partner_sub_caste_id" data-live-search="true">
                                <option value="">{{ translate('Any Option') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Mother Tongue / Language') }}</label>
                            <select class="form-control aiz-selectpicker" name="language_id" data-live-search="true">
                                <option value="">{{ translate('Any Language') }}</option>
                                @foreach ($languages as $language)
                                    <option value="{{ $language->id }}" @if($p_language_id == $language->id) selected @endif>{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Family Value') }}</label>
                            <select class="form-control aiz-selectpicker" name="family_value_id">
                                <option value="">{{ translate('Any Family Value') }}</option>
                                @foreach ($family_values as $family_value)
                                    <option value="{{ $family_value->id }}" @if($p_family_val_id == $family_value->id) selected @endif>{{ $family_value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Personal Values') }}</label>
                            <input type="text" name="partner_personal_value" value="{{ optional($pe)->personal_value ?? '' }}" class="form-control" placeholder="{{ translate('e.g. Religious, Honest, Caring') }}">
                        </div>
                        <div class="col-md-6 form-group @if(!$is_pref_hindu) d-none @endif" id="group-pref-partner-manglik">
                            <label class="profile-field-label font-weight-600">{{ translate('Manglik') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_manglik" id="pref_partner_manglik">
                                <option value="">{{ translate('Select Option') }}</option>
                                <option value="dose_not_matter" @if($p_manglik == 'dose_not_matter') selected @endif>{{ translate('Does not matter') }}</option>
                                <option value="yes" @if($p_manglik == 'yes') selected @endif>{{ translate('Yes') }}</option>
                                <option value="no" @if($p_manglik == 'no') selected @endif>{{ translate('No') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pref 3: Education & Profession Preferences --}}
<div class="modal fade profile-modal" id="modal-edit-pref-education" tabindex="-1" role="dialog" aria-labelledby="prefEducationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-bottom: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #ecfdf5; color: #059669; font-size: 22px;">
                        <i class="las la-graduation-cap"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefEducationModalTitle" style="font-size: 1.15rem;">
                            {{ translate('3. Education & Profession Preferences') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Specify minimum education and preferred profession for your partner.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-education">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Minimum Education') }}</label>
                            <input type="text" name="pertner_education" value="{{ optional($pe)->education ?? '' }}" class="form-control" placeholder="{{ translate('e.g. Graduation / Master’s Degree') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Preferred Profession / Occupation') }}</label>
                            <input type="text" name="partner_profession" value="{{ optional($pe)->profession ?? '' }}" class="form-control" placeholder="{{ translate('e.g. Job Holder, Govt Service, Business, Any') }}">
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pref 4: Location & Residence Preferences --}}
<div class="modal fade profile-modal" id="modal-edit-pref-location" tabindex="-1" role="dialog" aria-labelledby="prefLocationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border-bottom: 1px solid #dbeafe;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb; font-size: 22px;">
                        <i class="las la-map-marker-alt"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefLocationModalTitle" style="font-size: 1.15rem;">
                            {{ translate('4. Location & Residence Preferences') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Specify your preferred residence country and location.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-location">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Current Residence Country') }}</label>
                            <select class="form-control aiz-selectpicker" name="residence_country_id" data-live-search="true">
                                <option value="">{{ translate('Any Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if($p_residence_country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Preferred Country') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_country_id" id="pref_partner_country_id" data-live-search="true">
                                <option value="">{{ translate('Any Preferred Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if($p_country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Preferred State / Division') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_state_id" id="pref_partner_state_id" data-live-search="true">
                                <option value="">{{ translate('Any State / Division') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pref 5: Lifestyle & Habits Preferences --}}
<div class="modal fade profile-modal" id="modal-edit-pref-lifestyle" tabindex="-1" role="dialog" aria-labelledby="prefLifestyleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #fdf4ff 0%, #ffffff 100%); border-bottom: 1px solid #fae8ff;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #fdf4ff; color: #c026d3; font-size: 22px;">
                        <i class="las la-glass-martini-alt"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefLifestyleModalTitle" style="font-size: 1.15rem;">
                            {{ translate('5. Lifestyle & Habits Preferences') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Specify your preferences regarding diet, smoking and drinking.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-lifestyle">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Diet Preference') }}</label>
                            <select class="form-control aiz-selectpicker" name="partner_diet">
                                <option value="">{{ translate('Any Diet') }}</option>
                                <option value="Non-Vegetarian" @if($p_diet == 'Non-Vegetarian') selected @endif>{{ translate('Non-Vegetarian') }}</option>
                                <option value="Vegetarian" @if($p_diet == 'Vegetarian') selected @endif>{{ translate('Vegetarian') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Smoking Acceptable?') }}</label>
                            <select class="form-control aiz-selectpicker" name="smoking_acceptable">
                                <option value="">{{ translate('Select Option') }}</option>
                                <option value="no" @if($p_smoke == 'no') selected @endif>{{ translate('No') }}</option>
                                <option value="yes" @if($p_smoke == 'yes') selected @endif>{{ translate('Yes') }}</option>
                                <option value="dose_not_matter" @if($p_smoke == 'dose_not_matter') selected @endif>{{ translate('Does not matter') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="profile-field-label font-weight-600">{{ translate('Drinking Acceptable?') }}</label>
                            <select class="form-control aiz-selectpicker" name="drinking_acceptable">
                                <option value="">{{ translate('Select Option') }}</option>
                                <option value="no" @if($p_drink == 'no') selected @endif>{{ translate('No') }}</option>
                                <option value="yes" @if($p_drink == 'yes') selected @endif>{{ translate('Yes') }}</option>
                                <option value="dose_not_matter" @if($p_drink == 'dose_not_matter') selected @endif>{{ translate('Does not matter') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Pref 6: Expectations Note --}}
<div class="modal fade profile-modal" id="modal-edit-pref-general" tabindex="-1" role="dialog" aria-labelledby="prefGeneralModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between px-4 py-3" style="background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border-bottom: 1px solid #ffe4e6;">
                <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle mr-3" style="width: 44px; height: 44px; background: #fff1f2; color: #e11d48; font-size: 22px;">
                        <i class="las la-comment-dots"></i>
                    </span>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 text-dark" id="prefGeneralModalTitle" style="font-size: 1.15rem;">
                            {{ translate('6. Expectations Note') }}
                        </h5>
                        <p class="mb-0 text-muted small">
                            {{ translate('Describe what qualities, character, and lifestyle you expect from your life partner.') }}
                        </p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('partner_expectations.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-pref-general">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="id" value="{{ $member->id }}">

                <div class="modal-body p-4" style="background-color: #fafaf9; max-height: calc(85vh - 140px); overflow-y: auto;">
                    <div class="form-group mb-0">
                        <label class="profile-field-label font-weight-600">{{ translate('What kind of life partner you are looking for') }}</label>
                        <textarea name="general" class="form-control rounded-xl p-3 w-100" rows="5" placeholder="{{ translate('Describe what qualities, character and lifestyle you expect from your life partner...') }}">{{ optional($pe)->general ?? '' }}</textarea>
                        <small class="text-muted d-block mt-2">{{ translate('This description is highlighted prominently on your public biodata view.') }}</small>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-white border-top border-stone-200/80 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">
                        {{ translate('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 font-weight-bold">
                        <i class="las la-save mr-1"></i> {{ translate('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 14 Modular Section Edit Modals --}}

{{-- Modal 1: Basic Information --}}
@php
    $ms_name = strtolower(optional(optional($member->member)->marital_status)->name ?? (string)optional($member->member)->marital_status);
    $ms_id = optional($member->member)->marital_status_id;
    $is_never_married = empty($ms_name) || str_contains($ms_name, 'never married') || $ms_id == 1;
@endphp
<div class="modal fade profile-modal" id="modal-edit-basic-info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-user"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Basic Information') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.basic_info_update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-1">
                @csrf
                <input type="hidden" name="id" value="{{ $member->id }}">
                <input type="hidden" name="on_behalf" value="{{ optional($member->member)->on_behalves_id ?? '' }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('First Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ $member->first_name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Last Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ $member->last_name ?? '' }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Gender') }} <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control aiz-selectpicker" required>
                                <option value="1" @if((optional($member->member)->gender ?? 1) == 1) selected @endif>{{ translate('Male') }}</option>
                                <option value="2" @if((optional($member->member)->gender ?? 1) == 2) selected @endif>{{ translate('Female') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Date Of Birth') }} <span class="text-danger">*</span></label>
                            <input type="text" name="date_of_birth" class="form-control" value="{{ !empty(optional($member->member)->birthday) ? date('Y-m-d', strtotime($member->member->birthday)) : '' }}" placeholder="YYYY-MM-DD" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Marital Status') }} <span class="text-danger">*</span></label>
                            <select name="marital_status" id="modal-basic-marital-status" class="form-control aiz-selectpicker" required>
                                @foreach ($marital_statuses as $ms)
                                    <option value="{{ $ms->id }}" @if((optional($member->member)->marital_status_id ?? '') == $ms->id) selected @endif>{{ $ms->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group @if($is_never_married) d-none @endif" id="group-number-of-children">
                            <label class="profile-field-label">{{ translate('Number Of Children') }}</label>
                            <input type="number" name="children" id="modal-basic-children-input" class="form-control" value="{{ optional($member->member)->children ?? 0 }}" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="profile-field-label">{{ translate('Annual Salary Range') }}</label>
                            <input type="number" name="annual_salary_range" id="modal-annual-salary-input" class="form-control" value="{{ optional($member->member)->annual_salary_range_id ?? '' }}" placeholder="{{ translate('Enter Annual Salary') }}" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ $member->email ?? '' }}" placeholder="{{ translate('Email Address') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ $member->phone ?? '' }}" placeholder="{{ translate('Phone Number') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: Personal Details --}}
<div class="modal fade profile-modal" id="modal-edit-personal-details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-id-card"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Personal Details') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('physical-attribute.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-2">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="eye_color" value="{{ optional($member->physical_attributes)->eye_color ?? 'Black' }}">
                <input type="hidden" name="hair_color" value="{{ optional($member->physical_attributes)->hair_color ?? 'Black' }}">
                <input type="hidden" name="body_art" value="{{ optional($member->physical_attributes)->body_art ?? 'None' }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Height (In Feet)') }} <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="height" class="form-control" value="{{ optional($member->physical_attributes)->height ?? '' }}" placeholder="e.g. 5.8" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Weight (In Kg)') }} <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="weight" class="form-control" value="{{ optional($member->physical_attributes)->weight ?? '' }}" placeholder="e.g. 72" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Body Type') }} <span class="text-danger">*</span></label>
                            <input type="text" name="body_type" class="form-control" value="{{ optional($member->physical_attributes)->body_type ?? '' }}" placeholder="e.g. Slim, Athletic, Medium" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Complexion') }} <span class="text-danger">*</span></label>
                            <input type="text" name="complexion" class="form-control" value="{{ optional($member->physical_attributes)->complexion ?? '' }}" placeholder="e.g. Fair, Wheatish" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Blood Group') }} <span class="text-danger">*</span></label>
                            <input type="text" name="blood_group" class="form-control" value="{{ optional($member->physical_attributes)->blood_group ?? '' }}" placeholder="e.g. B+, O+, A+" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Disability (if any)') }}</label>
                            <input type="text" name="disability" class="form-control" value="{{ optional($member->physical_attributes)->disability ?? '' }}" placeholder="None">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 3: Present Address --}}
<div class="modal fade profile-modal" id="modal-edit-contact-details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-map-marker"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Present Address') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('address.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-3">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="address_type" value="present">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Country') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="present_country_id" id="present_country_id" data-selected="{{ $present_country_id }}" data-live-search="true" required>
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if($present_country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('State / Division') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="present_state_id" id="present_state_id" data-live-search="true" required>
                                @if(!empty(optional($present_address)->state))
                                    <option value="{{ $present_address->state->id }}" selected>{{ $present_address->state->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('City / District') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="present_city_id" id="present_city_id" data-live-search="true" required>
                                @if(!empty(optional($present_address)->city))
                                    <option value="{{ $present_address->city->id }}" selected>{{ $present_address->city->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Postal Code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="present_postal_code" value="{{ $present_postal_code }}" class="form-control" placeholder="{{ translate('Postal Code') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="profile-field-label">{{ translate('Home no and road or lane number') }}</label>
                            <input type="text" name="present_home_road" value="{{ optional($present_address)->home_road ?? '' }}" class="form-control" placeholder="{{ translate('e.g. House #12, Road #4, Block B') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
    $pure_degrees = [
        'School & Higher Secondary' => [
            'SSC',
            'Dakhil',
            'O-Level',
            'SSC (Vocational)',
            'HSC',
            'Alim',
            'A-Level',
            'HSC (Vocational)',
            'HSC (BM / BMT)',
        ],
        'Diploma' => [
            'Diploma in Engineering',
            'Diploma in Medical Technology',
            'Diploma in Nursing & Midwifery',
            'Diploma in Agriculture',
            'Diploma in Commerce',
            'Diploma (Other)',
        ],
        'Bachelor / Undergraduate' => [
            'B.Sc (Engg.)',
            'B.Sc',
            'B.Sc (Hons)',
            'B.Sc (Pass)',
            'MBBS',
            'BDS',
            'B.Pharm',
            'B.Sc in Nursing',
            'BBA',
            'B.Com',
            'B.Com (Hons)',
            'B.A',
            'B.A (Hons)',
            'B.A (Pass)',
            'B.S.S',
            'B.S.S (Hons)',
            'LL.B',
            'LL.B (Hons)',
            'B.Ed',
            'B.F.A',
            'Fazil',
            'Dawra-e-Hadith',
        ],
        'Master / Postgraduate' => [
            'M.Sc (Engg.)',
            'M.Sc',
            'MBA',
            'EMBA',
            'MBM',
            'M.Com',
            'M.A',
            'M.S.S',
            'LL.M',
            'MD / MS',
            'FCPS',
            'MPH',
            'M.Pharm',
            'Kamil',
            'Post Graduate Diploma (PGD)',
        ],
        'Doctorate & Higher Research' => [
            'M.Phil',
            'Ph.D',
            'Post Doctorate',
        ],
        'Professional Qualifications' => [
            'CA',
            'CMA',
            'ACCA',
            'CS',
            'CFA',
            'Hafiz-e-Quran',
            'Other / Equivalent',
        ],
    ];
@endphp

{{-- Modal 4: Add Education --}}
<div class="modal fade profile-modal" id="modal-add-education" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-graduation-cap"></i></span>
                    <h5 class="modal-title">{{ translate('Add Degree / Education') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('education_store') }}" method="POST" class="profile-ajax-form education-add-form" data-card="card-4">
                @csrf
                <input type="hidden" name="user_id" value="{{ $member->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Degree') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="degree" id="add_education_degree" data-live-search="true" required>
                                <option value="">{{ translate('Select Degree') }}</option>
                                @foreach($pure_degrees as $group_label => $degrees)
                                    <optgroup label="{{ translate($group_label) }}">
                                        @foreach($degrees as $degree_opt)
                                            <option value="{{ $degree_opt }}">{{ $degree_opt }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Subject') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker education-subject-select" name="subject" id="add_education_subject" data-live-search="true" required>
                                <option value="">{{ translate('Select Degree First') }}</option>
                            </select>
                            <input type="text" name="custom_subject" id="add_custom_subject" class="form-control mt-2 d-none" placeholder="{{ translate('Enter custom subject name') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="profile-field-label">{{ translate('Institution') }} <span class="text-danger">*</span></label>
                            <input type="text" name="institution" class="form-control" placeholder="{{ translate('e.g. North South University, Dhaka College') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Passing / End Year') }}</label>
                            <input type="number" name="education_end" class="form-control" placeholder="e.g. 2024">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('CGPA / Result') }}</label>
                            <input type="text" name="result" class="form-control" placeholder="{{ translate('e.g. 5.00, 3.85, First Division') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Add Degree') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 4: Edit Education --}}
<div class="modal fade profile-modal" id="modal-edit-education" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-graduation-cap"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Degree / Education') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-education" action="" method="POST" class="profile-ajax-form education-edit-form" data-card="card-4">
                @csrf
                <input type="hidden" name="education_id" id="edit_education_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Degree') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="degree" id="edit_education_degree" data-live-search="true" required>
                                <option value="">{{ translate('Select Degree') }}</option>
                                @foreach($pure_degrees as $group_label => $degrees)
                                    <optgroup label="{{ translate($group_label) }}">
                                        @foreach($degrees as $degree_opt)
                                            <option value="{{ $degree_opt }}">{{ $degree_opt }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Subject') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker education-subject-select" name="subject" id="edit_education_subject" data-live-search="true" required>
                                <option value="">{{ translate('Select Degree First') }}</option>
                            </select>
                            <input type="text" name="custom_subject" id="edit_custom_subject" class="form-control mt-2 d-none" placeholder="{{ translate('Enter custom subject name') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="profile-field-label">{{ translate('Institution') }} <span class="text-danger">*</span></label>
                            <input type="text" name="institution" id="edit_education_institution" class="form-control" placeholder="{{ translate('e.g. North South University, Dhaka College') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Passing / End Year') }}</label>
                            <input type="number" name="education_end" id="edit_education_end" class="form-control" placeholder="e.g. 2024">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('CGPA / Result') }}</label>
                            <input type="text" name="result" id="edit_education_result" class="form-control" placeholder="{{ translate('e.g. 5.00, 3.85, First Division') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 5: Career --}}
@php
    $c_status = optional($current_career)->profession_status ?? '';
    if (empty($c_status) && (!empty(optional($current_career)->designation) || !empty(optional($current_career)->company))) {
        $c_status = 'Job Holder';
    }
    $c_job_type = optional($current_career)->job_type ?? '';
    $c_occupation = optional($current_career)->occupation ?? '';
    $c_company = optional($current_career)->company ?? '';
    $c_designation = optional($current_career)->designation ?? '';
@endphp
<div class="modal fade profile-modal" id="modal-edit-career" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-briefcase"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Career Information') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if(!empty($current_career))
            <form action="{{ route('career_update', $current_career->id) }}" method="POST" class="profile-ajax-form" data-card="card-5">
            @else
            <form action="{{ route('career_store') }}" method="POST" class="profile-ajax-form" data-card="card-5">
                <input type="hidden" name="user_id" value="{{ $member->id }}">
            @endif
                @csrf
                <div class="modal-body">
                    {{-- Profession / Employment Status --}}
                    <div class="form-group mb-3">
                        <label class="profile-field-label font-weight-600">{{ translate('Profession / Employment Status') }} <span class="text-danger">*</span></label>
                        <select class="form-control aiz-selectpicker" name="profession_status" id="career_profession_status" required>
                            <option value="">{{ translate('Select Profession / Status') }}</option>
                            <option value="Job Holder" @if($c_status == 'Job Holder') selected @endif>{{ translate('Job Holder') }}</option>
                            <option value="Business" @if($c_status == 'Business') selected @endif>{{ translate('Business') }}</option>
                            <option value="Student" @if($c_status == 'Student') selected @endif>{{ translate('Student') }}</option>
                            <option value="Freelancer" @if($c_status == 'Freelancer') selected @endif>{{ translate('Freelancer / Self-Employed') }}</option>
                            <option value="Not Employed" @if($c_status == 'Not Employed') selected @endif>{{ translate('Not Employed / Looking for Job') }}</option>
                            <option value="Homemaker" @if($c_status == 'Homemaker') selected @endif>{{ translate('Homemaker') }}</option>
                            <option value="Other" @if($c_status == 'Other') selected @endif>{{ translate('Other') }}</option>
                        </select>
                    </div>

                    {{-- Dynamic Container: Job Holder Fields --}}
                    <div id="career_job_holder_fields" class="@if($c_status !== 'Job Holder') d-none @endif bg-light p-3 rounded-lg mb-3 border">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-building mr-1"></i> {{ translate('Job Details') }}</h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Job Type / Sector') }} <span class="text-danger">*</span></label>
                                <select class="form-control aiz-selectpicker" name="job_type" id="career_job_type">
                                    <option value="">{{ translate('Select Job Type') }}</option>
                                    @php
                                        $job_types = [
                                            'Govt. Service',
                                            'Semi-Govt / Autonomous',
                                            'Private Service',
                                            'Bank / Financial Institution',
                                            'NGO / Multinational Org',
                                            'Armed Forces / Police',
                                            'Other'
                                        ];
                                    @endphp
                                    @foreach($job_types as $jt)
                                        <option value="{{ $jt }}" @if($c_job_type == $jt) selected @endif>{{ translate($jt) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Occupation (পেশা)') }}</label>
                                <input type="text" name="occupation" id="career_job_occupation" class="form-control" value="{{ $c_occupation }}" placeholder="{{ translate('e.g. Software Engineer, Doctor, Banker, Teacher') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Company / Organization Name') }}</label>
                                <input type="text" name="company" id="career_job_company" class="form-control" value="{{ $c_company }}" placeholder="{{ translate('e.g. Bangladesh Bank, Walton, ABC Ltd') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Designation (পদবি)') }}</label>
                                <input type="text" name="designation" id="career_job_designation" class="form-control" value="{{ $c_designation }}" placeholder="{{ translate('e.g. Senior Software Engineer, Assistant Director, Manager') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Container: Business Fields --}}
                    <div id="career_business_fields" class="@if($c_status !== 'Business') d-none @endif bg-light p-3 rounded-lg mb-3 border">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-store mr-1"></i> {{ translate('Business Details') }}</h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Occupation (পেশা)') }}</label>
                                <input type="text" name="business_occupation" id="career_business_occupation" class="form-control" value="{{ $c_occupation }}" placeholder="{{ translate('e.g. Businessman, Entrepreneur, Trader') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Business / Enterprise Name') }}</label>
                                <input type="text" name="business_company" id="career_business_company" class="form-control" value="{{ $c_company }}" placeholder="{{ translate('e.g. ABC Trading, XYZ Agency') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Designation / Role (পদবি)') }}</label>
                                <input type="text" name="business_designation" id="career_business_designation" class="form-control" value="{{ $c_designation }}" placeholder="{{ translate('e.g. Managing Director, Owner, Partner') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Business Sector / Type') }}</label>
                                <input type="text" name="business_job_type" id="career_business_job_type" class="form-control" value="{{ $c_job_type }}" placeholder="{{ translate('e.g. IT, Import-Export, Retail, Manufacturing') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Container: Student Fields --}}
                    <div id="career_student_fields" class="@if($c_status !== 'Student') d-none @endif bg-light p-3 rounded-lg mb-3 border">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-graduation-cap mr-1"></i> {{ translate('Student Information') }}</h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Institution / University Name') }}</label>
                                <input type="text" name="student_institution" id="career_student_institution" class="form-control" value="{{ $c_company }}" placeholder="{{ translate('e.g. University of Dhaka, BUET') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Department / Level / Subject') }}</label>
                                <input type="text" name="student_subject" id="career_student_subject" class="form-control" value="{{ $c_designation }}" placeholder="{{ translate('e.g. CSE, BBA, Masters, Honors') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Container: Freelancer / Other Fields --}}
                    <div id="career_other_fields" class="@if(!in_array($c_status, ['Freelancer', 'Other'])) d-none @endif bg-light p-3 rounded-lg mb-3 border">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-laptop mr-1"></i> {{ translate('Profession Details') }}</h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Occupation / Field of Work') }}</label>
                                <input type="text" name="other_occupation" id="career_other_occupation" class="form-control" value="{{ $c_occupation }}" placeholder="{{ translate('e.g. Graphic Designer, Web Developer, Consultant') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Platform / Organization / Details') }}</label>
                                <input type="text" name="other_company" id="career_other_company" class="form-control" value="{{ $c_company }}" placeholder="{{ translate('e.g. Upwork, Freelance, Private Practice') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Notice for Not Employed / Homemaker --}}
                    <div id="career_empty_notice" class="@if(!in_array($c_status, ['Not Employed', 'Homemaker'])) d-none @endif alert alert-light border text-muted">
                        <i class="las la-info-circle mr-1"></i> {{ translate('No additional details required for this status.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal 7: Language --}}
<div class="modal fade profile-modal" id="modal-edit-language" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-language"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Language') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.language_info_update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-7">
                @csrf
                <div class="modal-body w-full space-y-4">
                    <div class="form-group mb-3">
                        <label class="profile-field-label font-weight-bold d-block mb-1">Mother Tongue</label>
                        @php
                            $current_mother_tongue = !empty(optional(optional($member->member)->mothereTongue)->name) 
                                ? optional(optional($member->member)->mothereTongue)->name 
                                : (optional($member->member)->mothere_tongue ?? '');
                        @endphp
                        <input type="text" name="mothere_tongue" value="{{ $current_mother_tongue }}" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem;" placeholder="{{ translate('e.g. Bengali') }}">
                    </div>
                    <div class="form-group mb-0">
                        <label class="profile-field-label font-weight-bold d-block mb-1">{{ translate('Known Languages') }}</label>
                        @php 
                            $raw_known = optional($member->member)->known_languages;
                            $known_arr = !empty($raw_known) ? json_decode($raw_known, true) : null;
                            if (is_array($known_arr) && !empty($known_arr)) {
                                $current_known = implode(', ', \App\Models\MemberLanguage::whereIn('id', (array)$known_arr)->pluck('name')->toArray());
                            } else {
                                $current_known = $raw_known ?? '';
                            }
                        @endphp
                        <input type="text" name="known_languages" value="{{ $current_known }}" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem;" placeholder="{{ translate('e.g. Bengali, English, Hindi') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 8: Hobbies & Interests --}}
<div class="modal fade profile-modal" id="modal-edit-hobbies" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-heart"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Hobbies & Interests') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('hobbies.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-8">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <div class="modal-body w-full space-y-4">
                    <div class="form-group mb-3">
                        <label class="profile-field-label font-weight-bold d-block mb-1">{{ translate('Hobbies') }}</label>
                        <textarea rows="3" name="hobbies" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem; resize: vertical;" placeholder="{{ translate('e.g. Reading, Traveling, Photography') }}">{{ optional($member->hobbies)->hobbies ?? '' }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="profile-field-label font-weight-bold d-block mb-1">{{ translate('Interests') }}</label>
                        <textarea rows="3" name="interests" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem; resize: vertical;" placeholder="{{ translate('e.g. AI & Tech, Music, Fitness') }}">{{ optional($member->hobbies)->interests ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 9: Family Information --}}
@php
    $parent_data = !empty(optional($member->families)->about_parents) ? json_decode($member->families->about_parents, true) : [];
    $sibling_data = !empty(optional($member->families)->about_siblings) ? json_decode($member->families->about_siblings, true) : [];

    $f_sec = $parent_data['father_sector'] ?? '';
    $f_pos = $parent_data['father_position'] ?? '';
    if (empty($f_sec) && !empty(optional($member->families)->father_occupation)) {
        $parts = explode(' - ', $member->families->father_occupation, 2);
        $f_sec = $parts[0] ?? '';
        $f_pos = $parts[1] ?? '';
    }

    $m_sec = $parent_data['mother_sector'] ?? '';
    $m_pos = $parent_data['mother_position'] ?? '';
    if (empty($m_sec) && !empty(optional($member->families)->mother_occupation)) {
        $parts = explode(' - ', $member->families->mother_occupation, 2);
        $m_sec = $parts[0] ?? '';
        $m_pos = $parts[1] ?? '';
    }

    $b_status = $sibling_data['brother_status'] ?? '';
    $b_detail = $sibling_data['brother_detail'] ?? '';
    $s_status = $sibling_data['sister_status'] ?? '';
    $s_detail = $sibling_data['sister_detail'] ?? '';
    $num_brothers = optional($member->families)->no_of_brothers ?? 0;
    $num_sisters = optional($member->families)->no_of_sisters ?? 0;
@endphp
<div class="modal fade profile-modal" id="modal-edit-family" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-users"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Family Information') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('families.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-9">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <div class="modal-body">
                    {{-- Father Details --}}
                    <div class="bg-light p-3 rounded-lg mb-3">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-male mr-1"></i> {{ translate("Father's Information") }}</h6>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate("Father's Name") }}</label>
                                <input type="text" name="father" value="{{ optional($member->families)->father ?? '' }}" class="form-control" placeholder="{{ translate("Father's Name") }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate("Sector / Occupation Type") }}</label>
                                <select class="form-control aiz-selectpicker" name="father_sector" id="family_father_sector" data-live-search="true">
                                    <option value="">{{ translate('Select Sector') }}</option>
                                    @php
                                        $f_sectors = [
                                            'Govt. Service', 'Semi-Govt / Autonomous', 'Private Service',
                                            'Business', 'Teacher / Professor', 'Doctor',
                                            'Engineer', 'Lawyer', 'Armed Forces / Police',
                                            'Expatriate / NRB', 'Retired', 'Deceased', 'Other'
                                        ];
                                    @endphp
                                    @foreach($f_sectors as $sec)
                                        <option value="{{ $sec }}" @if($f_sec == $sec) selected @endif>{{ translate($sec) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group @if($f_sec == 'Deceased') d-none @endif" id="family_father_position_group">
                                <label class="profile-field-label font-weight-600">{{ translate("Position / Organization") }}</label>
                                <input type="text" name="father_position" id="family_father_position" value="{{ $f_pos }}" class="form-control" placeholder="{{ translate('e.g. Officer, Manager, Owner') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Mother Details --}}
                    <div class="bg-light p-3 rounded-lg mb-3">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-female mr-1"></i> {{ translate("Mother's Information") }}</h6>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate("Mother's Name") }}</label>
                                <input type="text" name="mother" value="{{ optional($member->families)->mother ?? '' }}" class="form-control" placeholder="{{ translate("Mother's Name") }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate("Sector / Occupation Type") }}</label>
                                <select class="form-control aiz-selectpicker" name="mother_sector" id="family_mother_sector" data-live-search="true">
                                    <option value="">{{ translate('Select Sector') }}</option>
                                    @php
                                        $m_sectors = [
                                            'Homemaker', 'Govt. Service', 'Private Service',
                                            'Teacher / Professor', 'Doctor', 'Business',
                                            'Retired', 'Deceased', 'Other'
                                        ];
                                    @endphp
                                    @foreach($m_sectors as $sec)
                                        <option value="{{ $sec }}" @if($m_sec == $sec) selected @endif>{{ translate($sec) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group @if(in_array($m_sec, ['Homemaker', 'Deceased']) || empty($m_sec)) d-none @endif" id="family_mother_position_group">
                                <label class="profile-field-label font-weight-600">{{ translate("Position / Organization") }}</label>
                                <input type="text" name="mother_position" id="family_mother_position" value="{{ $m_pos }}" class="form-control" placeholder="{{ translate('e.g. Teacher, Officer, Owner') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Siblings Details --}}
                    <div class="bg-light p-3 rounded-lg">
                        <h6 class="font-weight-bold text-primary mb-3"><i class="las la-user-friends mr-1"></i> {{ translate("Siblings Information") }}</h6>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Number of Brothers') }}</label>
                                <select class="form-control aiz-selectpicker" name="no_of_brothers" id="family_no_of_brothers">
                                    @for($i=0; $i<=15; $i++)
                                        <option value="{{ $i }}" @if($num_brothers == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="profile-field-label font-weight-600">{{ translate('Number of Sisters') }}</label>
                                <select class="form-control aiz-selectpicker" name="no_of_sisters" id="family_no_of_sisters">
                                    @for($i=0; $i<=15; $i++)
                                        <option value="{{ $i }}" @if($num_sisters == $i) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Dynamic Brother Fields --}}
                        <div id="family_brother_dynamic_wrap" class="@if($num_brothers <= 0) d-none @endif border-top pt-2 mt-2">
                            <label class="font-weight-bold text-dark fs-13"><i class="las la-male text-primary"></i> {{ translate("Brother's Profession / Activity") }}</label>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="profile-field-label font-weight-600">{{ translate("Brother's Status") }}</label>
                                    <select class="form-control aiz-selectpicker" name="brother_status" id="family_brother_status">
                                        <option value="">{{ translate('Select Status') }}</option>
                                        <option value="Student" @if($b_status == 'Student') selected @endif>{{ translate('Student') }}</option>
                                        <option value="Job Holder" @if($b_status == 'Job Holder') selected @endif>{{ translate('Job Holder') }}</option>
                                        <option value="Business" @if($b_status == 'Business') selected @endif>{{ translate('Business') }}</option>
                                        <option value="Other" @if($b_status == 'Other') selected @endif>{{ translate('Other') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group @if(empty($b_status)) d-none @endif" id="family_brother_detail_group">
                                    <label class="profile-field-label font-weight-600" id="family_brother_detail_label">
                                        @if($b_status == 'Student')
                                            {{ translate('School / College / University') }}
                                        @elseif($b_status == 'Job Holder')
                                            {{ translate('Company & Designation') }}
                                        @elseif($b_status == 'Business')
                                            {{ translate('Business Details') }}
                                        @else
                                            {{ translate('Details / Activity') }}
                                        @endif
                                    </label>
                                    <input type="text" name="brother_detail" id="family_brother_detail" value="{{ $b_detail }}" class="form-control" placeholder="{{ translate('Enter details') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Sister Fields --}}
                        <div id="family_sister_dynamic_wrap" class="@if($num_sisters <= 0) d-none @endif border-top pt-2 mt-2">
                            <label class="font-weight-bold text-dark fs-13"><i class="las la-female text-danger"></i> {{ translate("Sister's Profession / Activity") }}</label>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="profile-field-label font-weight-600">{{ translate("Sister's Status") }}</label>
                                    <select class="form-control aiz-selectpicker" name="sister_status" id="family_sister_status">
                                        <option value="">{{ translate('Select Status') }}</option>
                                        <option value="Student" @if($s_status == 'Student') selected @endif>{{ translate('Student') }}</option>
                                        <option value="Job Holder" @if($s_status == 'Job Holder') selected @endif>{{ translate('Job Holder') }}</option>
                                        <option value="Homemaker" @if($s_status == 'Homemaker') selected @endif>{{ translate('Homemaker') }}</option>
                                        <option value="Business" @if($s_status == 'Business') selected @endif>{{ translate('Business') }}</option>
                                        <option value="Other" @if($s_status == 'Other') selected @endif>{{ translate('Other') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group @if(empty($s_status) || $s_status == 'Homemaker') d-none @endif" id="family_sister_detail_group">
                                    <label class="profile-field-label font-weight-600" id="family_sister_detail_label">
                                        @if($s_status == 'Student')
                                            {{ translate('School / College / University') }}
                                        @elseif($s_status == 'Job Holder')
                                            {{ translate('Company & Designation') }}
                                        @elseif($s_status == 'Business')
                                            {{ translate('Business Details') }}
                                        @else
                                            {{ translate('Details / Activity') }}
                                        @endif
                                    </label>
                                    <input type="text" name="sister_detail" id="family_sister_detail" value="{{ $s_detail }}" class="form-control" placeholder="{{ translate('Enter details') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 10: Lifestyle & Preferences --}}
<div class="modal fade profile-modal" id="modal-edit-lifestyle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-cocktail"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Lifestyle & Preferences') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('lifestyles.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-10">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="living_with" value="{{ optional($member->lifestyles)->living_with ?? 'Family' }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Smoking') }}</label>
                            @php $smoke_val = strtolower(optional($member->lifestyles)->smoke ?? 'no'); @endphp
                            <select class="form-control aiz-selectpicker" name="smoke">
                                <option value="no" @if($smoke_val == 'no') selected @endif>{{ translate('No') }}</option>
                                <option value="yes" @if($smoke_val == 'yes') selected @endif>{{ translate('Yes') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Drinking') }}</label>
                            @php $drink_val = strtolower(optional($member->lifestyles)->drink ?? 'no'); @endphp
                            <select class="form-control aiz-selectpicker" name="drink">
                                <option value="no" @if($drink_val == 'no') selected @endif>{{ translate('No') }}</option>
                                <option value="yes" @if($drink_val == 'yes') selected @endif>{{ translate('Yes') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Food Preference / Diet') }}</label>
                            @php $diet_val = strtolower(optional($member->lifestyles)->diet ?? 'no'); @endphp
                            <select class="form-control aiz-selectpicker" name="diet">
                                <option value="no" @if($diet_val == 'no') selected @endif>{{ translate('Non-Vegetarian') }}</option>
                                <option value="yes" @if($diet_val == 'yes') selected @endif>{{ translate('Vegetarian') }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Living With') }}</label>
                            <input type="text" name="living_with" value="{{ optional($member->lifestyles)->living_with ?? 'Family' }}" class="form-control" placeholder="e.g. Family, Alone">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 11: Residency Information --}}
<div class="modal fade profile-modal" id="modal-edit-residency" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-home"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Residency Information') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('recidencies.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-11">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Current Residence (Country)') }}</label>
                            <select class="form-control aiz-selectpicker" name="recidency_country_id" data-selected="{{ optional($member->recidency)->recidency_country_id ?? '' }}" data-live-search="true">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if((optional($member->recidency)->recidency_country_id ?? '') == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Permanent Residence (Birth Country)') }}</label>
                            <select class="form-control aiz-selectpicker" name="birth_country_id" data-selected="{{ optional($member->recidency)->birth_country_id ?? '' }}" data-live-search="true">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if((optional($member->recidency)->birth_country_id ?? '') == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Grow-up Country') }}</label>
                            <select class="form-control aiz-selectpicker" name="growup_country_id" data-selected="{{ optional($member->recidency)->growup_country_id ?? '' }}" data-live-search="true">
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if((optional($member->recidency)->growup_country_id ?? '') == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Immigration Status') }}</label>
                            <input type="text" name="immigration_status" value="{{ optional($member->recidency)->immigration_status ?? '' }}" class="form-control" placeholder="e.g. Citizen, Permanent Resident">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 12: Religion --}}
<div class="modal fade profile-modal" id="modal-edit-spiritual" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-place-of-worship"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Religion') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('spiritual_backgrounds.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-12">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group" id="group-member-religion">
                            <label class="profile-field-label font-weight-bold mb-1">{{ translate('Religion') }} <span class="text-danger">*</span></label>
                            <select class="form-control rounded-xl border border-stone-300 px-3" name="member_religion_id" id="modal_member_religion_id" style="height: 46px; border-radius: 0.75rem;" required>
                                <option value="">{{ translate('Select Religion') }}</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" @if($member_religion_id == $religion->id) selected @endif>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="group-member-caste">
                            <label class="profile-field-label font-weight-bold mb-1" id="label-member-caste">{{ translate('Sect / Mazhab') }}</label>
                            <select class="form-control rounded-xl border border-stone-300 px-3" name="member_caste_id" id="modal_member_caste_id" style="height: 46px; border-radius: 0.75rem;">
                                @if(!empty(optional($member->spiritual_backgrounds)->caste))
                                    <option value="{{ $member->spiritual_backgrounds->caste->id }}" selected>{{ $member->spiritual_backgrounds->caste->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="group-member-sub-caste">
                            <label class="profile-field-label font-weight-bold mb-1" id="label-member-sub-caste">{{ translate('Sub-Sect / School') }}</label>
                            <select class="form-control rounded-xl border border-stone-300 px-3" name="member_sub_caste_id" id="modal_member_sub_caste_id" style="height: 46px; border-radius: 0.75rem;">
                                @if(!empty(optional($member->spiritual_backgrounds)->sub_caste))
                                    <option value="{{ $member->spiritual_backgrounds->sub_caste->id }}" selected>{{ $member->spiritual_backgrounds->sub_caste->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 form-group" id="group-family-values">
                            <label class="profile-field-label font-weight-bold mb-1">{{ translate('Family Values') }}</label>
                            @php
                                $fv_items = (!empty($family_values) && count($family_values) > 0) ? $family_values : \App\Models\FamilyValue::all();
                            @endphp
                            <select class="form-control rounded-xl border border-stone-300 px-3" name="family_value_id" id="modal_family_value_id" style="height: 46px; border-radius: 0.75rem;">
                                <option value="">{{ translate('Select One') }}</option>
                                @foreach ($fv_items as $fv)
                                    <option value="{{ $fv->id }}" @if((optional($member->spiritual_backgrounds)->family_value_id ?? '') == $fv->id) selected @endif>{{ $fv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 13: Permanent Address --}}
<div class="modal fade profile-modal" id="modal-edit-permanent-address" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-map-marker-alt"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Permanent Address') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('address.update', $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-13">
                @csrf
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="address_type" value="permanent">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Country') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="permanent_country_id" id="permanent_country_id" data-selected="{{ $permanent_country_id }}" data-live-search="true" required>
                                <option value="">{{ translate('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if($permanent_country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Division / State') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="permanent_state_id" id="permanent_state_id" data-live-search="true" required>
                                @if(!empty(optional($permanent_address)->state))
                                    <option value="{{ $permanent_address->state->id }}" selected>{{ $permanent_address->state->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('District / City') }} <span class="text-danger">*</span></label>
                            <select class="form-control aiz-selectpicker" name="permanent_city_id" id="permanent_city_id" data-live-search="true" required>
                                @if(!empty(optional($permanent_address)->city))
                                    <option value="{{ $permanent_address->city->id }}" selected>{{ $permanent_address->city->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Postal Code') }} <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_postal_code" value="{{ $permanent_postal_code }}" class="form-control" placeholder="{{ translate('Postal Code') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="profile-field-label">{{ translate('Home no and road or lane number') }}</label>
                            <input type="text" name="permanent_home_road" value="{{ optional($permanent_address)->home_road ?? '' }}" class="form-control" placeholder="{{ translate('e.g. House #12, Road #4, Block B') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 14: Additional Information --}}
<div class="modal fade profile-modal" id="modal-edit-additional-info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-file-alt"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Additional Information') }}</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.introduction.update', optional($member->member)->id ?? $member->id) }}" method="POST" class="profile-ajax-form" data-card="card-14">
                @csrf
                <div class="modal-body w-full space-y-4">
                    <div class="form-group mb-3">
                        <label class="profile-field-label font-weight-bold d-block mb-1">{{ translate('About Yourself / Introduction') }} <span class="text-danger">*</span></label>
                        <textarea name="introduction" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem; resize: vertical;" rows="4" placeholder="{{ translate('Describe yourself, personality, interests and values...') }}" required>{{ optional($member->member)->introduction ?? '' }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label class="profile-field-label font-weight-bold d-block mb-1">{{ translate('Expectations from Partner') }}</label>
                        <textarea name="expectations" class="form-control rounded-xl border border-stone-300 focus:ring-2 focus:ring-brand-maroon/20 p-3 w-100" style="border-radius: 0.75rem; resize: vertical;" rows="4" placeholder="{{ translate('Describe what kind of partner and values you are looking for...') }}">{{ optional($member->partner_expectations)->general ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
