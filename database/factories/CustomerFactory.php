<?php

namespace Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\User;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['type' => UserType::CUSTOMER]),
            'company_name' => rand(0, 1) ? $this->faker->company : null,
        ];
    }
}
