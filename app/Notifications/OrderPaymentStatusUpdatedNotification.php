<?php

namespace App\Notifications;

use App\Models\Order;
use App\Support\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class OrderPaymentStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return [PushChannel::class];
    }

    public function toPush(object $notifiable): PushNotification
    {
        return new PushNotification(
            title: trans_choice('notifications.order-payment-status-updated.title', [
                'status' => Str::lower($this->order->payment_status->label()),
            ]),
            body: trans_choice('notifications.order-payment-status-updated.body', [
                'status' => Str::lower($this->order->payment_status->label()),
            ]),
        );
    }
}
