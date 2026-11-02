@extends('admin.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Happy Stories') }}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add_happy_story_modal" class="btn btn-circle btn-primary">
                <span>{{ translate('Add New Happy Story') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('All Happy Stories') }}</h5>
        </div>
        <div class="col-md-4">
            <form class="" id="sort_happy_story" action="" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Member Name')}}</th>
                    <th data-breakpoints="md">{{translate('Partner Name')}}</th>
                    <th data-breakpoints="md">{{translate('Story Title')}}</th>
                    <th data-breakpoints="md">{{translate('Post Time')}}</th>
                    <th>{{translate('Approval')}}</th>
                    <th class="text-right" width="20%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($happy_stories as $key => $happy_story)
                    <tr>
                        <td>{{ ($key+1) + ($happy_stories->currentPage() - 1)*$happy_stories->perPage() }}</td>
                        <td>{{ optional($happy_story->user)->first_name.' '.optional($happy_story->user)->last_name }}</td>
                        <td>{{ $happy_story->partner_name }}</td>
                        <td>{{ $happy_story->title }}</td>
                        <td>{{ $happy_story->created_at }}</td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_status(this)" value="{{ $happy_story->id }}" type="checkbox"
                                    @if($happy_story->approved == 1) checked @endif
                                    @if(auth()->user()->cannot('approve_happy_story') && auth()->user()->user_type != 'admin') disabled @endif >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">
                            @if(auth()->user()->can('edit_happy_story') || auth()->user()->user_type == 'admin')
                                <a href="{{ route('happy-story.edit', encrypt($happy_story->id)) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endif
                            @if(auth()->user()->can('view_details_happy_story') || auth()->user()->user_type == 'admin')
                                <a href="{{ route('happy-story.show', encrypt($happy_story->id)) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $happy_stories->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
<div class="modal fade" id="add_happy_story_modal" tabindex="-1" role="dialog" aria-labelledby="addHappyStoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6" id="addHappyStoryModalLabel">{{ translate('Add New Happy Story') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('happy-story.store') }}" method="POST">
                @csrf
                <input type="hidden" name="from_admin" value="1">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">{{ translate('Select Member') }}</label>
                        <select class="form-control aiz-selectpicker" name="user_id" data-live-search="true">
                            <option value="{{ auth()->id() }}">{{ translate('Admin / Featured Couple (No Specific Member)') }}</option>
                            @if(isset($members))
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }} ({{ $member->code }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Story Title') }} <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="{{ translate('e.g. Rahim & Karima') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Partner Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="partner_name" class="form-control" placeholder="{{ translate('Partner Name') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Story Details') }} <span class="text-danger">*</span></label>
                        <textarea name="details" class="aiz-text-editor form-control" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' placeholder="{{ translate('Write the happy story details...') }}" data-min-height="180" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Photos') }} <span class="text-danger">*</span></label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="photos" class="selected-files" required>
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">{{ translate('Video Provider') }}</label>
                                <select class="form-control aiz-selectpicker" name="video_provider">
                                    <option value="youtube">{{ translate('Youtube') }}</option>
                                    <option value="dailymotion">{{ translate('Dailymotion') }}</option>
                                    <option value="vimeo">{{ translate('Vimeo') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="form-label">{{ translate('Video Link (Optional)') }}</label>
                                <input type="text" name="video_link" class="form-control" placeholder="{{ translate('Video Link') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save Happy Story') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
  function sort_family_status(el){
      $('#sort_happy_story').submit();
  }
  function update_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('happy_story_approval.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Happy story approval status updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
