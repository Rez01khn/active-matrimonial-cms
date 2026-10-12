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
                        <h3 class="h4 fw-700 text-white mb-2" style="font-family: var(--mat-font-serif);">
                            {{ translate('Upcoming 1-on-1 Matchmaker Meeting') }}
                        </h3>
                        <div class="d-flex flex-wrap align-items-center fs-14 text-white-90 mb-2" style="gap: 16px;">
                            <div>
                                <i class="las la-calendar text-warning mr-1 fs-16"></i>
                                <strong>{{ \Carbon\Carbon::parse($upcoming_meeting->scheduled_at)->format('l, d F Y') }}</strong>
                            </div>
                            <div>
                                <i class="las la-clock text-warning mr-1 fs-16"></i>
                                <strong>{{ \Carbon\Carbon::parse($upcoming_meeting->scheduled_at)->format('h:i A') }}</strong>
                            </div>
                            <div>
                                <i class="las la-phone text-warning mr-1 fs-16"></i>
                                <span>{{ $upcoming_meeting->phone }}</span>
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
                            <div class="d-flex align-items-center">
                                <h5 class="h6 fw-700 mb-0 text-dark mr-2">{{ translate('Consultation Request Pending') }}</h5>
                                <span class="consult-badge-pending">{{ translate('Pending Confirmation') }}</span>
                            </div>
                            <p class="fs-13 text-muted mb-0 mt-1">
                                {{ translate('Preferred:') }} <strong>{{ \Carbon\Carbon::parse($pending_request->preferred_date)->format('d M, Y') }}</strong> ({{ $pending_request->preferred_slot }}) &middot; {{ translate('Contact:') }} {{ $pending_request->phone }}
                            </p>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">
                        {{ translate('Matchmaker team will confirm time & link shortly.') }}
                    </span>
                </div>
            </div>
        @endif

        {{-- MIDDLE SECTION: Book a 1-on-1 Call (Minimal 3-Field Form) --}}
        <div class="consultation-card mb-4">
            <div class="consultation-card-header">
                <h2 class="consultation-card-title">
                    <i class="las la-calendar-plus" style="color: var(--mat-gold); font-size: 22px;"></i>
                    {{ translate('Book a 1-on-1 Matchmaker Call') }}
                </h2>
                <span class="fs-12 text-muted">{{ translate('Fast 24h Confirmation') }}</span>
            </div>
            <div class="p-3 p-md-4">
                <form action="{{ route('consultations.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        {{-- Field 1: WhatsApp / Contact Number --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600 fs-13 text-dark mb-1">
                                {{ translate('WhatsApp / Contact Number') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white" style="border-color: var(--mat-border); color: var(--mat-gold);">
                                        <i class="lab la-whatsapp fs-18"></i>
                                    </span>
                                </div>
                                <input type="text" name="phone" class="form-control" style="border-color: var(--mat-border);"
                                       value="{{ old('phone', $user->phone) }}" placeholder="+880 1700 000000" required>
                            </div>
                            <small class="text-muted fs-11">{{ translate('Used to send meeting link & reminders.') }}</small>
                        </div>

                        {{-- Field 2A: Preferred Date --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-600 fs-13 text-dark mb-1">
                                {{ translate('Preferred Date') }} <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="preferred_date" class="form-control" style="border-color: var(--mat-border);"
                                   min="{{ date('Y-m-d') }}" value="{{ old('preferred_date', date('Y-m-d', strtotime('+1 day'))) }}" required>
                            <small class="text-muted fs-11">{{ translate('Select any convenient date.') }}</small>
                        </div>

                        {{-- Field 2B: Preferred Time Slot --}}
                        <div class="col-md-4 mb-3">
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

                        {{-- Field 3: Short Note (1-line / brief note) --}}
                        <div class="col-12 mb-3">
                            <label class="form-label fw-600 fs-13 text-dark mb-1">
                                {{ translate('Short Note') }} <span class="text-muted fs-11">({{ translate('Optional') }})</span>
                            </label>
                            <textarea name="notes" class="form-control" rows="2" style="border-color: var(--mat-border);"
                                      placeholder="{{ translate('Describe what you are looking for, specific partner preferences, or questions for your matchmaker...') }}">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-2">
                        <button type="submit" class="btn btn-consult-submit">
                            <i class="las la-paper-plane mr-2"></i>{{ translate('Request Consultation →') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
                                    <div class="d-flex align-items-center">
                                        <span class="fw-700 text-dark fs-14 mr-2" style="font-family: var(--mat-font-serif);">
                                            {{ $item->scheduled_at ? \Carbon\Carbon::parse($item->scheduled_at)->format('d F, Y - h:i A') : \Carbon\Carbon::parse($item->preferred_date)->format('d F, Y') }}
                                        </span>
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

    </div>
@endsection
