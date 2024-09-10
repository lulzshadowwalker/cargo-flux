<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Driver;
use App\Models\Order;
use App\Support\SystemActor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderTrackingEntry>
 */
class OrderTrackingEntryFactory extends Factory
{
    public function definition(): array
    {
        $actorType = $this->faker->randomElement([SystemActor::class, Driver::class]);
        $actorId = $actorType === Driver::class ? Driver::factory() : null;

        return [
            'order_id' => Order::factory(),
            'status' => $this->faker->randomElement(OrderStatus::cases())->value,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'note' => $this->faker->text(),
        ];
    }
}
