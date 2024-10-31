<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OfferController extends ApiController
{
    public function index()
    {
        $this->authorize('view-offers', Order::class);

        $offers = Order::where('status', OrderStatus::PENDING_DRIVER_ASSIGNMENT)
            ->where('truck_category_id', Auth::user()->driver->truck->truck_category_id)
            ->get();

        return OrderResource::collection($offers);
    }

    public function accept(string $language, Order $order)
    {
        $this->authorize('accept-offer', $order);

        $order->update([ 'status' => OrderStatus::DRIVER_ASSIGNED ]);

        $order->driver()->associate(Auth::user()->driver)->save();

        return OrderResource::make($order);
    }
}
