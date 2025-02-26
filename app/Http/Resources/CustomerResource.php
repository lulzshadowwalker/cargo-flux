<?php

namespace App\Http\Resources;

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
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [],
            'meta' => (object) [],
        ];
    }
}
