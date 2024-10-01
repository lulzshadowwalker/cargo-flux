<?php

namespace Tests\Feature\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\UserType;
use App\Models\Order;
use App\Models\User;
use App\Notifications\AdminPendingOrderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOfPendingOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_admins_of_orders_pending_driver_assignment()
    {
        Notification::fake();

        $admins = User::factory()->count(3)->create([ 'type' => UserType::ADMIN ]);
        collect(OrderStatus::cases())->each(function ($status) {
            Order::withoutEvents(function () use ($status) {
                Order::factory()->create([ 'status' => $status, 'number' => uniqid() ]);
            });
        });

        $this->artisan('notify:pending')
            ->doesntExpectOutput()
            ->assertSuccessful();

        // NOTE: One pending orders, three admins = three notifications
        Notification::assertCount(3);
        Notification::assertSentTo(
            $admins,
            AdminPendingOrderNotification::class,
        );
    }
}
