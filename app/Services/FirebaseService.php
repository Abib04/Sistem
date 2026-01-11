<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Exception;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = config('services.firebase.credentials');

            if (!file_exists($credentialsPath)) {
                throw new Exception("Firebase credentials file not found at {$credentialsPath}");
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } catch (Exception $e) {
            \Log::error('Firebase Initialization Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send push notification to single device
     */
    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            return true;
        } catch (Exception $e) {
            \Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendMulticast(array $tokens, $title, $body, $data = [])
    {
        try {
            if (empty($tokens)) {
                return false;
            }

            $notification = Notification::create($title, $body);

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->sendMulticast($message, $tokens);
            return true;
        } catch (Exception $e) {
            \Log::error('Firebase multicast error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to all subscribed users
     */
    public function sendToAll($title, $body, $data = [])
    {
        try {
            $tokens = \App\Models\Client::whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($tokens)) {
                return false;
            }

            return $this->sendMulticast($tokens, $title, $body, $data);
        } catch (Exception $e) {
            \Log::error('Firebase send to all error: ' . $e->getMessage());
            return false;
        }
    }
}
