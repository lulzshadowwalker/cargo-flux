<?php

namespace Tests\Feature\Listeners;

use App\Enums\DriverStatus;
use App\Enums\OrderStatus;
use App\Enums\UserStatus;
use App\Events\OrderStatusUpdated;
use App\Listeners\NotifyDriversOfNewOffer;
use App\Models\DeviceToken;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Truck;
use App\Models\TruckCategory;
use App\Models\User;
use App\Notifications\DriverOfferNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyDriversOfNewOfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_listens_to_order_status_updated_event()
    {
        Event::fake();

        Event::assertListening(
            OrderStatusUpdated::class,
            NotifyDriversOfNewOffer::class,
        );
    }

    public function test_it_sends_the_offer_to_elligible_drivers_only()
    {
        //
        Notification::fake();

        $requestedCategory = TruckCategory::factory()->create();

        $targets = Driver::factory()
            ->count(3)
            ->for(User::factory()->has(DeviceToken::factory())->create(['status' => UserStatus::ACTIVE]))
            ->has(Truck::factory()->for($requestedCategory, 'category'))
            ->create(['status' => DriverStatus::APPROVED]);
        Driver::factory()->create(['status' => DriverStatus::REJECTED]);
        Driver::factory()->create(['status' => DriverStatus::UNDER_REVIEW]);
        Driver::factory()->for(User::factory()->create(['status' => UserStatus::BANNED]))->create(['status' => DriverStatus::APPROVED]);
        Driver::factory()->for(User::factory()->create(['status' => UserStatus::SUSPENDED]))->create(['status' => DriverStatus::APPROVED]);

        Order::withoutEvents(function () use ($requestedCategory) {
            Order::factory()->for($requestedCategory)->create([
                'driver_id' => null,
                'truck_id' => null,
                'number' => '123',
                'status' => OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            ]);
        });

        //
        $listener = new NotifyDriversOfNewOffer();
        $listener->handle(new OrderStatusUpdated(Order::first(), null));

        //
        Notification::assertSentTo(
            $targets,
            DriverOfferNotification::class,
        );
    }

    public function test_it_only_notifies_drivers_of_approved_orders()
    {
        $this->markTestIncomplete();

        Notification::fake();

        $requestedCategory = TruckCategory::factory()->create();

        Driver::factory()
            ->count(3)
            ->for(User::factory()->has(DeviceToken::factory())->create(['status' => UserStatus::ACTIVE]))
            ->has(Truck::factory()->for($requestedCategory, 'category'))
            ->create(['status' => DriverStatus::APPROVED]);

        // NOTE: Assert drivers are only notified of orders that have been approved
        Order::withoutEvents(function () use ($requestedCategory) {
            Order::factory()->for($requestedCategory)->create([
                'driver_id' => null,
                'truck_id' => null,
                'number' => '123',
                'status' => OrderStatus::PENDING_APPROVAL,
            ]);
        });

        $listener = new NotifyDriversOfNewOffer();
        $listener->handle(new OrderStatusUpdated(Order::latest()->first(), null));

        Notification::assertNothingSent();
    }
}
