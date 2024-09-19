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
                'tonnage' => $this->tonnage,
                'image' => $this->image,
            ],
            'links' => [
                'self' => route('trucks.categories.show', ['lang' => app()->getLocale(), 'truckCategory' => $this]),
            ],
            'relationships' => (object) [],
            'includes' => (object) [],
        ];
    }
}
