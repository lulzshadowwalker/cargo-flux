<?php

namespace App\Http\Requests;

class UpdateCustomerProfileRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): array
    {
        $allowedAttributes = [
            'data.attributes.firstName' => 'first_name',
            'data.attributes.lastName' => 'last_name',
            'data.attributes.email' => 'email',
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

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.firstName' => 'sometimes|string',
            'data.attributes.lastName' => 'sometimes|string',
            'data.attributes.email' => 'sometimes|email',
        ];
    }
}
