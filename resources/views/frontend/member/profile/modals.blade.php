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
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Designation / Occupation') }} <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" value="{{ $current_career->designation ?? '' }}" placeholder="e.g. Software Engineer" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Company Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="company" class="form-control" value="{{ $current_career->company ?? '' }}" placeholder="e.g. Tech Company" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Start Year') }} <span class="text-danger">*</span></label>
                            <input type="number" name="career_start" class="form-control" value="{{ $current_career->start ?? '' }}" placeholder="e.g. 2019" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('End Year') }}</label>
                            <input type="number" name="career_end" class="form-control" value="{{ $current_career->end ?? '' }}" placeholder="Leave blank if currently working">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Save Changes') }}</button>
                </div>
            </form>
            @else
            <form action="{{ route('career_store') }}" method="POST" class="profile-ajax-form" data-card="card-5">
                @csrf
                <input type="hidden" name="user_id" value="{{ $member->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Designation / Occupation') }} <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Software Engineer" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Company Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="company" class="form-control" placeholder="e.g. Tech Company" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Start Year') }} <span class="text-danger">*</span></label>
                            <input type="number" name="career_start" class="form-control" placeholder="e.g. 2019" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('End Year') }}</label>
                            <input type="number" name="career_end" class="form-control" placeholder="Leave blank if currently working">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ translate('Add Career') }}</button>
                </div>
            </form>
            @endif
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
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate("Father's Name") }}</label>
                            <input type="text" name="father" value="{{ optional($member->families)->father ?? '' }}" class="form-control" placeholder="{{ translate("Father's Name") }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate("Father's Occupation") }}</label>
                            <input type="text" name="father_occupation" value="{{ optional($member->families)->father_occupation ?? '' }}" class="form-control" placeholder="e.g. Business, Government Officer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate("Mother's Name") }}</label>
                            <input type="text" name="mother" value="{{ optional($member->families)->mother ?? '' }}" class="form-control" placeholder="{{ translate("Mother's Name") }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate("Mother's Occupation") }}</label>
                            <input type="text" name="mother_occupation" value="{{ optional($member->families)->mother_occupation ?? '' }}" class="form-control" placeholder="e.g. Housewife, Teacher">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Number of Brothers') }}</label>
                            <select class="form-control aiz-selectpicker" name="no_of_brothers" data-selected="{{ optional($member->families)->no_of_brothers ?? 0 }}">
                                @for($i=0; $i<=15; $i++)
                                    <option value="{{ $i }}" @if((optional($member->families)->no_of_brothers ?? '') == $i) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="profile-field-label">{{ translate('Number of Sisters') }}</label>
                            <select class="form-control aiz-selectpicker" name="no_of_sisters" data-selected="{{ optional($member->families)->no_of_sisters ?? 0 }}">
                                @for($i=0; $i<=15; $i++)
                                    <option value="{{ $i }}" @if((optional($member->families)->no_of_sisters ?? '') == $i) selected @endif>{{ $i }}</option>
                                @endfor
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

{{-- Modal 12: Spiritual & Social Background --}}
<div class="modal fade profile-modal" id="modal-edit-spiritual" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="profile-card-icon-badge mr-2"><i class="las la-place-of-worship"></i></span>
                    <h5 class="modal-title">{{ translate('Edit Spiritual & Social Background') }}</h5>
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
