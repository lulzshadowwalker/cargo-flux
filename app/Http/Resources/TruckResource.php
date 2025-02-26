<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TruckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'truck',
            'id' => $this->id,
            'attributes' => [
                'licensePlate' => $this->license_plate,
                'isPersonalProperty' => $this->is_personal_property,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => (object) [],
            'links' => (object) [],
            'includes' => (object) [],
        ];
    }
}
