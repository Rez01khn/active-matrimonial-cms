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
    $latest_notifications = $auth_user->notifications()->take(5)->get();
@endphp

<!-- Dedicated Private Portal Dashboard Header -->
<header class="member-portal-header sticky-top bg-white border-bottom shadow-xs">
    <div class="container h-100">
        <div class="d-flex align-items-center justify-content-between h-100 py-2">
            <!-- Left Side: Mobile Hamburger Toggle + Brand Logo + Back to Website Link -->
            <div class="d-flex align-items-center">
                <!-- Hamburger Toggle Button for Offcanvas Mobile Sidebar (< 1200px) -->
                <button type="button" class="btn btn-icon member-sidebar-toggle-btn d-xl-none mr-2 p-1" id="memberSidebarToggle" aria-label="{{ translate('Open Menu') }}">
                    <i class="las la-bars fs-24 text-mat-maroon"></i>
                </button>

                <!-- Brand Logo -->
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center member-portal-logo mr-3" title="{{ get_setting('website_name') }}">
                    @if(get_setting('header_logo'))
                        <img src="{{ uploaded_asset(get_setting('header_logo')) }}" alt="{{ get_setting('website_name') }}" height="38" class="mw-100">
                    @else
                        <span class="fs-20 fw-700 text-mat-maroon font-serif">{{ get_setting('website_name', 'Matrimonial') }}</span>
                    @endif
                </a>

                <!-- Back to Website Link -->
                <a href="{{ route('home') }}" class="btn btn-sm btn-luxury-outline rounded-pill d-none d-md-inline-flex align-items-center fs-12 fw-600 px-3 py-1 ml-2">
                    <i class="las la-arrow-left mr-1"></i>
                    <span>{{ translate('Back to Website') }}</span>
                </a>
            </div>

            <!-- Right Side: Find Matches + Notifications Bell + User Profile + Logout -->
            <div class="d-flex align-items-center">
                <!-- Quick Matchmaking Link -->
                <a href="{{ Route::has('active_members') ? route('active_members') : url('/member-listing') }}" class="d-none d-lg-inline-flex align-items-center fs-13 fw-600 text-mat-maroon mr-3 hov-text-gold">
                    <i class="las la-search mr-1 text-mat-gold fs-16"></i>
                    <span>{{ translate('Find Matches') }}</span>
                </a>

                <!-- Notification Bell Dropdown -->
                <div class="dropdown mr-2 mr-sm-3">
                    <button type="button" class="btn btn-icon btn-circle btn-light position-relative member-notify-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ translate('Notifications') }}">
                        <i class="las la-bell fs-20 text-mat-maroon"></i>
                        @if($unread_count > 0)
                            <span class="member-notify-badge">{{ $unread_count > 99 ? '99+' : $unread_count }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0 shadow-lg border-0" style="min-width: 320px;">
                        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                            <span class="fs-14 fw-700 text-dark">{{ translate('Notifications') }}</span>
                            @if($unread_count > 0)
                                <a href="{{ route('notification.mark_all_as_read') }}" class="fs-12 text-primary fw-600">{{ translate('Mark all as read') }}</a>
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
                                <div class="text-center py-4 text-muted fs-13">
                                    <i class="las la-bell-slash la-2x mb-1 d-block opacity-40"></i>
                                    {{ translate('No notifications yet') }}
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

                <!-- User Profile Quick Link -->
                <a href="{{ route('profile_settings') }}" class="d-flex align-items-center text-reset mr-2 mr-sm-3 member-header-user-link" title="{{ translate('My Profile') }}">
                    <img src="{{ $header_user_photo }}"
                         class="member-header-avatar rounded-circle border border-mat-gold shadow-xs"
                         width="36"
                         height="36"
                         style="width: 36px !important; height: 36px !important; min-width: 36px !important; max-width: 36px !important; min-height: 36px !important; max-height: 36px !important; object-fit: cover !important; border-radius: 50% !important; display: inline-block !important; flex-shrink: 0 !important;"
                         alt="{{ $auth_user->first_name }}"
                         onerror="this.onerror=null;this.src='{{ $gender_placeholder }}';">
                    <span class="fs-13 fw-600 text-dark ml-2 d-none d-sm-inline">{{ $auth_user->first_name }}</span>
                </a>

                <!-- Direct Logout Button -->
                <a href="{{ route('user.logout') }}" class="btn btn-mat-maroon btn-sm py-1 px-3 rounded-pill fs-12 fw-600 d-inline-flex align-items-center shadow-xs" title="{{ translate('Logout') }}">
                    <i class="las la-sign-out-alt mr-1"></i>
                    <span class="d-none d-sm-inline">{{ translate('Logout') }}</span>
                </a>
            </div>
        </div>
    </div>
</header>
