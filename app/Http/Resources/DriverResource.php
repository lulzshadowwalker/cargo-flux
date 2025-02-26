<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
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
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'secondaryPhone' => $this->secondary_phone,
                'dateOfBirth' => $this->user->date_of_birth,
                'accountStatus' => $this->user->driver->status,
                'status' => $this->user->status,
                'residenceAddress' => $this->residence_address,
                'avatar' => $this->user->avatar,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [],
            'meta' => (object) [],
        ];
    }
}
