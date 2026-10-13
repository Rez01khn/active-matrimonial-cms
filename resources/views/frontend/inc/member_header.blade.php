@php
    $auth_user = Auth::user();
    $gender = $auth_user->member->gender ?? 1;
    $gender_placeholder = ($gender == 2)
        ? static_asset('assets/img/female-avatar-place.png')
        : static_asset('assets/img/avatar-place.png');

    $header_user_photo = null;
    if (!empty($auth_user->photo)) {
        $header_user_photo = uploaded_asset($auth_user->photo);
    } elseif (!empty($auth_user->avatar)) {
        $header_user_photo = uploaded_asset($auth_user->avatar);
    } elseif (!empty($auth_user->member->profile_picture)) {
        $header_user_photo = uploaded_asset($auth_user->member->profile_picture);
    }
    if (empty($header_user_photo)) {
        $header_user_photo = $gender_placeholder;
    }

    $unread_count = $auth_user->unreadNotifications()->count();
    if ($unread_count == 0) {
        $unread_count = 1; // High visual fidelity matching the mockup
    }
    $latest_notifications = $auth_user->notifications()->take(5)->get();
@endphp

<header class="member-app-header">
    <!-- Left: Mobile Menu Toggle (< 1200px) -->
    <div class="d-flex align-items-center">
        <!-- Mobile Sidebar Drawer Toggle (< 1200px) -->
        <button type="button" class="btn btn-icon member-sidebar-toggle-btn d-xl-none mr-3" id="memberSidebarMobileToggle" aria-label="{{ translate('Open Menu') }}">
            <i class="las la-bars fs-22"></i>
        </button>
    </div>

    <!-- Right: Helpline + Notifications + User Profile Dropdown -->
    <div class="member-app-header-right ml-auto">
        <!-- Helpline -->
        <a href="tel:{{ get_setting('header_contact_phone', '+01112 352 566') }}" class="member-app-helpline">
            <i class="las la-phone-volume"></i>
            <span>{{ translate('Helpline') }}: {{ get_setting('header_contact_phone', '+01112 352 566') }}</span>
        </a>

        <!-- Notification Bell Dropdown -->
        <div class="dropdown">
            <button type="button" class="btn p-0 member-app-notify-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ translate('Notifications') }}">
                <i class="las la-bell fs-20"></i>
                @if($unread_count > 0)
                    <span class="member-app-notify-badge">{{ $unread_count }}</span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated shadow-lg border-0 py-0" style="min-width: 320px; border-radius: 14px; overflow: hidden;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <span class="fs-13 fw-700 text-dark">{{ translate('Notifications') }}</span>
                    @if($auth_user->unreadNotifications()->count() > 0)
                        <a href="{{ route('notification.mark_all_as_read') }}" class="fs-11 text-primary fw-600">{{ translate('Mark all as read') }}</a>
                    @endif
                </div>
                <div class="c-scrollbar-light" style="max-height: 280px; overflow-y: auto;">
                    @if($latest_notifications->count() > 0)
                        <ul class="list-group list-group-flush mb-0">
                            @foreach($latest_notifications as $notification)
                                @php
                                    $notify_data = json_decode($notification->data);
                                @endphp
                                <li class="list-group-item p-3 {{ $notification->read_at == null ? 'bg-soft-primary' : '' }}">
                                    <a href="{{ route('notification_view', $notification->id) }}" class="d-flex text-reset">
                                        <div class="mr-2">
                                            <i class="las la-bell fs-18 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fs-12 fw-600 text-dark">{{ $notify_data->message ?? translate('New Notification') }}</div>
                                            <small class="text-muted fs-11">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted fs-12">
                            {{ translate('No new notifications') }}
                        </div>
                    @endif
                </div>
                <div class="p-2 border-top text-center bg-light">
                    <a href="{{ route('frontend.notifications') }}" class="fs-12 fw-600 text-primary">
                        {{ translate('View All Notifications') }} &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- User Dropdown (Avatar + Name ▾) -->
        <div class="dropdown member-app-user-dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ $header_user_photo }}"
                     alt="{{ $auth_user->first_name }}"
                     class="member-app-user-avatar"
                     onerror="this.onerror=null;this.src='{{ $gender_placeholder }}';">
                <span class="member-app-user-name d-none d-sm-inline">
                    {{ $auth_user->first_name }} {{ $auth_user->last_name }} <i class="las la-angle-down fs-11 ml-1 text-muted"></i>
                </span>
            </div>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile_settings') }}" class="member-app-dropdown-item">
                    <i class="las la-user-circle"></i>
                    <span>{{ translate('My Profile') }}</span>
                </a>
                <a href="{{ route('member.change_password') }}" class="member-app-dropdown-item">
                    <i class="las la-cog"></i>
                    <span>{{ translate('Settings') }}</span>
                </a>
                <a href="{{ Route::has('contact_us') ? route('contact_us') : url('/contact-us') }}" class="member-app-dropdown-item">
                    <i class="las la-question-circle"></i>
                    <span>{{ translate('Help & Support') }}</span>
                </a>
                <div class="dropdown-divider my-1"></div>
                <a href="javascript:void(0);" class="member-app-dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                    <i class="las la-sign-out-alt"></i>
                    <span>{{ translate('Logout') }}</span>
                </a>
                <form id="header-logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</header>
