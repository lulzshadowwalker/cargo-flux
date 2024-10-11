<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\RouteGroup;
use App\Models\TruckCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RouteGroupTruckOption>
 */
class RouteGroupTruckOptionFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'route_group_id' => RouteGroup::factory(),
            'truck_category_id' => TruckCategory::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 10000),
            'currency_id' => $this->faker->randomElement(Currency::pluck('id')->toArray()),
        ];
    }
}
