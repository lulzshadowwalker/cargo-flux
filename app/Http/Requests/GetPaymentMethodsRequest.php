<?php

namespace App\Http\Requests;

class GetPaymentMethodsRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price.amount' => ['required', 'numeric', 'min:0'],
            'price.currency' => ['required', 'string', 'size:3', 'exists:currencies,code'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.currency' => 'The currency must be a valid ISO 4217 currency code.',
        ];
    }
}
