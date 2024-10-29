<?php

namespace App\Http\Resources;

use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PriceReviewResource extends JsonResource
{
    protected ?Money $price = null; 

    public function toArray(Request $request): array
    {
        assert($this->price, 'Price must be set before converting to array');

        return [
            'type' => 'price-preview',
            'id' => Str::uuid(),
            'attributes' => [
                'price' => [
                    'amount' => $this->price->getAmount(),
                    'currency' => $this->price->getCurrency()->getCurrencyCode(),
                ],
            ]
        ];
    }

    public function price(Money $price): self
    {
        $this->price = $price;

        return $this;
    }
}
