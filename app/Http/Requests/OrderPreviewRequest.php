<?php

namespace App\Http\Requests;

class OrderPreviewRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.pickupLocation.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'data.attributes.pickupLocation.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'data.attributes.deliveryLocation.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'data.attributes.deliveryLocation.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'data.relationships.truckCategory.data.id' => ['required', 'exists:truck_categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.attributes.pickupLocation.latitude' => 'Pickup location latitude must be a valid location between -90 and 90',
            'data.attributes.pickupLocation.longitude' => 'Pickup location longitude must be a valid location between -90 and 90',
            'data.attributes.deliveryLocation.latitude' => 'Delivery location latitude must be a valid location between -90 and 90',
            'data.attributes.deliveryLocation.longitude' => 'Delivery location longitude must be a valid location between -90 and 90',
        ];
    }
}
