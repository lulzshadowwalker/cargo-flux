<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'notification',
            'attributes' => [
                'title' => $this->data['title'],
                'message' => $this->data['message'],
                'data' => (object) ($this->data['data'] ?? []),
                'readAt' => $this->read_at,
                'isRead' => isset($this->read_at),
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'links' => [
                'self' => route('notifications.show', [
                    'lang' => app()->getLocale(),
                    'notification' => $this->id
                ]),
            ],
            'relationships' => (object) [],
        ];
    }
}
