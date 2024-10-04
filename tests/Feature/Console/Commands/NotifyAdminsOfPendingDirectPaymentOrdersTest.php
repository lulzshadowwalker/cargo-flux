<?php

namespace Tests\Feature\Console\Commands;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminPendingDirectPaymentOrderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOfPendingDirectPaymentOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_admins_of_orders_pending_direct_payment_orders_that_are_neither_rejected_not_approved()
    {
        Notification::fake();

        $admins = User::factory()->count(3)->create(['type' => UserType::ADMIN]);
        Order::withoutEvents(function () {
            Order::factory()->create([
                'status' => OrderStatus::PENDING_APPROVAL,
                'payment_status' => OrderPaymentStatus::PENDING_APPROVAL,
                'payment_method' => OrderPaymentMethod::DIRECT,
                'number' => uniqid(),
                'created_at' => now()->subHours(2)
            ]);

            Order::factory()->create([
                'status' => OrderStatus::PENDING_APPROVAL,
                'payment_status' => OrderPaymentStatus::PENDING_APPROVAL,
                'payment_method' => OrderPaymentMethod::DIRECT,
                'number' => uniqid(),
                //  NOTE: Only orders that have been created for more than 2 hours should be notified
                'created_at' => now(),
            ]);
        });

        $this->artisan('notify:pending-direct-payment-orders')
            ->doesntExpectOutput()
            ->assertSuccessful();

        Notification::assertCount(3);
        Notification::assertSentTo(
            $admins,
            AdminPendingDirectPaymentOrderNotification::class,
        );
    }
}
