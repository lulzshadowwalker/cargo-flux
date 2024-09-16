<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $reviewerType = $this->faker->randomElement([Customer::class, Driver::class]);

        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(),
            'reviewer_id' => $reviewerType::factory(),
            'reviewer_type' => $reviewerType,
            'order_id' => Order::factory(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
