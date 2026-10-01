<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseMessagingNotification;

class FirbaseNotification
{
    protected static function getMessaging()
    {
        $fileName = get_setting('firebase_service_account_file');

        if (!$fileName || !Storage::disk('firebase')->exists($fileName)) {
            Log::warning('Firebase service account file not configured.');
            return null;
        }

        $path = Storage::disk('firebase')->path($fileName);

        try {
            return (new Factory())->withServiceAccount($path)->createMessaging();
        } catch (\Throwable $e) {
            Log::error('Firebase Messaging init failed: ' . $e->getMessage());
            return null;
        }
    }

    public static function send($data)
    {
        $messaging = self::getMessaging();
        if (!$messaging || empty($data->fcm_token)) {
            return;
        }

        try {
            $message = CloudMessage::withTarget('token', $data->fcm_token)
                ->withNotification(FirebaseMessagingNotification::create(
                    str_replace("_", " ", $data->title),
                    $data->text
                ))
                ->withData([
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'route' => (string) $data->title,
                    'notify_by' => (string) ($data->notify_by ?? ''),
                ]);

            $messaging->send($message);
        } catch (\Throwable $e) {
            Log::error('FCM send failed: ' . $e->getMessage());
        }
    }
}