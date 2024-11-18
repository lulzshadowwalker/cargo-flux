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
use App\Models\TruckCategory;

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
            'pickup_location_latitude' => $this->faker->randomFloat(7, 16.0, 32.0), // Latitude range for Saudi Arabia
            'pickup_location_longitude' => $this->faker->randomFloat(7, 34.0, 55.0), // Longitude range for Saudi Arabia
            'delivery_location_latitude' => $this->faker->randomFloat(7, 29.0, 33.5), // Latitude range for Jordan
            'delivery_location_longitude' => $this->faker->randomFloat(7, 35.5, 42.0), // Longitude range for Jordan
            'current_location_latitude' => $this->faker->randomFloat(7, 16.0, 33.5), // Latitude range between Saudi Arabia and Jordan
            'current_location_longitude' => $this->faker->randomFloat(7, 34.0, 42.0), // Longitude range between Saudi Arabia and Jordan
            'current_location_recorded_at' => $this->faker->dateTime(),
            'customer_id' => Customer::factory(),
            'driver_id' => Driver::factory(),
            'currency_id' => Currency::firstOrCreate(['code' => 'SAR', 'symbol' => 'ر.س'])->id,
            'truck_id' => Truck::factory(),
            'cargo' => $this->faker->sentence(rand(7, 22)),
            'truck_category_id' => TruckCategory::factory(),
        ];
    }
}
