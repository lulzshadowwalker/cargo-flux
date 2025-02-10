<?php

namespace App\Livewire\Website;

use App\Models\Order;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class OrderTrackingButton extends Component
{
    public ?string $orderNumber = null;
    public ?Order $order = null;

    public function trackOrder()
    {
        if (! $this->orderNumber) return;

        $this->order = Order::whereNumber($this->orderNumber)->first();

        if (!$this->order) {
            $this->dispatch('warning', ['message' => __('website/order-tracking.order-not-found')]);
            return;
        }

        Session::flash('order', $this->order->number);


        // TODO: Handle order not found
    }

    public function clear()
    {
        $this->orderNumber = null;
        $this->order = null;
    }

    public function render()
    {
        $this->order = \App\Models\Order::first();
        return view(
            'livewire.website.order-tracking-button',
            [
                'stages' => $this->order?->stages,
            ]
        );
    }
}
