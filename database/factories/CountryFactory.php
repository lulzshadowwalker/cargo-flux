<?php

namespace Database\Factories;

use Altwaireb\World\Models\Country;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CountryFactory extends BaseFactory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
            'iso2' => $this->faker->unique()->countryCode(),
            'iso3' => $this->faker->unique()->countryISOAlpha3(),
            'numeric_code' => $this->faker->unique()->randomNumber(3, true),
            'phonecode' => $this->faker->numberBetween(1, 999),
            'capital' => $this->faker->city(),
            'currency' => $this->faker->currencyCode(),
            'currency_name' => $this->faker->word(),
            'currency_symbol' => $this->faker->randomLetter(),
            'tld' => '.' . strtolower($this->faker->unique()->lexify('??')),
            'native' => $this->faker->country(),
            'region' => $this->faker->randomElement(['Africa', 'Americas', 'Asia', 'Europe', 'Oceania']),
            'subregion' => $this->faker->word(),
            'timezones' => json_encode([$this->faker->timezone()]),
            'translations' => json_encode(['en' => $this->faker->country()]),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'emoji' => $this->faker->emoji(),
            'emojiU' => $this->faker->emoji(),
            'flag' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
