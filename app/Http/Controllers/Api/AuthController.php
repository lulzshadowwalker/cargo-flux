<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ResponseBuilder;
use App\Enums\TokenType;
use App\Factories\RegisterationServiceFactory;
use App\Http\Requests\BaseRegisterationRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Resources\TokenResource;
use App\Support\AuthToken;
use Illuminate\Http\Response;

class AuthController extends ApiController
{
    public function __construct(
        protected RegisterationServiceFactory $serviceFactory,
        protected ResponseBuilder $response
    ) {
        //
    }

    public function register(BaseRegisterationRequest $request)
    {
        $user = $this->serviceFactory->make($request->mappedAttributes()['type'])->register($request);

        $deviceToken = $request->mappedAttributes()['device_token'];
        if ($deviceToken) {
            $user->deviceTokens()->firstOrCreate(['token' => $deviceToken]);
        }

        $token = $user->createToken(config('app.name'))->plainTextToken;

        return TokenResource::make(new AuthToken($token, TokenType::PERMANENT));
    }

    public function logout(LogoutRequest $request)
    {
        if ($request->deviceToken) {
            $request->user()->deviceTokens()->whereToken($request->deviceToken)->delete();
        }

        $request->user()->currentAccessToken()->delete();

        return $this->response->message('Logged out successfully')->build(Response::HTTP_OK);
    }
}
