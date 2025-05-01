<?php

namespace App\Listeners;

use App\Enums\RewardsWalletReason;
use App\Events\UserReferred;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AwardReferralPoints implements ShouldQueue
{
    const POINTS = 100;

    public function handle(UserReferred $event): void
    {
        $referred = $event->referral->referred;

        Log::info('Awarding referral points', [
            'referred_id' => $referred->id,
            'points' => self::POINTS,
        ]);

        $referred->getWallet(User::WALLET_REWARDS)->deposit(
            self::POINTS,
            [
                'description' => 'Referral bonus for ' . $event->referral->referred->name,
                'reason' => RewardsWalletReason::REFERRAL_BONUS->value,
            ]
        );
    }
}
