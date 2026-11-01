@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Send Newsletter')}}</h5>
            </div>

            <div class="card-body">
                <form class="form-horizontal" action="{{ route('newsletters.send') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="user_emails">{{translate('Emails')}} ({{translate('Users')}})</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true" title="{{ translate('Select Users') }}">
                                @foreach($users as $user)
                                    <option value="{{$user->email}}">{{$user->email}} ({{$user->first_name}} {{$user->last_name}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="subscriber_emails">{{translate('Emails')}} ({{translate('Newsletter Subscribers')}})</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" name="subscriber_emails[]" multiple data-selected-text-format="count" data-actions-box="true" title="{{ translate('Select Subscribers') }}">
                                @foreach($subscribers as $subscriber)
                                    <option value="{{$subscriber->email}}">{{$subscriber->email}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Total subscribers: ') }}{{ $subscribers->count() }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="subject">{{translate('Newsletter subject')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="{{ translate('Enter newsletter subject...') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="content">{{translate('Newsletter content')}}</label>
                        <div class="col-sm-10">
                            <textarea rows="8" class="form-control aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]], ["insert", ["link", "picture"]],["view", ["undo","redo"]]]' name="content" required></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Send Newsletter')}}</button>
                    </div>
                  </form>
              </div>
        </div>
    </div>
</div>

@endsection
