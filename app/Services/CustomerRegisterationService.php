<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\CustomerRegisterationRequest;
use App\Models\User;

class CustomerRegisterationService implements RegisterationService
{
    /**
     * Register a user based on the provided form request data.
     * 
     * @param CustomerRegisterationRequest $request
     * @return User
     */
    public function register($request): User
    {
        $user = User::create($request->mappedAttributes(['type' => UserType::CUSTOMER]));

        $user->customer()->create(['company_name' => $request->mappedAttributes->company_name]);

        return $user;
    }
}
