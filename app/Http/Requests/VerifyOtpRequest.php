<?php

namespace App\Http\Requests;

class VerifyOtpRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|phone',
            'code' => 'required|string',
            'type' => 'required|in:CUSTOMER,DRIVER',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => 'Phone number should be in e.164 format',
        ];
    }
}
