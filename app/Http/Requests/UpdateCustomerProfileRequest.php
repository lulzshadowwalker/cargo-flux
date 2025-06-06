<?php

namespace App\Http\Requests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class UpdateCustomerProfileRequest extends BaseFormRequest
{
    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.firstName' => 'first_name',
            'data.attributes.lastName' => 'last_name',
            'data.attributes.email' => 'email',
            'data.attributes.avatar' => 'avatar',
        ], $extraAttributes);
    }

    public function customerMappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.companyName' => 'company_name',
        ], $extraAttributes);
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
            'data.attributes.companyName' => 'nullable|string',
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
}
