<?php

namespace Tests\Unit\Models;

use App\Enums\OrderPaymentStatus;
use App\Events\OrderPaymentStatusUpdated;
use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_order_number()
    {
        $order = Order::factory()->create();

        $this->assertStringStartsWith('ORDER-', $order->number);
    }

    public function test_it_emits_order_placed_event_when_created()
    {
        Event::fake(OrderPlaced::class);

        Order::factory()->create(['number' => 'ORDER-1234']);

        Event::assertDispatched(OrderPlaced::class);
    }

    public function test_it_emits_order_payment_status_updated_event_when_payment_status_is_updated()
    {
        Event::fake(OrderPaymentStatusUpdated::class);

        $order = Order::factory()->create(['payment_status' => OrderPaymentStatus::PENDING_APPROVAL]);

        $order->update(['payment_status' => OrderPaymentStatus::APPROVED]);

        Event::assertDispatched(OrderPaymentStatusUpdated::class);
    }
}
