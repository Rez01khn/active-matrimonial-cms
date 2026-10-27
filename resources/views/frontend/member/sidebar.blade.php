@php
    $auth_user = Auth::user();
    
    // Unseen interest requests: incoming pending requests created after the user last viewed the interest page
    $last_interest_viewed = session('interest_requests_last_viewed_at');
    $sidebar_interests_badge = \App\Models\ExpressInterest::where('user_id', $auth_user->id)
        ->where('status', 0)
        ->when($last_interest_viewed, function ($q, $last_viewed) {
            $q->where('created_at', '>', $last_viewed);
        })
        ->count();
    
    // Real unread chat messages count
    $sidebar_messages_badge = \App\Models\Chat::whereIn('chat_thread_id', function ($q) use ($auth_user) {
        $q->select('id')->from('chat_threads')->where('sender_user_id', $auth_user->id)->orWhere('receiver_user_id', $auth_user->id);
    })->where('sender_user_id', '!=', $auth_user->id)->where('seen', 0)->count();
@endphp

<aside class="member-dark-sidebar transition-transform duration-300 ease-in-out" id="memberDarkSidebar">
    <!-- Top: Brand Logo + Collapse Toggle Chevron -->
    <div class="member-dark-sidebar-header">
        <a href="{{ route('dashboard') }}" class="member-dark-brand-logo d-flex align-items-center text-reset">
            <div class="member-dark-logo-badge">
                <i class="las la-heart"></i>
            </div>
            <div class="member-dark-logo-text-wrap ml-2">
                <div class="member-dark-logo-title">{{ get_setting('website_name', 'Matrimony') }}</div>
                <div class="member-dark-logo-sub">{{ translate('Together for a Better Tomorrow') }}</div>
            </div>
        </a>
        <button type="button" class="member-dark-toggle-btn" id="memberSidebarCollapseBtn" title="{{ translate('Toggle Sidebar') }}" aria-label="{{ translate('Toggle Sidebar') }}">
            <i class="las la-angle-left"></i>
        </button>
    </div>

    <!-- Navigation Menu Links -->
    <ul class="member-dark-nav">
        <!-- Dashboard -->
        <li class="member-dark-nav-item">
            <a href="{{ route('dashboard') }}" class="member-dark-nav-link {{ areActiveRoutes(['dashboard']) }}">
                <i class="las la-home"></i>
                <span>{{ translate('Dashboard') }}</span>
            </a>
        </li>

        <!-- My Profile -->
        <li class="member-dark-nav-item">
            <a href="{{ route('profile_settings') }}" class="member-dark-nav-link {{ areActiveRoutes(['profile_settings']) }}">
                <i class="las la-user-circle"></i>
                <span>{{ translate('My Profile') }}</span>
            </a>
        </li>

        <!-- Photo Gallery -->
        <li class="member-dark-nav-item">
            <a href="{{ route('gallery-image_index') }}" class="member-dark-nav-link {{ areActiveRoutes(['gallery-image_index', 'gallery-image.create']) }}">
                <i class="las la-image"></i>
                <span>{{ translate('Photo Gallery') }}</span>
            </a>
        </li>

        <!-- Happy Story -->
        <li class="member-dark-nav-item">
            <a href="{{ route('happy_story.member') }}" class="member-dark-nav-link {{ areActiveRoutes(['happy_story.member']) }}">
                <i class="las la-handshake"></i>
                <span>{{ translate('Happy Story') }}</span>
            </a>
        </li>

        <!-- Interest -->
        <li class="member-dark-nav-item">
            <a href="{{ route('interest_requests') }}" class="member-dark-nav-link {{ areActiveRoutes(['interest_requests', 'my_interests.index']) }}">
                <i class="las la-heart"></i>
                <span>{{ translate('Interest') }}</span>
                @if($sidebar_interests_badge > 0)
                    <span class="badge-counter">{{ $sidebar_interests_badge }}</span>
                @endif
            </a>
        </li>

        <!-- Messages -->
        <li class="member-dark-nav-item">
            <a href="{{ route('all.messages') }}" class="member-dark-nav-link {{ areActiveRoutes(['all.messages']) }}">
                <i class="las la-comment-alt"></i>
                <span>{{ translate('Messages') }}</span>
                @if($sidebar_messages_badge > 0)
                    <span class="badge-counter">{{ $sidebar_messages_badge }}</span>
                @endif
            </a>
        </li>

        <!-- Matchmaker Meeting -->
        <li class="member-dark-nav-item">
            <a href="{{ route('consultations.index') }}" class="member-dark-nav-link {{ areActiveRoutes(['consultations.index']) }}">
                <i class="las la-calendar-check"></i>
                <span class="text-nowrap">{{ translate('Matchmaker Meeting') }}</span>
            </a>
        </li>

        <!-- Matched Profiles -->
        <li class="member-dark-nav-item">
            <a href="{{ route('my_matched_profiles') }}" class="member-dark-nav-link {{ areActiveRoutes(['my_matched_profiles']) }}">
                <i class="las la-user-friends"></i>
                <span>{{ translate('Matched Profiles') }}</span>
            </a>
        </li>

        <!-- Shortlisted Profiles -->
        <li class="member-dark-nav-item">
            <a href="{{ route('my_shortlists') }}" class="member-dark-nav-link {{ areActiveRoutes(['my_shortlists']) }}">
                <i class="las la-star"></i>
                <span>{{ translate('Shortlisted Profiles') }}</span>
            </a>
        </li>

        <!-- Profile Viewers -->
        <li class="member-dark-nav-item">
            <a href="{{ route('profile-viewers.index') }}" class="member-dark-nav-link {{ areActiveRoutes(['profile-viewers.index']) }}">
                <i class="las la-eye"></i>
                <span>{{ translate('Profile Viewers') }}</span>
            </a>
        </li>

        <!-- Membership Packages -->
        <li class="member-dark-nav-item">
            <a href="{{ route('packages') }}" class="member-dark-nav-link {{ areActiveRoutes(['packages', 'package_purchase_history']) }}">
                <i class="las la-crown"></i>
                <span>{{ translate('Membership Packages') }}</span>
            </a>
        </li>

        <!-- Account Settings -->
        <li class="member-dark-nav-item">
            <a href="{{ route('member.change_password') }}" class="member-dark-nav-link {{ areActiveRoutes(['member.change_password']) }}">
                <i class="las la-cog"></i>
                <span>{{ translate('Account Settings') }}</span>
            </a>
        </li>
    </ul>

    <!-- Bottom: Logout Button & Floral Watermark -->
    <div class="member-dark-sidebar-bottom">
        <a href="javascript:void(0);" class="member-dark-logout-btn" onclick="event.preventDefault(); document.getElementById('member-logout-form').submit();">
            <i class="las la-sign-out-alt"></i>
            <span>{{ translate('Logout') }}</span>
        </a>

        <form id="member-logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Faint Floral Watermark Silhouette -->
        <svg class="member-sidebar-watermark" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 90 C 20 60, 50 50, 80 40 C 70 65, 45 75, 10 90 Z" fill="white" opacity="0.3"/>
            <path d="M25 80 C 15 50, 40 30, 70 20 C 55 45, 45 65, 25 80 Z" fill="white" opacity="0.2"/>
            <path d="M40 70 C 45 40, 70 25, 95 15 C 80 40, 65 60, 40 70 Z" fill="white" opacity="0.15"/>
            <circle cx="75" cy="25" r="4" fill="white" opacity="0.25"/>
            <circle cx="85" cy="40" r="3" fill="white" opacity="0.2"/>
        </svg>
    </div>
</aside>
