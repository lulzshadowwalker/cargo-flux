<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserPreference;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'language' => $this->faker->randomElement(["en", "ar"]),
            'email_notifications' => $this->faker->boolean(),
            'sms_notifications' => $this->faker->boolean(),
            'user_id' => User::factory(),
        ];
    }
}
