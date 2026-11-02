<!-- Global Footer -->
<footer class="footer-matrimony">
    <div class="container">
        <div class="row pb-2">
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
                    {{-- 1. Address --}}
                    @if(!empty(trim((string) get_setting('footer_address'))))
                        <li class="mb-2 d-flex align-items-start">
                            <i class="las la-map-marker-alt mr-2 fs-16 text-mat-gold-light mt-1"></i>
                            <span>{{ get_setting('footer_address') }}</span>
                        </li>
                    @endif

                    {{-- 2. Email --}}
                    @if(!empty(trim((string) get_setting('footer_email'))))
                        <li class="mb-2 d-flex align-items-center">
                            <i class="las la-envelope mr-2 fs-16 text-mat-gold-light"></i>
                            <a href="mailto:{{ get_setting('footer_email') }}">{{ get_setting('footer_email') }}</a>
                        </li>
                    @endif

                    {{-- 3. Mobile Number(s) --}}
                    @php
                        $footer_phones = get_setting('footer_phones') ? json_decode(get_setting('footer_phones'), true) : [];
                    @endphp
                    @if(!empty($footer_phones) && is_array($footer_phones))
                        @foreach($footer_phones as $phone)
                            @if(!empty(trim((string) $phone)))
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="las la-phone mr-2 fs-16 text-mat-gold-light"></i>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif

                    {{-- 4. Working Hours --}}
                    @if(!empty(trim((string) get_setting('footer_working_hours'))))
                        <li class="mb-2 d-flex align-items-center">
                            <i class="las la-clock mr-2 fs-16 text-mat-gold-light"></i>
                            <span>{{ get_setting('footer_working_hours') }}</span>
                        </li>
                    @endif
                </ul>

                {{-- Dynamic Social Media Links (Only renders links configured by Admin) --}}
                @php
                    $social_links = [
                        'facebook_link'  => 'lab la-facebook-f',
                        'twitter_link'   => 'lab la-twitter',
                        'instagram_link' => 'lab la-instagram',
                        'youtube_link'   => 'lab la-youtube',
                        'linkedin_link'  => 'lab la-linkedin-in',
                    ];
                @endphp
                <div class="footer-social-links">
                    @foreach($social_links as $setting_key => $icon_class)
                        @php
                            $social_url = trim((string) get_setting($setting_key));
                        @endphp
                        @if(!empty($social_url) && $social_url !== '#')
                            <a href="{{ $social_url }}" target="_blank" rel="noopener noreferrer" class="footer-social-icon">
                                <i class="{{ $icon_class }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Compact Newsletter Subscription Strip (Aligned from Resources to Contact) -->
        <div class="footer-newsletter-section pb-3 border-bottom border-secondary border-opacity-20">
            <div class="row">
                <div class="col-lg-6 offset-lg-6 col-md-12">
                    <div class="footer-newsletter-box">
                        <div class="row align-items-center gutters-5">
                            <div class="col-md-5 mb-2 mb-md-0 text-center text-md-left">
                                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                    <div class="footer-newsletter-icon-wrap mr-2">
                                        <i class="las la-envelope-open-text fs-16 text-mat-gold-light"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-serif fw-700 fs-13 text-white mb-0 lh-1-2">
                                            {{ translate('Subscribe to Our Newsletter') }}
                                        </h4>
                                        <p class="fs-10 text-muted mb-0 mt-1 lh-1-2">
                                            {{ translate('Get match highlights & exclusive offers.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
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
                                    <div id="footer-newsletter-msg" class="fs-11 mt-1 d-none text-center text-md-left"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright & Scroll Top -->
        <div class="d-flex justify-content-between align-items-center pt-2 fs-12 text-muted footer-copyright-bar">
            <div class="footer-copyright-text">
                @if(!empty(trim(strip_tags((string) get_setting('footer_copyright_text')))))
                    {!! get_setting('footer_copyright_text') !!}
                @else
                    © {{ date('Y') }} {{ get_setting('website_name', 'Matrimony') }}. {{ translate('All Rights Reserved.') }}
                @endif
            </div>
            <a href="javascript:void(0)" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="scroll-top-btn" title="Back to top">
                <i class="las la-angle-up fs-16"></i>
            </a>
        </div>
    </div>
</footer>

<style>
.footer-matrimony {
    padding-bottom: 16px !important;
}
.footer-social-links {
    display: flex;
    align-items: center;
    gap: 10px;
}
.footer-social-icon {
    color: #FFFFFF !important;
    font-size: 20px;
    line-height: 1;
    opacity: 0.85;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.footer-social-icon:hover {
    color: var(--mat-gold-light, #C5A059) !important;
    opacity: 1;
    transform: translateY(-1px);
}
.footer-copyright-text p {
    margin-bottom: 0;
}
.footer-newsletter-icon-wrap {
    width: 30px;
    height: 30px;
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
    padding: 2px 3px 2px 12px;
    position: relative;
    transition: all 0.3s ease;
}
.footer-newsletter-input-group:focus-within {
    border-color: var(--mat-gold, #C5A059);
    background: rgba(255, 255, 255, 0.14);
    box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.18);
}
.footer-newsletter-input-icon {
    font-size: 14px;
    color: #C5A059;
    margin-right: 6px;
    flex-shrink: 0;
}
.footer-newsletter-input {
    background: transparent;
    border: none;
    outline: none;
    color: #FFFFFF;
    font-size: 12px;
    width: 100%;
    padding: 4px 0;
}
.footer-newsletter-input::placeholder {
    color: rgba(230, 220, 210, 0.55);
}
.footer-newsletter-btn {
    background: linear-gradient(135deg, #C5A059 0%, #967240 100%);
    color: #FFFFFF !important;
    border: none;
    font-weight: 700;
    font-size: 11.5px;
    padding: 6px 13px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
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
        padding: 10px;
        gap: 8px;
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
