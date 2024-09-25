<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TruckCategory>
 */
class TruckCategoryFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'name' => $this->localized(fn() => $this->faker->word),
            'tonnage' => $this->faker->randomelement([5, 10, 15, 20, 25, 30]),
            'length' => $this->faker->randomelement([3, 4, 5, 6]),
        ];
    }
}
