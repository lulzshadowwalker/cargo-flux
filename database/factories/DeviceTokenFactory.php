<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeviceToken>
 */
class DeviceTokenFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => $this->faker->sentence(),
        ];
    }
}
