<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Http\Request;

interface RegisterationService
{
    public function register(Request $request): User;
}
