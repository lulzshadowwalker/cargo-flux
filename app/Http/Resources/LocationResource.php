<?php

namespace App\Http\Resources;

use App\Models\Place;
use App\Support\GeoPoint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    protected ?GeoPoint $location;
    protected ?string $recordedAt = null;

    public function toArray(Request $request): array
    {
        return [
            'latitude' => $this->location?->latitude,
            'longitude' => $this->location?->longitude,
            'recordedAt' => $this->recordedAt ?: null,
            'name' => $this->location && ! app()->runningUnitTests() ? Place::whereContains('boundaries', $this->location->toBoundaryPoint())->first()?->name : null,
        ];
    }

    public function location(?GeoPoint $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function recordedAt(?string $recordedAt): self
    {
        $this->recordedAt = $recordedAt;

        return $this;
    }
}
