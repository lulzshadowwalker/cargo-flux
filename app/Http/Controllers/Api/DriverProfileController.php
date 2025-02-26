<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProfileController;
use App\Http\Requests\UpdateDriverProfileRequest;
use App\Http\Resources\DriverResource;
use Illuminate\Support\Facades\Auth;

class DriverProfileController extends ApiController // implements ProfileController
{
    public function index()
    {
        return DriverResource::make(Auth::user()->driver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDriverProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $language, UpdateDriverProfileRequest $request)
    {
        Auth::user()->update($request->mappedAttributes()->toArray());
        if ($avatar = $request->avatar()) {
            //  TODO: Should we delete the old avatar ?
            Auth::user()->addMedia($avatar)->toMediaCollection('avatar');
        }

        Auth::user()->driver->update([
            'first_name' => $request->firstName(),
            'last_name' => $request->lastName(),
            'middle_name' => $request->middleName(),
            'residence_address' => $request->residenceAddress(),
        ]);

        return DriverResource::make(Auth::user()->driver);
    }
}
