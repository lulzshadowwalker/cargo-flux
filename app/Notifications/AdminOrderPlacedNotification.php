<?php

namespace App\Notifications;

use App\Enums\OrderPaymentMethod;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class AdminOrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    public function via(object $notifiable): array
    {
        if ($this->order->payment_method == OrderPaymentMethod::DIRECT) {
            return ['mail', 'database'];
        }

        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line(__('notifications.order-created.title', ['payment-method' => $this->order->payment_method->label()]))
            ->action(__('notifications.order-created.view-order'), OrderResource::getUrl('edit', ['record' => $this->order]));
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title(__('notifications.order-created.title', ['payment-method' => $this->order->payment_method->label()]))
            ->actions([
                Action::make('go-to-order')
                    ->button()
                    ->label(__('notifications.order-created.view-order'))
                    ->url(OrderResource::getUrl('edit', ['record' => $this->order]))
            ])
            ->icon(OrderResource::getNavigationIcon())
            ->getDatabaseMessage();
    }
}
