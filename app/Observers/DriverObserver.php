<?php

namespace App\Observers;

use App\Enums\DriverStatus;
use App\Models\Driver;

class DriverObserver
{
    public function creating(Driver $driver): void
    {
        $driver->status = DriverStatus::UNDER_REVIEW;
    }
}
