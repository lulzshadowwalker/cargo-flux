<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'referral',
            'id' => (string) $this->id,
            'attributes' => [
                'referralCode' => $this->referral_code,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [],
        ];
    }
}
