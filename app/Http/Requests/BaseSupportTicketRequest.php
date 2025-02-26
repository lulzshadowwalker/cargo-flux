<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;

abstract class BaseSupportTicketRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.message' => 'message',
            'data.attributes.phone' => 'phone',
            'data.attributes.name' => 'name',
        ], $extraAttributes);
    }
}
