<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ translate('Matrimonial Biodata') }} - {{ $user->first_name . ' ' . $user->last_name }} (#{{ $user->code }})</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'DejaVuSans', sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #2D2D2D;
            background-color: #ffffff;
        }
        .profile-summary-table {
            width: 100%;
            margin-bottom: 14px;
            background-color: #FAF7F2;
            border: 1px solid #E7E0D5;
            border-radius: 6px;
        }
        .profile-photo {
            width: 110px;
            height: 130px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #967240;
        }
        .profile-name {
            font-size: 18px;
            font-weight: 700;
            color: #4A1521;
            margin-bottom: 4px;
        }
        .profile-subtitle {
            font-size: 11px;
            color: #686868;
            margin-bottom: 8px;
        }
        .summary-badge-cell {
            padding: 2px 6px;
            font-size: 10px;
            color: #4A1521;
            font-weight: 600;
        }
        .section-header {
            width: 100%;
            background-color: #4A1521;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 8px;
            margin-top: 10px;
            margin-bottom: 6px;
            border-left: 4px solid #967240;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 10px;
        }
        .info-table tr:nth-child(even) td {
            background-color: #FCFAF7;
        }
        .info-label {
            width: 25%;
            font-weight: 700;
            color: #555555;
            border-bottom: 1px solid #EFEAE3;
        }
        .info-value {
            width: 25%;
            color: #222222;
            border-bottom: 1px solid #EFEAE3;
        }
        .footer-note {
            font-size: 9px;
            color: #888888;
            text-align: center;
            border-top: 1px solid #E7E0D5;
            padding-top: 6px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    {{-- Top Profile Summary Box --}}
    <table class="profile-summary-table" cellpadding="10">
        <tr>
            @if(!empty($photo_path))
                <td style="width: 120px; vertical-align: top; text-align: center;">
                    <img src="{{ $photo_path }}" alt="{{ $user->first_name }}" class="profile-photo">
                </td>
            @endif
            <td style="vertical-align: top;">
                <div class="profile-name">
                    {{ $user->first_name . ' ' . $user->last_name }}
                    <span style="font-size: 12px; font-weight: normal; color: #967240;">(#{{ $user->code }})</span>
                </div>
                <div class="profile-subtitle">
                    @if(!empty($user->member->birthday))
                        {{ \Carbon\Carbon::parse($user->member->birthday)->age }} {{ translate('Years') }}
                    @endif
                    @if(!empty($user->physical_attributes->height))
                        &bull; {{ $user->physical_attributes->height }} {{ translate('ft') }}
                    @endif
                    @if(!empty($user->member->marital_status->name))
                        &bull; {{ $user->member->marital_status->name }}
                    @endif
                    @if(!empty($user->spiritual_backgrounds->religion->name))
                        &bull; {{ $user->spiritual_backgrounds->religion->name }}
                    @endif
                    @if(!empty($present_address->city->name) || !empty($present_address->country->name))
                        &bull; {{ $present_address->city->name ?? '' }}{{ !empty($present_address->city->name) && !empty($present_address->country->name) ? ', ' : '' }}{{ $present_address->country->name ?? '' }}
                    @endif
                </div>

                @if(!empty($user->member->introduction))
                    <div style="font-size: 10px; color: #444444; line-height: 1.4; border-top: 1px dashed #DCD4C8; padding-top: 6px;">
                        <em>"{{ Str::limit($user->member->introduction, 220) }}"</em>
                    </div>
                @endif
            </td>
        </tr>
    </table>

    {{-- 1. Personal & Physical Attributes --}}
    <div class="section-header">{{ translate('Personal & Physical Details') }}</div>
    <table class="info-table">
        <tr>
            <td class="info-label">{{ translate('Full Name') }}:</td>
            <td class="info-value">{{ $user->first_name . ' ' . $user->last_name }}</td>
            <td class="info-label">{{ translate('Member ID') }}:</td>
            <td class="info-value">#{{ $user->code }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Gender') }}:</td>
            <td class="info-value">{{ ($user->member && $user->member->gender == 2) ? translate('Female') : translate('Male') }}</td>
            <td class="info-label">{{ translate('Marital Status') }}:</td>
            <td class="info-value">{{ $user->member->marital_status->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Date of Birth') }}:</td>
            <td class="info-value">
                {{ !empty($user->member->birthday) ? date('d M, Y', strtotime($user->member->birthday)) : '-' }}
                @if(!empty($user->member->birthday))
                    ({{ \Carbon\Carbon::parse($user->member->birthday)->age }} {{ translate('yrs') }})
                @endif
            </td>
            <td class="info-label">{{ translate('Mother Tongue') }}:</td>
            <td class="info-value">{{ $user->member->mothere_tongue->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Height') }}:</td>
            <td class="info-value">{{ !empty($user->physical_attributes->height) ? $user->physical_attributes->height . ' ft' : '-' }}</td>
            <td class="info-label">{{ translate('Weight') }}:</td>
            <td class="info-value">{{ !empty($user->physical_attributes->weight) ? $user->physical_attributes->weight . ' kg' : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Blood Group') }}:</td>
            <td class="info-value">{{ $user->physical_attributes->blood_group ?? '-' }}</td>
            <td class="info-label">{{ translate('Complexion') }}:</td>
            <td class="info-value">{{ $user->physical_attributes->complexion ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Body Type') }}:</td>
            <td class="info-value">{{ $user->physical_attributes->body_type ?? '-' }}</td>
            <td class="info-label">{{ translate('Disability / Special Needs') }}:</td>
            <td class="info-value">{{ $user->physical_attributes->disability ?? translate('None') }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Children') }}:</td>
            <td class="info-value" colspan="3">{{ $user->member->children ?? translate('No') }}</td>
        </tr>
    </table>

    {{-- 2. Residential / Address Details --}}
    <div class="section-header">{{ translate('Residential & Contact Details') }}</div>
    <table class="info-table">
        <tr>
            <td class="info-label">{{ translate('Present Address') }}:</td>
            <td class="info-value" colspan="3">
                @if(!empty($present_address))
                    {{ $present_address->city->name ?? '' }}{{ !empty($present_address->city->name) && !empty($present_address->state->name) ? ', ' : '' }}{{ $present_address->state->name ?? '' }}{{ !empty($present_address->country->name) ? ', ' . $present_address->country->name : '' }}
                    @if(!empty($present_address->postal_code)) ({{ $present_address->postal_code }}) @endif
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Permanent Address') }}:</td>
            <td class="info-value" colspan="3">
                @if(!empty($permanent_address))
                    {{ $permanent_address->city->name ?? '' }}{{ !empty($permanent_address->city->name) && !empty($permanent_address->state->name) ? ', ' : '' }}{{ $permanent_address->state->name ?? '' }}{{ !empty($permanent_address->country->name) ? ', ' . $permanent_address->country->name : '' }}
                    @if(!empty($permanent_address->postal_code)) ({{ $permanent_address->postal_code }}) @endif
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Contact Phone') }}:</td>
            <td class="info-value">{{ $user->phone ?? '-' }}</td>
            <td class="info-label">{{ translate('Contact Email') }}:</td>
            <td class="info-value">{{ $user->email ?? '-' }}</td>
        </tr>
    </table>

    {{-- 3. Education & Career Background --}}
    <div class="section-header">{{ translate('Education & Career Qualification') }}</div>
    <table class="info-table">
        @if($user->education->count() > 0)
            @foreach($user->education as $edu)
                <tr>
                    <td class="info-label">{{ translate('Education') }}:</td>
                    <td class="info-value"><strong>{{ $edu->degree }}</strong></td>
                    <td class="info-label">{{ translate('Institution / Year') }}:</td>
                    <td class="info-value">{{ $edu->institution }} ({{ $edu->start }} - {{ $edu->end ?? translate('Present') }})</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="info-label">{{ translate('Education') }}:</td>
                <td class="info-value" colspan="3">-</td>
            </tr>
        @endif

        @if($user->career->count() > 0)
            @foreach($user->career as $car)
                <tr>
                    <td class="info-label">{{ translate('Profession') }}:</td>
                    <td class="info-value"><strong>{{ $car->designation }}</strong></td>
                    <td class="info-label">{{ translate('Company / Period') }}:</td>
                    <td class="info-value">{{ $car->company }} ({{ $car->start }} - {{ $car->end ?? translate('Present') }})</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="info-label">{{ translate('Profession') }}:</td>
                <td class="info-value" colspan="3">-</td>
            </tr>
        @endif
    </table>

    {{-- 4. Spiritual, Religious & Social Background --}}
    <div class="section-header">{{ translate('Religious & Social Background') }}</div>
    <table class="info-table">
        <tr>
            <td class="info-label">{{ translate('Religion') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->religion->name ?? '-' }}</td>
            <td class="info-label">{{ translate('Caste / Sect') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->caste->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Sub Caste') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->sub_caste->name ?? '-' }}</td>
            <td class="info-label">{{ translate('Ethnicity') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->ethnicity ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Personal Value') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->personal_value ?? '-' }}</td>
            <td class="info-label">{{ translate('Family Value') }}:</td>
            <td class="info-value">{{ $user->spiritual_backgrounds->family_value->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Community Value') }}:</td>
            <td class="info-value" colspan="3">{{ $user->spiritual_backgrounds->community_value ?? '-' }}</td>
        </tr>
    </table>

    {{-- 5. Family Details --}}
    <div class="section-header">{{ translate('Family Background') }}</div>
    <table class="info-table">
        <tr>
            <td class="info-label">{{ translate("Father's Name / Profession") }}:</td>
            <td class="info-value" colspan="3">{{ $user->families->father ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate("Mother's Name / Profession") }}:</td>
            <td class="info-value" colspan="3">{{ $user->families->mother ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Siblings (Brothers/Sisters)') }}:</td>
            <td class="info-value" colspan="3">{{ $user->families->sibling ?? '-' }}</td>
        </tr>
    </table>

    {{-- 6. Lifestyle & Habits --}}
    <div class="section-header">{{ translate('Lifestyle & Habits') }}</div>
    <table class="info-table">
        <tr>
            <td class="info-label">{{ translate('Diet') }}:</td>
            <td class="info-value">{{ $user->lifestyles->diet ?? '-' }}</td>
            <td class="info-label">{{ translate('Drink') }}:</td>
            <td class="info-value">{{ $user->lifestyles->drink ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">{{ translate('Smoke') }}:</td>
            <td class="info-value">{{ $user->lifestyles->smoke ?? '-' }}</td>
            <td class="info-label">{{ translate('Living With') }}:</td>
            <td class="info-value">{{ $user->lifestyles->living_with ?? '-' }}</td>
        </tr>
    </table>

    {{-- 7. Partner Expectations (if available) --}}
    @if(!empty($user->partner_expectations))
        <div class="section-header">{{ translate('Partner Expectation & Preferences') }}</div>
        <table class="info-table">
            <tr>
                <td class="info-label">{{ translate('Preferred Age') }}:</td>
                <td class="info-value">
                    @if(!empty($user->partner_expectations->general))
                        {{ $user->partner_expectations->general }}
                    @else
                        -
                    @endif
                </td>
                <td class="info-label">{{ translate('Preferred Height') }}:</td>
                <td class="info-value">{{ !empty($user->partner_expectations->height) ? $user->partner_expectations->height . ' ft' : '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">{{ translate('Marital Status') }}:</td>
                <td class="info-value">{{ $user->partner_expectations->marital_status->name ?? '-' }}</td>
                <td class="info-label">{{ translate('Preferred Religion') }}:</td>
                <td class="info-value">{{ $user->partner_expectations->religion->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">{{ translate('Education') }}:</td>
                <td class="info-value">{{ $user->partner_expectations->education ?? '-' }}</td>
                <td class="info-label">{{ translate('Profession') }}:</td>
                <td class="info-value">{{ $user->partner_expectations->profession ?? '-' }}</td>
            </tr>
        </table>
    @endif

    {{-- Footer Disclaimer --}}
    <div class="footer-note">
        <strong>{{ get_setting('website_name', 'Active Matrimonial') }}</strong> &bull;
        {{ translate('This is a confidential matrimonial biodata intended solely for matchmaking deliberation.') }}
    </div>

</body>
</html>
