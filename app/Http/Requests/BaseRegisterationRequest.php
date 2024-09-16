<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Facades\JWTAuth;

class BaseRegisterationRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.firstName' => 'first_name',
            'data.attributes.lastName' => 'last_name',
            'data.attributes.dateOfBirth' => 'date_of_birth',
            'data.attributes.email' => 'email',
        ], $extraAttributes);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'authorization' => $this->header('Authorization'),
        ]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.type' => 'required|in:CUSTOMER,DRIVER',
            'data.attributes.phone' => 'required|phone|unique:users,phone',
            'authorization' => 'required|starts_with:Bearer ',
        ];
    }

    public function messages(): array
    {
        return [
            'authorization.required' => 'Bearer token is required.',
            'authorization.starts_with' => 'Bearer token must start with "Bearer ".',
            'data.attributes.phone.phone' => 'Phone number should be in e.164 format',
            'data.attributes.type.in' => 'type has to be either CUSTOMER or DRIVER',
        ];
    }

    protected function passedValidation()
    {
        $phone = JWTAuth::parseToken()->getPayload()['sub'];
        if ($phone !== $this->input('data.attributes.phone')) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid phone number');
        }
    }
}
