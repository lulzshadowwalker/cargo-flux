<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PriceReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'price-preview',
            'id' => Str::uuid(),
            'attributes' => [
                'price' => [
                    'amount' => '4000.00',
                    'currency' => 'JOD',
                ],
            ]
        ];
    }
}
