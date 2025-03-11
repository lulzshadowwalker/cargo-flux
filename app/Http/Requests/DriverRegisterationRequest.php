<?php

namespace App\Http\Requests;

use App\Enums\Nationality;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class DriverRegisterationRequest extends BaseFormRequest
{
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        $phoneKeys = [
            'data.attributes.phone',
            'data.attributes.secondaryPhone',
        ];

        foreach ($phoneKeys as $key) {
            $phone = data_get($data, $key);

            if (is_string($phone)) {
                if (str_starts_with($phone, '00')) {
                    $phone = '+' . substr($phone, 2);
                } elseif (!str_starts_with($phone, '+')) {
                    $phone = '+' . $phone;
                }

                data_set($data, $key, $phone);
            }
        }

        $this->replace($data);
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.firstName.ar' => 'first_name',
            'data.attributes.middleName.ar' => 'middle_name',
            'data.attributes.lastName.ar' => 'last_name',
            'data.attributes.dateOfBirth' => 'date_of_birth',
            'data.attributes.email' => 'email',
            'data.attributes.phone' => 'phone',
            'data.attributes.avatar' => 'avatar',
            'data.attributes.passport' => 'passport',
            'data.attributes.license' => 'driver_license',
            'data.attributes.residenceAddress' => 'residence_address',
            'data.relationships.truck.data.attributes.license' => 'license',
            'data.relationships.truck.data.attributes.isPersonalProperty' => 'is_personal_property',
            'data.relationships.truck.data.attributes.authorizationClause' => 'authorization_clause',
            'data.attributes.truckLicense' => 'license',
            'data.attributes.truckImages' => 'images',
            'data.relationships.truck.data.attributes.licensePlate' => 'license_plate',
            'data.relationships.truck.data.attributes.truckCategory' => 'truck_category_id',
            'data.relationships.truck.data.attributes.nationality' => 'nationality',
        ], $extraAttributes);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //  NOTE: This is not being called automatically because I believe we are manually constructing this request object in the service class
        $this->prepareForValidation();

        return [
            'data.attributes.firstName' => ['required', 'array'],
            'data.attributes.middleName' => ['required', 'array'],
            'data.attributes.lastName' => ['required', 'array'],

            'data.attributes.firstName.ar' => ['required', 'string', 'max:255'],
            'data.attributes.middleName.ar' => ['required', 'string', 'max:255'],
            'data.attributes.lastName.ar' => ['required', 'string', 'max:255'],

            'data.attributes.firstName.en' => ['required', 'string', 'max:255'],
            'data.attributes.middleName.en' => ['required', 'string', 'max:255'],
            'data.attributes.lastName.en' => ['required', 'string', 'max:255'],

            'data.attributes.secondaryPhone' => 'required|phone',

            'data.attributes.residenceAddress' => ['required', 'string'],
            'data.attributes.dateOfBirth' => ['required', 'date'],
            'data.attributes.email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'data.attributes.avatar' => ['nullable', 'image'],
            'data.attributes.passport' => ['required', 'image'],
            'data.attributes.license' => ['required', 'image'],
            'data.attributes.truckLicense' => ['required', 'image'],
            'data.attributes.truckImages' => ['required', 'array'],
            'data.attributes.truckImages.*' => ['required', 'image'],
            'data.relationships.truck.data.licensePlate' => ['required', 'string', 'max:255'],
            'data.relationships.truck.data.truckCategory' => ['required', 'integer', 'exists:truck_categories,id'],
            'data.relationships.truck.data.isPersonalProperty' => ['required', 'boolean'],
            'data.relationships.truck.data.authorizationClause' => ['required_if:data.relationships.truck.data.isPersonalProperty,false', 'image'],
            'data.relationships.truck.data.nationality' => ['required', Rule::enum(Nationality::class)],
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

    public function firstName(): mixed
    {
        return $this->input('data.attributes.firstName');
    }

    public function middleName(): mixed
    {
        return $this->input('data.attributes.middleName');
    }

    public function lastName(): mixed
    {
        return $this->input('data.attributes.lastName');
    }

    public function residenceAddress(): mixed
    {
        return $this->input('data.attributes.residenceAddress');
    }

    public function secondaryPhone(): mixed
    {
        return $this->input('data.attributes.secondaryPhone');
    }

    public function isTruckPersonalProperty(): bool
    {
        return (bool) $this->input('data.relationships.truck.data.isPersonalProperty');
    }

    public function authorizationClause(): mixed
    {
        return $this->file('data.relationships.truck.data.authorizationClause');
    }

    public function nationality(): Nationality
    {
        return Nationality::from($this->input('data.relationships.truck.data.nationality'));
    }
}
