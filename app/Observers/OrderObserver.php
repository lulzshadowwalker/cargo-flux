<?php

namespace App\Observers;

use App\Enums\OrderPaymentMethod;
use App\Events\OrderScheduleUpdated;
use App\Events\OrderStatusUpdated;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;

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
                    ->url(OrderResource::getUrl('edit', ['record' => $order]))
            ])
            ->icon(OrderResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            OrderStatusUpdated::dispatch($order, Auth::user());
        }

        if ($order->isDirty('scheduled_at')) {
            OrderScheduleUpdated::dispatch($order, Auth::user());
        }
    }
}
