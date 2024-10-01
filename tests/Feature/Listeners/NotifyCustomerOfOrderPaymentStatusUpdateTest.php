<?php

namespace Tests\Feature\Listeners;

use App\Events\OrderPaymentStatusUpdated;
use App\Events\OrderPlaced;
use App\Listeners\NotifyAdminsOfNewOrder;
use App\Listeners\NotifyCustomerOfOrderPaymentStatusUpdate;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminOrderPlacedNotification;
use App\Notifications\OrderPaymentStatusUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyCustomerOfOrderPaymentStatusUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_listens_to_order_payment_status_updated_event()
    {
        Event::fake();

        Event::assertListening(
            OrderPaymentStatusUpdated::class,
            NotifyCustomerOfOrderPaymentStatusUpdate::class,
        );
    }

    public function test_it_sends_out_notification_to_the_customer()
    {
        Notification::fake();

        Order::withoutEvents(function () {
            Order::factory()->create(['number' => '123']);
        });

        $order = Order::first();

        $listener = new NotifyCustomerOfOrderPaymentStatusUpdate();
        $listener->handle(new OrderPaymentStatusUpdated($order, null));

        Notification::assertCount(1);
        Notification::assertSentTo(
            $order->customer,
            OrderPaymentStatusUpdatedNotification::class,
        );
    }
}
