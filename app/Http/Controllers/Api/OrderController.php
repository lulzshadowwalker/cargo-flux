<?php

namespace App\Http\Controllers\Api;

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
        if (Auth::user()->isCustomer) {
            $orders = Auth::user()->customer->orders()->filter($filters)->get();
        } else {
            $orders = Auth::user()->driver->orders()->filter($filters)->get();
        }

        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        //
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

    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    public function destroy(Order $order)
    {
        //
    }
}
