<?php

namespace App\Http\Requests;

use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;

class UpdateDriverProfileRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.email' => 'email',
            'data.attributes.avatar' => 'avatar',
            'data.attributes.dateOfBirth' => 'date_of_birth',
        ], $extraAttributes);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.firstName' => 'sometimes|array',
            'data.attributes.lastName' => 'sometimes|array',
            'data.attributes.middleName' => 'sometimes|array',
            'data.attributes.firstName.ar' => 'sometimes|string',
            'data.attributes.firstName.en' => 'sometimes|string',
            'data.attributes.lastName.ar' => 'sometimes|string',
            'data.attributes.lastName.en' => 'sometimes|string',
            'data.attributes.middleName.ar' => 'sometimes|string',
            'data.attributes.middleName.en' => 'sometimes|string',
            'data.attributes.email' => 'sometimes|email',
            'data.attributes.avatar' => 'nullable|image',
        ];
    }

    /**
     * @return UploadedFile|UploadedFile[]|array|null
     */
    public function avatar(): ?UploadedFile
    {
        return $this->file('data.attributes.avatar');
    }

    public function firstName(): array
    {
        return $this->input('data.attributes.firstName');
    }

    public function lastName(): array
    {
        return $this->input('data.attributes.lastName');
    }

    public function middleName(): array
    {
        return $this->input('data.attributes.middleName');
    }
}
