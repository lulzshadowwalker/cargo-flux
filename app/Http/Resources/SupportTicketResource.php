<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'support-ticket',
            'id' => $this->id,
            'attributes' => [
                'subject' => $this->subject,
                'message' => $this->message,
                'phone' => $this->phone,
                'name' => $this->name,
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
                'user_id' => $this->user_id,
            ],
            'links' => [
                'self' => route('support-tickets.show', ['lang' => app()->getLocale(), 'supportTicket' => $this]),
            ],
            'relationships' => (object) [],

            'includes' => (object) [],
        ];
    }
}
