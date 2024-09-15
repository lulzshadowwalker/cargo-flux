<?php

namespace App\Contracts;

use App\Http\Requests\UpdateCustomerProfileRequest;
use Illuminate\Foundation\Http\FormRequest;

interface ProfileController
{
    public function index();
    public function update(FormRequest|UpdateCustomerProfileRequest $request);
}
