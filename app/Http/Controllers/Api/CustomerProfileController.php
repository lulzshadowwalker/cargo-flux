<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProfileController;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends ApiController implements ProfileController
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
    public function update($request)
    {
        Auth::user()->update($request->mappedAttributes()->toArray());
        return CustomerResource::make(Auth::user()->customer);
    }
}
