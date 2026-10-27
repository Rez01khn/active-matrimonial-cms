@extends('frontend.layouts.member_panel')

@section('panel_content')
@php
    $user = Auth::user();
    $is_deactivated = $user->deactivated == 1;
@endphp

<style>
    .settings-card {
        background: #ffffff;
        border: 1px solid #ECE7E1;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(59, 23, 33, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .settings-card-header {
        background: #FAF6F1;
        border-bottom: 1px solid #EFE5DA;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .settings-input-group {
        position: relative;
    }
    .settings-password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        font-size: 18px;
        padding: 0;
        z-index: 2;
    }
    .settings-password-toggle:hover {
        color: #3b1721;
    }
    .settings-submit-btn {
        background: #3b1721;
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .settings-submit-btn:hover {
        background: #562332;
        color: #ffffff;
        transform: translateY(-1px);
    }
    .settings-submit-btn:disabled {
        background: #CBD5E1;
        cursor: not-allowed;
        transform: none;
    }
    .danger-zone-card {
        background: #ffffff;
        border: 1px solid #FEE2E2;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(239, 68, 68, 0.03);
        overflow: hidden;
    }
    .danger-zone-header {
        background: #FEF2F2;
        border-bottom: 1px solid #FEE2E2;
        padding: 18px 24px;
    }
</style>

<!-- Header & Breadcrumbs -->
<div class="mb-4">
    <ul class="breadcrumb bg-transparent p-0 mb-2 small">
        <li class="breadcrumb-item opacity-50">
            <a class="text-reset" href="{{ route('dashboard') }}">{{ translate('Dashboard') }}</a>
        </li>
        <li class="text-dark fw-600 breadcrumb-item active">
            {{ translate('Account Settings') }}
        </li>
    </ul>
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <h1 class="h4 font-weight-bold mb-1 font-serif" style="color: #3b1721; letter-spacing: -0.3px;">
                {{ translate('Account Settings & Security') }}
            </h1>
            <p class="text-muted small mb-0">
                {{ translate('Manage your password, login security credentials, and account status.') }}
            </p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Password Change Form -->
    <div class="col-lg-7">
        <div class="settings-card">
            <div class="settings-card-header">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #3b1721; color: #f3d5a5;">
                        <i class="las la-lock fs-18"></i>
                    </div>
                    <div>
                        <h5 class="fw-700 fs-16 mb-0" style="color: #3b1721;">{{ translate('Change Password') }}</h5>
                        <small class="text-muted">{{ translate('Ensure your account is using a long, random password to stay secure.') }}</small>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <form action="{{ route('member.password_update', Auth::user()->id) }}" method="POST" id="passwordChangeForm">
                    @csrf

                    <!-- Old Password -->
                    <div class="form-group mb-3">
                        <label class="fw-600 fs-13 mb-1.5" style="color: #3b1721;">
                            {{ translate('Current Password') }} <span class="text-danger">*</span>
                        </label>
                        <div class="settings-input-group">
                            <input type="password"
                                   name="old_password"
                                   id="old_password"
                                   class="form-control rounded-lg"
                                   style="height: 44px; padding-right: 42px;"
                                   placeholder="{{ translate('Enter your current password') }}"
                                   required>
                            <button type="button" class="settings-password-toggle" onclick="togglePasswordVisibility('old_password', this)" tabindex="-1">
                                <i class="lar la-eye"></i>
                            </button>
                        </div>
                        @error('old_password')
                            <small class="form-text text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group mb-3">
                        <label class="fw-600 fs-13 mb-1.5" style="color: #3b1721;">
                            {{ translate('New Password') }} <span class="text-danger">*</span>
                        </label>
                        <div class="settings-input-group">
                            <input type="password"
                                   name="password"
                                   id="new_password"
                                   class="form-control rounded-lg"
                                   style="height: 44px; padding-right: 42px;"
                                   placeholder="{{ translate('At least 8 characters') }}"
                                   onkeyup="validatePasswordMatch()"
                                   required>
                            <button type="button" class="settings-password-toggle" onclick="togglePasswordVisibility('new_password', this)" tabindex="-1">
                                <i class="lar la-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="form-text text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-4">
                        <label class="fw-600 fs-13 mb-1.5" style="color: #3b1721;">
                            {{ translate('Confirm New Password') }} <span class="text-danger">*</span>
                        </label>
                        <div class="settings-input-group">
                            <input type="password"
                                   name="confirm_password"
                                   id="confirm_password"
                                   class="form-control rounded-lg"
                                   style="height: 44px; padding-right: 42px;"
                                   placeholder="{{ translate('Re-type your new password') }}"
                                   onkeyup="validatePasswordMatch()"
                                   required>
                            <button type="button" class="settings-password-toggle" onclick="togglePasswordVisibility('confirm_password', this)" tabindex="-1">
                                <i class="lar la-eye"></i>
                            </button>
                        </div>
                        <div id="password_match_feedback" class="fs-12 mt-1.5" style="display: none;"></div>
                        @error('confirm_password')
                            <small class="form-text text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="pt-2 border-top d-flex justify-content-end">
                        <button type="submit" class="settings-submit-btn" id="passSaveBtn" disabled>
                            <i class="las la-check mr-1"></i> {{ translate('Update Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Account Overview & Status Management -->
    <div class="col-lg-5">
        <!-- Account Overview Card -->
        <div class="settings-card mb-4">
            <div class="settings-card-header">
                <div class="d-flex align-items-center">
                    <div class="mr-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #fdf6f0; color: #8C6239;">
                        <i class="las la-user-circle fs-20"></i>
                    </div>
                    <h5 class="fw-700 fs-15 mb-0" style="color: #3b1721;">{{ translate('Account Details') }}</h5>
                </div>
            </div>
            <div class="p-4 fs-13">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">{{ translate('Member Code') }}</span>
                    <span class="fw-600 text-dark">{{ $user->code ?? 'ID-'.$user->id }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">{{ translate('Registered Email') }}</span>
                    <span class="fw-600 text-dark text-truncate ml-2" style="max-width: 200px;">{{ $user->email ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">{{ translate('Phone Number') }}</span>
                    <span class="fw-600 text-dark">{{ $user->phone ?? translate('Not set') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2">
                    <span class="text-muted">{{ translate('Membership') }}</span>
                    <span class="badge badge-inline px-2.5 py-1 text-white fw-600" style="background: #3b1721; border-radius: 999px;">
                        {{ ($user->member && $user->member->package) ? $user->member->package->name : translate('Free Member') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Danger Zone Card -->
        <div class="danger-zone-card mb-4">
            <div class="danger-zone-header d-flex align-items-center">
                <div class="mr-2.5 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #fee2e2; color: #dc2626;">
                    <i class="las la-exclamation-triangle fs-16"></i>
                </div>
                <h5 class="fw-700 fs-15 mb-0 text-danger">{{ translate('Account Status & Actions') }}</h5>
            </div>
            <div class="p-4 fs-13">
                <!-- Deactivate Account -->
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fw-600 text-dark">{{ translate('Deactivate Account') }}</span>
                        @if($is_deactivated)
                            <span class="badge badge-warning">{{ translate('Deactivated') }}</span>
                        @else
                            <span class="badge badge-success">{{ translate('Active') }}</span>
                        @endif
                    </div>
                    <p class="text-muted fs-12 mb-2">
                        {{ translate('Temporarily hide your profile from search results while preserving your biodata and messages.') }}
                    </p>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 font-weight-600 fs-12" onclick="openDeactivateModal()">
                        @if($is_deactivated)
                            <i class="las la-redo mr-1"></i> {{ translate('Reactivate Account') }}
                        @else
                            <i class="las la-pause-circle mr-1"></i> {{ translate('Deactivate Account') }}
                        @endif
                    </button>
                </div>

                <!-- Delete Account -->
                <div>
                    <div class="fw-600 text-dark mb-1">{{ translate('Delete Account') }}</div>
                    <p class="text-muted fs-12 mb-2">
                        {{ translate('Permanently delete your account and remove all personal biodata from our platform.') }}
                    </p>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 font-weight-600 fs-12" onclick="openDeleteAccountModal()">
                        <i class="las la-trash-alt mr-1"></i> {{ translate('Delete Account') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Modal -->
<div class="modal fade" id="deactivateAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-2xl border-0 overflow-hidden shadow-lg">
            <div class="modal-header bg-warning text-dark py-3 px-4 border-0">
                <h5 class="modal-title font-serif fw-700 fs-16 mb-0">
                    <i class="las la-pause-circle mr-1"></i>
                    {{ $is_deactivated ? translate('Reactivate Account') : translate('Deactivate Account') }}
                </h5>
                <button type="button" class="close p-0 m-0 text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.account_deactivation') }}" method="POST">
                @csrf
                <input type="hidden" name="deacticvation_status" value="{{ $is_deactivated ? 0 : 1 }}">
                <div class="modal-body p-4 text-center">
                    <p class="mb-0 fs-14 text-dark">
                        @if($is_deactivated)
                            {{ translate('Are you sure you want to reactivate your account? Your profile will become visible again to potential matches.') }}
                        @else
                            {{ translate('Are you sure you want to deactivate your account? Your profile will be temporarily hidden until you log back in.') }}
                        @endif
                    </p>
                </div>
                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-end border-0">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-warning rounded-pill px-4 fw-600">
                        {{ $is_deactivated ? translate('Confirm Reactivation') : translate('Confirm Deactivation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-2xl border-0 overflow-hidden shadow-lg">
            <div class="modal-header bg-danger text-white py-3 px-4 border-0">
                <h5 class="modal-title font-serif fw-700 fs-16 mb-0">
                    <i class="las la-exclamation-circle mr-1"></i> {{ translate('Delete Account Confirmation') }}
                </h5>
                <button type="button" class="close p-0 m-0 text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('member.account_delete') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 54px; height: 54px; background: #fee2e2; color: #dc2626;">
                        <i class="las la-trash-alt fs-24"></i>
                    </div>
                    <h6 class="fw-700 text-danger mb-2">{{ translate('This action is permanent and cannot be undone!') }}</h6>
                    <p class="text-muted fs-13 mb-0">
                        {{ translate('All your biodata, photos, saved matches, and chat conversations will be permanently deleted from our servers.') }}
                    </p>
                </div>
                <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-end border-0">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4 fw-600">
                        {{ translate('Yes, Delete My Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('lar', 'la-eye');
            icon.classList.add('las', 'la-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('las', 'la-eye-slash');
            icon.classList.add('lar', 'la-eye');
        }
    }

    function validatePasswordMatch() {
        var newPass = $('#new_password').val();
        var confirmPass = $('#confirm_password').val();
        var feedback = $('#password_match_feedback');
        var btn = $('#passSaveBtn');

        if (!newPass && !confirmPass) {
            feedback.hide();
            btn.prop('disabled', true);
            return;
        }

        feedback.show();

        if (newPass.length < 8) {
            feedback.html('<span class="text-danger"><i class="las la-times-circle"></i> {{ translate("Password must be at least 8 characters long") }}</span>');
            btn.prop('disabled', true);
            return;
        }

        if (confirmPass.length === 0) {
            feedback.html('<span class="text-muted"><i class="las la-info-circle"></i> {{ translate("Please confirm your new password") }}</span>');
            btn.prop('disabled', true);
            return;
        }

        if (newPass === confirmPass) {
            feedback.html('<span class="text-success fw-600"><i class="las la-check-circle"></i> {{ translate("Passwords match perfectly") }}</span>');
            btn.prop('disabled', false);
        } else {
            feedback.html('<span class="text-danger"><i class="las la-times-circle"></i> {{ translate("Passwords do not match") }}</span>');
            btn.prop('disabled', true);
        }
    }

    function openDeactivateModal() {
        $('#deactivateAccountModal').modal('show');
    }

    function openDeleteAccountModal() {
        $('#deleteAccountModal').modal('show');
    }
</script>
@endsection
