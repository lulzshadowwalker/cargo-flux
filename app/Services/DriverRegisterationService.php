<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\DriverRegisterationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DriverRegisterationService implements RegisterationService
{
    public function register(Request $request): User
    {
        $request = $this->validate($request);

        $user = User::create($request->mappedAttributes(['type' => UserType::DRIVER])->toArray());

        $user->driver()->create();

        return $user;
    }

    private function validate(Request $request): DriverRegisterationRequest
    {
        $r = new DriverRegisterationRequest;
        $r->setMethod('POST');
        $r->merge($request->all());

        $r->validate($r->rules());

        return $r;
    }
}
