<?php

namespace Tests\Unit\Models;

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
}
