<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'faq',
            'id' => $this->id,
            'attributes' => [
                'question' => $this->question,
                'answer' => $this->answer,
            ],

            'links' => [
                'self' => route('faq.show', ['lang' => app()->getLocale(), 'faq' => $this]),
            ],
            'relationships' => (object) [],
            'includes' => (object) [],
        ];
    }
}
