<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    private $title;
    private $body;
    private $clickAction;
    private $image;
    private $icon;
    private $additionalData;
    private $sound;
    private $priority = 'normal';

    public function withTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function withBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function withClickAction($clickAction)
    {
        $this->clickAction = $clickAction;
        return $this;
    }

    public function withImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function withIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function withSound($sound)
    {
        $this->sound = $sound;
        return $this;
    }

    public function withPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function withAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    protected function getMessaging()
    {
        $fileName = get_setting('firebase_service_account_file');

        if (!$fileName || !Storage::disk('firebase')->exists($fileName)) {
            Log::warning('Firebase service account file not configured.');
            return null;
        }

        $path = Storage::disk('firebase')->path($fileName);

        try {
            $factory = (new Factory())->withServiceAccount($path);
            return $factory->createMessaging();
        } catch (\Throwable $e) {
            Log::error('Firebase Messaging init failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Works like Larafirebase::sendMessage() -- data-only message
     */
    public function sendMessage($tokens)
    {
        $messaging = $this->getMessaging();
        if (!$messaging) {
            return null;
        }

        $tokens = $this->normalizeTokens($tokens);
        if (empty($tokens)) {
            return null;
        }

        $data = [
            'title' => (string) ($this->title ?? ''),
            'body' => (string) ($this->body ?? ''),
        ];

        if ($this->additionalData) {
            $data = array_merge($data, $this->stringifyData($this->additionalData));
        }

        try {
            $message = CloudMessage::new()->withData($data);

            if (count($tokens) === 1) {
                $message = $message->withChangedTarget('token', $tokens[0]);
                return $messaging->send($message);
            }

            return $messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::error('FCM sendMessage failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Works like Larafirebase::sendNotification() -- notification + data
     */
    public function sendNotification($tokens)
    {
        $messaging = $this->getMessaging();
        if (!$messaging) {
            return null;
        }

        $tokens = $this->normalizeTokens($tokens);
        if (empty($tokens)) {
            return null;
        }

        try {
            $message = CloudMessage::new()
                ->withNotification(FirebaseNotification::create(
                    (string) ($this->title ?? ''),
                    (string) ($this->body ?? '')
                ));

            if ($this->additionalData) {
                $message = $message->withData($this->stringifyData($this->additionalData));
            }

            if (count($tokens) === 1) {
                $message = $message->withChangedTarget('token', $tokens[0]);
                return $messaging->send($message);
            }

            return $messaging->sendMulticast($message, $tokens);
        } catch (\Throwable $e) {
            Log::error('FCM sendNotification failed: ' . $e->getMessage());
            return null;
        }
    }

    private function normalizeTokens($tokens)
    {
        if (is_string($tokens)) {
            $tokens = explode(',', $tokens);
        }

        if (!is_array($tokens)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $tokens)));
    }

    private function stringifyData($data)
    {
        return array_map(function ($value) {
            return is_array($value) ? json_encode($value) : (string) $value;
        }, $data);
    }
}