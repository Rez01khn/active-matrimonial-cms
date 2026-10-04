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

                <h1 class="matches-hero-title mb-3"
                    style="color: #2C0A12; font-family: 'Playfair Display', Georgia, serif; font-size: 38px; font-weight: 700; line-height: 1.2;">
                    Find Your Perfect Match</h1>
                <p class="matches-hero-subtitle mb-0" style="color: #555555; font-size: 15px; line-height: 1.6;">
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
                    style="border-radius: 16px; border: 1px solid #EAE5DD; padding: 22px; background: #FFFFFF; box-shadow: 0 4px 18px rgba(0,0,0,0.04);">

                    <!-- Filter Header -->
                    <div class="filter-sidebar-header d-flex align-items-center mb-4 pb-3"
                        style="border-bottom: 1px solid #F0ECE6; gap: 10px;">
                        <i class="las la-sliders-h fs-20" style="color: #8C6239;"></i>
                        <span class="fs-18 fw-700 text-mat-maroon"
                            style="color: #2C0A12; font-weight: 700;">{{ translate('Filter Matches') }}</span>
                    </div>

                    <form action="{{ route('member.listing') }}" method="get" id="filter-form">
                        <!-- 1. Looking For -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-user-tag fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Looking For') }}</span>
                            </label>
                            <select name="gender" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Bride') }}</option>
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
                                <i class="las la-history fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Age Range') }}</span>
                            </label>
                            <select name="age_range" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('18 - 30') }}</option>
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
                                <i class="las la-praying-hands fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Religion') }}</span>
                            </label>
                            @php $religions = \App\Models\Religion::all(); @endphp
                            <select name="religion_id" id="religion_id" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Any Religion') }}</option>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}" @if(request('religion_id') == $religion->id) selected
                                    @endif>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Location -->
                        <div class="filter-field-group mb-3">
                            <label class="filter-field-label fs-13 fw-600 mb-1 d-flex align-items-center"
                                style="color: #333; gap: 6px;">
                                <i class="las la-map-marker-alt fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Location') }}</span>
                            </label>
                            @php $cities = \App\Models\City::all(); @endphp
                            <select name="city_id" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
                                <option value="">{{ translate('Dhaka') }}</option>
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
                                <i class="las la-graduation-cap fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Education') }}</span>
                            </label>
                            <select name="education" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
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
                                <i class="las la-briefcase fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Profession') }}</span>
                            </label>
                            <select name="profession" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
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
                                <i class="las la-ring fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Marital Status') }}</span>
                            </label>
                            @php $marital_statuses = \App\Models\MaritalStatus::all(); @endphp
                            <select name="marital_status" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
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
                                <i class="las la-ruler-vertical fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Height') }}</span>
                            </label>
                            <select name="min_height" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
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
                                <i class="las la-wallet fs-15" style="color: #8C6239;"></i>
                                <span>{{ translate('Income') }}</span>
                            </label>
                            <select name="income" class="filter-field-input form-control"
                                style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 42px; background-color: #FFF;">
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
                                <span><i class="las la-leaf mr-1" style="color: #8C6239;"></i>
                                    {{ translate('Advanced Filters') }}</span>
                                <i class="las la-angle-down"></i>
                            </a>
                            <div class="collapse pt-2" id="advanced-collapse">
                                <div class="form-group mb-2">
                                    <label class="filter-field-label fs-12 text-muted">{{ translate('Member ID') }}</label>
                                    <input type="text" name="member_code" value="{{ request('member_code') }}"
                                        placeholder="e.g. 100234" class="filter-field-input form-control"
                                        style="border: 1px solid #E2DCD5; border-radius: 10px; font-size: 13px; height: 40px;">
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Buttons -->
                        <div>
                            <button type="submit" class="btn w-100 py-3 mb-2 fw-600 text-white"
                                style="background-color: #8C6239; border-radius: 12px; font-weight: 600; font-size: 14px; border: none; box-shadow: 0 4px 14px rgba(140, 98, 57, 0.25);">
                                <i class="las la-search fs-16 mr-1"></i>
                                <span>{{ translate('Search Matches') }}</span>
                            </button>
                            <a href="{{ route('member.listing') }}"
                                class="btn w-100 py-2.5 fw-500 d-flex align-items-center justify-content-center"
                                style="border: 1px solid #DCD5CC; border-radius: 12px; color: #555; font-size: 13px; text-decoration: none; background: transparent;">
                                <i class="las la-undo-alt fs-15 mr-1"></i>
                                <span>{{ translate('Reset Filters') }}</span>
                            </a>
                        </div>
                    </form>

                    <!-- Bottom Accent Card -->
                    <div class="mt-4 p-4 text-center rounded-2xl"
                        style="background: #FAF7F2; border: 1px solid #EFEAE3; border-radius: 14px;">
                        <div class="fs-15 mb-1" style="color: #E63946;">❤</div>
                        <div class="font-serif italic fs-16 fw-600"
                            style="color: #2C0A12; font-family: 'Playfair Display', Georgia, serif;">
                            Better Matches,<br>Brighter Futures
                        </div>
                        <div class="text-muted fs-11 mt-1 opacity-60">✦ ✦ ✦</div>
                    </div>

                </div>
            </aside>

            <!-- 4. Right Column (72% - 75% width): Results Header, 3x3 Grid, and Pagination -->
            <main class="lg:col-span-9">

                <!-- Top Controls Bar -->
                <div class="matches-controls-bar d-flex align-items-center justify-content-between mb-4 pb-3"
                    style="border-bottom: 1px solid #EFEAE3;">
                    <!-- Left: Matches Count -->
                    <div class="matches-count-text d-flex align-items-center" style="gap: 8px;">
                        <i class="las la-users fs-22" style="color: #8C6239;"></i>
                        <span class="fs-18 fw-700" style="color: #2C0A12; font-weight: 700;">1,248 Matches Found</span>
                    </div>

                    <!-- Right: Sort By & View Toggles -->
                    <div class="d-flex align-items-center" style="gap: 14px;">
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="fs-13 text-muted fw-600 d-none d-sm-inline">{{ translate('Sort By:') }}</span>
                            <select class="form-control fs-13 py-1 px-3"
                                style="border: 1px solid #E2DCD5; border-radius: 8px; height: 38px; width: auto; color: #444; background-color: #FFF;">
                                <option value="latest">{{ translate('Recently Joined') }} ▾</option>
                                <option value="relevance">{{ translate('Relevance') }}</option>
                                <option value="age_asc">{{ translate('Age: Low to High') }}</option>
                                <option value="age_desc">{{ translate('Age: High to Low') }}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 4px;">
                            <button type="button"
                                class="btn p-0 d-flex align-items-center justify-content-center text-white"
                                style="width: 36px; height: 36px; border-radius: 8px; background-color: #8C6239; border: 1px solid #8C6239;">
                                <i class="las la-border-all fs-18"></i>
                            </button>
                            <button type="button"
                                class="btn p-0 d-flex align-items-center justify-content-center text-muted"
                                style="width: 36px; height: 36px; border-radius: 8px; background-color: #FFF; border: 1px solid #E2DCD5;">
                                <i class="las la-list fs-18"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Profile Cards Grid (Strict 3 Columns on Desktop: 3x3 = 9 Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    <!-- 1. Ayesha Rahman -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/ayesha.jpg') }}" alt="Ayesha Rahman"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(1)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Ayesha Rahman</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">26</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Dhaka, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>BSc in Computer Science</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Software Engineer</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(1)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Tanvir Hasan -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/tanvir.jpg') }}" alt="Tanvir Hasan"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(2)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Tanvir Hasan</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">29</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Chittagong, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>MBBS</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Doctor</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Upper Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(2)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Nusrat Jahan -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/nusrat.jpg') }}" alt="Nusrat Jahan"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(3)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Nusrat Jahan</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">24</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Sylhet, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>B.A. in English</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Teacher</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(3)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Rifat Islam -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/rifat.jpg') }}" alt="Rifat Islam"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(4)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Rifat Islam</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">30</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Dhaka, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>BSc in Engineering</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Engineer</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Upper Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(4)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Fatema Akter -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/fatema.jpg') }}" alt="Fatema Akter"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(5)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Fatema Akter</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">28</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Rajshahi, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>MSc in Biotechnology</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Researcher</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(5)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Mahfuz Rahman -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/mahfuz.jpg') }}" alt="Mahfuz Rahman"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(6)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Mahfuz Rahman</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">32</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Dhaka, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>MBA</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Business Analyst</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Upper Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(6)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Shanta Islam -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/maliha.jpg') }}" alt="Shanta Islam"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(7)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Shanta Islam</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">25</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Khulna, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>BSc in EEE</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Electrical Engineer</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(7)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 8. Arif Hossain -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/rahim.jpg') }}" alt="Arif Hossain"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(8)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Arif Hossain</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">27</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Barishal, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>BSc in Agriculture</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Agricultural Officer</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(8)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Tasmia Rahman -->
                    <div class="match-profile-card bg-white rounded-2xl shadow-sm border border-stone-200/80"
                        style="border-radius: 16px; border: 1px solid #ECE7E0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; display: flex; flex-direction: column;">
                        <div class="match-card-photo-wrap position-relative"
                            style="height: 250px; background-color: #F5F2ED; overflow: hidden;">
                            <img src="{{ static_asset('assets/img/custom/tasmia.jpg') }}" alt="Tasmia Rahman"
                                class="match-card-photo w-100 h-100"
                                style="object-fit: cover; object-position: top center;">
                            <button type="button" class="match-card-fav-btn" title="Add to Wishlist"
                                onclick="do_shortlist(9)"
                                style="position: absolute; top: 12px; right: 12px; width: 36px; height: 36px; background: #FFF; border-radius: 50%; border: none; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; cursor: pointer; color: #666;">
                                <i class="lar la-heart fs-18"></i>
                            </button>
                            <div class="match-card-verified-badge"
                                style="position: absolute; bottom: 12px; left: 12px; background: rgba(235, 245, 255, 0.94); border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 600; color: #0066CC; border: 1px solid rgba(0, 102, 204, 0.25); display: inline-flex; align-items: center; gap: 4px;">
                                <i class="las la-check-circle fs-13"></i>
                                <span>Verified Profile</span>
                            </div>
                        </div>
                        <div class="match-card-body p-3 d-flex flex-column flex-grow-1" style="padding: 16px 18px 18px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h3 class="fs-17 fw-700 text-mat-maroon mb-0"
                                    style="font-size: 17px; font-weight: 700; color: #2C0A12;">Tasmia Rahman</h3>
                                <span class="fs-15 fw-600 text-muted" style="color: #666;">31</span>
                            </div>
                            <ul class="match-meta-list list-unstyled mb-3" style="padding: 0; margin-bottom: 14px;">
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-map-marker-alt"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Comilla, Bangladesh</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-graduation-cap"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>M.A. in Islamic Studies</span>
                                </li>
                                <li class="match-meta-item fs-13 mb-1.5 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-briefcase"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Lecturer</span>
                                </li>
                                <li class="match-meta-item fs-13 d-flex align-items-center"
                                    style="gap: 8px; color: #555; line-height: 1.5;">
                                    <i class="las la-users"
                                        style="color: #8C6239; min-width: 18px; text-align: center;"></i>
                                    <span>Upper Middle Class Family</span>
                                </li>
                            </ul>
                            <div class="match-card-actions mt-auto pt-3 d-flex"
                                style="gap: 6px; border-top: 1px solid #F3EFEA;">
                                <a href="{{ route('register') }}" class="btn btn-match-view flex-grow-1 text-center"
                                    style="border: 1px solid #D8CFC4; border-radius: 9px; text-decoration: none; color: #2C0A12; background: transparent; font-weight: 600; white-space: nowrap; font-size: 12px; padding: 7px 6px;">View
                                    Profile</a>
                                <button type="button"
                                    class="btn btn-match-interest flex-grow-1 text-center d-flex align-items-center justify-content-center"
                                    onclick="express_interest(9)"
                                    style="background-color: #8C6239; border-radius: 9px; border: none; color: #FFF; font-weight: 600; gap: 4px; white-space: nowrap; font-size: 12px; padding: 7px 6px;">
                                    <i class="lar la-heart fs-13"></i>
                                    <span>Send Interest</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 5. Centered Bottom Pagination -->
                <div class="matches-pagination-wrap d-flex justify-content-center align-items-center mt-5 mb-4"
                    style="padding-top: 24px;">
                    <nav aria-label="Matches Pagination">
                        <ul class="pagination mb-0 d-flex align-items-center"
                            style="gap: 6px; list-style: none; padding: 0;">
                            <li class="page-item disabled">
                                <a class="page-link prev-next-btn" href="#" tabindex="-1"
                                    style="border: 1px solid #E2DCD5; border-radius: 8px !important; color: #888; padding: 8px 16px; text-decoration: none; font-size: 13px; font-weight: 500;">
                                    <i class="las la-angle-left mr-1"></i> {{ translate('Previous') }}
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#"
                                    style="background-color: #8C6239; border-color: #8C6239; color: #FFF; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600;">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    style="border: 1px solid #E2DCD5; color: #555; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600; background: #FFF;">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    style="border: 1px solid #E2DCD5; color: #555; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600; background: #FFF;">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    style="border: 1px solid #E2DCD5; color: #555; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600; background: #FFF;">4</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    style="border: 1px solid #E2DCD5; color: #555; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600; background: #FFF;">5</a>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link"
                                    style="border: 1px solid #E2DCD5; color: #888; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; font-size: 13px; background: #FFF;">...</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    style="border: 1px solid #E2DCD5; color: #555; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; text-decoration: none; font-size: 13px; font-weight: 600; background: #FFF;">20</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link prev-next-btn" href="#"
                                    style="border: 1px solid #E2DCD5; border-radius: 8px !important; color: #555; padding: 8px 16px; text-decoration: none; font-size: 13px; font-weight: 500; background: #FFF;">
                                    {{ translate('Next') }} <i class="las la-angle-right ml-1"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
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

        var package_validity = false;
        @if(Auth::check() && package_validity(Auth::user()->id))
            package_validity = true;
        @endif

            function express_interest(id) {
                @if(!Auth::check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @else
                    var user_id = {{ Auth::user()->id }};
                    $.post('{{ route('user.remaining_package_value') }}', {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        colmn_name: 'remaining_interest'
                    }, function (data) {
                        var remaining_interest = data;
                        if (!package_validity || remaining_interest < 1) {
                            $('.package_update_alert_modal').modal('show');
                        } else {
                            $('.confirm_modal').modal('show');
                            $("#confirm_modal_title").html("{{ translate('Confirm Express Interest!') }}");
                            $("#confirm_modal_content").html("<p class='fs-14'>{{ translate('Remaining Express Interest') }}: " + remaining_interest + " {{ translate('Times') }}</p><p class='fs-12 text-danger'>{{ translate('**N.B. Expressing An Interest Will Cost 1 From Your Remaining Interests**') }}</p>");
                            $("#confirm_button").attr("onclick", "do_express_interest(" + id + ")");
                        }
                    });
                @endif
        }

        function do_express_interest(id) {
            $('.confirm_modal').modal('hide');
            $.post('{{ route('express-interest.store') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function (data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Interest Expressed Successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
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