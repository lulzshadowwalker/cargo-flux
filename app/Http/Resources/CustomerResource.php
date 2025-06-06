<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'customer',
            'id' => $this->id,
            'attributes' => [
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
                'fullName' => $this->user->full_name,
                'companyName' => $this->company_name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'dateOfBirth' => $this->user->date_of_birth,
                'avatar' => SpatieImageResource::make($this->avatarFile),
                'status' => $this->user->status,
                'referralCode' => $this->user->referral_code,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [
                'rewardsWallet' => WalletResource::make($this->user->getWallet(User::WALLET_REWARDS)),
            ],
            'meta' => (object) [],
        ];
    }
}
