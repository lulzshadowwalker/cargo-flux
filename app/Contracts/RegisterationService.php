<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

interface RegisterationService
{
    /**
     * Register a user based on the provided form request data.
     *
     * @param FormRequest $request
     * @return User
     */
    public function register($request): User;
}
