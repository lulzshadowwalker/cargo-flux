<?php

namespace Database\Factories;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'external_reference' => $this->faker->unique()->uuid,
            'gateway' => $this->faker->randomElement(PaymentGateway::cases()),
            'status' => $this->faker->randomElement(PaymentStatus::cases()),
            'user_id' => User::factory(),
            'payable_type' => Order::class,
            'payable_id' => Order::factory(),
            'currency_id' => Currency::factory(),
            'amount' => $this->faker->randomFloat(2, 250, 3500),
        ];
    }

    public function pending(): self
    {
        return $this->state(fn () => ['status' => PaymentStatus::PENDING]);
    }
}
