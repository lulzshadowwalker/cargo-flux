<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\CustomerRegisterationRequest;
use App\Models\User;

class CustomerRegisterationService // implements RegisterationService
{
    /**
     * Register a user based on the provided form request data.
     *
     * @param CustomerRegisterationRequest $request
     * @return User
     */
    public function register(CustomerRegisterationRequest $request): User
    {
        $user = User::create($request->mappedAttributes(['type' => UserType::CUSTOMER])->toArray());

        $user->customer()->create($request->mappedAttributes()->toArray());

        return $user;
    }
}
