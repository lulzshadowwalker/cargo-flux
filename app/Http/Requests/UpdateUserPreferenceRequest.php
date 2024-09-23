<?php

namespace App\Http\Requests;

use App\Enums\Language;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class UpdateUserPreferenceRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.language' => ['sometimes', Rule::enum(Language::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'data.attributes.language' => 'value must be one of ' . implode(', ', Language::values()),
        ];
    }

    public function mappedAttributes(array $extraAttributes = []): Collection
    {
        return $this->mapped([
            'data.attributes.language' => 'language',
        ], $extraAttributes);
    }
}
