<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order-tracking-entry',
            'id' => $this->id,
            'attributes' => [
                'status' => $this->status,
                'note' => $this->note,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => (object) [],
            'includes' => (object) [],
        ];
    }
}
