<?php

namespace App\Http\Controllers\Api;

use App\Events\UserReferred;
use App\Http\Requests\StoreReferralRequest;
use App\Http\Resources\ReferralResource;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReferralController extends ApiController
{
    public function store(StoreReferralRequest $request)
    {
        //  NOTE: Prevent the user from submitting multiple referrals
        if ($request->user()->referral()->exists()) {
            return $this->response->error(
                title: 'You have already submitted a referral.',
                detail: 'You have already submitted a referral.',
                code: Response::HTTP_CONFLICT,
            )->build(Response::HTTP_CONFLICT);
        }

        if (! User::where('referral_code', $request->referralCode())->exists()) {
            return $this->response->error(
                title: 'Invalid referral code.',
                detail: 'The referral code you provided is invalid.',
                code: Response::HTTP_NOT_FOUND,
            )->build(Response::HTTP_NOT_FOUND);
        }

        DB::transaction(function () use ($request) {
            $referrer = User::where('referral_code', $request->referralCode())->first();

            $referral = Referral::create([
                'referral_code' => $request->referralCode(),
                'referrer_id' => $referrer->id,
                'referred_id' => $request->user()->id,
            ]);

            UserReferred::dispatch($referral);
        });

        return ReferralResource::make($request->user()->referral()->firstOrFail());
    }
}
