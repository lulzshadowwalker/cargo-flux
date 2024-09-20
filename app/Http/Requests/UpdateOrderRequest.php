<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;

class UpdateOrderRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.currentLocation.latitude' => ['sometimes', 'numeric', 'min:-90', 'max:90'],
            'data.attributes.currentLocation.longitude' => ['sometimes', 'numeric', 'min:-180', 'max:180'],
        ];
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.currentLocation.latitude' => 'current_location_latitude',
            'data.attributes.currentLocation.longitude' => 'current_location_longitude',
        ], $extraAttributes);
    }
}
