<?php

namespace App\Http\Controllers\Api;

//  TODO: Update customer/driver profile avatar 

use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends ApiController // implements ProfileController
{
    public function index()
    {
        return CustomerResource::make(Auth::user()->customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(string $language, UpdateCustomerProfileRequest $request)
    {
        Auth::user()->update($request->mappedAttributes()->toArray());
        Auth::user()->customer->update($request->customerMappedAttributes()->toArray());

        return CustomerResource::make(Auth::user()->customer);
    }
}
