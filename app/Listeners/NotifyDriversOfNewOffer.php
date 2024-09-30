<?php

namespace App\Listeners;

use App\Enums\DriverStatus;
use App\Enums\UserStatus;
use App\Events\OrderPlaced;
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

    public function handle(OrderPlaced $event): void
    {
        $requestedCategory = $event->order->truckCategory;

        // TODO: Might want to use a global scope for this.
        $drivers = Driver::whereStatus(DriverStatus::APPROVED)
            ->whereHas('user', fn($query) => $query->whereStatus(UserStatus::ACTIVE))
            ->whereHas('truck', fn($query) => $query->whereTruckCategoryId($requestedCategory->id))
            ->get();

        Notification::send($drivers, new DriverOfferNotification($event->order));
    }
}
