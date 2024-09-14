<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class StoreSupportTicketRequest extends BaseSupportTicketRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.subject' => 'required|string',
            'data.attributes.message' => 'required|string',
            'data.attributes.phone' => 'sometimes|string',
            'data.attributes.name' => 'sometimes|string',
        ];

        if (!Auth::check()) {
            $rules['data.attributes.phone'] = 'required|string';
            $rules['data.attributes.name'] = 'required|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        if (!Auth::check()) {
            $messages['data.attributes.phone.required'] = 'The phone field is required when the user is not authenticated.';
            $messages['data.attributes.name.required'] = 'The name field is required when the user is not authenticated';
        }

        return $messages;
    }
}
