@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $package_price = $package->price;
    $has_discount = false;
    if ($package->id != 1 && addon_activation('referral_system') && Auth::user()->referred_by != null && Auth::user()->referral_comission == 0) {
        $has_discount = true;
        $discount_type = get_setting('referral_user_package_purchase_discount_type');
        if ($discount_type == 'percent') {
            $discount = ($package->price * get_setting('referral_user_package_purchase_discount')) / 100;
        } else {
            $discount = get_setting('referral_user_package_purchase_discount');
        }
        $package_price = $package_price - $discount;
    }

    $hasAnyPayment = (get_setting('paypal_payment_activation') == 1) ||
                     (get_setting('stripe_payment_activation') == 1) ||
                     (get_setting('instamojo_payment_activation') == 1) ||
                     (get_setting('razorpay_payment_activation') == 1) ||
                     (get_setting('paystack_payment_activation') == 1) ||
                     (get_setting('paytm_payment_activation') == 1) ||
                     (get_setting('aamarpay_payment_activation') == 1) ||
                     (get_setting('sslcommerz_payment_activation') == 1) ||
                     (get_setting('phonepe_payment_activation') == 1) ||
                     (get_setting('wallet_system') == 1) ||
                     (isset($manual_payments) && count($manual_payments) > 0);
@endphp

<!-- 1. Page Header & Breadcrumb -->
<div class="mp-pkg-header-wrap">
    <ul class="mp-pkg-breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}" class="mp-pkg-breadcrumb-link">{{ translate('Dashboard') }}</a>
        </li>
        <li class="mp-pkg-breadcrumb-sep">›</li>
        <li>
            <a href="{{ route('packages') }}" class="mp-pkg-breadcrumb-link">{{ translate('Membership Packages') }}</a>
        </li>
        <li class="mp-pkg-breadcrumb-sep">›</li>
        <li class="mp-pkg-breadcrumb-active">{{ translate('Complete Payment') }}</li>
    </ul>

    <div class="mp-pkg-title-bar">
        <div>
            <h1 class="mp-pkg-page-title">{{ translate('Complete Your Upgrade') }}</h1>
            <p class="mp-pkg-page-subtitle">
                {{ translate('Review your selected membership plan and choose your preferred payment method below.') }}
            </p>
        </div>
        <div>
            <a href="{{ route('packages') }}" class="mp-pkg-history-btn">
                <i class="las la-arrow-left"></i>
                <span>{{ translate('Change Package') }}</span>
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger text-center mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="row gutters-15">
    <!-- Left Column: Selected Package Summary -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="mp-checkout-summary-card">
            <div class="mp-checkout-card-header">
                <h3 class="mp-checkout-card-title">{{ translate('Order Summary') }}</h3>
                <span class="badge badge-inline badge-success">{{ $package->validity }} {{ translate('Days') }}</span>
            </div>
            <div class="mp-checkout-card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="mp-pkg-icon-wrap mb-0 mr-3">
                        @if(!empty($package->image) && uploaded_asset($package->image))
                            <img src="{{ uploaded_asset($package->image) }}" alt="{{ $package->name }}" class="mp-pkg-icon-img">
                        @else
                            <i class="las la-crown"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="mp-pkg-name mb-0">{{ $package->name }}</h4>
                        <span class="text-muted fs-12">{{ translate('Membership Upgrade') }}</span>
                    </div>
                </div>

                <div class="mp-pkg-price-row mb-3">
                    @if ($package->id == 1)
                        <span class="mp-pkg-price-val">{{ translate('Free') }}</span>
                    @else
                        @if ($has_discount)
                            <del class="text-muted mr-2 fs-15">{{ single_price($package->price) }}</del>
                            <span class="mp-pkg-price-val">{{ single_price($package_price) }}</span>
                        @else
                            <span class="mp-pkg-price-val">{{ single_price($package_price) }}</span>
                        @endif
                    @endif
                    <span class="mp-pkg-price-period">/ {{ $package->validity }} {{ translate('Days') }}</span>
                </div>

                <div class="mp-pkg-divider"></div>

                <ul class="mp-pkg-features mb-0">
                    <li class="mp-pkg-feature-item">
                        <i class="las la-check-circle"></i>
                        <span><strong>{{ $package->express_interest }}</strong> {{ translate('Express Interests') }}</span>
                    </li>
                    <li class="mp-pkg-feature-item">
                        <i class="las la-check-circle"></i>
                        <span><strong>{{ $package->photo_gallery }}</strong> {{ translate('Gallery Photo Uploads') }}</span>
                    </li>
                    <li class="mp-pkg-feature-item">
                        <i class="las la-check-circle"></i>
                        <span><strong>{{ $package->contact }}</strong> {{ translate('Contact Info Views') }}</span>
                    </li>
                    <li class="mp-pkg-feature-item">
                        <i class="las la-check-circle"></i>
                        <span><strong>{{ $package->profile_viewers_view }}</strong> {{ translate('Profile Viewer Views') }}</span>
                    </li>
                    @if (get_setting('profile_picture_privacy') == 'only_me')
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->profile_image_view }}</strong> {{ translate('Profile Image Views') }}</span>
                        </li>
                    @endif
                    @if (get_setting('gallery_image_privacy') == 'only_me')
                        <li class="mp-pkg-feature-item">
                            <i class="las la-check-circle"></i>
                            <span><strong>{{ $package->gallery_image_view }}</strong> {{ translate('Gallery Image Views') }}</span>
                        </li>
                    @endif
                    <li class="mp-pkg-feature-item {{ $package->auto_profile_match == 0 ? 'mp-pkg-feature-disabled' : '' }}">
                        @if ($package->auto_profile_match == 0)
                            <i class="las la-times-circle"></i>
                            <span>{{ translate('Show Auto Profile Match') }}</span>
                        @else
                            <i class="las la-check-circle"></i>
                            <span>{{ translate('Show Auto Profile Match') }}</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Payment Gateways Form -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <form action="{{ route('package.payment') }}" class="form-default" role="form" method="POST" id="package-payment-form">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">
            <input type="hidden" name="amount" value="{{ $package_price }}">
            <input type="hidden" id="payment_type" value="">

            <div class="mp-checkout-methods-card">
                <div class="mp-checkout-card-header">
                    <h3 class="mp-checkout-card-title">{{ translate('Select a Payment Option') }}</h3>
                    <span class="text-muted fs-12"><i class="las la-lock mr-1"></i>{{ translate('Secure Checkout') }}</span>
                </div>

                <div class="mp-checkout-card-body">
                    @if(!$hasAnyPayment)
                        <div class="text-center py-4">
                            <div class="p-4 rounded border-warning bg-soft-warning">
                                <i class="las la-wallet fs-36 text-warning mb-2 d-block"></i>
                                <h5 class="fw-700 text-dark mb-2">{{ translate('Online Payment Under Upgrade') }}</h5>
                                <p class="fs-14 text-muted mb-2">
                                    {{ translate('Payment systems are temporarily paused for integration of Bangladeshi payment gateways (bKash, Nagad, SSLCommerz).') }}
                                </p>
                                <p class="fs-13 text-muted mb-0">
                                    {{ translate('Please use the test premium account or contact admin to activate membership.') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="row gutters-10">
                        @if (get_setting('paypal_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="paypal" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/paypal.png') }}" class="mp-payment-gateway-img" alt="PayPal">
                                        <span class="mp-payment-gateway-name">{{ translate('Paypal') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('stripe_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="stripe" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/stripe.png') }}" class="mp-payment-gateway-img" alt="Stripe">
                                        <span class="mp-payment-gateway-name">{{ translate('Stripe') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('instamojo_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="instamojo" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/instamojo.png') }}" class="mp-payment-gateway-img" alt="Instamojo">
                                        <span class="mp-payment-gateway-name">{{ translate('Instamojo') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('razorpay_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="razorpay" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/rozarpay.png') }}" class="mp-payment-gateway-img" alt="Razorpay">
                                        <span class="mp-payment-gateway-name">{{ translate('Razorpay') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('paystack_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="paystack" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/paystack.png') }}" class="mp-payment-gateway-img" alt="Paystack">
                                        <span class="mp-payment-gateway-name">{{ translate('Paystack') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('paytm_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="paytm" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/paytm.png') }}" class="mp-payment-gateway-img" alt="Paytm">
                                        <span class="mp-payment-gateway-name">{{ translate('Paytm') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('aamarpay_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="aamarpay" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/aamarpay.png') }}" class="mp-payment-gateway-img" alt="Aamarpay">
                                        <span class="mp-payment-gateway-name">{{ translate('Aamarpay') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('sslcommerz_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="sslcommerz" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/sslcommerz.png') }}" class="mp-payment-gateway-img" alt="SSLCommerz">
                                        <span class="mp-payment-gateway-name">{{ translate('Sslcommerz') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('phonepe_payment_activation') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="phonepe" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/phonepe.png') }}" class="mp-payment-gateway-img" alt="PhonePe">
                                        <span class="mp-payment-gateway-name">{{ translate('PhonePe') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @if (get_setting('wallet_system') == 1)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="wallet" class="online_payment" type="radio" name="payment_option">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ static_asset('assets/img/payment_method/wallet.png') }}" class="mp-payment-gateway-img" alt="Wallet">
                                        <span class="mp-payment-gateway-name">{{ translate('Wallet') }}</span>
                                    </span>
                                </label>
                            </div>
                        @endif

                        @foreach ($manual_payments as $method)
                            <div class="col-6 col-md-4 mb-3">
                                <label class="aiz-megabox d-block mb-0">
                                    <input value="manual_payment" class="manual_payment" id="payment_option" type="radio"
                                        data-method-id="{{ $method->id }}" name="payment_option"
                                        onchange="toggleManualPaymentData({{ $method->id }})">
                                    <span class="mp-payment-option-box aiz-megabox-elem">
                                        <img src="{{ uploaded_asset($method->photo) }}" class="mp-payment-gateway-img" alt="{{ $method->heading }}">
                                        <span class="mp-payment-gateway-name">{{ $method->heading }}</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    @foreach ($manual_payments as $method)
                        <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                            <div>@php echo $method->description @endphp</div>
                            @if ($method->bank_info != null)
                                <ul class="list-unstyled mt-2 mb-0">
                                    @foreach (json_decode($method->bank_info) as $key => $info)
                                        <li><strong>{{ translate('Bank Name') }}:</strong> {{ $info->bank_name }}</li>
                                        <li><strong>{{ translate('Account Name') }}:</strong> {{ $info->account_name }}</li>
                                        <li><strong>{{ translate('Account Number') }}:</strong> {{ $info->account_number }}</li>
                                        <li><strong>{{ translate('Routing Number') }}:</strong> {{ $info->routing_number }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach

                    <div class="card mb-3 p-3 d-none w-100 manual_payment_description">
                        <div id="manual_payment_description"></div>
                    </div>

                    <div class="w-100 d-none mt-3" id="purchase_by_manual_payment">
                        <input type="hidden" name="manual_payment_id" class="manual_payment_id">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="fw-600">{{ translate('Transaction Id') }} <span class="text-danger">*</span></label>
                                <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="{{ translate('Transaction Id') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="fw-600">{{ translate('Payment Proof') }} <span class="text-danger">*</span></label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="payment_proof" id="payment_proof" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <label class="fw-600">{{ translate('Payment Details') }}</label>
                                <textarea name="payment_details" class="form-control" rows="2" placeholder="{{ translate('Payment Details') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3 border-top mt-2">
                        <button type="button" onclick="package_purchase(this)" class="btn btn-mp-pkg-gold w-auto px-5 purchase_button" disabled>
                            <span>{{ translate('Confirm & Pay') }}</span>
                            <i class="las la-check-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(".online_payment").click(function() {
            $('.manual_payment_description').addClass('d-none');
            $('#purchase_by_manual_payment').addClass('d-none');
            $(".purchase_button").prop('disabled', false);
            $("#payment_type").val('online_payment');
        });
        $(".manual_payment").click(function() {
            $(".purchase_button").prop('disabled', false);
            $("#payment_type").val('manual_payment');
            $('.manual_payment_description').removeClass('d-none');
        });

        function package_purchase(el) {
            $(el).prop('disabled', true);
            var payment_type = $("#payment_type").val();
            if (payment_type == 'manual_payment') {
                var transaction_id = $("#transaction_id").val();
                var payment_proof = $("#payment_proof").val();
                if (transaction_id == '' || payment_proof == '') {
                    AIZ.plugins.notify('danger', '{{ translate('Please Provide transaction id and payemnt proof.') }}');
                    $(el).prop('disabled', false);
                } else {
                    $('#package-payment-form').submit();
                }
            } else {
                $('#package-payment-form').submit();
            }
        }

        function toggleManualPaymentData(id) {
            $('#manual_payment_description').parent().removeClass('d-none');
            $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            $('#purchase_by_manual_payment').removeClass('d-none');
            $('.manual_payment_id').val(id);
        }
    </script>
@endsection
