<?php

namespace App\Services\FirebasePushNotification;

use App\Contracts\NotificationStrategy;
use App\Enums\Audience;
use App\InteractsWithFirebase;
use App\Support\PushNotification;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AudienceNotificationStrategy implements NotificationStrategy
{
    use InteractsWithFirebase;

    /**
     * @param PushNotification $notification
     * @param Audience $notifiable
     * 
     * @throws Exception
     * 
     * @return void
     */
    public static function send(PushNotification $notification, $audience): void
    {
        if (!is_array($audience)) $audience = [$audience];

        foreach ($audience as $a) {
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . self::firebaseAccessToken()])
                ->post(self::endpoint('messages:send'), [
                    'message' => [
                        'data' => (object) [],
                        'notification' => [
                            'title' => $notification->title,
                            'body' => $notification->body,
                            'image' => $notification->image,
                        ],
                        'topic' => $a->value,
                    ],
                ]);

            if ($response->ok()) return;

            Log::error('Failed to send notification', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new Exception('Failed to send notification');
        }
    }

    public static function isSatisfiedBy(mixed $notifiable): bool
    {
        if (! $notifiable) return false;

        $n = $notifiable;
        if (is_array($notifiable)) $n = $notifiable[0];

        return (bool) Audience::tryFrom($n->value);
    }
}
