<?php

namespace Database\Factories;

use Altwaireb\World\Models\State;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RouteGroup>
 */
class RouteGroupFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'pickup_state_id' => $this->faker->randomElement(State::pluck('id')->toArray()),
        ];
    }
}
