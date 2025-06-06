<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends BaseFactory
{
    public function definition(): array
    {
        return [
            //  NOTE: Typically [referral_code] would be generated by the UserObserver so obviously this is not tied to an actual user like it would be in the real world
            'referral_code' => $this->faker->unique()->word(),
            'referrer_id' => User::factory(),
            'referred_id' => User::factory(),
        ];
    }
}
