<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\CustomerRegisterationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerRegisterationService implements RegisterationService
{
    public function register($request): User
    {
        return DB::transaction(function () use ($request) {
            $request = $this->validate($request);

            $user = User::create($request->mappedAttributes(['type' => UserType::CUSTOMER])->toArray());

            $user->customer()->create($request->mappedAttributes()->toArray());

            if ($request->avatar()) {
                $user->addMedia($request->avatar())->toMediaCollection(User::MEDIA_COLLECTION_AVATAR);
            }

            return $user;
        });
    }

    private function validate(Request $request): CustomerRegisterationRequest
    {
        $r = new CustomerRegisterationRequest;
        $r->setMethod('POST');
        $r->merge($request->all());
        $r->files = $request->files;

        $r->validate($r->rules());


        return $r;
    }
}
