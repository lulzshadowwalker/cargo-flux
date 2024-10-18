<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public ?string $url;

    public function toArray(Request $request): array
    {
        assert($this->url);

        return [
            'type' => 'payment',
            'id' => $this->id,
            'attributes' => [
                'externalReference' => $this->external_reference,
                'status' => $this->status,
                'gateway' => $this->gateway,
                'price' => [
                    'amount' => $this->price->getAmount(),
                    'currency' => $this->price->getCurrency()->getCurrencyCode(),
                ],
                'createdAt' => $this->created_at->toIso8601String(),
                'updatedAt' => $this->updated_at->toIso8601String(),
            ],
            'links' => (object) [],
            'relationships' => (object) [],
            'meta' => [
                'url' => $this->url,
            ],
        ];
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
