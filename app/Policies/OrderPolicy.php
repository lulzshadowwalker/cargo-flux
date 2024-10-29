<?php

namespace App\Policies;

use App\Enums\OrderPaymentStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        $relation = $user->isCustomer ? 'customer' : 'driver';
        return $user->$relation->id === $order->{$relation . '_id'};
    }

    public function create(User $user): bool
    {
        return $user->isCustomer;
    }

    public function update(User $user, Order $order): bool
    {
        $isAssignedDriver =  $user->isDriver && $user->driver->id === $order->driver_id;
        return $isAssignedDriver || $user->isAdmin;
    }

    public function pay(User $user, Order $order): bool
    {
        $isOwner = $user->customer?->id === $order->customer_id;

        return $user->isCustomer && $isOwner &&
            in_array($order->payment_status, [OrderPaymentStatus::UNPAID, OrderPaymentStatus::REJECTED]);
    }
}
