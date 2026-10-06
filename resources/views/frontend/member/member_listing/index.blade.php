@extends('frontend.layouts.app')

@section('content')

    <!-- 1. Page Header & Hero Banner -->
    <section class="matches-hero-banner position-relative">
        <div class="matches-hero-overlay"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 position-relative"
            style="padding-top: 56px; padding-bottom: 56px; z-index: 2;">
            <div style="max-width: 580px;">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-3 fs-13" style="margin: 0; background: none;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                class="text-muted text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item active text-dark fw-600" aria-current="page">Find Matches</li>
                    </ol>
                </nav>

                <h1 class="matches-hero-title mb-3 font-serif fw-700 text-mat-maroon-dark"
                    style="font-size: 38px; line-height: 1.2;">
                    Find Your Perfect Match</h1>
                <p class="matches-hero-subtitle mb-0 text-muted" style="font-size: 15px; line-height: 1.6;">
                    Discover genuine connections with like-minded people and build a beautiful future together.
                </p>
            </div>
        </div>
    </section>

    <!-- 2. Clean Contained 2-Column Desktop Layout Wrapper -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="padding-top: 36px; padding-bottom: 60px;">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- 3. Left Column (25% - 28% width): Strictly ONE Single Filter Sidebar -->
            <aside class="lg:col-span-3">
                <div class="filter-card-container bg-white rounded-2xl shadow-sm border border-stone-200/80 p-5"
                    style="border-radius: 16px; border: 1px solid var(--mat-border); padding: 22px; background: #FFFFFF; box-shadow: 0 4px 18px rgba(0,0,0,0.04);">

                    <!-- Filter Header -->
                    <div class="filter-sidebar-header d-flex align-items-center mb-4 pb-3"
                        style="border-bottom: 1px solid var(--mat-border-light); gap: 10px;">
                        <i class="las la-sliders-h fs-20 text-mat-gold"></i>
                        <span class="fs-18 fw-700 text-mat-maroon-dark">{{ translate('Filter Matches') }}</span>
                    </div>

                    <form action="{{ route('member.listing') }}" method="get" id="filter-form">
                        <input type="hidden" name="sort" id="filter-sort-input" value="{{ request('sort', 'latest') }}">
                        <!-- 1. Looking For -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-user-tag fs-15 text-mat-gold"></i>
                                <span>{{ translate('Looking For') }}</span>
                            </label>
                            <select name="gender" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('All / Any') }}</option>
                                <option value="2" @if(request('gender') == '2') selected @endif>
                                    {{ translate('Bride (Female)') }}</option>
                                <option value="1" @if(request('gender') == '1') selected @endif>
                                    {{ translate('Groom (Male)') }}</option>
                            </select>
                        </div>

                        <!-- 2. Age Range -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-history fs-15 text-mat-gold"></i>
                                <span>{{ translate('Age Range') }}</span>
                            </label>
                            <select name="age_range" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Age') }}</option>
                                <option value="18-25" @if(request('age_range') == '18-25') selected @endif>18 - 25</option>
                                <option value="26-32" @if(request('age_range') == '26-32') selected @endif>26 - 32</option>
                                <option value="33-40" @if(request('age_range') == '33-40') selected @endif>33 - 40</option>
                                <option value="40+" @if(request('age_range') == '40+') selected @endif>40+</option>
                            </select>
                        </div>

                        <!-- 3. Religion -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-praying-hands fs-15 text-mat-gold"></i>
                                <span>{{ translate('Religion') }}</span>
                            </label>
                            @php $religions = \App\Models\Religion::all(); @endphp
                            <select name="religion_id" id="religion_id" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Religion') }}</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" @if(request('religion_id') == $religion->id) selected
                                    @endif>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Division / Region -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-map-marked-alt fs-15 text-mat-gold"></i>
                                <span>{{ translate('Division / Region') }}</span>
                            </label>
                            @php $allStates = \App\Models\State::where('country_id', 18)->orWhere('id', '<=', 8)->get(); @endphp
                            <select name="state_id" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('All Divisions') }}</option>
                                @foreach ($allStates as $st)
                                    <option value="{{ $st->id }}" @if(request('state_id', $state_id) == $st->id || (request('division') && strtolower(request('division')) == strtolower($st->name))) selected @endif>
                                        {{ $st->name }} {{ translate('Division') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 5. City / Location -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-map-marker-alt fs-15 text-mat-gold"></i>
                                <span>{{ translate('City / District') }}</span>
                            </label>
                            @php $cities = \App\Models\City::select('id', 'name')->orderBy('name', 'asc')->get(); @endphp
                            <select name="city_id" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Location') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @if(request('city_id') == $city->id) selected @endif>
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 5. Education -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-graduation-cap fs-15 text-mat-gold"></i>
                                <span>{{ translate('Education') }}</span>
                            </label>
                            <select name="education" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Education') }}</option>
                                <option value="BSc in Computer Science">BSc in Computer Science</option>
                                <option value="BSc in Engineering">BSc in Engineering</option>
                                <option value="MBBS">MBBS / Medical</option>
                                <option value="MBA">MBA / Business</option>
                                <option value="M.A. in English">M.A. in English</option>
                            </select>
                        </div>

                        <!-- 6. Profession -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-briefcase fs-15 text-mat-gold"></i>
                                <span>{{ translate('Profession') }}</span>
                            </label>
                            <select name="profession" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Profession') }}</option>
                                <option value="Software Engineer">Software Engineer</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Business Analyst">Business Analyst</option>
                            </select>
                        </div>

                        <!-- 7. Marital Status -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-ring fs-15 text-mat-gold"></i>
                                <span>{{ translate('Marital Status') }}</span>
                            </label>
                            @php $marital_statuses = \App\Models\MaritalStatus::all(); @endphp
                            <select name="marital_status" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Status') }}</option>
                                @foreach ($marital_statuses as $status)
                                    <option value="{{ $status->id }}" @if(request('marital_status') == $status->id) selected
                                    @endif>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 8. Height -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-ruler-vertical fs-15 text-mat-gold"></i>
                                <span>{{ translate('Height') }}</span>
                            </label>
                            <select name="min_height" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Height') }}</option>
                                <option value="5.0">5' 0" (152 cm)</option>
                                <option value="5.2">5' 2" (157 cm)</option>
                                <option value="5.4">5' 4" (162 cm)</option>
                                <option value="5.6">5' 6" (167 cm)</option>
                                <option value="5.8">5' 8" (172 cm)</option>
                                <option value="6.0">6' 0" (182 cm)</option>
                            </select>
                        </div>

                        <!-- 9. Income -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-wallet fs-15 text-mat-gold"></i>
                                <span>{{ translate('Income') }}</span>
                            </label>
                            <select name="income" class="filter-field-input form-control"
                                style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Income') }}</option>
                                <option value="50k-100k">৳ 50,000 - ৳ 1,00,000</option>
                                <option value="100k-200k">৳ 1,00,000 - ৳ 2,00,000</option>
                                <option value="200k+">৳ 2,00,000+</option>
                            </select>
                        </div>

                        <!-- 10. Advanced Filters Trigger -->
                        <div class="filter-field-group mb-4">
                            <a href="#advanced-collapse"
                                class="d-flex align-items-center justify-content-between text-muted fs-13 fw-600 text-decoration-none py-2 border-top"
                                data-toggle="collapse" role="button" aria-expanded="false"
                                style="color: #666; text-decoration: none;">
                                <span><i class="las la-leaf mr-1 text-mat-gold"></i>
                                    {{ translate('Advanced Filters') }}</span>
                                <i class="las la-angle-down"></i>
                            </a>
                            <div class="collapse pt-2" id="advanced-collapse">
                                <div class="form-group mb-2">
                                    <label class="filter-field-label fs-12 text-muted">{{ translate('Member ID') }}</label>
                                    <input type="text" name="member_code" value="{{ request('member_code') }}"
                                        placeholder="e.g. 100234" class="filter-field-input form-control"
                                        style="border: 1px solid var(--mat-border-light); border-radius: 10px; font-size: 13px; height: 40px;">
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Buttons -->
                        <div>
                            <button type="submit" class="btn w-100 py-3 mb-2 fw-600 text-white"
                                style="background-color: var(--mat-gold); border-radius: 12px; font-weight: 600; font-size: 14px; border: none; box-shadow: 0 4px 14px rgba(150, 114, 64, 0.25);">
                                <i class="las la-search fs-16 mr-1"></i>
                                <span>{{ translate('Search Matches') }}</span>
                            </button>
                            <a href="{{ route('member.listing') }}"
                                class="btn w-100 py-2.5 fw-500 d-flex align-items-center justify-content-center"
                                style="border: 1px solid var(--mat-border); border-radius: 12px; color: #555; font-size: 13px; text-decoration: none; background: transparent;">
                                <i class="las la-undo-alt fs-15 mr-1"></i>
                                <span>{{ translate('Reset Filters') }}</span>
                            </a>
                        </div>
                    </form>

                    <!-- Bottom Accent Card -->
                    <div class="mt-4 p-4 text-center rounded-2xl"
                        style="background: var(--mat-bg-section); border: 1px solid var(--mat-border); border-radius: 14px;">
                        <div class="fs-15 mb-1" style="color: #E63946;">❤</div>
                        <div class="font-serif italic fs-16 fw-600 text-mat-maroon-dark">
                            Better Matches,<br>Brighter Futures
                        </div>
                        <div class="text-muted fs-11 mt-1 opacity-60">✦ ✦ ✦</div>
                    </div>

                </div>
            </aside>

            <!-- 4. Right Column (72% - 75% width): Results Header, 3x3 Grid, and Pagination -->
            <main class="lg:col-span-9">

                @if (!empty($state_id))
                    @php $activeState = \App\Models\State::find($state_id); @endphp
                    @if ($activeState)
                        <div class="active-division-alert d-flex align-items-center justify-content-between p-3 mb-4 rounded-xl"
                            style="background-color: #FAF6F0; border: 1px solid rgba(140, 98, 57, 0.35); border-radius: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <span class="fs-22 text-mat-gold">📍</span>
                                <div>
                                    <div class="fs-15 fw-700 text-mat-maroon-dark">
                                        {{ $activeState->name }} {{ translate('Division Matches') }}
                                    </div>
                                    <div class="fs-12 text-muted">
                                        {{ translate('Showing only verified brides and grooms from') }} {{ $activeState->name }} {{ translate('Division') }}.
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('member.listing') }}" class="btn btn-sm btn-outline-secondary fs-12 px-3 py-1.5"
                                style="border-radius: 8px; font-weight: 600; text-decoration: none;">
                                <i class="las la-times mr-1"></i>{{ translate('Clear Filter') }}
                            </a>
                        </div>
                    @endif
                @endif

                <!-- Top Controls Bar -->
                <div class="matches-controls-bar d-flex align-items-center justify-content-between mb-4 pb-3"
                    style="border-bottom: 1px solid var(--mat-border);">
                    <!-- Left: Matches Count -->
                    <div class="matches-count-text d-flex align-items-center" style="gap: 8px;">
                        <i class="las la-users fs-22 text-mat-gold"></i>
                        <span class="fs-18 fw-700 text-mat-maroon-dark">{{ $users->total() }} {{ translate('Matches Found') }}</span>
                    </div>

                    <!-- Right: Sort By & View Toggles -->
                    <div class="d-flex align-items-center" style="gap: 14px;">
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="fs-13 text-muted fw-600 d-none d-sm-inline">{{ translate('Sort By:') }}</span>
                            <select name="sort" onchange="document.getElementById('filter-sort-input').value = this.value; document.getElementById('filter-form').submit();" class="form-control fs-13 py-1 px-3"
                                style="border: 1px solid var(--mat-border-light); border-radius: 8px; height: 38px; width: auto; color: #444; background-color: #FFF;">
                                <option value="latest" @if(request('sort', 'latest') == 'latest') selected @endif>{{ translate('Recently Joined') }}</option>
                                <option value="relevance" @if(request('sort', 'relevance') == 'relevance') selected @endif>{{ translate('Relevance') }}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 4px;">
                            <button type="button"
                                class="btn p-0 d-flex align-items-center justify-content-center text-white"
                                style="width: 36px; height: 36px; border-radius: 8px; background-color: var(--mat-gold); border: 1px solid var(--mat-gold);">
                                <i class="las la-border-all fs-18"></i>
                            </button>
                            <button type="button"
                                class="btn p-0 d-flex align-items-center justify-content-center text-muted"
                                style="width: 36px; height: 36px; border-radius: 8px; background-color: #FFF; border: 1px solid var(--mat-border-light);">
                                <i class="las la-list fs-18"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Profile Cards Grid (Strict 3 Columns on Desktop: 3x3 = 9 Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    @forelse ($users as $user)
                        @php
                            $user_member = $user->member;
                            $user_address = $user->addresses ? $user->addresses->first() : null;
                            $user_career = $user->career ? $user->career->first() : null;
                            $user_education = $user->education ? $user->education->first() : null;
                            $user_spiritual = $user->spiritual_backgrounds;
                            $user_family = $user->families;

                            // Calculate Age
                            $age = null;
                            if ($user_member && !empty($user_member->birthday)) {
                                try {
                                    $age = \Carbon\Carbon::parse($user_member->birthday)->age;
                                } catch (\Exception $e) {
                                    $age = null;
                                }
                            }

                            // Shortlist info safely
                            $shortlist_info = \App\Utility\MemberUtility::member_shortlist_info($user->id);
                            $is_shortlisted = isset($shortlist_info['shortlist_status']) && $shortlist_info['shortlist_status'] == 0;

                            // Express Interest info safely
                            $interest_info = \App\Utility\MemberUtility::member_interest_info($user->id);
                            $interest_status = $interest_info['interest_status'] ?? 1;

                            // Location text
                            $location_parts = [];
                            if ($user_address) {
                                if ($user_address->city && !empty($user_address->city->name)) {
                                    $location_parts[] = $user_address->city->name;
                                }
                                if ($user_address->state && !empty($user_address->state->name)) {
                                    $location_parts[] = $user_address->state->name;
                                } elseif ($user_address->country && !empty($user_address->country->name)) {
                                    $location_parts[] = $user_address->country->name;
                                }
                            }
                            $location_text = !empty($location_parts) ? implode(', ', $location_parts) : translate('Bangladesh');

                            // Education text
                            $education_text = ($user_education && !empty($user_education->degree)) ? $user_education->degree : translate('Graduate');

                            // Career text
                            $career_text = ($user_career && !empty($user_career->designation)) ? $user_career->designation : translate('Professional');

                            // Family text
                            $family_text = ($user_family && !empty($user_family->family_class)) ? $user_family->family_class : translate('Respectable Family');

                            // Photo URL
                            $avatar_url = null;
                            if (!empty($user->photo)) {
                                $avatar_url = is_numeric($user->photo) ? uploaded_asset($user->photo) : static_asset($user->photo);
                            }
                            if (empty($avatar_url)) {
                                $gender_val = $user_member ? $user_member->gender : 1;
                                $avatar_url = ($gender_val == 2)
                                    ? static_asset('assets/img/avatar-female.png')
                                    : static_asset('assets/img/avatar-place.png');
                            }
                        @endphp

                        <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                            style="border-radius: 16px; border: 1px solid var(--mat-border); box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                            <div class="match-card-photo-wrap position-relative"
                                style="height: 250px; background-color: var(--mat-gold-bg); overflow: hidden;">
                                <img src="{{ $avatar_url }}" alt="{{ $user->first_name }} {{ $user->last_name }}"
                                    class="match-card-photo w-100 h-100"
                                    style="object-fit: cover; object-position: top center;"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                <button type="button" class="match-card-fav-btn" title="{{ $is_shortlisted ? translate('Shortlisted') : translate('Add to Wishlist') }}"
                                    onclick="do_shortlist({{ $user->id }})"
                                    style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: {{ $is_shortlisted ? '#E63946' : '#666' }};">
                                    <i class="{{ $is_shortlisted ? 'las la-heart' : 'lar la-heart' }} fs-18"></i>
                                </button>
                                @if($user->approved == 1)
                                    <div class="match-card-verified-badge"
                                        style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="las la-check-circle fs-13"></i>
                                        <span>{{ translate('Verified Profile') }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="fs-17 fw-700 text-mat-maroon-dark mb-0">
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </h3>
                                    @if($age)
                                        <span class="fs-15 fw-600 text-muted" style="color: #666;">{{ $age }}</span>
                                    @endif
                                </div>
                                <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                    <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                        style="gap: 8px; color: #555; line-height: 1.5;">
                                        <i class="las la-map-marker-alt text-mat-gold"
                                            style="min-width: 18px; text-align: center;"></i>
                                        <span>{{ $location_text }}</span>
                                    </li>
                                    <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                        style="gap: 8px; color: #555; line-height: 1.5;">
                                        <i class="las la-graduation-cap text-mat-gold"
                                            style="min-width: 18px; text-align: center;"></i>
                                        <span>{{ $education_text }}</span>
                                    </li>
                                    <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                        style="gap: 8px; color: #555; line-height: 1.5;">
                                        <i class="las la-briefcase text-mat-gold"
                                            style="min-width: 18px; text-align: center;"></i>
                                        <span>{{ $career_text }}</span>
                                    </li>
                                    <li class="match-meta-item fs-13 d-flex align-items-center"
                                        style="gap: 8px; color: #555; line-height: 1.5;">
                                        <i class="las la-users text-mat-gold"
                                            style="min-width: 18px; text-align: center;"></i>
                                        <span>{{ $family_text }}</span>
                                    </li>
                                </ul>
                                <div class="match-card-actions mt-auto pt-3 d-flex"
                                    style="gap: 6px; border-top: 1px solid var(--mat-border-light);">
                                    <a href="{{ route('member_profile', $user->id) }}" class="btn btn-match-view flex-grow-1 text-center"
                                        style="border: 1px solid var(--mat-border); border-radius: 9px; text-decoration: none; color: var(--mat-maroon-dark); background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">{{ translate('View Profile') }}</a>
                                    <button type="button"
                                        id="interest_btn_{{ $user->id }}"
                                        class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                        onclick="express_interest({{ $user->id }})"
                                        style="background-color: {{ $interest_status === 0 ? '#4A5568' : 'var(--mat-gold)' }}; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px; {{ $interest_status === 0 ? 'cursor: default;' : '' }}"
                                        {{ $interest_status === 0 ? 'disabled' : '' }}>
                                        <i class="{{ $interest_status === 0 ? 'las la-heart' : 'lar la-heart' }} fs-13"></i>
                                        <span id="interest_text_{{ $user->id }}">{{ $interest_status === 0 ? translate('Interest Sent') : ($interest_info['interest_text'] ?? translate('Send Interest')) }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-5" style="grid-column: 1 / -1;">
                            <div class="p-5 bg-white rounded-2xl border border-stone-200/80 shadow-sm" style="border-radius: 16px;">
                                <i class="las la-search fs-48 mb-3 text-mat-gold"></i>
                                <h3 class="fs-18 fw-700 text-mat-maroon-dark mb-2">{{ translate('No Matches Found') }}</h3>
                                <p class="text-muted fs-14 mb-4">{{ translate('Try adjusting your search filters to find suitable profiles.') }}</p>
                                <a href="{{ route('member.listing') }}" class="btn text-white px-4 py-2" style="background-color: var(--mat-gold); border-radius: 8px;">
                                    {{ translate('Reset Filters') }}
                                </a>
                            </div>
                        </div>
                    @endforelse

                </div>            </div>
                <!-- 5. Centered Bottom Pagination -->
                <div class="matches-pagination-wrap d-flex justify-content-center align-items-center mt-5 mb-4"
                    style="padding-top: 24px;">
                    {{ $users->appends(request()->input())->links() }}
                </div>

            </main>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.package_update_alert_modal')
    @include('modals.confirm_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function package_update_alert() {
            $('.package_update_alert_modal').modal('show');
        }

        var is_logged_in = {{ Auth::check() ? 'true' : 'false' }};
        var is_paid_user = {{ (Auth::check() && is_paid_member(Auth::id())) ? 'true' : 'false' }};

        function express_interest(id) {
            if (!is_logged_in) {
                window.location.href = "{{ route('user.login') }}";
                return;
            }

            if (!is_paid_user) {
                $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
                $('#package_alert_text').text("{{ translate('Please upgrade to a premium package to send matchmaking proposals.') }}");
                $('.package_update_alert_modal').modal('show');
                return;
            }

            var $btn = $('#interest_btn_' + id);
            var $text = $('#interest_text_' + id);
            var originalContent = $btn.html();

            $btn.prop('disabled', true);
            $text.text("{{ translate('Sending...') }}");

            $.post('{{ route('express-interest.store') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function (data) {
                if (data == 1 || (data && data.result)) {
                    $btn.css('background-color', '#4A5568')
                        .css('cursor', 'default')
                        .prop('disabled', true)
                        .removeAttr('onclick')
                        .html('<i class="las la-heart fs-13"></i> <span>{{ translate('Interest Sent') }}</span>');
                    AIZ.plugins.notify('success', '{{ translate('Interest expressed successfully!') }}');
                } else if (data && data.status === 'upgrade_required') {
                    $btn.prop('disabled', false).html(originalContent);
                    $('#package_alert_title').text("{{ translate('Upgrade to Express Interest') }}");
                    $('#package_alert_text').text(data.message || "{{ translate('Please upgrade to a premium package to send matchmaking proposals.') }}");
                    $('.package_update_alert_modal').modal('show');
                } else {
                    $btn.prop('disabled', false).html(originalContent);
                    AIZ.plugins.notify('danger', (data && data.message) ? data.message : '{{ translate('Something went wrong') }}');
                }
            }).fail(function() {
                $btn.prop('disabled', false).html(originalContent);
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            });
        }

        function do_shortlist(id) {
            @if(!Auth::check())
                window.location.href = "{{ route('login') }}";
                return;
            @else
                $.post('{{ route('member.add_to_shortlist') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function (data) {
                    if (data == 1) {
                        AIZ.plugins.notify('success', '{{ translate('Added to Shortlist') }}');
                    } else {
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                });
            @endif
        }
    </script>
@endsection