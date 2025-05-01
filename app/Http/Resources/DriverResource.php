<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource {
    public function toArray(Request $request): array
    {
        return [
            'type' => 'driver',
            'id' => $this->id,
            'attributes' => [
                'firstName' => $this->first_name,
                'middleName' => $this->middle_name,
                'lastName' => $this->last_name,
                'fullName' => $this->fullName,
                'fullLegalName' => $this->fullLegalName,
                'firstName' => [
                    'en' => $this->getTranslation('first_name', 'en'),
                    'ar' => $this->getTranslation('first_name', 'ar'),
                ],
                'middleName' => [
                    'en' => $this->getTranslation('middle_name', 'en'),
                    'ar' => $this->getTranslation('middle_name', 'ar'),
                ],
                'lastName' => [
                    'en' => $this->getTranslation('last_name', 'en'),
                    'ar' => $this->getTranslation('last_name', 'ar'),
                ],
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'secondaryPhone' => $this->secondary_phone,
                'dateOfBirth' => $this->user->date_of_birth,
                'accountStatus' => $this->user->driver->status,
                'status' => $this->user->status,
                'residenceAddress' => $this->residence_address,
                'avatar' => SpatieImageResource::make($this->user->avatarFile),
                'passport' => SpatieImageResource::make($this->passportFile),
                'license' => SpatieImageResource::make($this->licenseFile),
                'referralCode' => $this->user->referral_code,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [
                'truck' => $this->mergeWhen($this->truck, TruckResource::make($this->truck)),
                'rewardsWallet' => WalletResource::make($this->user->getWallet(User::WALLET_REWARDS)),
            ],
            'meta' => (object) [],
        ];
    }
}
