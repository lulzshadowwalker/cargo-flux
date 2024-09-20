<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Filters\OrderFilter;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends ApiController
{
    public function index(OrderFilter $filters)
    {
        $user = Auth::user();
        $relation = $user->isCustomer ? 'customer' : 'driver';

        $orders = $user->$relation->orders()->filter($filters)->get();

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        $details = $request->mappedAttributes();
        if (OrderPaymentMethod::tryFrom($details['payment_method']) === OrderPaymentMethod::ONLINE) {
            $details['payment_status'] = OrderPaymentStatus::APPROVED;
            $details['status'] = OrderStatus::PENDING_DRIVER_ASSIGNMENT;
        }

        $order = Order::create($details->toArray())->refresh()->load('customer', 'truck', 'driver', 'reviews');

        return OrderResource::make($order);
    }

    public function show(string $language, Order $order)
    {
        $this->authorize('view', $order);

        $includes = ['customer', 'driver', 'truck', 'reviews'];
        foreach ($includes as $include) {
            if ($this->include($include)) {
                $order->load($include);
            }
        }

        return OrderResource::make($order);
    }

    public function update(string $language, UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->update($request->mappedAttributes()->toArray());

        return OrderResource::make($order);
    }
}
