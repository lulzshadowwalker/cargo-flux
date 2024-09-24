<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'data.attributes.status' => ['sometimes', Rule::enum(OrderStatus::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'data.attributes.status' => 'status must be one of ' . implode(', ', OrderStatus::values())
        ];
    }

    protected function passedValidation()
    {
        if ($this->has('data.attributes.status') && !Auth::user()->isDriver) {
            abort(Response::HTTP_FORBIDDEN, 'Only the driver can update the order status');
        }
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.currentLocation.latitude' => 'current_location_latitude',
            'data.attributes.currentLocation.longitude' => 'current_location_longitude',
            'data.attributes.status' => 'status',
        ], $extraAttributes);
    }
}
