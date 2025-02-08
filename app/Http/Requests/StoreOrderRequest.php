<?php

namespace App\Http\Requests;

use App\Enums\OrderPaymentMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends BaseFormRequest
{

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.paymentMethod' => ['required', Rule::enum(OrderPaymentMethod::class)],
            'data.attributes.scheduledAt' => ['nullable', 'date', 'after:now'],
            'data.attributes.cargo' => ['required', 'string', 'max:255'],
            'data.attributes.pickupLocation.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'data.attributes.pickupLocation.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'data.attributes.deliveryLocation.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'data.attributes.deliveryLocation.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            'data.relationships.truckCategory.data.id' => ['required', 'exists:truck_categories,id'],
            'data.attributes.images' => ['required', 'array'],
            'data.attributes.images.*' => ['required', 'image'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.attributes.paymentMethod' => 'The payment method must be one of the following: ' . implode(', ', OrderPaymentMethod::values()),
            'data.attributes.pickupLocation.latitude' => 'Pickup location latitude must be a valid location between -90 and 90',
            'data.attributes.pickupLocation.longitude' => 'Pickup location longitude must be a valid location between -90 and 90',
            'data.attributes.deliveryLocation.latitude' => 'Delivery location latitude must be a valid location between -90 and 90',
            'data.attributes.deliveryLocation.longitude' => 'Delivery location longitude must be a valid location between -90 and 90',
        ];
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.paymentMethod' => 'payment_method',
            'data.attributes.scheduledAt' => 'scheduled_at',
            'data.attributes.cargo' => 'cargo',
            'data.attributes.pickupLocation.latitude' => 'pickup_location_latitude',
            'data.attributes.pickupLocation.longitude' => 'pickup_location_longitude',
            'data.attributes.deliveryLocation.latitude' => 'delivery_location_latitude',
            'data.attributes.deliveryLocation.longitude' => 'delivery_location_longitude',
            'data.relationships.truckCategory.data.id' => 'truck_category_id',
            'data.attributes.images' => 'images',
        ], [
            ...$extraAttributes,
            'customer_id' => Auth::user()->customer->id,
        ]);
    }


    /**
     * @return UploadedFile|UploadedFile[]|array
     */
    public function images(): mixed
    {
        return $this->file('data.attributes.images');
    }
}
