<?php

namespace App\Http\Requests;

abstract class BaseSupportTicketRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): array
    {
        $allowedAttributes = [
            'data.attributes.subject' => 'subject',
            'data.attributes.message' => 'message',
            'data.attributes.phone' => 'phone',
            'data.attributes.name' => 'name',
        ];

        $availabledAttributes = [];
        foreach ($allowedAttributes as $key => $value) {
            if ($this->has($key)) {
                $availabledAttributes[$value] = $this->input($key);
            }
        }

        $availabledAttributes = array_merge($availabledAttributes, $extraAttributes);

        return $availabledAttributes;
    }
}
