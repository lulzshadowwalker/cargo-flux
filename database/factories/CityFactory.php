<?php

namespace Database\Factories;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CityFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'country_id' => CountryFactory::new(),
            'state_id' => StateFactory::new(),
            'name' => $this->faker->city(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
