<?php

namespace Tests\Feature\Listeners;

use App\Events\OrderPlaced;
use App\Listeners\NotifyAdminsOfNewOrder;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminOrderPlacedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOfNewOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_listens_to_order_placed_event()
    {
        Event::fake();

        Event::assertListening(
            OrderPlaced::class,
            NotifyAdminsOfNewOrder::class,
        );
    }

    public function test_it_sends_out_notification_to_admins()
    {
        Notification::fake();

        User::factory()->admin()->count(3)->create();

        Order::withoutEvents(function () {
            Order::factory()->create(['number' => '123']);
        });

        $listener = new NotifyAdminsOfNewOrder();
        $listener->handle(new OrderPlaced(Order::first()));

        Notification::assertCount(User::admins()->count());
        Notification::assertSentTo(
            User::admins()->get(),
            AdminOrderPlacedNotification::class,
        );
    }
}
