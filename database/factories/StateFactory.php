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
        //  TODO: Remove me
        return [
            'country_id' => CountryFactory::new(),
            'name' => "Amman Governate",
            'latitude' => 90,
            'longitude' => 180,
            'is_active' => $this->faker->boolean,
        ];

        return [
            'country_id' => CountryFactory::new(),
            'name' => $this->faker->city,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'is_active' => $this->faker->boolean,
        ];
    }
}
