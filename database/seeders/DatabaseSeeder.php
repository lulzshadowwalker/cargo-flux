<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderTrackingEntry;
use App\Models\Otp;
use App\Models\Review;
use App\Support\SystemActor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory(1)->create();
        Driver::factory(1)->create();
        // Otp::factory(3)->create();

        Order::factory()->count(4)->create()->each(function (Order $order) {
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
    }
}
