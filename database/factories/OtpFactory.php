<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Otp>
 */
class OtpFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone' => $this->faker->e164PhoneNumber(),
            'code' => $this->faker->randomNumber(6),
            'expires_at' => now()->addMinutes(5),
            'verified_at' => rand(0, 1) ? now()->subMinutes(rand(1, 12)) : null,
        ];
    }
}
