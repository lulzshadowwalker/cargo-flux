<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\CustomerRegisterationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerRegisterationService implements RegisterationService
{
    public function register($request): User
    {
        $request = $this->validate($request);

        $user = User::create($request->mappedAttributes(['type' => UserType::CUSTOMER])->toArray());

        $user->customer()->create($request->mappedAttributes()->toArray());

        return $user;
    }

    private function validate(Request $request): CustomerRegisterationRequest
    {
        $r = new CustomerRegisterationRequest;
        $r->setMethod('POST');
        $r->merge($request->all());

        $r->validate($r->rules());

        return $r;
    }
}
