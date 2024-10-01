<?php

namespace App\Notifications;

use App\Filament\Resources\OrderResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class AdminPendingOrderNotification extends Notification
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
            ->line(__('notifications.admin-pending-order.introduction'))
            ->action(__('notifications.admin-pending-order.view-order'), OrderResource::getUrl('edit', ['record' => $this->order]))
            ->line(__('notifications.admin-pending-order.ending'));
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('notifications.admin-pending-order.introduction'))
            ->actions([
                Action::make('see-pending-order')
                    ->label(__('notifications.admin-pending-order.view-order'))
                    ->url(OrderResource::getUrl('edit', ['record' => $this->order]))
                    ->button(),
            ])
            ->getDatabaseMessage();
    }
}
