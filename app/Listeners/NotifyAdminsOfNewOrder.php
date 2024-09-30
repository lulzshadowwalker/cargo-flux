<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Filament\Resources\OrderResource;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminsOfNewOrder implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPlaced $event): void
    {
        $admins = User::admins()->get();

        Notification::make()
            ->title(__('notifications.order-created.title', ['payment-method' => $event->order->payment_method->label()]))
            ->actions([
                Action::make('go-to-order')
                    ->button()
                    ->label(__('notifications.order-created.view-order'))
                    ->url(OrderResource::getUrl('edit', ['record' => $event->order]))
            ])
            ->icon(OrderResource::getNavigationIcon())
            ->sendToDatabase($admins);
    }
}
