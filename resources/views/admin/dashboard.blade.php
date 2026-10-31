@extends('admin.layouts.app')

@section('content')
<!-- Google Fonts for Brand Typography -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">

<style>
    :root {
        --mat-maroon: #4A1521;
        --mat-maroon-dark: #2C0A12;
        --mat-maroon-light: #6A2131;
        --mat-gold: #967240;
        --mat-gold-light: #C5A059;
        --mat-gold-bg: #FAF6F1;
        --mat-border: #ECE7E1;
        --mat-font-serif: 'Playfair Display', Georgia, serif;
        --mat-font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .dash-hero-title {
        font-family: var(--mat-font-serif);
        font-size: 25px;
        font-weight: 700;
        color: var(--mat-maroon);
        letter-spacing: -0.3px;
        margin-bottom: 3px;
    }
    .dash-font-serif {
        font-family: var(--mat-font-serif);
    }
    .dash-card {
        background: #FFFFFF;
        border: 1px solid var(--mat-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(74, 21, 33, 0.03);
        transition: all 0.25s ease;
        overflow: hidden;
        height: 100%;
        margin-bottom: 20px;
    }
    .dash-card:hover {
        box-shadow: 0 6px 24px rgba(74, 21, 33, 0.07);
        border-color: #DFD7CD;
    }
    .dash-card-body {
        padding: 22px 24px;
    }
    /* Compact Action Center Card (Royal Maroon with Gold accents) */
    .dash-stat-brand-maroon {
        background: linear-gradient(135deg, #4A1521 0%, #2C0A12 100%);
        color: #FFFFFF;
        border: 1px solid rgba(197, 160, 89, 0.35);
        position: relative;
        height: auto !important;
        min-height: unset !important;
        margin-bottom: 16px !important;
    }
    .dash-stat-brand-navy {
        background: linear-gradient(135deg, #2C0A12 0%, #3B1721 100%);
        color: #FFFFFF;
        border: 1px solid rgba(197, 160, 89, 0.25);
    }
    .dash-stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        opacity: 0.85;
        margin-bottom: 6px;
        display: block;
    }
    .dash-stat-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .dash-stat-value {
        font-family: var(--mat-font-sans);
        font-size: 34px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .dash-stat-desc {
        font-size: 12px;
        opacity: 0.85;
        margin-top: 10px;
        line-height: 1.4;
    }
    /* Time Filter Tabs (Today / Weekly / Monthly) */
    .dash-time-tabs {
        display: inline-flex;
        background: #F1F5F9;
        padding: 3px;
        border-radius: 999px;
        gap: 3px;
        border: 1px solid #E2E8F0;
    }
    .dash-tab-pill {
        border: none;
        background: transparent;
        padding: 4px 13px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        color: #64748B;
        transition: all 0.2s ease;
        cursor: pointer;
        line-height: 1.3;
    }
    .dash-tab-pill:hover {
        color: var(--mat-maroon);
    }
    .dash-tab-pill.active {
        background: #FFFFFF;
        color: var(--mat-maroon);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .avatar-stack-group {
        display: flex;
        align-items: center;
    }
    .avatar-stack-item {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #FFFFFF;
        margin-left: -10px;
        object-fit: cover;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background: #F1F5F9;
    }
    .avatar-stack-item:first-child {
        margin-left: 0;
    }
    .dash-table {
        margin-bottom: 0;
    }
    .dash-table th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748B;
        border-top: none;
        border-bottom: 1px solid var(--mat-border);
        padding: 14px 16px;
        background: #FAF8F5;
    }
    .dash-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-top: 1px solid #FAF8F5;
        font-size: 13px;
        color: #2C2C2C;
    }
    .dash-table tr:hover td {
        background-color: #FAF6F1;
    }
    .plan-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 999px;
        line-height: 1.3;
        white-space: nowrap;
    }
    .plan-pill-free {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
    }
    .plan-pill-gold {
        background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);
        color: #92400E;
        border: 1px solid #FCD34D;
        box-shadow: 0 1px 2px rgba(245, 158, 11, 0.1);
    }
    .plan-pill-maroon {
        background: rgba(74, 21, 33, 0.08);
        color: var(--mat-maroon);
        border: 1px solid rgba(74, 21, 33, 0.22);
    }
    .dash-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #FAF6F1;
        color: var(--mat-maroon);
        border: 1px solid var(--mat-border);
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .dash-action-btn:hover {
        background: var(--mat-maroon);
        color: #FFFFFF;
        border-color: var(--mat-maroon);
    }
    .dash-alert-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.12);
        color: #FFFFFF;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid rgba(197, 160, 89, 0.25);
        transition: all 0.2s ease;
    }
    .dash-alert-pill:hover {
        background: rgba(255, 255, 255, 0.22);
        color: #FFFFFF;
    }

    /* Custom Scrollable Table Container */
    .dash-table-scroll {
        max-height: 380px;
        overflow-y: auto;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #C5A059 #F8F9FA;
    }
    .dash-table-scroll::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .dash-table-scroll::-webkit-scrollbar-track {
        background: #FAF8F5;
        border-radius: 4px;
    }
    .dash-table-scroll::-webkit-scrollbar-thumb {
        background: #D8CEBE;
        border-radius: 4px;
    }
    .dash-table-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--mat-gold);
    }
    .dash-table-sticky thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #FAF8F5;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

</style>

@can('admin_dashboard')
    <!-- Top Header & Breadcrumb -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h1 class="dash-hero-title">{{ translate('Admin Dashboard') }}</h1>
            <p class="text-muted fs-12 mb-0">{{ translate('Welcome back! Here is a summary of your matrimonial platform.') }}</p>
        </div>
        <ul class="breadcrumb bg-transparent p-0 mb-0 fs-12 font-weight-500">
            <li class="breadcrumb-item opacity-50"><a class="text-reset" href="{{ route('admin.dashboard') }}">{{ translate('Home') }}</a></li>
            <li class="breadcrumb-item active font-weight-700" style="color: var(--mat-maroon);">{{ translate('Dashboard') }}</li>
        </ul>
    </div>

    @if(env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
        <div class="alert alert-danger d-flex align-items-center rounded-xl mb-4 border-0 shadow-sm" style="background: #FEE2E2; color: #991B1B;">
            <i class="las la-exclamation-circle fs-20 mr-2"></i>
            <div>
                {{ translate('Please Configure SMTP Setting to enable all email verification and notification functionality.') }}
                <a class="alert-link font-weight-700 ml-2" href="{{ route('smtp_settings') }}" style="color: #7F1D1D; text-decoration: underline;">{{ translate('Configure Now') }} &rarr;</a>
            </div>
        </div>
    @endif

    <!-- Row 1: Action Center & Tabbed Registrations (Left) + Monthly Earnings Bar Chart (Right) -->
    <div class="row">
        <!-- Left Column: Compact Action Center + Tabbed Registrations -->
        <div class="col-xl-5 col-lg-6">
            <div class="row">
                <!-- Compact Action Center Card -->
                <div class="col-12">
                    <div class="dash-card dash-stat-brand-maroon shadow-sm" style="height: auto !important; margin-bottom: 20px !important;">
                        <div class="dash-card-body" style="padding: 22px 24px;">
                            @php
                                $total_pending_tasks = $pending_payments_count + $unapproved_photos_count + $pending_members_count + $pending_meetings_count + $pending_contact_queries_count;
                            @endphp

                            <!-- Top Header: Icon + Title + Tasks Count -->
                            <div class="d-flex align-items-center justify-content-between mb-3" style="gap: 12px;">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: rgba(197, 160, 89, 0.2); border: 1px solid rgba(197, 160, 89, 0.45);">
                                        <i class="las la-bell fs-22" style="color: var(--mat-gold-light);"></i>
                                    </div>
                                    <div>
                                        <span class="dash-stat-label mb-1" style="color: var(--mat-gold-light); font-size: 11px; letter-spacing: 0.8px; line-height: 1.2;">{{ translate('Action Center') }}</span>
                                        <h5 class="fs-16 font-weight-700 text-white mb-0 dash-font-serif" style="line-height: 1.3;">{{ translate('Pending Approvals') }}</h5>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 11.5px; font-weight: 700; color: #FAF5EB; background: rgba(197, 160, 89, 0.22); border: 1px solid rgba(197, 160, 89, 0.5); border-radius: 999px; line-height: 1.2; white-space: nowrap;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #F59E0B; display: inline-block;"></span>
                                        {{ $total_pending_tasks }} {{ translate('Tasks') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Pending Badges / Quick Links -->
                            <div class="d-flex flex-wrap align-items-center mb-3 pt-1" style="gap: 10px;">
                                @if($pending_payments_count > 0)
                                    <a href="{{ route('package_payments.index') }}" class="dash-alert-pill text-decoration-none" style="padding: 6px 14px; font-size: 12px; gap: 7px;">
                                        <i class="las la-wallet" style="color: var(--mat-gold-light); font-size: 14px;"></i>
                                        <span>{{ $pending_payments_count }} {{ translate('Payments') }}</span>
                                    </a>
                                @endif
                                @if($unapproved_photos_count > 0)
                                    <a href="{{ route('unapproved_profile_pictures') }}" class="dash-alert-pill text-decoration-none" style="padding: 6px 14px; font-size: 12px; gap: 7px;">
                                        <i class="las la-camera text-info" style="font-size: 14px;"></i>
                                        <span>{{ $unapproved_photos_count }} {{ translate('Photos') }}</span>
                                    </a>
                                @endif
                                @if($pending_members_count > 0)
                                    <a href="{{ route('filterbyStatus', 'pending') }}" class="dash-alert-pill text-decoration-none" style="padding: 6px 14px; font-size: 12px; gap: 7px;">
                                        <i class="las la-user-clock text-success" style="font-size: 14px;"></i>
                                        <span>{{ $pending_members_count }} {{ translate('Members') }}</span>
                                    </a>
                                @endif
                                @if($pending_meetings_count > 0)
                                    <a href="{{ route('admin.consultations.index') }}" class="dash-alert-pill text-decoration-none" style="padding: 6px 14px; font-size: 12px; gap: 7px;">
                                        <i class="las la-handshake" style="color: var(--mat-gold-light); font-size: 14px;"></i>
                                        <span>{{ $pending_meetings_count }} {{ translate('Meetings') }}</span>
                                    </a>
                                @endif
                                @if($pending_contact_queries_count > 0)
                                    <a href="{{ route('contact-us.index') }}" class="dash-alert-pill text-decoration-none" style="padding: 6px 14px; font-size: 12px; gap: 7px;">
                                        <i class="las la-envelope text-info" style="font-size: 14px;"></i>
                                        <span>{{ $pending_contact_queries_count }} {{ translate('Emails') }}</span>
                                    </a>
                                @endif
                                @if($total_pending_tasks == 0)
                                    <span class="fs-12 text-white-50 py-1">
                                        <i class="las la-check-circle text-success mr-1.5" style="font-size: 15px;"></i> {{ translate('All tasks completed! No pending items.') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Bottom Action Row -->
                            @if($total_pending_tasks > 0)
                                <div class="pt-3 border-top d-flex justify-content-between align-items-center" style="border-color: rgba(255,255,255,0.15) !important;">
                                    <span class="fs-12 text-white-50">{{ translate('Requires your review') }}</span>
                                    <a href="{{ $pending_payments_count > 0 ? route('package_payments.index') : ($unapproved_photos_count > 0 ? route('unapproved_profile_pictures') : ($pending_members_count > 0 ? route('filterbyStatus', 'pending') : ($pending_meetings_count > 0 ? route('admin.consultations.index') : route('contact-us.index')))) }}" class="btn btn-xs rounded-pill font-weight-700 px-3.5 py-1.5 text-dark shadow-sm" style="background: var(--mat-gold-light); font-size: 11.5px;">
                                        {{ translate('Take Action') }} &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Clean White Card: New Registrants with Dynamic Avatar Stack -->
                <div class="col-12 mb-3">
                    <div class="dash-card">
                        <div class="dash-card-body" style="padding: 20px 22px;">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap: 8px;">
                                <div>
                                    <span class="dash-stat-label text-muted">{{ translate('Growth Activity') }}</span>
                                    <h5 class="fs-16 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('New Registrations') }}</h5>
                                </div>

                                <!-- Time Filter Tabs (Today / Weekly / Monthly) -->
                                <div class="dash-time-tabs">
                                    <button type="button" class="dash-tab-pill" onclick="switchRegTab('today', this)">
                                        {{ translate('Today') }}
                                    </button>
                                    <button type="button" class="dash-tab-pill" onclick="switchRegTab('weekly', this)">
                                        {{ translate('Weekly') }}
                                    </button>
                                    <button type="button" class="dash-tab-pill active" onclick="switchRegTab('monthly', this)">
                                        {{ translate('Monthly') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Count Display with Dynamic Subtext -->
                            <div class="d-flex align-items-baseline mb-3" style="gap: 10px;">
                                <div class="dash-stat-value" id="reg-count-display" style="color: var(--mat-maroon);">
                                    {{ $this_month_members }}
                                </div>
                                <span class="fs-13 text-muted font-weight-500" id="reg-desc-display">
                                    {{ translate('registered this month') }}
                                </span>
                            </div>
                            
                            <!-- Overlapping Dynamic Avatar Groups -->
                            <div class="d-flex align-items-center justify-content-between pt-2.5 border-top" style="border-color: var(--mat-border) !important;">
                                <!-- Today Avatars -->
                                <div id="reg-avatars-today" class="reg-avatar-group d-none">
                                    @if(count($today_registrants) > 0)
                                        <div class="avatar-stack-group">
                                            @foreach($today_registrants as $reg)
                                                @php
                                                    $photo_url = !empty($reg->photo) ? uploaded_asset($reg->photo) : static_asset('assets/img/avatar-place.png');
                                                @endphp
                                                <img src="{{ $photo_url }}" class="avatar-stack-item" title="{{ $reg->first_name }} {{ $reg->last_name }}" alt="{{ $reg->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fs-12 font-weight-500"><i class="las la-info-circle mr-1"></i>{{ translate('No members registered today') }}</span>
                                    @endif
                                </div>

                                <!-- Weekly Avatars -->
                                <div id="reg-avatars-weekly" class="reg-avatar-group d-none">
                                    @if(count($weekly_registrants) > 0)
                                        <div class="avatar-stack-group">
                                            @foreach($weekly_registrants as $reg)
                                                @php
                                                    $photo_url = !empty($reg->photo) ? uploaded_asset($reg->photo) : static_asset('assets/img/avatar-place.png');
                                                @endphp
                                                <img src="{{ $photo_url }}" class="avatar-stack-item" title="{{ $reg->first_name }} {{ $reg->last_name }}" alt="{{ $reg->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fs-12 font-weight-500"><i class="las la-info-circle mr-1"></i>{{ translate('No members registered this week') }}</span>
                                    @endif
                                </div>

                                <!-- Monthly Avatars (Default Active) -->
                                <div id="reg-avatars-monthly" class="reg-avatar-group">
                                    @if(count($monthly_registrants) > 0)
                                        <div class="avatar-stack-group">
                                            @foreach($monthly_registrants as $reg)
                                                @php
                                                    $photo_url = !empty($reg->photo) ? uploaded_asset($reg->photo) : static_asset('assets/img/avatar-place.png');
                                                @endphp
                                                <img src="{{ $photo_url }}" class="avatar-stack-item" title="{{ $reg->first_name }} {{ $reg->last_name }}" alt="{{ $reg->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fs-12 font-weight-500"><i class="las la-info-circle mr-1"></i>{{ translate('No members registered this month') }}</span>
                                    @endif
                                </div>

                                <a href="{{ route('members.index') }}" class="fs-12 font-weight-700 text-decoration-none ml-2 text-nowrap" style="color: var(--mat-maroon);">
                                    {{ translate('View all members') }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Monthly Earnings Bar Chart -->
        <div class="col-xl-7 col-lg-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <span class="dash-stat-label text-muted">{{ translate('Revenue Analytics') }}</span>
                            <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Monthly Earnings') }} ({{ date('Y') }})</h5>
                        </div>
                        <div class="text-right">
                            <span class="fs-11 text-muted d-block">{{ translate('This Month') }}</span>
                            <span class="fs-22 font-weight-800" style="color: var(--mat-maroon);">{{ single_price($this_month_earnings) }}</span>
                        </div>
                    </div>
                    <div>
                        <canvas id="monthly-earnings-bar-chart" height="195"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Distribution Analytics (All Members Doughnut + Package Earnings Doughnut + Active Status) -->
    <div class="row">
        <!-- All Members Card with Donut Chart -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body text-center">
                    <span class="dash-stat-label text-muted">{{ translate('Membership Split') }}</span>
                    <h5 class="fs-16 font-weight-700 text-dark mb-1 dash-font-serif">{{ translate('All Members') }}</h5>
                    <div class="dash-stat-value mb-3" style="color: var(--mat-maroon);">{{ $total_members }}</div>

                    <div style="height: 180px; position: relative;">
                        <canvas id="members-doughnut-chart"></canvas>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap pt-3 mt-2 border-top" style="border-color: var(--mat-border) !important; gap: 10px;">
                        <span class="plan-pill plan-pill-gold">
                            <i class="las la-crown"></i> {{ translate('Premium') }}: <strong>{{ $premium_members }}</strong>
                        </span>
                        <span class="plan-pill plan-pill-free">
                            <i class="las la-user"></i> {{ translate('Free') }}: <strong>{{ $free_members }}</strong>
                        </span>
                        @if($blocked_members > 0)
                            <span class="plan-pill" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FECACA;">
                                <i class="las la-ban"></i> {{ translate('Blocked') }}: <strong>{{ $blocked_members }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Earnings & Package Distribution -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body text-center">
                    <span class="dash-stat-label text-muted">{{ translate('Financial Overview') }}</span>
                    <h5 class="fs-16 font-weight-700 text-dark mb-1 dash-font-serif">{{ translate('Total Earnings') }}</h5>
                    <div class="dash-stat-value mb-3" style="color: var(--mat-maroon);">{{ single_price($total_earnings) }}</div>

                    <div style="height: 180px; position: relative;">
                        <canvas id="packages-doughnut-chart"></canvas>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap pt-3 mt-2 border-top" style="border-color: var(--mat-border) !important; gap: 8px;">
                        @foreach($package_stats as $pkg)
                            <span class="plan-pill plan-pill-maroon">
                                {{ $pkg['name'] }}: <strong>{{ $pkg['count'] }}</strong>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Currently Active Users / Gender Summary (Brand Maroon-Dark Card) -->
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="dash-card dash-stat-brand-navy" style="overflow: hidden;">
                <div class="dash-card-body d-flex flex-column justify-content-between h-100" style="padding: 22px 24px; min-height: 290px;">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2 flex-nowrap" style="gap: 8px;">
                            <span class="dash-stat-label mb-0" style="color: var(--mat-gold-light); font-size: 11px;">{{ translate('Platform Status') }}</span>
                            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; font-size: 11px; font-weight: 700; color: #FAF5EB; background: rgba(197, 160, 89, 0.22); border: 1px solid rgba(197, 160, 89, 0.4); border-radius: 999px; line-height: 1.2; white-space: nowrap; flex-shrink: 0;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #22C55E; display: inline-block; box-shadow: 0 0 6px #22C55E;"></span>
                                {{ translate('Live System') }}
                            </span>
                        </div>
                        <div class="dash-stat-title text-white fs-18 mb-1 dash-font-serif">{{ translate('Active Member Profiles') }}</div>
                        <div class="dash-stat-value text-white mb-2">{{ $active_members_count }}</div>
                        <div class="dash-stat-desc text-white-50 fs-12" style="margin-top: 4px;">
                            {{ translate('Publicly visible biodatas currently available for matching and search results.') }}
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-top" style="border-color: rgba(197, 160, 89, 0.2) !important;">
                        <div class="row text-center text-white no-gutters">
                            <div class="col-6 border-right" style="border-color: rgba(197, 160, 89, 0.2) !important; padding: 0 6px;">
                                <div class="fs-11 text-uppercase font-weight-700" style="color: var(--mat-gold-light); letter-spacing: 0.5px;">{{ translate('Male') }}</div>
                                <div class="fs-22 font-weight-800" style="color: #FFFFFF;">{{ $male_members_count }}</div>
                            </div>
                            <div class="col-6" style="padding: 0 6px;">
                                <div class="fs-11 text-uppercase font-weight-700" style="color: var(--mat-gold-light); letter-spacing: 0.5px;">{{ translate('Female') }}</div>
                                <div class="fs-22 font-weight-800" style="color: #FFFFFF;">{{ $female_members_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Modern Data Tables (Recent Members & Renewal Reminder) -->
    <div class="row">
        <!-- Left Table: Recent Members -->
        <div class="col-xl-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body p-0">
                    <div class="d-flex align-items-center justify-content-between p-4 pb-3">
                        <div>
                            <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Recent Members') }}</h5>
                            <span class="fs-12 text-muted">{{ translate('Recently registered member profiles') }}</span>
                        </div>
                        <a href="{{ route('members.index') }}" class="btn btn-sm rounded-pill px-3 font-weight-600 border" style="background: var(--mat-gold-bg); color: var(--mat-maroon); border-color: var(--mat-border) !important;">
                            {{ translate('View All') }}
                        </a>
                    </div>

                    <div class="dash-table-scroll">
                        <table class="table dash-table dash-table-sticky">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>{{ translate('Profile') }}</th>
                                    <th>{{ translate('Phone') }}</th>
                                    <th>{{ translate('Join Date') }}</th>
                                    <th>{{ translate('Plan Type') }}</th>
                                    <th class="text-right" style="width: 50px;">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_members as $index => $member)
                                    @php
                                        $photo_url = !empty($member->photo) ? uploaded_asset($member->photo) : static_asset('assets/img/avatar-place.png');
                                        $is_premium = $member->membership == 2;
                                        $pkg_name = ($member->member && $member->member->package) ? $member->member->package->name : ($is_premium ? 'Premium' : 'Free');
                                    @endphp
                                    <tr>
                                        <td class="font-weight-600 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $photo_url }}" class="rounded-circle mr-2.5 flex-shrink-0 border" style="width: 36px; height: 36px; object-fit: cover; border-color: var(--mat-border) !important;" alt="{{ $member->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                                <div class="text-truncate" style="max-width: 150px;">
                                                    <div class="font-weight-700 fs-13 mb-0" style="color: var(--mat-maroon);">{{ $member->first_name }} {{ $member->last_name }}</div>
                                                    <div class="text-muted fs-11 text-truncate">{{ $member->email ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fs-12 font-weight-500 text-dark">{{ $member->phone ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="fs-12 text-muted font-weight-500">{{ $member->created_at ? $member->created_at->format('d M, Y') : '-' }}</span>
                                        </td>
                                        <td>
                                            @if($is_premium)
                                                <span class="plan-pill plan-pill-gold">{{ $pkg_name }}</span>
                                            @else
                                                <span class="plan-pill plan-pill-free">{{ translate('Free') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('members.show', $member->id) }}" class="dash-action-btn" title="{{ translate('View Profile') }}">
                                                <i class="las la-eye fs-16"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">{{ translate('No members found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Table: Renewal Reminder / Expiring Soon -->
        <div class="col-xl-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body p-0">
                    <div class="d-flex align-items-center justify-content-between p-4 pb-3">
                        <div>
                            <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Renewal Reminder') }}</h5>
                            <span class="fs-12 text-muted">{{ translate('Profiles with upcoming expiration or active plan') }}</span>
                        </div>
                        <a href="{{ route('premium.members.index') }}" class="btn btn-sm rounded-pill px-3 font-weight-600 border" style="background: var(--mat-gold-bg); color: var(--mat-maroon); border-color: var(--mat-border) !important;">
                            {{ translate('View Premium') }}
                        </a>
                    </div>

                    <div class="dash-table-scroll">
                        <table class="table dash-table dash-table-sticky">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>{{ translate('Profile') }}</th>
                                    <th>{{ translate('Phone') }}</th>
                                    <th>{{ translate('Expiry Date') }}</th>
                                    <th>{{ translate('Plan Type') }}</th>
                                    <th class="text-right" style="width: 50px;">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expiring_members as $index => $exp_m)
                                    @php
                                        $exp_user = $exp_m->user;
                                        if (!$exp_user) continue;
                                        $photo_url = !empty($exp_user->photo) ? uploaded_asset($exp_user->photo) : static_asset('assets/img/avatar-place.png');
                                        $is_premium = $exp_user->membership == 2;
                                        $pkg_name = $exp_m->package ? $exp_m->package->name : ($is_premium ? 'Premium' : 'Free');
                                        $expiry_text = !empty($exp_m->package_validity) ? date('d M, Y', strtotime($exp_m->package_validity)) : translate('Unlimited');
                                    @endphp
                                    <tr>
                                        <td class="font-weight-600 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $photo_url }}" class="rounded-circle mr-2.5 flex-shrink-0 border" style="width: 36px; height: 36px; object-fit: cover; border-color: var(--mat-border) !important;" alt="{{ $exp_user->first_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                                <div class="text-truncate" style="max-width: 150px;">
                                                    <div class="font-weight-700 fs-13 mb-0" style="color: var(--mat-maroon);">{{ $exp_user->first_name }} {{ $exp_user->last_name }}</div>
                                                    <div class="text-muted fs-11 text-truncate">{{ $exp_user->email ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fs-12 font-weight-500 text-dark">{{ $exp_user->phone ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="fs-12 font-weight-600 {{ !empty($exp_m->package_validity) && strtotime($exp_m->package_validity) < strtotime('+7 days') ? 'text-danger' : 'text-dark' }}">
                                                {{ $expiry_text }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($is_premium)
                                                <span class="plan-pill plan-pill-gold">{{ $pkg_name }}</span>
                                            @else
                                                <span class="plan-pill plan-pill-free">{{ translate('Free') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('members.show', $exp_user->id) }}" class="dash-action-btn" title="{{ translate('View Profile') }}">
                                                <i class="las la-eye fs-16"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">{{ translate('No renewal records found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Service Operations (Meeting & Consultation Requests + Email Inquiries) -->
    <div class="row">
        <!-- Left Card: Meeting & Consultation Requests -->
        <div class="col-xl-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body p-0">
                    <div class="d-flex align-items-center justify-content-between p-4 pb-3">
                        <div>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Meeting Requests') }}</h5>
                                @if($pending_meetings_count > 0)
                                    <span class="badge badge-warning rounded-pill px-2.5 py-1 fs-11 font-weight-700">{{ $pending_meetings_count }} {{ translate('Pending') }}</span>
                                @endif
                            </div>
                            <span class="fs-12 text-muted">{{ translate('Member consultations & meeting bookings') }}</span>
                        </div>
                        <a href="{{ route('admin.consultations.index') }}" class="btn btn-sm rounded-pill px-3 font-weight-600 border" style="background: var(--mat-gold-bg); color: var(--mat-maroon); border-color: var(--mat-border) !important;">
                            {{ translate('View All') }}
                        </a>
                    </div>

                    <div class="dash-table-scroll">
                        <table class="table dash-table dash-table-sticky">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>{{ translate('Requester') }}</th>
                                    <th>{{ translate('Target Member') }}</th>
                                    <th>{{ translate('Type & Schedule') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-right" style="width: 50px;">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_consultations as $index => $consultation)
                                    @php
                                        $c_user = $consultation->user;
                                        $c_target = $consultation->targetUser;
                                        $c_photo = ($c_user && !empty($c_user->photo)) ? uploaded_asset($c_user->photo) : static_asset('assets/img/avatar-place.png');
                                        $target_photo = ($c_target && !empty($c_target->photo)) ? uploaded_asset($c_target->photo) : static_asset('assets/img/avatar-place.png');
                                    @endphp
                                    <tr>
                                        <td class="font-weight-600 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $c_photo }}" class="rounded-circle mr-2 flex-shrink-0 border" style="width: 34px; height: 34px; object-fit: cover; border-color: var(--mat-border) !important;" alt="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                                <div class="text-truncate" style="max-width: 140px;">
                                                    <div class="font-weight-700 fs-13 mb-0" style="color: var(--mat-maroon);">{{ $c_user ? $c_user->first_name . ' ' . $c_user->last_name : translate('Deleted Member') }}</div>
                                                    <div class="text-muted fs-11">{{ $consultation->phone ?? ($c_user ? $c_user->phone : '-') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($c_target)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $target_photo }}" class="rounded-circle mr-2 flex-shrink-0 border" style="width: 28px; height: 28px; object-fit: cover; border-color: var(--mat-border) !important;" alt="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                                    <span class="fs-12 font-weight-600 text-dark text-truncate" style="max-width: 120px;">{{ $c_target->first_name }} {{ $c_target->last_name }}</span>
                                                </div>
                                            @else
                                                <span class="badge badge-inline badge-light text-muted fs-11"><i class="las la-user-tie mr-1"></i>{{ translate('Matchmaker') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fs-12 font-weight-600 text-dark text-capitalize">{{ str_replace('_', ' ', $consultation->meeting_type ?? 'Consultation') }}</div>
                                            <div class="fs-11 text-muted">
                                                @if(!empty($consultation->preferred_date))
                                                    {{ date('d M, Y', strtotime($consultation->preferred_date)) }}
                                                @endif
                                                @if(!empty($consultation->preferred_slot))
                                                    <span class="text-nowrap">({{ $consultation->preferred_slot }})</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($consultation->status == 'pending')
                                                <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                                            @elseif($consultation->status == 'scheduled')
                                                <span class="badge badge-inline badge-success">{{ translate('Scheduled') }}</span>
                                            @elseif($consultation->status == 'completed')
                                                <span class="badge badge-inline badge-info">{{ translate('Completed') }}</span>
                                            @elseif($consultation->status == 'cancelled')
                                                <span class="badge badge-inline badge-danger">{{ translate('Cancelled') }}</span>
                                            @else
                                                <span class="badge badge-inline badge-light">{{ ucfirst($consultation->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.consultations.index', ['search' => $consultation->phone ?? ($c_user ? $c_user->first_name : '')]) }}" class="dash-action-btn" title="{{ translate('Manage Meeting') }}">
                                                <i class="las la-calendar-check fs-16"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">{{ translate('No meeting requests found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Card: Email & Contact Queries -->
        <div class="col-xl-6 mb-4">
            <div class="dash-card">
                <div class="dash-card-body p-0">
                    <div class="d-flex align-items-center justify-content-between p-4 pb-3">
                        <div>
                            <div class="d-flex align-items-center" style="gap: 8px;">
                                <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Email & Contact Queries') }}</h5>
                                @if($pending_contact_queries_count > 0)
                                    <span class="badge badge-danger rounded-pill px-2.5 py-1 fs-11 font-weight-700">{{ $pending_contact_queries_count }} {{ translate('Unreplied') }}</span>
                                @endif
                            </div>
                            <span class="fs-12 text-muted">{{ translate('Incoming messages from contact form') }}</span>
                        </div>
                        <a href="{{ route('contact-us.index') }}" class="btn btn-sm rounded-pill px-3 font-weight-600 border" style="background: var(--mat-gold-bg); color: var(--mat-maroon); border-color: var(--mat-border) !important;">
                            {{ translate('View All') }}
                        </a>
                    </div>

                    <div class="dash-table-scroll">
                        <table class="table dash-table dash-table-sticky">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>{{ translate('Sender') }}</th>
                                    <th>{{ translate('Subject & Message') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-right" style="width: 50px;">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_contact_queries as $index => $contact)
                                    <tr>
                                       <td class="font-weight-600 text-muted">{{ $index + 1 }}</td>
                                       <td>
                                           <div class="font-weight-700 fs-13 mb-0" style="color: var(--mat-maroon);">{{ $contact->name }}</div>
                                           <div class="text-muted fs-11 text-truncate" style="max-width: 140px;">{{ $contact->email }}</div>
                                       </td>
                                       <td>
                                           <div class="font-weight-600 fs-12 text-dark text-truncate" style="max-width: 180px;">{{ $contact->subject }}</div>
                                           <div class="text-muted fs-11 text-truncate" style="max-width: 180px;">{{ Str::limit($contact->description, 45) }}</div>
                                       </td>
                                       <td>
                                           <span class="fs-12 text-muted font-weight-500">{{ date('d M, Y', strtotime($contact->created_at)) }}</span>
                                       </td>
                                       <td>
                                           @if($contact->status == 0)
                                               <span class="badge badge-inline badge-warning">{{ translate('Not Replied') }}</span>
                                           @else
                                               <span class="badge badge-inline badge-success">{{ translate('Replied') }}</span>
                                           @endif
                                       </td>
                                       <td class="text-right">
                                           <a href="{{ route('contact-us.show', $contact->id) }}" class="dash-action-btn" title="{{ translate('View Query') }}">
                                               <i class="las la-envelope-open-text fs-16"></i>
                                           </a>
                                       </td>
                                    </tr>
                                @empty
                                    <tr>
                                       <td colspan="6" class="text-center py-4 text-muted">{{ translate('No contact queries found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Happy Stories Section (Preserved) -->
    @if($total_happy_stories > 0)
        <div class="dash-card mb-4">
            <div class="dash-card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <span class="dash-stat-label text-muted">{{ translate('Success Stories') }}</span>
                        <h5 class="fs-17 font-weight-700 text-dark mb-0 dash-font-serif">{{ translate('Recent Happy Stories') }}</h5>
                    </div>
                    <a href="{{ route('happy-story.index') }}" class="btn btn-sm rounded-pill px-3 font-weight-600 border" style="background: var(--mat-gold-bg); color: var(--mat-maroon); border-color: var(--mat-border) !important;">
                        {{ translate('Manage Stories') }} &rarr;
                    </a>
                </div>

                <div class="aiz-carousel gutters-10" data-items="4" data-xl-items="4" data-lg-items="3" data-md-items="2" data-sm-items="1" data-xs-items="1">
                    @foreach ($happy_stories as $key => $happy_story)
                        @php
                            $photo = explode(',', $happy_story->photos);
                        @endphp
                        <div class="carousel-box">
                            <div class="card overflow-hidden border rounded-xl shadow-none mb-0" style="border-color: var(--mat-border) !important;">
                                <a href="{{ route('story_details', $happy_story->id) }}" class="text-reset d-block position-relative">
                                    <img src="{{ uploaded_asset($photo[0]) }}" class="img-fit h-140px mw-100" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                </a>
                                <div class="p-3" style="background: #FFFDF9;">
                                    <div class="fw-700 fs-13 text-truncate mb-1" style="color: var(--mat-maroon);">{{ $happy_story->user ? $happy_story->user->first_name : '' }} & {{ $happy_story->partner_name }}</div>
                                    <div class="fs-12 text-muted text-truncate-2" style="height: 34px; overflow: hidden;">
                                        {{ $happy_story->title }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endcan
@endsection

@section('script')
<script type="text/javascript">
    // Interactive Tab Switching for Today / Weekly / Monthly Registrations
    var regCounts = {
        today: {
            count: '{{ $today_new_members }}',
            desc: '{{ translate("registered today") }}'
        },
        weekly: {
            count: '{{ $this_week_members }}',
            desc: '{{ translate("registered this week") }}'
        },
        monthly: {
            count: '{{ $this_month_members }}',
            desc: '{{ translate("registered this month") }}'
        }
    };

    function switchRegTab(type, el) {
        $('.dash-tab-pill').removeClass('active');
        $(el).addClass('active');
        $('#reg-count-display').text(regCounts[type].count);
        $('#reg-desc-display').text(regCounts[type].desc);

        // Switch avatar stacks
        $('.reg-avatar-group').addClass('d-none');
        $('#reg-avatars-' + type).removeClass('d-none');
    }

    $(document).ready(function() {
        // 1. Monthly Earnings Bar Chart (Luxury Maroon & Gold Theme)
        AIZ.plugins.chart('#monthly-earnings-bar-chart', {
            type: 'bar',
            data: {
                labels: [
                    '{{ translate("Jan") }}',
                    '{{ translate("Feb") }}',
                    '{{ translate("Mar") }}',
                    '{{ translate("Apr") }}',
                    '{{ translate("May") }}',
                    '{{ translate("Jun") }}',
                    '{{ translate("Jul") }}',
                    '{{ translate("Aug") }}',
                    '{{ translate("Sep") }}',
                    '{{ translate("Oct") }}',
                    '{{ translate("Nov") }}',
                    '{{ translate("Dec") }}'
                ],
                datasets: [{
                    label: '{{ translate("Earnings") }}',
                    data: {!! json_encode($monthly_earnings) !!},
                    backgroundColor: 'rgba(74, 21, 33, 0.18)',
                    borderColor: '#4A1521',
                    borderWidth: 1.5,
                    hoverBackgroundColor: '#4A1521',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontSize: 11,
                            fontColor: '#94A3B8'
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: '#F1F5F9',
                            drawBorder: false,
                            zeroLineColor: '#F1F5F9'
                        },
                        ticks: {
                            fontSize: 11,
                            fontColor: '#94A3B8',
                            beginAtZero: true,
                            maxTicksLimit: 6
                        }
                    }]
                }
            }
        });

        // 2. Members Distribution Doughnut Chart (Maroon, Gold, Slate)
        AIZ.plugins.chart('#members-doughnut-chart', {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ translate("Premium") }}',
                    '{{ translate("Free") }}',
                    '{{ translate("Blocked") }}'
                ],
                datasets: [{
                    data: [
                        {{ $premium_members }},
                        {{ $free_members }},
                        {{ $blocked_members }}
                    ],
                    backgroundColor: [
                        '#C5A059', // Brand Gold
                        '#E2DCD5', // Brand Cream-Gray
                        '#DC2626'  // Danger Red
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 72,
                legend: {
                    display: false
                }
            }
        });

        // 3. Package Stats Doughnut Chart
        AIZ.plugins.chart('#packages-doughnut-chart', {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_column($package_stats, 'name')) !!},
                datasets: [{
                    data: {!! json_encode(array_column($package_stats, 'count')) !!},
                    backgroundColor: [
                        '#4A1521', // Maroon
                        '#967240', // Gold
                        '#C5A059', // Light Gold
                        '#2C0A12', // Dark Maroon
                        '#6A2131'  // Light Maroon
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 72,
                legend: {
                    display: false
                }
            }
        });
    });
</script>
@endsection
