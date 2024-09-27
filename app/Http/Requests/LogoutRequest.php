<?php

namespace App\Http\Requests;

class LogoutRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'deviceToken' => 'nullable|string'
        ];
    }
}
