<?php

namespace App\Services;

use App\Contracts\RegisterationService;
use App\Enums\UserType;
use App\Http\Requests\DriverRegisterationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverRegisterationService implements RegisterationService
{
    public function register(Request $request): User
    {
        return DB::transaction(function () use ($request) {
            $request = $this->validate($request);

            $user = User::create($request->mappedAttributes(['type' => UserType::DRIVER])->toArray());

            $user->driver()->create();

            if ($request->avatar()) {
                $user->addMedia($request->avatar())->toMediaCollection(User::MEDIA_COLLECTION_AVATAR);
            }

            return $user;
        });
    }

    private function validate(Request $request): DriverRegisterationRequest
    {
        $r = new DriverRegisterationRequest;
        $r->setMethod('POST');
        $r->merge($request->all());
        $r->files = $request->files;

        $r->validate($r->rules());

        return $r;
    }
}
