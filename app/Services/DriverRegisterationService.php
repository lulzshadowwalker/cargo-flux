<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Models\User;

class DriverRegisterationService implements RegisterationService
{
    /**
     * Register a user based on the provided form request data.
     * 
     * @param DriverRegisterationRequest $request
     * @return User
     */
    public function register($request): User
    {
        $user = User::create($request->mappedAttributes(['type' => UserType::DRIVER]));

        $user->driver()->create();

        return $user;
    }
}
