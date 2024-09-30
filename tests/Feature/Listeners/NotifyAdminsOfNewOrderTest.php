<?php

namespace Tests\Feature\Listeners;

use App\Events\OrderPlaced;
use App\Listeners\NotifyAdminsOfNewOrder;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NotifyAdminsOfNewOrderTest extends TestCase
{
    public function test_it_listens_to_order_placed_event()
    {
        Event::fake();

        Event::assertListening(
            OrderPlaced::class,
            NotifyAdminsOfNewOrder::class,
        );
    }
}
