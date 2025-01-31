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
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
                'fullName' => $this->user->full_name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'dateOfBirth' => $this->user->date_of_birth,
                'accountStatus' => $this->user->driver->status,
                'status' => $this->user->status,
                'avatar' => $this->user->avatar,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'includes' => (object) [],
            'meta' => (object) [],
        ];
    }
}
