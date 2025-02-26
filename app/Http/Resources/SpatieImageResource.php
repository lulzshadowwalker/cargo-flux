<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpatieImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'fileName' => $this->file_name,
            'mimeType' => $this->mime_type,
            'size' => $this->size,
            'url' => $this->getUrl(),
            'conversions' => [
                'thumb' => $this->hasGeneratedConversion('thumb') ? $this->getUrl('thumb') : null,
                'preview' => $this->hasGeneratedConversion('preview') ? $this->getUrl('preview') : null,
            ],
        ];
    }
}
