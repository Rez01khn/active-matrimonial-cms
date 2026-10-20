@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="consultations-portal-wrap">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 fw-700 text-dark mb-1" style="font-family: var(--mat-font-serif); color: var(--mat-maroon) !important;">
                    {{ translate('Matchmaker Consultation & Meeting Manager') }}
                </h1>
                <p class="text-muted fs-13 mb-0">
                    {{ translate('Private 1-on-1 advisory sessions with your personal relationship expert.') }}
                </p>
            </div>
            <div>
                <span class="badge badge-inline" style="background: rgba(229, 194, 134, 0.2); color: var(--mat-gold); border: 1px solid var(--mat-gold); padding: 6px 12px; font-weight: 600; font-size: 12px; border-radius: 20px;">
                    <i class="las la-certificate mr-1"></i>{{ translate('Dedicated Matchmaking') }}
                </span>
            </div>
        </div>

        {{-- TOP SECTION: Upcoming / Active Meeting Card --}}
        @if ($upcoming_meeting)
            <div class="consultation-upcoming-hero">
                <div class="consultation-hero-glow"></div>
                <div class="row align-items-center position-relative z-1">
                    <div class="col-lg-8 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center mb-2">
                            <span class="consult-badge-scheduled mr-2">
                                <i class="las la-check-circle mr-1"></i>{{ translate('Scheduled') }}
                            </span>
                            <span class="fs-12 text-white-50">
                                {{ translate('Request ID') }}: #{{ $upcoming_meeting->id }}
                            </span>
                        </div>
                        @if ($upcoming_meeting->targetUser)
                            @php
                                $meeting_partner = ($upcoming_meeting->user_id == $user->id) ? $upcoming_meeting->targetUser : $upcoming_meeting->user;
                            @endphp
                            <h3 class="h4 fw-700 text-white mb-2" style="font-family: var(--mat-font-serif);">
                                <i class="las la-user-friends mr-1 text-warning"></i>
                                {{ translate('Arranged Meeting with') }} {{ $meeting_partner->first_name }} {{ $meeting_partner->last_name }}
                            </h3>
                        @else
                            <h3 class="h4 fw-700 text-white mb-2" style="font-family: var(--mat-font-serif);">
                                {{ translate('Upcoming 1-on-1 Matchmaker Meeting') }}
                            </h3>
                        @endif
                        <div class="d-flex flex-wrap align-items-center fs-14 text-white-90 mb-2" style="gap: 16px;">
                            <div>
                                <i class="las la-calendar text-warning mr-1 fs-16"></i>
                                <strong>{{ \Carbon\Carbon::parse($upcoming_meeting->scheduled_at)->format('l, d F Y') }}</strong>
                            </div>
                            <div>
                                <i class="las la-clock text-warning mr-1 fs-16"></i>
                                <strong>{{ \Carbon\Carbon::parse($upcoming_meeting->scheduled_at)->format('h:i A') }}</strong>
                            </div>
                        </div>
                        @if ($upcoming_meeting->notes)
                            <p class="fs-13 text-white-50 mb-0">
                                <em>"{{ $upcoming_meeting->notes }}"</em>
                            </p>
                        @endif
                    </div>
                    <div class="col-lg-4 text-lg-right">
                        @if ($upcoming_meeting->meeting_link)
                            <a href="{{ $upcoming_meeting->meeting_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-consult-join">
                                <i class="las la-video fs-18 mr-2"></i>{{ translate('Join Video Meeting') }}
                            </a>
                        @else
                            <button class="btn btn-consult-join" disabled style="opacity: 0.7; cursor: not-allowed;">
                                <i class="las la-clock fs-18 mr-2"></i>{{ translate('Link Generating Soon') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @elseif ($pending_request)
            <div class="consultation-card mb-4" style="border-left: 4px solid var(--mat-gold);">
                <div class="p-3 p-md-4 d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <div class="mr-3" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center; color: #D97706; font-size: 22px;">
                            <i class="las la-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center flex-wrap">
                                <h5 class="h6 fw-700 mb-0 text-dark mr-2">
                                    @if ($pending_request->targetUser)
                                        @php
                                            $pending_partner = ($pending_request->user_id == $user->id) ? $pending_request->targetUser : $pending_request->user;
                                        @endphp
                                        {{ translate('Arranged Meeting Request with') }} {{ $pending_partner->first_name }} {{ $pending_partner->last_name }}
                                    @else
                                        {{ translate('Consultation Request Pending') }}
                                    @endif
                                </h5>
                                <span class="consult-badge-pending">{{ translate('Pending Confirmation') }}</span>
                            </div>
                            <p class="fs-13 text-muted mb-0 mt-1">
                                {{ translate('Preferred:') }} <strong>{{ \Carbon\Carbon::parse($pending_request->preferred_date)->format('d M, Y') }}</strong> ({{ $pending_request->preferred_slot }})
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 12px;">
                        <span class="fs-12 text-muted">
                            {{ translate('Matchmaker team will confirm time & link shortly.') }}
                        </span>
                        @if ($pending_request->user_id == $user->id)
                            <form action="{{ route('consultations.cancel', $pending_request->id) }}" method="POST" onsubmit="return confirm('{{ translate('Are you sure you want to cancel this consultation request?') }}');" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 20px; font-size: 11px; padding: 4px 12px;">
                                    <i class="las la-times mr-1"></i>{{ translate('Cancel Request') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- MIDDLE SECTION: Book a Call (or VIP Upgrade Banner for Free Members) --}}
        @if ($user->membership == 2)
            <div class="consultation-card mb-4">
                <div class="consultation-card-header">
                    <h2 class="consultation-card-title">
                        <i class="las la-calendar-plus" style="color: var(--mat-gold); font-size: 22px;"></i>
                        @if ($target_user)
                            {{ translate('Schedule Meeting with') }} {{ $target_user->first_name }} {{ $target_user->last_name }}
                        @else
                            {{ translate('Book a 1-on-1 Matchmaker Call') }}
                        @endif
                    </h2>
                    <span class="fs-12 text-muted">{{ translate('Fast 24h Coordination') }}</span>
                </div>
                <div class="p-3 p-md-4">
                    @if ($target_user)
                        <div class="alert alert-light border mb-4 p-3 d-flex align-items-center justify-content-between flex-wrap" style="border-left: 4px solid var(--mat-maroon) !important; background: #FFF8F6; border-radius: 12px; gap: 12px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ uploaded_asset($target_user->photo) ?? static_asset('assets/img/avatar-place.png') }}" 
                                     class="rounded-circle mr-3 border" style="width: 50px; height: 50px; object-fit: cover; border-color: var(--mat-gold) !important;"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                <div>
                                    <span class="badge badge-inline badge-soft-primary px-2 py-1 mb-1 font-weight-bold fs-11">
                                        <i class="las la-heart mr-1"></i>{{ translate('Candidate Arranged Meeting') }}
                                    </span>
                                    <h6 class="mb-0 fw-700 text-dark">{{ $target_user->first_name }} {{ $target_user->last_name }}</h6>
                                    <small class="text-muted">#{{ $target_user->code ?? $target_user->id }} &middot; {{ $target_user->member->marital_status->name ?? translate('Member') }}</small>
                                </div>
                            </div>
                            <a href="{{ route('consultations.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold" style="border-radius: 20px;">
                                <i class="las la-times mr-1"></i>{{ translate('General Matchmaker Call') }}
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('consultations.store') }}" method="POST">
                        @csrf
                        @if ($target_user)
                            <input type="hidden" name="target_user_id" value="{{ $target_user->id }}">
                        @endif

                        <div class="row">
                            {{-- Field 1: Preferred Date --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 fs-13 text-dark mb-1">
                                    {{ translate('Preferred Date') }} <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="preferred_date" class="form-control" style="border-color: var(--mat-border);"
                                       min="{{ date('Y-m-d') }}" value="{{ old('preferred_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                                <small class="text-muted fs-11">{{ translate('Select any convenient date.') }}</small>
                            </div>

                            {{-- Field 2: Preferred Time Slot --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 fs-13 text-dark mb-1">
                                    {{ translate('Preferred Slot') }} <span class="text-danger">*</span>
                                </label>
                                <select name="preferred_slot" class="form-control" style="border-color: var(--mat-border);" required>
                                    <option value="Morning (10:00 AM - 01:00 PM)">{{ translate('Morning (10:00 AM - 01:00 PM)') }}</option>
                                    <option value="Afternoon (02:00 PM - 05:00 PM)" selected>{{ translate('Afternoon (02:00 PM - 05:00 PM)') }}</option>
                                    <option value="Evening (06:00 PM - 09:00 PM)">{{ translate('Evening (06:00 PM - 09:00 PM)') }}</option>
                                    <option value="Night (09:00 PM - 11:00 PM)">{{ translate('Night (09:00 PM - 11:00 PM)') }}</option>
                                </select>
                                <small class="text-muted fs-11">{{ translate('Preferred window for meeting.') }}</small>
                            </div>

                            {{-- Field 3: Short Note --}}
                            <div class="col-12 mb-3">
                                <label class="form-label fw-600 fs-13 text-dark mb-1">
                                    {{ translate('Discussion Topic / Note') }} <span class="text-muted fs-11">({{ translate('Optional') }})</span>
                                </label>
                                <textarea name="notes" class="form-control" rows="2" style="border-color: var(--mat-border);"
                                          placeholder="{{ $target_user ? translate('Write any specific topics or questions you would like to discuss with the candidate or matchmaker...') : translate('Describe what you are looking for, specific partner preferences, or questions for your matchmaker...') }}">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-consult-submit">
                                <i class="las la-paper-plane mr-2"></i>{{ $target_user ? translate('Request Candidate Meeting →') : translate('Request Consultation →') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            {{-- HIGH-CONVERTING VIP UPGRADE CARD FOR FREE USERS --}}
            <div class="consultation-card mb-4" style="background: linear-gradient(135deg, #FFFDF9 0%, #FFF5EB 100%); border: 1px solid #F3E5D8; overflow: hidden; position: relative;">
                <div style="position: absolute; right: -20px; bottom: -20px; font-size: 160px; color: rgba(212, 175, 55, 0.08); pointer-events: none;">
                    <i class="las la-crown"></i>
                </div>
                <div class="p-4 p-md-5 position-relative z-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill mb-3" style="background: rgba(180, 83, 9, 0.1); border: 1px solid rgba(180, 83, 9, 0.2); color: #B45309; font-weight: 700; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">
                                <i class="las la-crown mr-1 fs-16"></i> {{ translate('Exclusive VIP & Premium Feature') }}
                            </div>
                            <h2 class="h3 fw-700 text-dark mb-2" style="font-family: var(--mat-font-serif); color: #2C0A12 !important;">
                                {{ translate('Unlock 1-on-1 Matchmaker Consultations & Candidate Meetings') }}
                            </h2>
                            <p class="text-muted fs-14 mb-4" style="max-width: 600px; line-height: 1.6;">
                                {{ translate('Arranging personal 1-on-1 video calls, dedicated matchmaker counseling, and mediated family introduction meetings is exclusively available to our verified VIP & Premium members.') }}
                            </p>

                            <div class="row gutters-10">
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center fs-13 text-dark font-weight-500">
                                        <i class="las la-check-circle text-success fs-18 mr-2"></i>
                                        <span>{{ translate('Direct arranged candidate & family meetings') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center fs-13 text-dark font-weight-500">
                                        <i class="las la-check-circle text-success fs-18 mr-2"></i>
                                        <span>{{ translate('Dedicated Senior Relationship Expert') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center fs-13 text-dark font-weight-500">
                                        <i class="las la-check-circle text-success fs-18 mr-2"></i>
                                        <span>{{ translate('Discussion notes & personalized recommendations') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="d-flex align-items-center fs-13 text-dark font-weight-500">
                                        <i class="las la-check-circle text-success fs-18 mr-2"></i>
                                        <span>{{ translate('100% Verified biodatas & background matching') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-right text-center">
                            <div class="bg-white p-4 rounded-xl shadow-sm border text-center" style="border-radius: 16px; border-color: #E5D5C5 !important;">
                                <div class="mb-2">
                                    <span class="fs-12 text-muted text-uppercase font-weight-bold">{{ translate('Membership Plans') }}</span>
                                    <h4 class="h5 fw-700 text-dark mt-1">{{ translate('Upgrade to Premium') }}</h4>
                                </div>
                                <p class="fs-12 text-muted mb-3">{{ translate('Choose a suitable plan to start booking arranged meetings immediately.') }}</p>
                                <a href="{{ route('packages') }}" class="btn btn-block text-white py-2 font-weight-bold" style="background: linear-gradient(135deg, #800020 0%, #500014 100%); border-radius: 8px; box-shadow: 0 4px 12px rgba(128, 0, 32, 0.25);">
                                    <i class="las la-crown mr-1"></i>{{ translate('View Membership Plans →') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- BOTTOM SECTION: Discussion History --}}
        <div class="consultation-card">
            <div class="consultation-card-header">
                <h2 class="consultation-card-title">
                    <i class="las la-history" style="color: var(--mat-gold); font-size: 22px;"></i>
                    {{ translate('Discussion History & Matchmaker Notes') }}
                </h2>
                <span class="fs-12 text-muted">{{ count($history) }} {{ translate('records') }}</span>
            </div>
            <div class="p-3 p-md-4">
                @if (count($history) > 0)
                    <div class="consultation-timeline">
                        @foreach ($history as $item)
                            <div class="consultation-timeline-item">
                                <div class="consultation-timeline-dot"></div>
                                <div class="d-flex align-items-center justify-content-between flex-wrap mb-1">
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                        <span class="fw-700 text-dark fs-14 mr-2" style="font-family: var(--mat-font-serif);">
                                            {{ $item->scheduled_at ? \Carbon\Carbon::parse($item->scheduled_at)->format('d F, Y - h:i A') : \Carbon\Carbon::parse($item->preferred_date)->format('d F, Y') }}
                                        </span>
                                        @if ($item->targetUser)
                                            @php
                                                $session_partner = ($item->user_id == $user->id) ? $item->targetUser : $item->user;
                                            @endphp
                                            <span class="badge badge-inline badge-soft-primary" style="font-size: 11px;">
                                                <i class="las la-user-friends mr-1"></i>{{ translate('Meeting with') }} {{ $session_partner->first_name }} {{ $session_partner->last_name }}
                                            </span>
                                        @else
                                            <span class="badge badge-inline badge-soft-warning" style="font-size: 11px;">
                                                <i class="las la-certificate mr-1"></i>{{ translate('Matchmaker Advisory') }}
                                            </span>
                                        @endif
                                        @if ($item->status == 'completed')
                                            <span class="consult-badge-completed">{{ translate('Completed') }}</span>
                                        @elseif ($item->status == 'cancelled')
                                            <span class="consult-badge-cancelled">{{ translate('Cancelled') }}</span>
                                        @elseif ($item->status == 'scheduled')
                                            <span class="consult-badge-scheduled">{{ translate('Scheduled') }}</span>
                                        @else
                                            <span class="consult-badge-pending">{{ translate('Pending') }}</span>
                                        @endif
                                    </div>
                                    <span class="fs-12 text-muted">
                                        {{ translate('Slot') }}: {{ $item->preferred_slot }}
                                    </span>
                                </div>

                                {{-- User's note --}}
                                @if ($item->notes)
                                    <div class="fs-12 text-muted mb-2">
                                        <i class="las la-comment-alt mr-1"></i><strong>{{ translate('Your Note') }}:</strong> {{ $item->notes }}
                                    </div>
                                @endif

                                {{-- Matchmaker Team's Discussion History --}}
                                @if ($item->discussion_history)
                                    <div class="p-3 rounded" style="background: var(--mat-bg-section); border: 1px solid var(--mat-border); border-left: 3px solid var(--mat-gold);">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="las la-user-tie text-warning mr-1 fs-16"></i>
                                            <strong class="fs-13" style="color: var(--mat-maroon);">{{ translate('Matchmaker Feedback & Action Plan') }}:</strong>
                                        </div>
                                        <p class="fs-13 text-dark mb-0" style="white-space: pre-line;">{{ $item->discussion_history }}</p>
                                    </div>
                                @else
                                    <p class="fs-12 text-muted font-italic mb-0">
                                        {{ translate('No discussion notes logged for this session yet.') }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--mat-gold-bg); color: var(--mat-gold); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 28px;">
                            <i class="las la-comments"></i>
                        </div>
                        <h4 class="h6 fw-700 text-dark mb-1">{{ translate('No Consultation History Yet') }}</h4>
                        <p class="text-muted fs-13 mb-0" style="max-width: 420px; margin: 0 auto;">
                            {{ translate('Once you complete a consultation call with our matchmaking team, your personalized discussion summary and suggestions will appear here.') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ALL CONSULTATION RECORDS TABLE --}}
        <div class="consultation-card mt-4">
            <div class="consultation-card-header d-flex justify-content-between align-items-center flex-wrap">
                <h2 class="consultation-card-title mb-0">
                    <i class="las la-list-alt" style="color: var(--mat-gold); font-size: 22px;"></i>
                    {{ translate('All Meeting Records & Status') }}
                </h2>
                <span class="fs-12 text-muted">{{ $all_consultations->total() }} {{ translate('total requests') }}</span>
            </div>
            <div class="p-3 p-md-4">
                @if ($all_consultations->count() > 0)
                    <div class="table-responsive">
                        <table class="table aiz-table mb-0 text-left">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th class="fs-12">{{ translate('Type & Candidate') }}</th>
                                    <th class="fs-12">{{ translate('Preferred Date & Slot') }}</th>
                                    <th class="fs-12">{{ translate('Confirmed Schedule') }}</th>
                                    <th class="fs-12">{{ translate('Status') }}</th>
                                    <th class="fs-12 text-right">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_consultations as $item)
                                    <tr>
                                        <td>
                                            @if ($item->targetUser)
                                                @php
                                                    $item_partner = ($item->user_id == $user->id) ? $item->targetUser : $item->user;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ uploaded_asset($item_partner->photo) }}"
                                                         class="size-35px rounded-circle img-fit mr-2 border"
                                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                                    <div>
                                                        <div class="fw-600 text-dark fs-13">
                                                            {{ $item_partner->first_name }} {{ $item_partner->last_name }}
                                                        </div>
                                                        <small class="badge badge-inline badge-soft-primary px-1" style="font-size: 10px;">
                                                            {{ translate('Arranged Candidate Meeting') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center">
                                                    <div class="size-35px rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center mr-2 font-weight-bold fs-16">
                                                        <i class="las la-certificate"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-600 text-dark fs-13">{{ translate('1-on-1 Matchmaker Advisory') }}</div>
                                                        <small class="text-muted fs-11">{{ translate('General Consultation') }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fs-13 font-weight-600 text-dark">
                                                <i class="las la-calendar text-muted mr-1"></i>{{ \Carbon\Carbon::parse($item->preferred_date)->format('d M, Y') }}
                                            </div>
                                            <small class="text-muted d-block">{{ $item->preferred_slot }}</small>
                                        </td>
                                        <td>
                                            @if ($item->scheduled_at)
                                                <span class="text-success font-weight-bold fs-13">
                                                    <i class="las la-clock mr-1"></i>{{ \Carbon\Carbon::parse($item->scheduled_at)->format('d M Y, h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted font-italic fs-12">{{ translate('Awaiting Schedule') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 'scheduled')
                                                <span class="consult-badge-scheduled">{{ translate('Scheduled') }}</span>
                                            @elseif ($item->status == 'completed')
                                                <span class="consult-badge-completed">{{ translate('Completed') }}</span>
                                            @elseif ($item->status == 'cancelled')
                                                <span class="consult-badge-cancelled">{{ translate('Cancelled') }}</span>
                                            @else
                                                <span class="consult-badge-pending">{{ translate('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($item->status == 'scheduled' && $item->meeting_link)
                                                <a href="{{ $item->meeting_link }}" target="_blank" class="btn btn-xs btn-primary font-weight-bold px-2 py-1" style="border-radius: 12px;">
                                                    <i class="las la-video mr-1"></i>{{ translate('Join') }}
                                                </a>
                                            @elseif ($item->status == 'pending' && $item->user_id == $user->id)
                                                <form action="{{ route('consultations.cancel', $item->id) }}" method="POST" onsubmit="return confirm('{{ translate('Cancel this consultation request?') }}');" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-soft-danger px-2 py-1" style="border-radius: 12px;" title="{{ translate('Cancel') }}">
                                                        <i class="las la-times mr-1"></i>{{ translate('Cancel') }}
                                                    </button>
                                                </form>
                                            @elseif ($item->status == 'completed' && $item->discussion_history)
                                                <span class="badge badge-inline badge-soft-success" title="{{ translate('Discussion Notes Available') }}">
                                                    <i class="las la-check-circle mr-1"></i>{{ translate('Notes Available') }}
                                                </span>
                                            @else
                                                <span class="text-muted fs-12">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="aiz-pagination mt-3">
                        {{ $all_consultations->links() }}
                    </div>
                @else
                    <p class="text-muted fs-13 mb-0">{{ translate('No consultation requests recorded yet.') }}</p>
                @endif
            </div>
        </div>

    </div>
@endsection
