<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Artisan;
use MehediIitdu\CoreComponentRepository\CoreComponentRepository;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_profile_sections'])->only('member_profile_sections_configuration');
        $this->middleware(['permission:header'])->only('website_header_settings');
        $this->middleware(['permission:footer'])->only('website_footer_settings');
        $this->middleware(['permission:appearances'])->only('website_appearances');
        $this->middleware(['permission:general_settings'])->only('general_settings');
        $this->middleware(['permission:payment_method_settings'])->only('payment_method_settings');
        $this->middleware(['permission:smtp_settings'])->only('smtp_settings');
        $this->middleware(['permission:third_party_settings'])->only('third_party_settings');
        $this->middleware(['permission:social_media_login_settings'])->only('social_media_login_settings');
        $this->middleware(['permission:system_update'])->only('system_update');
        $this->middleware(['permission:server_status'])->only('system_server');
        $this->middleware(['permission:firebase_push_notification'])->only('fcm_settings');
        $this->middleware(['permission:manage_member_verification_form'])->only('member_verification_form');
    }

    public function general_settings()
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        return view('admin.settings.general_settings');
    }

    public function smtp_settings()
    {
        return view('admin.settings.smtp_settings');
    }

    public function payment_method_settings()
    {
        CoreComponentRepository::instantiateShopRepository();
        CoreComponentRepository::initializeCache();
        return view('admin.settings.payment_method_settings');
    }

    public function third_party_settings(){
        return view('admin.settings.third_party_settings');
    }

    public function member_profile_sections_configuration ()
    {
        return view('admin.member_profile_attributes.member_profile_sections.index');
    }

    public function member_verification_form(){
        return view('admin.members.member_verification_form');
    }

    public function member_verification_form_update(Request $request){
        $form = array();
        $select_types = ['select', 'multi_select', 'radio'];
        $j = 0;
        for ($i=0; $i < count($request->type); $i++) {
            $item['type'] = $request->type[$i];
            $item['label'] = $request->label[$i];
            if(in_array($request->type[$i], $select_types)){
                $item['options'] = json_encode($request['options_'.$request->option[$j]]);
                $j++;
            }
            array_push($form, $item);
        }
        $business_settings = Setting::where('type', 'verification_form')->first();
        $business_settings->value = json_encode($form);
        if($business_settings->save()){
            Artisan::call('cache:clear');
            
            flash(translate("Verification form updated successfully"))->success();
            return back();
        }
    }

    public function social_media_login_settings(){
        return view('admin.settings.social_media_login');
    }

    public function website_header_settings()
    {
        return view('admin.website_settings.header');
    }

    public function website_footer_settings()
    {
      return view('admin.website_settings.footer');
    }

    public function website_appearances()
    {
      return view('admin.website_settings.appearances');
    }

    public function system_update()
    {
      return view('admin.system.update');
    }
    public function system_server()
    {
      return view('admin.system.server_status');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
     {
        foreach ($request->types as $key => $type) {
           if($type == 'site_name'){
                $this->overWriteEnvFile('APP_NAME', $request[$type]);
            }

            if($type == 'timezone'){
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            }
            else {
                $settings = Setting::where('type', $type)->first();
                if($settings != null){
                    if(gettype($request[$type]) == 'array'){
                        $settings->value = json_encode($request[$type]);
                    }
                    else {
                        $settings->value = $request[$type];
                    }
                    $settings->save();
                }
                else{
                    $settings = new Setting;
                    $settings->type = $type;
                    if(gettype($request[$type]) == 'array'){
                        $settings->value = json_encode($request[$type]);
                    }
                    else {
                        $settings->value = $request[$type];
                    }
                    $settings->save();
                }
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
     }


    public function payment_method_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $payemnt_sandbox = Setting::where('type', $request->payment_method.'_sandbox')->first();
        if($payemnt_sandbox != null){
            if ($request->has($request->payment_method.'_sandbox')) {
                $payemnt_sandbox->value = 1;
                $payemnt_sandbox->save();
            }
            else{
                $payemnt_sandbox->value = 0;
                $payemnt_sandbox->save();
            }
        }

          // Save phonepe_version to settings
       if ($request->has('phonepe_version')) {
            $phonepeVersion = Setting::where('type', 'phonepe_version')->first();
            if ($phonepeVersion) {
                $phonepeVersion->value = $request->phonepe_version;
                $phonepeVersion->save();
            } else {
                $newSetting = new Setting();
                $newSetting->type = 'phonepe_version';
                $newSetting->value = $request->phonepe_version;
                $newSetting->save();
            }
        }
        $payemnt_activation = Setting::where('type', $request->payment_method.'_payment_activation')->first();
        if( $payemnt_activation == null){
            $payemnt_activation =  new Setting;
            $payemnt_activation->type = $request->payment_method.'_payment_activation';
            $payemnt_activation->save();
        }

        if ($request->has($request->payment_method.'_payment_activation'))
        {
            $payemnt_activation->value = 1;
            $payemnt_activation->save();
        }
        else
        {
            $payemnt_activation->value = 0;
            $payemnt_activation->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function third_party_settings_update(Request $request)
    {
      foreach ($request->types as $key => $type) {
          $this->overWriteEnvFile($type, $request[$type]);
      }

      $activation = Setting::where('type', $request->setting_type.'_activation')->first();
      if($activation != null){
          if ($request->has($request->setting_type.'_activation')) {
              $activation->value = 1;
              $activation->save();
          }
          else{
              $activation->value = 0;
              $activation->save();
          }
      }

      Artisan::call('cache:clear');

      flash(translate("Settings updated successfully"))->success();
      return back();
    }


     public function env_key_update(Request $request)
     {
         foreach ($request->types as $key => $type) {
             $this->overWriteEnvFile($type, $request[$type]);
         }
         flash(translate("Settings has been updated successfully"))->success();
         return back();
     }

     public function overWriteEnvFile($type, $val)
     {  
        if(env('DEMO_MODE') != 'On'){
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                    file_put_contents($path, str_replace(
                        $type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)
                    ));
                } else {
                    file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
                }
            }
        }
     }

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $settings = Setting::where('type', $request->type)->first();
        if($settings!=null){

            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if(env('DEMO_MODE') != 'On'){
                    Artisan::call('down');
                }
            }
            elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if(env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            }

            $settings->value = $request->value;
            $settings->save();
        }
        else {
        $settings = new Setting;
        $settings->type = $request->type;
        $settings->value = $request->value;
        $settings->save();
        }

        
    Artisan::call('cache:clear');
    return 1;
    }



    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if(strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }

        }
        elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if(strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }

        }

        return '1';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fcm_settings(){
        return view('admin.settings.google_configurations.fcm');
    }
    
    public function fcm_settings_update(Request $request){
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }
        $settings = Setting::where('type', 'firebase_push_notification')->first();
        if($settings){
            $settings->value = $request->has('firebase_push_notification') ? 1 : 0;
            $settings->save();
        } else {
            $settings = new Setting();
            $settings->type = 'firebase_push_notification';
            $settings->value = $request->has('firebase_push_notification') ? 1 : 0;
            $settings->save();
        }

        if ($request->hasFile('firebase_service_account')) {
            $request->validate([
                'firebase_service_account' => 'file|mimetypes:application/json,text/plain|max:2048',
            ]);

            $file = $request->file('firebase_service_account');

            $content = json_decode(file_get_contents($file->getRealPath()), true);
            if (json_last_error() !== JSON_ERROR_NONE || !isset($content['type']) || $content['type'] !== 'service_account') {
                flash(translate('Invalid Firebase Service Account JSON file'))->error();
                return back();
            }

            $oldFile = Setting::where('type', 'firebase_service_account_file')->first();
            if ($oldFile && $oldFile->value && \Storage::disk('firebase')->exists($oldFile->value)) {
                \Storage::disk('firebase')->delete($oldFile->value);
            }

            $fileName = 'firebase_credentials_' . uniqid() . '.json';
            $file->storeAs('', $fileName, 'firebase'); 

            $fileSetting = Setting::where('type', 'firebase_service_account_file')->first();
            if ($fileSetting) {
                $fileSetting->value = $fileName;
                $fileSetting->save();
            } else {
                $fileSetting = new Setting();
                $fileSetting->type = 'firebase_service_account_file';
                $fileSetting->value = $fileName;
                $fileSetting->save();
            }
        } elseif ($request->remove_firebase_file == '1') {
            $oldFile = Setting::where('type', 'firebase_service_account_file')->first();
            if ($oldFile && $oldFile->value) {
                if (\Storage::disk('firebase')->exists($oldFile->value)) {
                    \Storage::disk('firebase')->delete($oldFile->value);
                }
                $oldFile->value = null;
                $oldFile->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function fcm_service_worker()
    {
        $config = [
            'apiKey' => config('larafirebase.api_key'),
            'authDomain' => config('larafirebase.auth_domain'),
            'projectId' => config('larafirebase.project_id'),
            'storageBucket' => config('larafirebase.storage_bucket'),
            'messagingSenderId' => config('larafirebase.messaging_sender_id'),
            'appId' => config('larafirebase.app_id'),
        ];

        $content = 'importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");' . PHP_EOL;
        $content .= 'importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");' . PHP_EOL . PHP_EOL;
        $content .= 'firebase.initializeApp(' . json_encode($config, JSON_PRETTY_PRINT) . ');' . PHP_EOL . PHP_EOL;
        $content .= 'const messaging = firebase.messaging();' . PHP_EOL;
        $content .= 'messaging.setBackgroundMessageHandler(function ({ data: { title, body, icon } }) {' . PHP_EOL;
        $content .= '    return self.registration.showNotification(title, { body, icon });' . PHP_EOL;
        $content .= '});' . PHP_EOL;

        return response($content, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Service-Worker-Allowed', '/');
    }
}
