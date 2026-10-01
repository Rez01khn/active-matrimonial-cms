@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Firebase Push Notification') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('settings.fcm.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('Activation')}}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="firebase_push_notification" type="checkbox" @if (get_setting('firebase_push_notification') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_API_KEY">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM API KEY') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_API_KEY"
                                    value="{{ env('FCM_API_KEY') }}" placeholder="{{ translate('FCM API KEY') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_AUTH_DOMAIN">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM AUTH DOMAIN') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_AUTH_DOMAIN"
                                    value="{{ env('FCM_AUTH_DOMAIN') }}" placeholder="{{ translate('FCM AUTH DOMAIN') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_PROJECT_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM PROJECT ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_PROJECT_ID"
                                    value="{{ env('FCM_PROJECT_ID') }}" placeholder="{{ translate('FCM PROJECT ID') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_STORAGE_BUCKET">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM STORAGE BUCKET') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_STORAGE_BUCKET"
                                    value="{{ env('FCM_STORAGE_BUCKET') }}"
                                    placeholder="{{ translate('FCM STORAGE BUCKET') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_MESSAGING_SENDER_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM MESSAGING SENDER ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_MESSAGING_SENDER_ID"
                                    value="{{ env('FCM_MESSAGING_SENDER_ID') }}"
                                    placeholder="{{ translate('FCM MESSAGING SENDER ID') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_APP_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM APP ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_APP_ID"
                                    value="{{ env('FCM_APP_ID') }}" placeholder="{{ translate('FCM APP ID') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('Firebase Service Account JSON') }}</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <label for="firebase-json-input" class="input-group-text bg-soft-secondary font-weight-medium mb-0" style="cursor:pointer;">
                                            {{ translate('Browse') }}
                                        </label>
                                    </div>
                                    <div class="form-control file-amount" id="firebase-file-name">
                                        @if(get_setting('firebase_service_account_file'))
                                            {{ translate('1 File selected') }}
                                        @else
                                            {{ translate('Choose File') }}
                                        @endif
                                    </div>
                                    <input
                                        type="file"
                                        id="firebase-json-input"
                                        name="firebase_service_account"
                                        accept=".json,application/json"
                                        style="display:none;"
                                    >
                                </div>

                                <div class="file-preview" id="firebase-file-preview">
                                    @if(get_setting('firebase_service_account_file'))
                                        <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item"
                                            data-existing="1"
                                            title="{{ get_setting('firebase_service_account_file') }}">
                                            <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                                <i class="la la-file-text"></i>
                                            </div>
                                            <div class="col body">
                                                <h6 class="d-flex">
                                                    <span class="text-truncate title">{{ pathinfo(get_setting('firebase_service_account_file'), PATHINFO_FILENAME) }}</span>
                                                    <span class="ext">.json</span>
                                                </h6>
                                                <p>{{ \Illuminate\Support\Facades\Storage::disk('firebase')->exists(get_setting('firebase_service_account_file')) ? number_format(\Illuminate\Support\Facades\Storage::disk('firebase')->size(get_setting('firebase_service_account_file')) / 1024, 1) . ' KB' : '' }}</p>
                                            </div>
                                            <div class="remove">
                                                <button class="btn btn-sm btn-link remove-firebase-attachment" type="button">
                                                    <i class="la la-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <input type="hidden" name="remove_firebase_file" id="remove_firebase_file" value="0">

                                <small class="text-muted d-block mt-1">
                                    {{ translate('Upload the service_account.json file downloaded from Firebase Console → Project Settings → Service Accounts') }}
                                </small>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ translate('Please be carefull when you are configuring Firebase Push Notification.') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            {{ translate("1. Log in to Google Firebase and Create a new app if you don't have any") }}.</li>
                        <li class="list-group-item text-dark">
                            {{ translate('2. Go to Project Settings and select General tab') }}.</li>
                        <li class="list-group-item text-dark">
                            {{ translate('3. Select Config and you will find Firebase Config Credentials') }}.
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate("4. Copy your App's Credentials and paste the Credentials into appropriate fields") }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('5. Now, select Cloud Messaging tab and Enable Cloud Messaging API (V1)') }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('6. Go to Project Settings and select Service Accounts tab') }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('7. Click on "Generate new private key" button. A JSON file will be downloaded to your computer') }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('8. Upload that downloaded JSON file into the "Firebase Service Account JSON" field below') }}
                        </li>
                        <li class="list-group-item text-danger">
                            {{ translate('Note: Google has deprecated the old Server Key based (Legacy) API. You must use the Service Account JSON (FCM HTTP v1) method shown above.') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function bytesToSize(bytes) {
            if (bytes === 0) return '0 KB';
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
        }

        document.getElementById('firebase-json-input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const nameBox = document.getElementById('firebase-file-name');
            const preview = document.getElementById('firebase-file-preview');
            const removeFlag = document.getElementById('remove_firebase_file');

            if (!file) return;

            if (!file.name.endsWith('.json')) {
                alert('{{ translate("Please select a valid .json file") }}');
                e.target.value = '';
                return;
            }

            nameBox.textContent = '1 File selected';
            removeFlag.value = '0';

            var fileNameOnly = file.name.replace(/\.json$/i, '');

            preview.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-existing="0" title="${file.name}">
                    <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                        <i class="la la-file-text"></i>
                    </div>
                    <div class="col body">
                        <h6 class="d-flex">
                            <span class="text-truncate title">${fileNameOnly}</span>
                            <span class="ext">.json</span>
                        </h6>
                        <p>${bytesToSize(file.size)}</p>
                    </div>
                    <div class="remove">
                        <button class="btn btn-sm btn-link remove-firebase-attachment" type="button">
                            <i class="la la-close"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        $(document).on('click', '#firebase-file-preview .remove-firebase-attachment', function () {
            const item = $(this).closest('.file-preview-item');
            const nameBox = document.getElementById('firebase-file-name');
            const fileInput = document.getElementById('firebase-json-input');
            const removeFlag = document.getElementById('remove_firebase_file');

            if (item.data('existing') == 1) {
                removeFlag.value = '1';
            }

            fileInput.value = '';
            nameBox.textContent = '{{ translate("Choose File") }}';
            item.remove();
        });
    </script>
@endsection