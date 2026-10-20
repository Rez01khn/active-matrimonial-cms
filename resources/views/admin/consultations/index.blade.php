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

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('All Consultation Requests') }}</h5>
        </div>
        <div class="col-md-3">
            <form id="filter_status_form" action="" method="GET">
                <select class="form-control form-control-sm aiz-selectpicker" name="status" onchange="$('#filter_status_form').submit()">
                    <option value="">{{ translate('Filter by Status (All)') }}</option>
                    <option value="pending" @if($status == 'pending') selected @endif>{{ translate('Pending') }}</option>
                    <option value="scheduled" @if($status == 'scheduled') selected @endif>{{ translate('Scheduled') }}</option>
                    <option value="completed" @if($status == 'completed') selected @endif>{{ translate('Completed') }}</option>
                    <option value="cancelled" @if($status == 'cancelled') selected @endif>{{ translate('Cancelled') }}</option>
                </select>
            </form>
        </div>
        <div class="col-md-3">
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
                    <th class="text-right" width="15%">{{ translate('Options') }}</th>
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
                                <a href="{{ $item->meeting_link }}" target="_blank" class="btn btn-sm btn-soft-primary px-2 py-1">
                                    <i class="las la-external-link-alt mr-1"></i>{{ translate('Join Meeting') }}
                                </a>
                            @else
                                <span class="text-muted fs-12">-</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'scheduled')
                                <span class="badge badge-inline badge-success">{{ translate('Scheduled') }}</span>
                            @elseif($item->status == 'completed')
                                <span class="badge badge-inline badge-primary">{{ translate('Completed') }}</span>
                            @elseif($item->status == 'cancelled')
                                <span class="badge badge-inline badge-danger">{{ translate('Cancelled') }}</span>
                            @else
                                <span class="badge badge-inline badge-warning">{{ translate('Pending') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <button type="button"
                                    class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                    onclick="open_manage_modal({{ json_encode($item) }})"
                                    title="{{ translate('Manage & Schedule') }}">
                                <i class="las la-calendar-check"></i>
                            </button>
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

{{-- Modal: Manage / Schedule Consultation --}}
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
                        <small class="text-muted d-block text-uppercase font-weight-bold">{{ translate('User Requested Note') }}:</small>
                        <p class="mb-0 fs-13 text-dark font-italic" id="modal-user-note-text"></p>
                    </div>

                    <div class="row">
                        {{-- Status Selection --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">{{ translate('Meeting Status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="modal-status" class="form-control" required>
                                <option value="pending">{{ translate('Pending') }}</option>
                                <option value="scheduled">{{ translate('Scheduled (Confirmed Time & Link)') }}</option>
                                <option value="completed">{{ translate('Completed (Session Finished)') }}</option>
                                <option value="cancelled">{{ translate('Cancelled') }}</option>
                            </select>
                        </div>

                        {{-- Confirmed Date & Time --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">{{ translate('Confirmed Meeting Date & Time') }}</label>
                            <input type="datetime-local" name="scheduled_at" id="modal-scheduled-at" class="form-control">
                            <small class="text-muted fs-11">{{ translate('Will be shown prominently in the member portal.') }}</small>
                        </div>

                        {{-- Google Meet / Zoom Link --}}
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">{{ translate('Video Meeting Link (Google Meet / Zoom)') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="las la-video"></i></span>
                                </div>
                                <input type="url" name="meeting_link" id="modal-meeting-link" class="form-control"
                                       placeholder="https://meet.google.com/abc-defg-hij or Zoom link">
                            </div>
                        </div>

                        {{-- Matchmaker Feedback & Discussion History --}}
                        <div class="col-12 mb-3">
                            <label class="form-label font-weight-bold">
                                {{ translate('Meeting Summary & Matchmaker Discussion Notes') }}
                            </label>
                            <textarea name="discussion_history" id="modal-discussion-history" rows="4" class="form-control"
                                      placeholder="{{ translate('Enter key profile insights, partner suggestions given to member, next match candidates discussed...') }}"></textarea>
                            <small class="text-muted fs-11">{{ translate('Visible to the member in their Discussion History section.') }}</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">{{ translate('Save & Update Consultation') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('modals.delete_modal')

@endsection

@section('script')
<script type="text/javascript">
    function open_manage_modal(item) {
        var actionUrl = '{{ url("admin/consultations/update") }}/' + item.id;
        $('#manage-consultation-form').attr('action', actionUrl);

        var memberName = item.user ? (item.user.first_name + ' ' + item.user.last_name) : 'Guest';
        $('#modal-title-text').text('{{ translate("Manage Consultation") }} #' + item.id + ' - ' + memberName);

        $('#modal-status').val(item.status);
        $('#modal-meeting-link').val(item.meeting_link || '');
        $('#modal-discussion-history').val(item.discussion_history || '');

        if (item.scheduled_at) {
            // Format datetime for datetime-local (YYYY-MM-DDTHH:mm)
            var dt = new Date(item.scheduled_at);
            var year = dt.getFullYear();
            var month = ('0' + (dt.getMonth() + 1)).slice(-2);
            var day = ('0' + dt.getDate()).slice(-2);
            var hours = ('0' + dt.getHours()).slice(-2);
            var minutes = ('0' + dt.getMinutes()).slice(-2);
            $('#modal-scheduled-at').val(year + '-' + month + '-' + day + 'T' + hours + ':' + minutes);
        } else {
            $('#modal-scheduled-at').val('');
        }

        if (item.target_user) {
            $('#modal-candidate-text').text(item.target_user.first_name + ' ' + item.target_user.last_name + ' (#' + (item.target_user.code || item.target_user.id) + ') ' + (item.target_user.phone ? '- ' + item.target_user.phone : ''));
            $('#modal-candidate-box').show();
        } else {
            $('#modal-candidate-box').hide();
        }

        if (item.notes) {
            $('#modal-user-note-text').text(item.notes);
            $('#modal-user-note-box').show();
        } else {
            $('#modal-user-note-box').hide();
        }

        $('#manage-consultation-modal').modal('show');
    }
</script>
@endsection
