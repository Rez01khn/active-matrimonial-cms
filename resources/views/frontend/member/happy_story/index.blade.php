@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $user = Auth::user();
    $happy_story = \App\Models\HappyStory::where('user_id', $user->id)->first();
    $is_editing = request()->query('edit') == '1';
@endphp

@if ($user->membership == 2)
    @if (empty($happy_story) || $is_editing)
        @include('frontend.member.happy_story.create', ['happy_story' => $happy_story])
    @else
        @include('frontend.member.happy_story.view', ['happy_story' => $happy_story])
    @endif
@else
    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden" style="background: #fff; border: 1px solid #f0e6df !important;">
        <div class="card-body p-5 text-center">
            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 68px; height: 68px; background: #fdf6f0; color: #8C6239; font-size: 30px;">
                <i class="las la-crown"></i>
            </div>
            <h4 class="font-serif fw-700 mb-2" style="color: #3b1721;">{{ translate('Share Your Love Story') }}</h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 460px;">
                {{ translate('Please upgrade to a Premium Membership to share your inspiring love story with our community.') }}
            </p>
            <a href="{{ route('packages') }}" class="btn px-4 py-2 rounded-pill fw-600 text-white" style="background: #3b1721;">
                <i class="las la-crown mr-1"></i> {{ translate('Upgrade to Premium') }}
            </a>
        </div>
    </div>
@endif
@endsection
