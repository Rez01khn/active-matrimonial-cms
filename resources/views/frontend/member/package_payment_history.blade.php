@extends('frontend.layouts.member_panel')

@section('panel_content')
<!-- Header & Breadcrumb -->
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
        <li class="mp-pkg-breadcrumb-active">{{ translate('Purchase History') }}</li>
    </ul>

    <div class="mp-pkg-title-bar">
        <div>
            <h1 class="mp-pkg-page-title">{{ translate('Package Purchase History') }}</h1>
            <p class="mp-pkg-page-subtitle">
                {{ translate('View your past membership package transactions and download official invoices.') }}
            </p>
        </div>
        <div>
            <a href="{{ route('packages') }}" class="mp-pkg-history-btn">
                <i class="las la-crown"></i>
                <span>{{ translate('Explore Packages') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="mp-checkout-methods-card">
    <div class="mp-checkout-card-header">
        <h3 class="mp-checkout-card-title">{{ translate('Transaction Records') }}</h3>
    </div>
    <div class="mp-checkout-card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="md">#</th>
                    <th>{{ translate('Code') }}</th>
                    <th>{{ translate('Package') }}</th>
                    <th data-breakpoints="md">{{ translate('Payment Method') }}</th>
                    <th data-breakpoints="md">{{ translate('Amount') }}</th>
                    <th data-breakpoints="md" class="text-center">{{ translate('Payment Status') }}</th>
                    <th data-breakpoints="md">{{ translate('Purchase Date') }}</th>
                    <th class="text-right">{{ translate('Invoice') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($package_payments as $key => $package_payment)
                    <tr>
                        <td>{{ ($key + 1) + ($package_payments->currentPage() - 1) * $package_payments->perPage() }}</td>
                        <td class="fw-600">{{ $package_payment->payment_code }}</td>
                        <td>{{ $package_payment->package ? $package_payment->package->name : translate('N/A') }}</td>
                        <td>
                            @if($package_payment->payment_method == "manual_payment")
                                {{ $package_payment->custom_payment_name }}
                            @else
                                {{ ucwords($package_payment->payment_method) }}
                            @endif
                        </td>
                        <td class="fw-600">{{ single_price($package_payment->amount) }}</td>
                        <td class="text-center">
                            @if ($package_payment->payment_status == 'Paid')
                                <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                            @else
                                <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                            @endif
                        </td>
                        <td>{{ $package_payment->created_at ? $package_payment->created_at->format('d M, Y') : '' }}</td>
                        <td class="text-right">
                            <a href="{{ route('package_payment.invoice', $package_payment->id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Invoice') }}">
                                <i class="las la-file-invoice"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination mt-3">
            {{ $package_payments->links() }}
        </div>
    </div>
</div>
@endsection
