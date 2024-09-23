<?php

namespace Database\Factories;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Support\Str;

class UserPreferenceFactory extends Factory
{
    protected $model = UserPreference::class;

    public function definition(): array
    {
        return [
            'language' => $this->faker->randomElement(array_map(fn ($language) => Str::lower($language->value), Language::cases())),
            'user_id' => User::factory(),
        ];
    }
}
