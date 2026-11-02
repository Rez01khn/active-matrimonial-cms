@extends('admin.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Newsletter & Subscribers') }}</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Send Newsletter')}}</h5>
            </div>

            <div class="card-body">
                <form class="form-horizontal" action="{{ route('newsletters.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="user_emails">{{translate('Emails')}} ({{translate('Users')}})</label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true" title="{{ translate('Select Users') }}">
                                @foreach($users as $user)
                                    <option value="{{$user->email}}">{{$user->email}} ({{$user->first_name}} {{$user->last_name}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="subscriber_emails">{{translate('Emails')}} ({{translate('Subscribers')}})</label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="subscriber_emails[]" multiple data-selected-text-format="count" data-actions-box="true" title="{{ translate('Select Subscribers') }}">
                                @foreach($subscribers as $subscriber)
                                    <option value="{{$subscriber->email}}">{{$subscriber->email}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ translate('Total subscribers from footer: ') }}{{ $subscribers->count() }}</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="subject">{{translate('Newsletter subject')}}</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="{{ translate('Enter newsletter subject...') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="content">{{translate('Newsletter content')}}</label>
                        <div class="col-sm-9">
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

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 h6">{{ translate('Subscribed Emails (Footer Newsletter)') }}</h5>
                <span class="badge badge-inline badge-primary">{{ $subscribers->count() }} {{ translate('Total') }}</span>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>{{ translate('Email Address') }}</th>
                            <th class="text-right">{{ translate('Subscribed Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $key => $subscriber)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td class="text-right">{{ $subscriber->created_at ? $subscriber->created_at->format('d M, Y') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    {{ translate('No newsletter subscribers yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
