<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        // TODO: Cleanup
        if ($user->isCustomer) {
            return $user->customer->id === $order->customer_id;
        }

        if ($user->isDriver) {
            return $user->driver->id === $order->driver_id;
        }

        return false;
    }
}
