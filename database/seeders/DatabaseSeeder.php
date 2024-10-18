<?php

namespace Database\Seeders;

use App\Enums\DriverStatus;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Faq;
use App\Models\Order;
use App\Models\OrderTrackingEntry;
use App\Models\Page;
use App\Models\Review;
use App\Models\RouteGroup;
use App\Models\RouteGroupDestination;
use App\Models\RouteGroupTruckOption;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserPreference;
use App\Notifications\FakeDatabaseNotification;
use App\Support\SystemActor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShieldSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(WorldSeeder::class);

        $customer = Customer::factory()
            ->for(
                User::factory()
                    ->has(UserPreference::factory(), 'preferences')
                    ->create([
                        'phone' => '+962777777777',
                        'status' => UserStatus::ACTIVE,
                        'type' => UserType::CUSTOMER,
                    ])
            )->create();

        Notification::send($customer->user, new FakeDatabaseNotification);

        Customer::factory()->for(
            User::factory()->create([
                'phone' => '+962777777771',
                'status' => UserStatus::BANNED,
                'type' => UserType::CUSTOMER,
            ])
        )->create();

        Customer::factory()->for(
            User::factory()->create([
                'phone' => '+962777777772',
                'status' => UserStatus::SUSPENDED,
                'type' => UserType::CUSTOMER,
            ])
        )->create();

        Driver::factory()->for(
            User::factory()->create([
                'phone' => '+962777777773',
                'status' => UserStatus::ACTIVE,
                'type' => UserType::DRIVER,
            ])
        )->create(['status' => DriverStatus::UNDER_REVIEW]);

        Driver::factory()->for(
            User::factory()->create([
                'phone' => '+962777777774',
                'status' => UserStatus::ACTIVE,
                'type' => UserType::DRIVER,
            ])
        )->create(['status' => DriverStatus::REJECTED]);

        $driver = Driver::factory()->for(
            User::factory()->create([
                'phone' => '+962777777778',
                'status' => UserStatus::ACTIVE,
                'type' => UserType::DRIVER,
            ])
        )->create(['status' => DriverStatus::APPROVED]);
        // Otp::factory(3)->create();

        Notification::send($driver->user, new FakeDatabaseNotification);

        Order::factory()->count(100)->for($customer)->for($driver)->create()->each(function (Order $order) {
            $actorType = rand(0, 1) ? Driver::class : SystemActor::class;
            $actorId = $actorType === Driver::class ? $order->driver->id : 1;

            Review::factory()->for($order)->count(1)->create();
            foreach (range(1, rand(1, 5)) as $index) {
                OrderTrackingEntry::factory()->create([
                    'order_id' => $order->id,
                    'actor_type' => $actorType,
                    'actor_id' => $actorId,
                ]);
            }
        });

        $pages = ['Terms and conditions', 'Privacy policy', 'About Us'];
        foreach ($pages as $page) {
            Page::factory()->create([
                'title' => $page,
            ]);
        }

        Faq::factory()->count(19)->create();
        // SupportTicket::factory()->count(50)->create();
        SupportTicket::factory()->count(1)->create([
            'created_at' => now()->subDays(20),
        ]);

        RouteGroup::factory()
            ->count(8)
            ->has(RouteGroupDestination::factory()->count(rand(1, 2)), 'destinations')
            ->has(RouteGroupTruckOption::factory()->count(rand(2, 5)), 'truckOptions')
            ->create();
    }
}
