<!-- Global Footer -->
<footer class="footer-matrimony">
    <div class="container">
        <!-- Top Row: Luxury Newsletter Subscription Strip -->
        <div class="footer-newsletter-box mb-5 pb-5 border-bottom border-secondary border-opacity-20">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 mb-3 mb-lg-0 text-center text-md-left">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <div class="footer-newsletter-icon-wrap mr-3">
                            <i class="las la-envelope-open-text fs-24 text-mat-gold-light"></i>
                        </div>
                        <div>
                            <h4 class="font-serif fw-700 fs-20 text-white mb-1">
                                {{ translate('Subscribe to Our Newsletter') }}
                            </h4>
                            <p class="fs-13 text-muted mb-0">
                                {{ translate('Get curated match highlights, wedding inspirations, and exclusive member offers.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <form id="footer-newsletter-form" onsubmit="submitFooterNewsletter(event)" class="footer-newsletter-form">
                        @csrf
                        <div class="footer-newsletter-input-group">
                            <i class="las la-envelope footer-newsletter-input-icon"></i>
                            <input type="email" name="email" id="footer-newsletter-email" 
                                class="footer-newsletter-input" 
                                placeholder="{{ translate('Enter your email address...') }}" 
                                required autocomplete="email">
                            <button type="submit" id="footer-newsletter-btn" class="footer-newsletter-btn">
                                <span>{{ translate('Subscribe') }}</span>
                                <i class="las la-paper-plane ml-1"></i>
                            </button>
                        </div>
                        <div id="footer-newsletter-msg" class="fs-12 mt-2 d-none text-center text-md-left"></div>
                    </form>
                </div>
            </div>
        </div>

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

<style>
.footer-newsletter-icon-wrap {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: rgba(197, 160, 89, 0.12);
    border: 1px solid rgba(197, 160, 89, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.footer-newsletter-input-group {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(231, 224, 213, 0.22);
    border-radius: 999px;
    padding: 5px 6px 5px 18px;
    position: relative;
    transition: all 0.3s ease;
}
.footer-newsletter-input-group:focus-within {
    border-color: var(--mat-gold, #C5A059);
    background: rgba(255, 255, 255, 0.14);
    box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.18);
}
.footer-newsletter-input-icon {
    font-size: 18px;
    color: #C5A059;
    margin-right: 10px;
    flex-shrink: 0;
}
.footer-newsletter-input {
    background: transparent;
    border: none;
    outline: none;
    color: #FFFFFF;
    font-size: 14px;
    width: 100%;
    padding: 6px 0;
}
.footer-newsletter-input::placeholder {
    color: rgba(230, 220, 210, 0.55);
}
.footer-newsletter-btn {
    background: linear-gradient(135deg, #C5A059 0%, #967240 100%);
    color: #FFFFFF !important;
    border: none;
    font-weight: 700;
    font-size: 13px;
    padding: 10px 24px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.25s ease;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(150, 114, 64, 0.3);
}
.footer-newsletter-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(150, 114, 64, 0.45);
    filter: brightness(1.08);
}
.footer-newsletter-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
@media (max-width: 575.98px) {
    .footer-newsletter-input-group {
        border-radius: 14px;
        flex-direction: column;
        padding: 12px;
        gap: 10px;
    }
    .footer-newsletter-input {
        text-align: center;
    }
    .footer-newsletter-btn {
        width: 100%;
        justify-content: center;
    }
    .footer-newsletter-input-icon {
        display: none;
    }
}
</style>

<script>
function submitFooterNewsletter(e) {
    e.preventDefault();
    var emailInput = document.getElementById('footer-newsletter-email');
    var btn = document.getElementById('footer-newsletter-btn');
    var msg = document.getElementById('footer-newsletter-msg');
    var email = emailInput ? emailInput.value.trim() : '';

    if (!email) return;

    btn.disabled = true;
    var originalBtnHtml = btn.innerHTML;
    btn.innerHTML = '<span>{{ translate("Subscribing...") }}</span>';

    $.ajax({
        url: "{{ route('subscribers.store') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            email: email
        },
        success: function(response) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
            emailInput.value = '';
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('success', response.message);
            }
            if (msg) {
                msg.classList.remove('d-none', 'text-danger');
                msg.classList.add('text-success');
                msg.innerHTML = '<i class="las la-check-circle mr-1"></i> ' + response.message;
            }
        },
        error: function(xhr) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
            var errorMsg = '{{ translate("Failed to subscribe. Please try again.") }}';
            if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                errorMsg = xhr.responseJSON.errors.email[0];
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                AIZ.plugins.notify('danger', errorMsg);
            }
            if (msg) {
                msg.classList.remove('d-none', 'text-success');
                msg.classList.add('text-danger');
                msg.innerHTML = '<i class="las la-exclamation-circle mr-1"></i> ' + errorMsg;
            }
        }
    });
}
</script>
