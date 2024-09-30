<?php

namespace Database\Factories;

use App\Enums\DriverStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Driver;
use App\Models\User;

class DriverFactory extends Factory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'status' => DriverStatus::APPROVED,
            'iban' => $this->faker->word(),
            'user_id' => User::factory()->create(['type' => UserType::DRIVER]),
        ];
    }
}
