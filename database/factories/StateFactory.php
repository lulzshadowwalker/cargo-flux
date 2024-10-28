<?php

namespace Database\Factories;

use Altwaireb\World\Models\State;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StateFactory extends BaseFactory
{
    protected $model = State::class;

    public function definition(): array
    {
        return [
            'country_id' => CountryFactory::new(),
            'name' => $this->faker->state(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
