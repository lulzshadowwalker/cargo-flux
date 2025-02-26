<?php

namespace Database\Factories;

use App\Enums\DriverStatus;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Driver;
use App\Models\User;

class DriverFactory extends BaseFactory
{
    protected $model = Driver::class;

    public function definition(): array
    {
        return [
            'status' => DriverStatus::APPROVED,
            'iban' => $this->faker->word(),
            'user_id' => User::factory()->create(['type' => UserType::DRIVER]),
            'first_name' => $this->localized(fn(): string => $this->faker->firstName),
            'middle_name' => $this->localized(fn(): string  => $this->faker->firstName),
            'last_name' => $this->localized(fn(): string => $this->faker->lastName),
            'residence_address' => $this->faker->address,
        ];
    }

    public function underReview(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DriverStatus::UNDER_REVIEW,
            ];
        });
    }

    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DriverStatus::APPROVED,
            ];
        });
    }

    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => DriverStatus::REJECTED,
            ];
        });
    }
}
