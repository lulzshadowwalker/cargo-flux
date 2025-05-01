<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Resources\WalletResource;
use App\Models\User;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_rewards_wallet()
    {
        $user = User::factory()->create();
        $resource = WalletResource::make($user->getWallet(User::WALLET_REWARDS));

        $this->actingAs($user);

        $response = $this->getJson(route('profile.wallets.rewards', ['lang' => Language::EN]));

        $response->assertOk();
        $response->assertExactJson(
            $resource->response()->getData(true)
        );
    }
}
