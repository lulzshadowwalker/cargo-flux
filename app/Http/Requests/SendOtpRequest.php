<?php

namespace App\Http\Requests;

class SendOtpRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => 'Phone number should be in e.164 format',
        ];
    }
}
