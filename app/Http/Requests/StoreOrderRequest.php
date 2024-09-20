<?php

namespace App\Http\Requests;

use App\Enums\OrderPaymentMethod;
use App\Models\Currency;
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
            'data.attributes.price.amount' => ['required', 'numeric', 'min:0'],
            'data.attributes.price.currency' => ['required', 'string', 'size:3', 'exists:currencies,code'],
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
            'data.attributes.paymentMethod' => 'The payment method must be one of the following: ' . implode(', ', OrderPaymentMethod::values()),
        ];
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.paymentMethod' => 'payment_method',
            'data.attributes.scheduledAt' => 'scheduled_at',
            'data.attributes.cargo' => 'cargo',
            'data.attributes.price.amount' => 'amount',
            'data.attributes.pickupLocation.latitude' => 'pickup_location_latitude',
            'data.attributes.pickupLocation.longitude' => 'pickup_location_longitude',
            'data.attributes.deliveryLocation.latitude' => 'delivery_location_latitude',
            'data.attributes.deliveryLocation.longitude' => 'delivery_location_longitude',
            'data.relationships.truckCategory.data.id' => 'truck_category_id',
        ], [
            ...$extraAttributes,
            'customer_id' => Auth::user()->id,
            'currency_id' => Currency::whereCode($this->input('data.attributes.price.currency'))->first()->id,
        ]);
    }
}
