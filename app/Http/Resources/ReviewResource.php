<?php

namespace App\Http\Resources;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'review',
            'id' => $this->id,
            'attributes' => [
                'rating' => $this->rating,
                'comment' => $this->comment,
                'reviewerType' => $this->reviewer_type === Driver::class ? 'DRIVER' : 'CUSTOMER',
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
        ];
    }
}
