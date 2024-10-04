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

    public function test_it_notifies_admins_of_orders_pending_driver_assignment_that_has_been_posted_for_longer_than_six_hours()
    {
        Notification::fake();

        $admins = User::factory()->create(['type' => UserType::ADMIN]);

        //  NOTE: Two orders are created for each status
        //  one that has been created for more than 6 hours and another that has been created for less than 6 hours
        //  but of course only the single order with status PENDING_DRIVER_ASSIGNMENT that has been created for more than 6 hours should be notified
        collect(OrderStatus::cases())->each(function ($status) {
            Order::withoutEvents(function () use ($status) {
                Order::factory()->create([
                    'status' => $status,
                    'number' => uniqid(),
                    'created_at' => now()->subHours(6)
                ]);

                Order::factory()->create([
                    'status' => $status,
                    'number' => uniqid(),
                    'created_at' => now(),
                ]);
            });
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
