<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_order_number()
    {
        $order = Order::factory()->create();

        $this->assertStringStartsWith('ORDER-', $order->number);
    }
}
