<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TruckCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'truck-category',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'tonnage' => (float) $this->tonnage,
                'length' => (float) $this->length,
                'image' => $this->image,
                'isAvailable' => $this->isAvailable,
            ],
            'links' => [
                'self' => route('trucks.categories.show', ['lang' => app()->getLocale(), 'truckCategory' => $this]),
            ],
            'relationships' => (object) [],
            'includes' => (object) [],
        ];
    }
}
