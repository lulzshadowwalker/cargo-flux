<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class OrderStageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'order-stage',
            'id' => Str::uuid(),
            'attributes' => [
                'status' => $this['status'],
                'isCompleted' => $this['is_completed'],
                'completedAt' => $this['completed_at'],
            ],
            'links' => (object) [],
            'relationships' => (object) [],
        ];
    }
}
