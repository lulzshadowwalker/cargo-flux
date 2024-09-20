<?php

namespace Database\Factories;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Truck;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 0, 9999.99),
            'status' => $this->faker->randomElement(OrderStatus::cases())->value,
            'payment_method' => $this->faker->randomElement(OrderPaymentMethod::cases())->value,
            'payment_status' => $this->faker->randomElement(OrderPaymentStatus::cases())->value,
            'scheduled_at' => rand(0, 1) ? null : now()->addDays($this->faker->numberBetween(1, 30)),
            'pickup_location_latitude' => $this->faker->randomFloat(7, -90, 90),
            'pickup_location_longitude' => $this->faker->randomFloat(7, -180, 180),
            'delivery_location_latitude' => $this->faker->randomFloat(7, -90, 90),
            'delivery_location_longitude' => $this->faker->randomFloat(7, -180, 180),
            'current_location_latitude' => $this->faker->randomFloat(7, -90, 90),
            'current_location_longitude' => $this->faker->randomFloat(7, -180, 180),
            'current_location_recorded_at' => $this->faker->dateTime(),
            'customer_id' => Customer::factory(),
            'driver_id' => Driver::factory(),
            'currency_id' => Currency::factory(),
            'truck_id' => Truck::factory(),
            'cargo' => $this->faker->sentence(rand(7, 22)),
        ];
    }
}
