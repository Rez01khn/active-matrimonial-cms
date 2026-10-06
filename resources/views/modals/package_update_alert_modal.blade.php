<div class="modal fade package_update_alert_modal" id="modal-zoom">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content package_update_alert_modal_content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(197, 160, 89, 0.15);">
                    <i class="las la-crown la-2x" style="color: #C5A059;"></i>
                </div>
                <h4 class="modal-title h5 font-weight-bold mb-2 text-dark" id="package_alert_title">{{translate('Upgrade to Premium')}}</h4>
                <p class="text-muted fs-14 mb-4" id="package_alert_text">{{translate('Please upgrade to a premium package to send matchmaking proposals and view complete biodata.')}}</p>
                <div class="d-flex justify-content-center" style="gap: 12px;">
                    <button type="button" class="btn btn-light px-4 py-2" data-dismiss="modal" style="border-radius: 8px; font-weight: 500;">{{translate('Cancel')}}</button>
                    <a href="{{ route('packages') }}" class="btn text-white px-4 py-2" style="background-color: #800020; border-radius: 8px; font-weight: 600;">{{translate('View Membership Plans')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
