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
            'data.attributes.passport' => 'passport',
            'data.attributes.license' => 'driver_license',
            'data.relationships.truck.data.attributes.license' => 'license',
            'data.attributes.truckLicense' => 'license',
            'data.attributes.truckImages' => 'images',
            'data.relationships.truck.data.attributes.licensePlate' => 'license_plate',
            'data.relationships.truck.data.attributes.truckCategory' => 'truck_category_id',
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
            'data.attributes.passport' => ['required', 'image'],
            'data.attributes.license' => ['required', 'image'],
            'data.attributes.truckLicense' => ['required', 'image'],
            'data.attributes.truckImages' => ['required', 'array', 'size:4'],
            'data.attributes.truckImages.*' => ['required', 'image'],
            'data.relationships.truck.data.licensePlate' => ['required', 'string', 'max:255'],
            'data.relationships.truck.data.truckCategory' => ['required', 'integer', 'exists:truck_categories,id'],
        ];
    }

    /**
     * @return UploadedFile|UploadedFile[]|array|null
     */
    public function avatar(): ?UploadedFile
    {
        return $this->file('data.attributes.avatar');
    }

    /**
     * @return UploadedFile|UploadedFile[]|array
     */
    public function passport(): UploadedFile
    {
        return $this->file('data.attributes.passport');
    }

    /**
     * @return UploadedFile|UploadedFile[]|array
     */
    public function driverLicense(): UploadedFile
    {
        return $this->file('data.attributes.license');
    }

    /**
     * @return UploadedFile|UploadedFile[]|array
     */
    public function truckLicense(): UploadedFile
    {
        return $this->file('data.attributes.truckLicense');
    }

    /**
     * @return UploadedFile|UploadedFile[]|array
     */
    public function truckImages(): mixed
    {
        return $this->file('data.attributes.truckImages');
    }

    public function licensePlate(): string
    {
        return $this->input('data.relationships.truck.data.licensePlate');
    }

    /**
     * returns the truck category id
     */
    public function truckCategory(): int
    {
        return (int) $this->input('data.relationships.truck.data.truckCategory');
    }
}
