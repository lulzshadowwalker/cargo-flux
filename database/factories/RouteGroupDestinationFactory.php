<?php

namespace Database\Factories;

use Altwaireb\World\Models\State;
use App\Models\RouteGroup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RouteGroupDestination>
 */
class RouteGroupDestinationFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'route_group_id' => RouteGroup::factory(),
            'delivery_state_id' => $this->faker->unique()->randomElement(State::pluck('id')->toArray()),
        ];
    }
}
