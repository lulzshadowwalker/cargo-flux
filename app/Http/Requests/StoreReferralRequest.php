<?php

namespace App\Http\Requests;

class StoreReferralRequest extends BaseFormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //  NOTE: `exists:users,referral_code` check is being done in the controller itself
            'data.attributes.referralCode' => ['required', 'string'],
        ];
    }

    public function referralCode(): string
    {
        return $this->input('data.attributes.referralCode');
    }
}
