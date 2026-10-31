<!doctype html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <!-- Required meta tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

    <!-- Favicon -->
    <link name="favicon" type="image/x-icon" href="{{ uploaded_asset(get_setting('site_icon')) }}"
        rel="shortcut icon" />

    <!-- google font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!-- vendors css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">

    <!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000,9999) }}">

    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif


    <script>
        var AIZ = AIZ || {};
    </script>

    <style>
        /* Admin Sidebar Royal Heritage Theme (#331019 & #C5A059) */
        :root {
            --mat-maroon: #4A1521;
            --mat-maroon-dark: #331019;
            --mat-gold: #967240;
            --mat-gold-light: #C5A059;
            --mat-gold-hover: #FDE68A;
        }

        .aiz-sidebar.left {
            background-color: #331019 !important;
            border-right: 1px solid rgba(197, 160, 89, 0.15) !important;
        }
        .aiz-side-nav-logo-wrap {
            background-color: #240a11 !important;
            border-bottom: 1px solid rgba(197, 160, 89, 0.15) !important;
            padding: 18px 20px !important;
        }
        .aiz-side-nav-wrap #menu-search {
            background: rgba(255, 255, 255, 0.07) !important;
            border: 1px solid rgba(197, 160, 89, 0.25) !important;
            color: #FFFFFF !important;
            border-radius: 999px !important;
            padding-left: 36px !important;
            padding-right: 14px !important;
            height: 38px !important;
            font-size: 12px !important;
            transition: all 0.25s ease !important;
        }
        .aiz-side-nav-wrap #menu-search::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .aiz-side-nav-wrap #menu-search:focus {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: #C5A059 !important;
            box-shadow: 0 0 10px rgba(197, 160, 89, 0.25) !important;
        }

        /* Sidebar Section Category Headers */
        .aiz-side-nav-category {
            padding: 18px 18px 6px 18px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(197, 160, 89, 0.7);
            display: flex;
            align-items: center;
            gap: 8px;
            user-select: none;
        }
        .aiz-side-nav-category::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(197, 160, 89, 0.25), transparent);
        }

        /* Nav Item & Pill Styling */
        .aiz-side-nav-list .aiz-side-nav-item {
            margin: 2px 10px;
        }
        .aiz-side-nav-list .aiz-side-nav-link {
            color: #E2DCD5 !important;
            border-radius: 10px !important;
            padding: 9px 14px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            border: 1px solid transparent !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            align-items: center !important;
        }
        .aiz-side-nav-list .aiz-side-nav-icon {
            color: #C5A059 !important;
            font-size: 18px !important;
            width: 24px !important;
            margin-right: 10px !important;
            text-align: center !important;
            flex-shrink: 0 !important;
            transition: transform 0.2s ease, color 0.2s ease !important;
        }
        .aiz-side-nav-list .aiz-side-nav-link:hover {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(197, 160, 89, 0.2) !important;
            transform: translateX(2px);
        }
        .aiz-side-nav-list .aiz-side-nav-link:hover .aiz-side-nav-icon {
            color: #FDE68A !important;
            transform: scale(1.1);
        }
        .aiz-side-nav-list .aiz-side-nav-link.active,
        .aiz-side-nav-list .aiz-side-nav-link.level-2-active,
        .aiz-side-nav-list .aiz-side-nav-link.level-3-active {
            color: #FFFFFF !important;
            background: linear-gradient(135deg, #4A1521 0%, #2A0912 100%) !important;
            border: 1px solid rgba(197, 160, 89, 0.45) !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25) !important;
            font-weight: 700 !important;
        }
        .aiz-side-nav-list .aiz-side-nav-link.active .aiz-side-nav-icon {
            color: #FDE68A !important;
        }

        /* Submenu Tree Hierarchy Styling */
        .aiz-side-nav-list .level-2 {
            background: rgba(0, 0, 0, 0.16) !important;
            border-radius: 8px !important;
            margin: 4px 6px 6px 16px !important;
            padding: 6px 0 !important;
            border-left: 2px dashed rgba(197, 160, 89, 0.28) !important;
        }
        .aiz-side-nav-list .level-2 .aiz-side-nav-item {
            margin: 1px 6px !important;
        }
        .aiz-side-nav-list .level-2 .aiz-side-nav-link {
            padding: 7px 12px !important;
            font-size: 12.5px !important;
            border-radius: 6px !important;
        }
        .aiz-side-nav-list .level-2 .aiz-side-nav-link:after {
            display: none !important;
        }
        .aiz-side-nav-list .level-2 .aiz-side-nav-link::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(197, 160, 89, 0.4);
            margin-right: 8px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .aiz-side-nav-list .level-2 .aiz-side-nav-link:hover::before,
        .aiz-side-nav-list .level-2 .aiz-side-nav-link.active::before {
            background: #C5A059;
            box-shadow: 0 0 6px #C5A059;
            transform: scale(1.3);
        }

        .aiz-side-nav-arrow {
            color: rgba(197, 160, 89, 0.7) !important;
            font-size: 10px !important;
            transition: transform 0.25s ease !important;
        }    </style>
</head>

<body>

    <div class="aiz-main-wrapper">

        @include('admin.inc.sidenav')

        <div class="aiz-content-wrapper">

            @include('admin.inc.header')

            <!-- Main Content start-->
            <div class="aiz-main-content">
                <div class="px-15px px-lg-25px">
                    @yield('content')
                </div>

                <!-- Footer -->
                <div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">
                    <p class="mb-0">&copy; {{ env('APP_NAME') }} v{{ get_setting('current_version') }}</p>
                </div>
            </div>
            <!-- Mian content end -->

        </div>

    </div>

    @yield('modal')

    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>

    @if (get_setting('firebase_push_notification') == 1)
        {{-- fcm --}}
        <!-- The core Firebase JS SDK is always required and must be listed first -->
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

        <!-- TODO: Add SDKs for Firebase products that you want to use
        https://firebase.google.com/docs/web/setup#available-libraries -->

        <script>
            // Your web app's Firebase configuration
            var firebaseConfig = {
                apiKey: "{{ config('larafirebase.api_key') }}",
                authDomain: "{{ config('larafirebase.auth_domain') }}",
                projectId: "{{ config('larafirebase.project_id') }}",
                storageBucket: "{{ config('larafirebase.storage_bucket') }}",
                messagingSenderId: "{{ config('larafirebase.messaging_sender_id') }}",
                appId: "{{ config('larafirebase.app_id') }}",
            };

            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            const messaging = firebase.messaging();

            function initFirebaseMessagingRegistration() {
                navigator.serviceWorker.register('{{ url('/firebase-messaging-sw.js') }}', {
                    scope: '{{ url('/') }}/'
                }).then(function(registration) {
                    messaging.requestPermission()
                    .then(function() {
                        return messaging.getToken({ serviceWorkerRegistration: registration })
                    }).then(function(token) {

                        $.ajax({
                            url: '{{ route('fcmToken') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                fcm_token: token
                            },
                            dataType: 'JSON',
                            success: function (response) {
                                
                            },
                            error: function (err) {
                                console.log(" Can't do because: " + err);
                            },
                        });

                    }).catch(function(err) {
                        console.log(`Token Error :: ${err}`);
                    });
                }).catch(function(err) {
                    console.log(`Service Worker Registration Error :: ${err}`);
                });
            }

            initFirebaseMessagingRegistration();        

            messaging.onMessage(function({
                data: {
                    body,
                    title
                }
            }) {
                new Notification(title, {
                    body
                });
            });
        </script>
        {{-- End of fcm --}}
    @endif

    @yield('script')



    <script type="text/javascript">
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach

        // language Switch
        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}', {
                        _token: '{{ csrf_token() }}',
                        locale: locale
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }
        function menuSearch(){
			var filter, item;
			filter = $("#menu-search").val().toUpperCase();
			items = $("#main-menu").find("a");
			items = items.filter(function(i,item){
				if($(item).find(".aiz-side-nav-text")[0].innerText.toUpperCase().indexOf(filter) > -1 && $(item).attr('href') !== '#'){
					return item;
				}
			});

			if(filter !== ''){
				$("#main-menu").addClass('d-none');
				$("#search-menu").html('')
				if(items.length > 0){
					for (i = 0; i < items.length; i++) {
						const text = $(items[i]).find(".aiz-side-nav-text")[0].innerText;
						const link = $(items[i]).attr('href');
						 $("#search-menu").append(`<li class="aiz-side-nav-item"><a href="${link}" class="aiz-side-nav-link"><i class="las la-ellipsis-h aiz-side-nav-icon"></i><span>${text}</span></a></li`);
					}
				}else{
					$("#search-menu").html(`<li class="aiz-side-nav-item"><span	class="text-center text-muted d-block">{{ translate('Nothing Found') }}</span></li>`);
				}
			}else{
				$("#main-menu").removeClass('d-none');
				$("#search-menu").html('')
			}
        }
    </script>


</body>

</html>
