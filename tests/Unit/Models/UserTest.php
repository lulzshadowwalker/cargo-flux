<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_newly_created_users_have_default_preferences_and_rewards_wallet(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull(
            $user->preferences,
            'User preferences should be initialized.'
        );

        $this->assertNotNull($user->getWallet(User::WALLET_REWARDS), 'User should have a rewards wallet.');
    }
}
