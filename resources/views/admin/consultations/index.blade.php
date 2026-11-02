@extends('admin.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Matchmaker Consultations & Meetings') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <span class="badge badge-inline badge-info p-2">{{ translate('1-on-1 Advisory Management') }}</span>
        </div>
    </div>
</div>

{{-- Modern Status Filter Tabs --}}
@php
    $counts = $status_counts ?? [
        'all' => $consultations->total(),
        'pending' => 0,
        'scheduled' => 0,
        'completed' => 0,
        'cancelled' => 0,
    ];
@endphp
<div class="mb-3 d-flex flex-wrap align-items-center" style="gap: 8px;">
    <a href="{{ route('admin.consultations.index', ['search' => $search]) }}"
       class="btn btn-sm {{ empty($status) ? 'btn-primary' : 'btn-light border' }} rounded-pill px-3">
        {{ translate('All') }}
        <span class="badge badge-inline {{ empty($status) ? 'badge-light text-dark' : 'badge-secondary' }} ml-1">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.consultations.index', ['status' => 'pending', 'search' => $search]) }}"
       class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-white' : 'btn-light border' }} rounded-pill px-3">
        <i class="las la-hourglass-half mr-1"></i>{{ translate('Pending') }}
        <span class="badge badge-inline {{ $status === 'pending' ? 'badge-light text-dark' : 'badge-warning' }} ml-1">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.consultations.index', ['status' => 'scheduled', 'search' => $search]) }}"
       class="btn btn-sm {{ $status === 'scheduled' ? 'btn-success' : 'btn-light border' }} rounded-pill px-3">
        <i class="las la-calendar-check mr-1"></i>{{ translate('Upcoming (Scheduled)') }}
        <span class="badge badge-inline {{ $status === 'scheduled' ? 'badge-light text-dark' : 'badge-success' }} ml-1">{{ $counts['scheduled'] }}</span>
    </a>
    <a href="{{ route('admin.consultations.index', ['status' => 'completed', 'search' => $search]) }}"
       class="btn btn-sm {{ $status === 'completed' ? 'btn-info' : 'btn-light border' }} rounded-pill px-3">
        <i class="las la-check-double mr-1"></i>{{ translate('Completed') }}
        <span class="badge badge-inline {{ $status === 'completed' ? 'badge-light text-dark' : 'badge-primary' }} ml-1">{{ $counts['completed'] }}</span>
    </a>
    <a href="{{ route('admin.consultations.index', ['status' => 'cancelled', 'search' => $search]) }}"
       class="btn btn-sm {{ $status === 'cancelled' ? 'btn-danger' : 'btn-light border' }} rounded-pill px-3">
        <i class="las la-ban mr-1"></i>{{ translate('Cancelled') }}
        <span class="badge badge-inline {{ $status === 'cancelled' ? 'badge-light text-dark' : 'badge-danger' }} ml-1">{{ $counts['cancelled'] }}</span>
    </a>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('All Consultation Requests') }}</h5>
        </div>
        <div class="col-md-4">
            <form action="" method="GET">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="{{ translate('Search member / phone & Enter') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Member') }}</th>
                    <th>{{ translate('WhatsApp / Phone') }}</th>
                    <th>{{ translate('Requested Date & Slot') }}</th>
                    <th data-breakpoints="md">{{ translate('Confirmed Schedule') }}</th>
                    <th data-breakpoints="md">{{ translate('Meeting Link') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right" width="22%">{{ translate('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations as $key => $item)
                    <tr>
                        <td>{{ ($key+1) + ($consultations->currentPage() - 1)*$consultations->perPage() }}</td>
                        <td>
                            @if($item->user)
                                <div class="d-flex align-items-center">
                                    <img src="{{ uploaded_asset($item->user->photo) }}"
                                         class="size-35px rounded-circle img-fit mr-2 border"
                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    <div>
                                        <div class="fw-600 text-dark">
                                            {{ $item->user->first_name.' '.$item->user->last_name }}
                                        </div>
                                        <small class="text-muted">#{{ $item->user->code ?? $item->user->id }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">{{ translate('Guest / Deleted') }}</span>
                            @endif

                            @if($item->targetUser)
                                <div class="mt-2 pt-2 border-top">
                                    <small class="text-uppercase text-muted font-weight-bold fs-10" style="letter-spacing: 0.5px;">
                                        <i class="las la-handshake text-warning mr-1"></i>{{ translate('Candidate') }}:
                                    </small>
                                    <div class="d-flex align-items-center mt-1">
                                        <img src="{{ uploaded_asset($item->targetUser->photo) }}"
                                             class="size-25px rounded-circle img-fit mr-1 border"
                                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                        <div class="fs-12 text-dark font-weight-bold">
                                            {{ $item->targetUser->first_name.' '.$item->targetUser->last_name }}
                                            <small class="text-muted">#{{ $item->targetUser->code ?? $item->targetUser->id }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="tel:{{ $item->phone }}" class="text-dark fw-600 mr-2" title="{{ translate('Call') }}">
                                    {{ $item->phone }}
                                </a>
                                @php
                                    $wa_clean = preg_replace('/[^0-9]/', '', $item->phone);
                                @endphp
                                <a href="https://wa.me/{{ $wa_clean }}" target="_blank" class="text-success" title="{{ translate('Open WhatsApp') }}">
                                    <i class="lab la-whatsapp fs-18"></i>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div>
                                <i class="las la-calendar text-muted mr-1"></i>
                                <strong>{{ \Carbon\Carbon::parse($item->preferred_date)->format('d M, Y') }}</strong>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="las la-clock mr-1"></i>{{ $item->preferred_slot }}
                            </small>
                        </td>
                        <td>
                            @if($item->scheduled_at)
                                <span class="badge badge-inline badge-soft-success">
                                    <i class="las la-calendar-check mr-1"></i>
                                    {{ \Carbon\Carbon::parse($item->scheduled_at)->format('d M Y, h:i A') }}
                                </span>
                            @else
                                <span class="text-muted font-italic fs-12">{{ translate('Not confirmed') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->meeting_link)
                                @if($item->status == 'scheduled')
                                    <a href="{{ $item->meeting_link }}" target="_blank" class="btn btn-sm btn-soft-primary px-2 py-1">
                                        <i class="las la-external-link-alt mr-1"></i>{{ translate('Join Meeting') }}
                                    </a>
                                @else
                                    <span class="text-muted fs-12" title="{{ $item->meeting_link }}">
                                        <i class="las la-video mr-1"></i>{{ translate('Session Ended') }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fs-12">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'scheduled')
                                <span class="badge badge-inline badge-success">{{ translate('Scheduled') }}</span>
                            @elseif($item->status == 'completed')
                                <span class="badge badge-inline badge-primary">
                                    <i class="las la-lock mr-1"></i>{{ translate('Completed') }}
                                </span>
                            @elseif($item->status == 'cancelled')
                                <span class="badge badge-inline badge-danger">
                                    <i class="las la-lock mr-1"></i>{{ translate('Cancelled') }}
                                </span>
                            @else
                                <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            @if($item->status == 'pending')
                                {{-- Pending: Approve & Schedule --}}
                                <button type="button"
                                        class="btn btn-primary btn-sm px-2 py-1 mr-1"
                                        onclick="open_manage_modal({{ json_encode($item) }}, 'schedule')"
                                        title="{{ translate('Approve & Schedule Meeting') }}">
                                    <i class="las la-calendar-plus mr-1"></i>{{ translate('Approve & Schedule') }}
                                </button>
                            @elseif($item->status == 'scheduled')
                                {{-- Scheduled: Mark Completed + Optional Reschedule --}}
                                <button type="button"
                                        class="btn btn-soft-success btn-sm px-2 py-1 mr-1"
                                        onclick="open_manage_modal({{ json_encode($item) }}, 'complete')"
                                        title="{{ translate('Mark Meeting as Completed & Add Outcome') }}">
                                    <i class="las la-check-circle mr-1"></i>{{ translate('Complete') }}
                                </button>
                                <button type="button"
                                        class="btn btn-soft-info btn-icon btn-circle btn-sm mr-1"
                                        onclick="open_manage_modal({{ json_encode($item) }}, 'reschedule')"
                                        title="{{ translate('Reschedule Time / Link') }}">
                                    <i class="las la-clock"></i>
                                </button>
                            @else
                                {{-- Completed or Cancelled: Locked Read-Only Summary --}}
                                <button type="button"
                                        class="btn btn-soft-secondary btn-sm px-2 py-1 mr-1"
                                        onclick="open_summary_modal({{ json_encode($item) }})"
                                        title="{{ translate('View Locked Consultation Summary') }}">
                                    <i class="las la-eye mr-1"></i>{{ translate('View Summary') }}
                                </button>
                            @endif

                            <a href="javascript:void(0);"
                               data-href="{{ route('admin.consultations.destroy', $item->id) }}"
                               class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                               title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            {{ translate('No consultation requests found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $consultations->appends(request()->input())->links() }}
        </div>
    </div>
</div>

{{-- Modal 1: Action Modal (Approve & Schedule / Complete / Reschedule) --}}
<div class="modal fade" id="manage-consultation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modal-title-text">
                    {{ translate('Manage Matchmaker Consultation') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="manage-consultation-form" action="" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Target Candidate Box --}}
                    <div class="alert alert-info border mb-3" id="modal-candidate-box" style="display: none;">
                        <small class="text-uppercase font-weight-bold d-block"><i class="las la-user-friends mr-1"></i>{{ translate('Arranged Candidate Meeting With') }}:</small>
                        <strong class="fs-14" id="modal-candidate-text"></strong>
                    </div>

                    {{-- User Note Alert --}}
                    <div class="alert alert-light border mb-3" id="modal-user-note-box">
                        <small class="text-muted d-block text-uppercase font-weight-bold">{{ translate('User Requested Note & Preferred Slot') }}:</small>
                        <div class="fw-600 text-primary mb-1" id="modal-user-preferred-slot"></div>
                        <p class="mb-0 fs-13 text-dark font-italic" id="modal-user-note-text"></p>
                    </div>

                    {{-- Read-only Confirmed Schedule Banner when completing --}}
                    <div class="alert alert-success border mb-3" id="modal-complete-banner" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-uppercase font-weight-bold d-block">{{ translate('Completing Scheduled Session') }}</small>
                                <span id="modal-complete-schedule-info" class="fw-600"></span>
                            </div>
                            <span class="badge badge-inline badge-success">{{ translate('Finalizing & Locking') }}</span>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Status Selection --}}
                        <div class="col-md-6 mb-3" id="modal-status-group">
                            <label class="form-label font-weight-bold">{{ translate('Action / Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="modal-status" class="form-control" required onchange="toggle_modal_fields_by_status(this.value)">
                                <option value="scheduled">{{ translate('Approve & Schedule (Confirmed Time & Link)') }}</option>
                                <option value="completed">{{ translate('Mark as Completed (Finalize & Lock)') }}</option>
                                <option value="cancelled">{{ translate('Cancel Request (Finalize & Lock)') }}</option>
                            </select>
                        </div>

                        {{-- Confirmed Date & Time --}}
                        <div class="col-md-6 mb-3" id="modal-schedule-datetime-group">
                            <label class="form-label font-weight-bold">{{ translate('Confirmed Meeting Date & Time') }} <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="scheduled_at" id="modal-scheduled-at" class="form-control">
                            <small class="text-muted fs-11">{{ translate('Will be shown prominently in the member portal.') }}</small>
                        </div>

                        {{-- Google Meet / Zoom Link + 1-Click Auto Generator --}}
                        <div class="col-12 mb-3" id="modal-meeting-link-group">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label font-weight-bold mb-0">{{ translate('Video Meeting Link (Google Meet / Zoom / Jitsi)') }}</label>
                                <button type="button" class="btn btn-xs btn-soft-primary" onclick="generate_instant_meeting_link()">
                                    <i class="las la-magic mr-1"></i>{{ translate('Auto-Generate Instant Video Room') }}
                                </button>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="las la-video"></i></span>
                                </div>
                                <input type="url" name="meeting_link" id="modal-meeting-link" class="form-control"
                                       placeholder="https://meet.google.com/abc-defg-hij or click Auto-Generate">
                            </div>
                        </div>

                        {{-- Matchmaker Feedback & Discussion History --}}
                        <div class="col-12 mb-2">
                            <label class="form-label font-weight-bold" id="modal-notes-label">
                                {{ translate('Post-Meeting Outcome & Matchmaker Discussion Notes') }}
                            </label>
                            <textarea name="discussion_history" id="modal-discussion-history" rows="4" class="form-control"
                                      placeholder="{{ translate('Enter key profile insights, partner suggestions given to member, next match candidates discussed...') }}"></textarea>
                            <small class="text-muted fs-11">{{ translate('Once marked as Completed, this consultation will be locked and saved in the member\'s Discussion History.') }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="modal-submit-btn">
                        {{ translate('Save & Update Consultation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: Read-Only Summary Modal (For Completed / Cancelled Locked Consultations) --}}
<div class="modal fade" id="summary-consultation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold" id="summary-modal-title">
                    <i class="las la-file-alt text-primary mr-1"></i>{{ translate('Consultation Summary (Locked)') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <div>
                        <small class="text-muted text-uppercase d-block">{{ translate('Member') }}</small>
                        <h6 class="mb-0 font-weight-bold" id="summary-member-name"></h6>
                    </div>
                    <div id="summary-status-badge"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">{{ translate('Requested Slot') }}</small>
                            <div class="fw-600 text-dark" id="summary-requested-slot"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2 mt-md-0">
                        <div class="p-3 bg-light rounded border">
                            <small class="text-muted text-uppercase font-weight-bold d-block mb-1">{{ translate('Confirmed Schedule') }}</small>
                            <div class="fw-600 text-success" id="summary-confirmed-schedule"></div>
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="summary-user-notes-wrap" style="display: none;">
                    <small class="text-muted text-uppercase font-weight-bold d-block mb-1">{{ translate('Member Initial Note') }}</small>
                    <div class="p-2 border rounded bg-white font-italic text-dark" id="summary-user-notes"></div>
                </div>

                <div class="mb-0">
                    <small class="text-muted text-uppercase font-weight-bold d-block mb-1">{{ translate('Matchmaker Discussion Summary & Outcome') }}</small>
                    <div class="p-3 border rounded bg-soft-secondary text-dark" style="white-space: pre-line; min-height: 80px;" id="summary-discussion-history"></div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="text-muted fs-12 mr-auto">
                    <i class="las la-lock mr-1"></i>{{ translate('This record is finalized and read-only.') }}
                </span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Close') }}</button>
            </div>
        </div>
    </div>
</div>

@include('modals.delete_modal')

@endsection

@section('script')
<script type="text/javascript">
    var currentConsultationId = null;

    function open_manage_modal(item, mode) {
        currentConsultationId = item.id;
        var actionUrl = '{{ url("admin/consultations/update") }}/' + item.id;
        $('#manage-consultation-form').attr('action', actionUrl);

        var memberName = item.user ? (item.user.first_name + ' ' + item.user.last_name) : 'Guest';
        $('#modal-meeting-link').val(item.meeting_link || '');
        $('#modal-discussion-history').val(item.discussion_history || '');

        if (item.scheduled_at) {
            var dt = new Date(item.scheduled_at);
            var year = dt.getFullYear();
            var month = ('0' + (dt.getMonth() + 1)).slice(-2);
            var day = ('0' + dt.getDate()).slice(-2);
            var hours = ('0' + dt.getHours()).slice(-2);
            var minutes = ('0' + dt.getMinutes()).slice(-2);
            $('#modal-scheduled-at').val(year + '-' + month + '-' + day + 'T' + hours + ':' + minutes);
        } else if (item.preferred_date) {
            var prefDate = item.preferred_date.substring(0, 10);
            $('#modal-scheduled-at').val(prefDate + 'T11:00');
        } else {
            $('#modal-scheduled-at').val('');
        }

        if (item.target_user) {
            $('#modal-candidate-text').text(item.target_user.first_name + ' ' + item.target_user.last_name + ' (#' + (item.target_user.code || item.target_user.id) + ') ' + (item.target_user.phone ? '- ' + item.target_user.phone : ''));
            $('#modal-candidate-box').show();
        } else {
            $('#modal-candidate-box').hide();
        }

        var slotInfo = (item.preferred_date ? item.preferred_date.substring(0, 10) : '') + (item.preferred_slot ? ' (' + item.preferred_slot + ')' : '');
        $('#modal-user-preferred-slot').text(slotInfo);
        $('#modal-user-note-text').text(item.notes ? item.notes : '{{ translate("No additional note provided by member.") }}');

        if (mode === 'complete') {
            $('#modal-title-text').text('{{ translate("Complete Consultation") }} #' + item.id + ' - ' + memberName);
            $('#modal-status').val('completed');
            $('#modal-complete-schedule-info').text(item.scheduled_at ? item.scheduled_at : slotInfo);
            $('#modal-complete-banner').show();
            $('#modal-submit-btn').text('{{ translate("Mark as Completed & Lock") }}').removeClass('btn-primary').addClass('btn-success');
            toggle_modal_fields_by_status('completed');
        } else if (mode === 'reschedule') {
            $('#modal-title-text').text('{{ translate("Reschedule Consultation") }} #' + item.id + ' - ' + memberName);
            $('#modal-status').val('scheduled');
            $('#modal-complete-banner').hide();
            $('#modal-submit-btn').text('{{ translate("Update Schedule") }}').removeClass('btn-success').addClass('btn-primary');
            toggle_modal_fields_by_status('scheduled');
        } else {
            $('#modal-title-text').text('{{ translate("Approve & Schedule Consultation") }} #' + item.id + ' - ' + memberName);
            $('#modal-status').val('scheduled');
            $('#modal-complete-banner').hide();
            $('#modal-submit-btn').text('{{ translate("Approve & Confirm Schedule") }}').removeClass('btn-success').addClass('btn-primary');
            toggle_modal_fields_by_status('scheduled');
        }

        $('#manage-consultation-modal').modal('show');
    }

    function toggle_modal_fields_by_status(status) {
        if (status === 'completed') {
            $('#modal-schedule-datetime-group').hide();
            $('#modal-meeting-link-group').hide();
            $('#modal-notes-label').html('{{ translate("Post-Meeting Outcome & Matchmaker Discussion Notes") }} <span class="text-danger">*</span>');
        } else if (status === 'cancelled') {
            $('#modal-schedule-datetime-group').hide();
            $('#modal-meeting-link-group').hide();
            $('#modal-notes-label').text('{{ translate("Cancellation Reason / Notes") }}');
        } else {
            $('#modal-schedule-datetime-group').show();
            $('#modal-meeting-link-group').show();
            $('#modal-notes-label').text('{{ translate("Pre-Meeting Agenda / Notes (Optional)") }}');
        }
    }

    function generate_instant_meeting_link() {
        var randomCode = Math.random().toString(36).substring(2, 8) + '-' + Math.random().toString(36).substring(2, 6);
        var link = 'https://meet.jit.si/MatrimonyConsultation-' + (currentConsultationId || 'Session') + '-' + randomCode;
        $('#modal-meeting-link').val(link);
    }

    function open_summary_modal(item) {
        var memberName = item.user ? (item.user.first_name + ' ' + item.user.last_name + ' (#' + (item.user.code || item.user.id) + ')') : 'Guest';
        $('#summary-member-name').text(memberName);

        if (item.status === 'completed') {
            $('#summary-status-badge').html('<span class="badge badge-inline badge-primary px-3 py-2"><i class="las la-check-double mr-1"></i>{{ translate("Completed & Locked") }}</span>');
        } else {
            $('#summary-status-badge').html('<span class="badge badge-inline badge-danger px-3 py-2"><i class="las la-ban mr-1"></i>{{ translate("Cancelled & Locked") }}</span>');
        }

        var reqSlot = (item.preferred_date ? item.preferred_date.substring(0, 10) : '-') + (item.preferred_slot ? ' • ' + item.preferred_slot : '');
        $('#summary-requested-slot').text(reqSlot);
        $('#summary-confirmed-schedule').text(item.scheduled_at ? item.scheduled_at : '{{ translate("Not scheduled") }}');

        if (item.notes) {
            $('#summary-user-notes').text(item.notes);
            $('#summary-user-notes-wrap').show();
        } else {
            $('#summary-user-notes-wrap').hide();
        }

        $('#summary-discussion-history').text(item.discussion_history ? item.discussion_history : '{{ translate("No outcome notes recorded for this session.") }}');
        $('#summary-consultation-modal').modal('show');
    }
</script>
@endsection
