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

        $admins = User::factory()->count(3)->create(['type' => UserType::ADMIN]);
        collect(OrderStatus::cases())->each(function ($status) {
            Order::withoutEvents(function () use ($status) {
                Order::factory()->create(['status' => $status, 'number' => uniqid()]);
            });
        });

        $this->artisan('notify:pending')
            ->doesntExpectOutput()
            ->assertSuccessful();

        // NOTE: One pending order, three admins = three notifications
        Notification::assertCount(3);
        Notification::assertSentTo(
            $admins,
            AdminPendingOrderNotification::class,
        );
    }

    public function test_it_notifies_admins_of_orders_pending_driver_assignment_that_has_been_posted_for_longer_than_six_hours()
    {
        Notification::fake();

        $admins = User::factory()->create(['type' => UserType::ADMIN]);
        Order::withoutEvents(function () {
            Order::factory()->create([
                'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
                'number' => uniqid(),
                'created_at' => now()->subHours(7)
            ]);

            Order::factory()->create([
                'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
                'number' => uniqid(),
                'created_at' => now()->subHours(1)
            ]);
        });

        $this->artisan('notify:pending')
            ->doesntExpectOutput()
            ->assertSuccessful();

        Notification::assertCount(1);
        Notification::assertSentTo(
            $admins,
            AdminPendingOrderNotification::class,
        );
    }
}
