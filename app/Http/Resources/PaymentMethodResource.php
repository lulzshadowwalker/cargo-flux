<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'payment-method',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'code' => $this->code,
                'total' => [
                    'amount' => $this->total->getAmount(),
                    'currency' => $this->total->getCurrency()->getCurrencyCode(),
                ],
                'serviceCharge' => [
                    'amount' => $this->serviceCharge->getAmount(),
                    'currency' => $this->serviceCharge->getCurrency()->getCurrencyCode(),
                ],
                'image' => $this->image,
            ],
            'links' => (object) [],
            'relationships' => (object) [],
        ];
    }
}
