<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerResource;
use Exception;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends ApiController
{
    public function me()
    {
        return CustomerResource::make(Auth::user()->customer);
    }

    public function update()
    {
        throw new Exception('Not implemented');
    }
}
