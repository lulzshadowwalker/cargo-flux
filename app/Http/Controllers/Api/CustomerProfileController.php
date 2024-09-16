<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends ApiController //  implements ProfileController
{
    public function index()
    {
        return CustomerResource::make(Auth::user()->customer);
    }

    public function update(UpdateCustomerProfileRequest $request)
    {
        Auth::user()->update($request->mappedAttributes());
        return CustomerResource::make(Auth::user()->customer);
    }
}
