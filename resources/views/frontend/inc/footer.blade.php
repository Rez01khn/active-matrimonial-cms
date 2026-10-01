<!-- Global Footer -->
<footer class="footer-matrimony">
    <div class="container">
        <div class="row pb-5 border-bottom border-secondary border-opacity-20">
            <!-- Column 1: Brand Info -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none mb-3">
                    @if(get_setting('footer_logo') != null)
                        <img src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" class="h-40px">
                    @else
                        <div class="d-flex align-items-center">
                            <div class="mr-2 d-flex align-items-center justify-content-center bg-mat-maroon rounded-circle text-white size-38px">
                                <i class="las la-heart fs-22 text-mat-gold-light"></i>
                            </div>
                            <div>
                                <span class="font-serif fw-700 fs-20 text-white lh-1 d-block">Matrimony</span>
                                <span class="fs-9 text-muted lh-1 tracking-wide d-block mt-1">Together for a Better Tomorrow</span>
                            </div>
                        </div>
                    @endif
                </a>
                <p class="fs-13 text-muted pr-lg-4 mt-2">
                    {{ translate('Helping people find meaningful connections through trusted matchmaking.') }}
                </p>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">{{ translate('Quick Links') }}</h5>
                <ul class="list-unstyled fs-13 mb-0">
                    <li class="mb-2"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                    <li class="mb-2"><a href="{{ Route::has('active_members') ? route('active_members') : url('/member-listing') }}">{{ translate('Find Matches') }}</a></li>
                    <li class="mb-2"><a href="{{ Route::has('packages') ? route('packages') : url('/packages') }}">{{ translate('Membership Plans') }}</a></li>
                    <li class="mb-2"><a href="{{ Route::has('happy_stories') ? route('happy_stories') : url('/happy-stories') }}">{{ translate('Success Stories') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/custom-page/about-us') }}">{{ translate('About Us') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Resources -->
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h5 class="footer-title">{{ translate('Resources') }}</h5>
                <ul class="list-unstyled fs-13 mb-0">
                    <li class="mb-2"><a href="{{ Route::has('blog') ? route('blog') : url('/blog') }}">{{ translate('Blog') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/privacy-policy') }}">{{ translate('Privacy Policy') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/terms-and-conditions') }}">{{ translate('Terms & Conditions') }}</a></li>
                    <li class="mb-2"><a href="{{ url('/faq') }}">{{ translate('FAQs') }}</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">{{ translate('Contact') }}</h5>
                <ul class="list-unstyled fs-13 mb-3">
                    <li class="mb-2 d-flex align-items-center">
                        <i class="las la-envelope mr-2 fs-16 text-mat-gold-light"></i>
                        <a href="mailto:{{ get_setting('footer_email', 'hello@yourmatrimony.com') }}">{{ get_setting('footer_email', 'hello@yourmatrimony.com') }}</a>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="las la-phone mr-2 fs-16 text-mat-gold-light"></i>
                        <a href="tel:+6801012345678">+680 1012 345678</a>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="las la-phone mr-2 fs-16 text-mat-gold-light"></i>
                        <a href="tel:+6801012345679">+680 1012 345679</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ get_setting('facebook_link', '#') }}" target="_blank" class="text-white mr-3 fs-16 opacity-80 hov-opacity-100">
                        <i class="lab la-facebook-f"></i>
                    </a>
                    <a href="{{ get_setting('instagram_link', '#') }}" target="_blank" class="text-white mr-3 fs-16 opacity-80 hov-opacity-100">
                        <i class="lab la-instagram"></i>
                    </a>
                    <a href="{{ get_setting('youtube_link', '#') }}" target="_blank" class="text-white fs-16 opacity-80 hov-opacity-100">
                        <i class="lab la-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright & Scroll Top -->
        <div class="d-flex justify-content-between align-items-center pt-4 fs-12 text-muted">
            <div>
                © {{ date('Y') }} {{ get_setting('website_name', 'Matrimony') }}. {{ translate('All Rights Reserved.') }}
            </div>
            <a href="javascript:void(0)" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="scroll-top-btn" title="Back to top">
                <i class="las la-angle-up fs-16"></i>
            </a>
        </div>
    </div>
</footer>