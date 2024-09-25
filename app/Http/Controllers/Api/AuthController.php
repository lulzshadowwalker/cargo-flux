<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ResponseBuilder;
use App\Enums\TokenType;
use App\Factories\RegisterationServiceFactory;
use App\Http\Requests\BaseRegisterationRequest;
use App\Http\Resources\TokenResource;
use App\Support\AuthToken;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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

        $token = $user->createToken(config('app.name'))->plainTextToken;

        return TokenResource::make(new AuthToken($token, TokenType::PERMANENT));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->response->message('Logged out successfully')->build(Response::HTTP_OK);
    }
}
