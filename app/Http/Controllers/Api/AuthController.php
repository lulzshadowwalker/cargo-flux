<?php

namespace App\Http\Controllers\Api;

use App\Enums\TokenType;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\TokenResource;
use App\Models\User;
use App\Support\AuthToken;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function register(RegisterRequest $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            abort(Response::HTTP_UNAUTHORIZED, 'Token from otp verification is required');
        }

        $phone = JWTAuth::parseToken()->getPayload()['sub'];
        if ($phone !== $request->input('phone')) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid phone number');
        }

        $user = User::create([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        return TokenResource::make(new AuthToken($token, TokenType::PERMANENT));
    }
}
