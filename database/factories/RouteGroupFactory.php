<?php

namespace Database\Factories;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RouteGroup>
 */
class RouteGroupFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'pickup_state_id' => new StateFactory(),
        ];
    }
}
