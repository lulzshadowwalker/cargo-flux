<?php

namespace App\Listeners;

use App\Enums\DriverStatus;
use App\Enums\OrderStatus;
use App\Enums\UserStatus;
use App\Events\OrderPlaced;
use App\Events\OrderStatusUpdated;
use App\Models\Driver;
use App\Notifications\DriverOfferNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyDriversOfNewOffer implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(OrderStatusUpdated $event): void
    {
        if ($event->order->status !== OrderStatus::PENDING_DRIVER_ASSIGNMENT) {
            return;
        }

        $requestedCategory = $event->order->truckCategory;

        // TODO: Might want to use a global scope for this.
        $drivers = Driver::whereStatus(DriverStatus::APPROVED)
            ->whereHas('user', fn($query) => $query->whereStatus(UserStatus::ACTIVE))
            ->whereHas('truck', fn($query) => $query->whereTruckCategoryId($requestedCategory->id))
            ->get();

        Notification::send($drivers, new DriverOfferNotification($event->order));
    }
}
