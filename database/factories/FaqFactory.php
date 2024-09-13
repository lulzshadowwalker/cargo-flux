<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            'question' => $this->localized(fn() => $this->faker->sentence),
            'answer' => $this->localized(fn() => $this->faker->paragraph(rand(1, 3))),
        ];
    }
}
