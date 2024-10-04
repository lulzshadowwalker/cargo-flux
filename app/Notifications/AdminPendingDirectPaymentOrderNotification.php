<?php

namespace App\Notifications;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class AdminPendingDirectPaymentOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(__('notifications.pending-direct-payment-order.title'), ['since' => $this->order->created_at->diffForHumans()])
            ->action(__('notifications.order-created.view-order'), OrderResource::getUrl('edit', ['record' => $this->order]));
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('notifications.pending-direct-payment-order.title'), ['since' => $this->order->created_at->diffForHumans()])
            ->actions([
                Action::make('go-to-order')
                    ->button()
                    ->label(__('notifications.pending-direct-payment-order'))
                    ->url(OrderResource::getUrl('edit', ['record' => $this->order]))
            ])
            ->icon(OrderResource::getNavigationIcon())
            ->getDatabaseMessage();
    }
}
