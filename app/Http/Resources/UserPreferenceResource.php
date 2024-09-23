<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user-preferences',
            'id' => $this->id,
            'attributes' => [
                'language' => $this->language,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'links' => (object) [
                'self' => route('profile.preferences.index', ['lang' => app()->getLocale()]),
            ],
            'relationships' => (object) [],
        ];
    }
}
