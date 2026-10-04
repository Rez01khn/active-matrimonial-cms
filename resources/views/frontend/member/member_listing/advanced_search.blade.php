<div class="filter-card-container">
    <div class="filter-sidebar-header">
        <i class="las la-sliders-h fs-20 text-mat-gold"></i>
        <span>{{ translate('Filter Matches') }}</span>
        <button class="btn btn-sm p-1 d-xl-none ml-auto text-muted" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" type="button">
            <i class="las la-times fs-20"></i>
        </button>
    </div>

    <form action="{{ route('member.listing') }}" method="get" id="filter-form">
        <!-- 1. Looking For -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-user-tag text-mat-gold fs-15"></i>
                <span>{{ translate('Looking For') }}</span>
            </label>
            <select name="gender" class="filter-field-input">
                <option value="">{{ translate('Bride / Groom') }}</option>
                <option value="2" @if(request('gender') == '2') selected @endif>{{ translate('Bride (Female)') }}</option>
                <option value="1" @if(request('gender') == '1') selected @endif>{{ translate('Groom (Male)') }}</option>
            </select>
        </div>

        <!-- 2. Age Range -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-history text-mat-gold fs-15"></i>
                <span>{{ translate('Age Range') }}</span>
            </label>
            <select name="age_range" class="filter-field-input">
                <option value="">{{ translate('18 - 30') }}</option>
                <option value="18-25" @if(request('age_range') == '18-25' || ($age_from == 18 && $age_to == 25)) selected @endif>18 - 25</option>
                <option value="26-32" @if(request('age_range') == '26-32' || ($age_from == 26 && $age_to == 32)) selected @endif>26 - 32</option>
                <option value="33-40" @if(request('age_range') == '33-40' || ($age_from == 33 && $age_to == 40)) selected @endif>33 - 40</option>
                <option value="40+" @if(request('age_range') == '40+') selected @endif>40+</option>
            </select>
            <input type="hidden" name="age_from" value="{{ $age_from }}">
            <input type="hidden" name="age_to" value="{{ $age_to }}">
        </div>

        <!-- 3. Religion -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-praying-hands text-mat-gold fs-15"></i>
                <span>{{ translate('Religion') }}</span>
            </label>
            @php $religions = \App\Models\Religion::all(); @endphp
            <select name="religion_id" id="religion_id" class="filter-field-input">
                <option value="">{{ translate('Any Religion') }}</option>
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}" @if($religion->id == $religion_id) selected @endif>{{ $religion->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 4. Location -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-map-marker-alt text-mat-gold fs-15"></i>
                <span>{{ translate('Location') }}</span>
            </label>
            @php $cities = \App\Models\City::all(); @endphp
            <select name="city_id" class="filter-field-input">
                <option value="">{{ translate('Dhaka / Any Location') }}</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" @if($city->id == $city_id) selected @endif>{{ $city->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 5. Education -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-graduation-cap text-mat-gold fs-15"></i>
                <span>{{ translate('Education') }}</span>
            </label>
            <select name="education" class="filter-field-input">
                <option value="">{{ translate('Any Education') }}</option>
                <option value="Bachelor / Engineering" @if(request('education') == 'Bachelor / Engineering') selected @endif>BSc / Engineering</option>
                <option value="Master / Business" @if(request('education') == 'Master / Business') selected @endif>MSc / MBA / Masters</option>
                <option value="Medical / MBBS" @if(request('education') == 'Medical / MBBS') selected @endif>MBBS / Medical</option>
                <option value="Doctorate / PhD" @if(request('education') == 'Doctorate / PhD') selected @endif>PhD / Doctorate</option>
            </select>
        </div>

        <!-- 6. Profession -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-briefcase text-mat-gold fs-15"></i>
                <span>{{ translate('Profession') }}</span>
            </label>
            <input type="text" name="profession" value="{{ $profession }}" placeholder="{{ translate('Any Profession') }}" class="filter-field-input">
        </div>

        <!-- 7. Marital Status -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-ring text-mat-gold fs-15"></i>
                <span>{{ translate('Marital Status') }}</span>
            </label>
            @php $marital_statuses = \App\Models\MaritalStatus::all(); @endphp
            <select name="marital_status" class="filter-field-input">
                <option value="">{{ translate('Any Status') }}</option>
                @foreach ($marital_statuses as $status)
                    <option value="{{ $status->id }}" @if($matital_status == $status->id) selected @endif>{{ $status->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 8. Height -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-ruler-vertical text-mat-gold fs-15"></i>
                <span>{{ translate('Height') }}</span>
            </label>
            <select name="min_height" class="filter-field-input">
                <option value="">{{ translate('Any Height') }}</option>
                <option value="5.0" @if($min_height == '5.0') selected @endif>5' 0" (152 cm)</option>
                <option value="5.2" @if($min_height == '5.2') selected @endif>5' 2" (157 cm)</option>
                <option value="5.4" @if($min_height == '5.4') selected @endif>5' 4" (162 cm)</option>
                <option value="5.6" @if($min_height == '5.6') selected @endif>5' 6" (167 cm)</option>
                <option value="5.8" @if($min_height == '5.8') selected @endif>5' 8" (172 cm)</option>
                <option value="6.0" @if($min_height == '6.0') selected @endif>6' 0" (182 cm)</option>
            </select>
        </div>

        <!-- 9. Income -->
        <div class="filter-field-group">
            <label class="filter-field-label">
                <i class="las la-wallet text-mat-gold fs-15"></i>
                <span>{{ translate('Income') }}</span>
            </label>
            <select name="income" class="filter-field-input">
                <option value="">{{ translate('Any Income') }}</option>
                <option value="50k-100k">৳ 50,000 - ৳ 1,00,000</option>
                <option value="100k-200k">৳ 1,00,000 - ৳ 2,00,000</option>
                <option value="200k+">৳ 2,00,000+</option>
            </select>
        </div>

        <!-- 10. Advanced Filters Accordion Trigger -->
        <div class="filter-field-group">
            <a href="#advanced-collapse" class="d-flex align-items-center justify-content-between text-muted fs-13 fw-600 text-decoration-none py-2 border-top" data-toggle="collapse" role="button" aria-expanded="false">
                <span><i class="las la-leaf text-mat-gold mr-1"></i> {{ translate('Advanced Filters') }}</span>
                <i class="las la-angle-down"></i>
            </a>
            <div class="collapse pt-2" id="advanced-collapse">
                <div class="form-group mb-2">
                    <label class="filter-field-label">{{ translate('Member ID') }}</label>
                    <input type="text" name="member_code" value="{{ $member_code }}" placeholder="e.g. 100234" class="filter-field-input">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4">
            <button type="submit" class="btn-search-matches mb-2">
                <i class="las la-search fs-16"></i>
                <span>{{ translate('Search Matches') }}</span>
            </button>
            <a href="{{ route('member.listing') }}" class="btn-reset-filters">
                <i class="las la-undo-alt fs-15"></i>
                <span>{{ translate('Reset Filters') }}</span>
            </a>
        </div>
    </form>

    <!-- Bottom Decorative Card -->
    <div class="filter-decorative-card">
        <div class="text-danger fs-14 mb-1">❤</div>
        <div class="font-serif italic text-mat-maroon fs-16 fw-600">
            Better Matches,<br>Brighter Futures
        </div>
        <div class="text-muted fs-11 mt-1 opacity-70">✦ ✦ ✦</div>
    </div>
</div>
