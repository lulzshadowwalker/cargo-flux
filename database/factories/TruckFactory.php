<?php

namespace Database\Factories;

use App\Enums\Nationality;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\TruckCategory;

class TruckFactory extends Factory
{
    protected $model = Truck::class;

    public function definition(): array
    {
        return [
            'license_plate' => $this->faker->word(),
            'driver_id' => Driver::factory(),
            'truck_category_id' => TruckCategory::factory(),
            'is_personal_property' => $this->faker->boolean(),
            'nationality' => $this->faker->randomElement(array_map(fn(Nationality $nationality): string => $nationality->value, Nationality::cases())),
        ];
    }
}
