<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;

class DriverRegisterationRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.firstName' => 'first_name',
            'data.attributes.lastName' => 'last_name',
            'data.attributes.dateOfBirth' => 'date_of_birth',
            'data.attributes.email' => 'email',
            'data.attributes.phone' => 'phone',
            'data.attributes.avatar' => 'avatar',
        ], $extraAttributes);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.firstName' => ['required', 'string', 'max:255'],
            'data.attributes.lastName' => ['required', 'string', 'max:255'],
            'data.attributes.dateOfBirth' => ['nullable', 'date'],
            'data.attributes.email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'data.attributes.avatar' => ['nullable', 'image'],
        ];
    }

    /**
     * @return UploadedFile|UploadedFile[]|array|null
     */
    public function avatar(): ?UploadedFile
    {
        return $this->file('data.attributes.avatar');
    }
}
