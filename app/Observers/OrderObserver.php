<?php

namespace App\Observers;

use App\Enums\OrderPaymentMethod;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class OrderObserver
{
    public function creating(Order $order)
    {
        $order->number = strtoupper(uniqid('ORDER-'));
    }

    public function created(Order $order): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__('notifications.order-created.title', ['payment-method' => $order->payment_method->label()]))
            ->actions([
                Action::make('go-to-order')
                    ->button()
                    ->label(__('notifications.order-created.view-order'))

                    // TODO: Add the edit order route below
                    // ->url(OrderResource::getUrl('edit', ['record' => $order]))
            ])
            ->icon('heroicon-o-ticket')
            ->sendToDatabase($admins);
    }
}
