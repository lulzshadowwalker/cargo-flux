<?php

namespace App\Http\Requests;

use App\Contracts\Payable;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class StorePaymentRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.details.paymentMethodId' => ['required', 'numeric'],
            'data.relationships.payable.data.type' => ['required', 'string', 'in:order'],
            //  NOTE: The `exists` rule may need to be dynamically generated based on the value of `data.relationships.payable.data.type`
            'data.relationships.payable.data.id' => ['required', 'numeric', 'exists:orders,id'],
        ];
    }

    public function paymentMethodId(): int
    {
        return $this->input('data.attributes.details.paymentMethodId');
    }

    public function payable(): Payable
    {
        $type = $this->input('data.relationships.payable.data.type');

        switch ($type) {
            case 'order':
                return Order::findOrFail($this->input('data.relationships.payable.data.id'));

            default:
                Log::error('Unsupported payable type.', ['type' => $type]);
                throw new InvalidArgumentException('Unsupported payable type.');
        }
    }
}
