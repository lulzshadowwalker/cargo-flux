<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ReferralControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_referral_successfully()
    {
        $referrer = User::factory()->create();
        $referred = User::factory()->create();

        $this->actingAs($referred);

        $this->assertEquals(0, $referred->getWallet(User::WALLET_REWARDS)->balance);
        $this->post(route('referrals.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'referralCode' => $referrer->referral_code,
                ],
            ],
        ])->assertOk();

        $referred->refresh();

        $this->assertNotNull($referred->referral);
        $this->assertDatabaseCount('referrals', 1);
        $this->assertDatabaseHas('referrals', [
            'referral_code' => $referrer->referral_code,
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
        ]);

        $this->assertEquals(100, $referred->getWallet(User::WALLET_REWARDS)->balance);
    }

    public function test_it_returns_http_not_found_when_referral_code_is_invalid()
    {
        $referred = User::factory()->create();

        $this->actingAs($referred);

        $this->post(route('referrals.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'referralCode' => 'invalid_code',
                ],
            ],
        ])->assertNotFound();
    }

    public function test_it_returns_http_conflict_when_user_already_submitted_referral()
    {
        $referrer = User::factory()->create();
        $referred = User::factory()->create();

        $this->actingAs($referred);

        $this->post(route('referrals.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'referralCode' => $referrer->referral_code,
                ],
            ],
        ])->assertOk();

        $this->post(route('referrals.store', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'referralCode' => $referrer->referral_code,
                ],
            ],
        ])->assertStatus(Response::HTTP_CONFLICT);
    }
}
