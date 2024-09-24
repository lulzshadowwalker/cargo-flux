<?php

namespace App\Http\Controllers\Api;

use App\Enums\TokenType;
use App\Enums\UserType;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\TokenResource;
use App\Models\Otp;
use App\Models\User;
use App\Support\AuthToken;
use Filament\Tables\Filters\Indicator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

// TODO: Refactor into a service class

class OtpController extends ApiController
{
    protected const OTP_EXPIRY = 15;

    public function send(SendOtpRequest $request)
    {
        // $code = mt_rand(100000, 999999);
        $code = '111111';

        Otp::create([
            'phone' => $request->phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY),
        ]);

        // TODO: send the OTP to the user

        return $this->response
            ->message('OTP sent successfully')
            ->meta(['expiresIn' => self::OTP_EXPIRY])
            ->build();
    }

    public function verify(VerifyOtpRequest $request)
    {
        $user = User::wherePhone('phone')->first();
        if (isset($user?->type) && $user->type !== UserType::tryFrom($request->type)) {
            return $this->response
                ->error(
                    title: 'User already registerd with a different type',
                    detail: 'User cannot register as both a customer and a driver',
                    code: Response::HTTP_CONFLICT,
                    indicator: 'USER_TYPE_CONFLICT'
                )->build();
        }

        $otp = Otp::latest()
            ->wherePhone($request->phone)
            ->latest()
            ->first();

        if (!$otp) {
            return $this->response
                ->message('OTP not found')
                ->error(
                    title: 'OTP not found',
                    detail: 'OTP not found',
                    code: Response::HTTP_NOT_FOUND,
                )
                ->build();
        }

        if ($otp->expired) {
            return $this->response
                ->message('OTP expired')
                ->error(
                    title: 'OTP expired',
                    detail: 'The OTP has expired',
                    code: Response::HTTP_GONE,
                )
                ->build();
        }

        if ($otp->verified) {
            return $this->response
                ->message('OTP already verified')
                ->error(
                    title: 'OTP already verified',
                    detail: 'The OTP has already been verified',
                    code: Response::HTTP_CONFLICT,
                    indicator: 'OTP_ALREADY_VERIFIED',
                )
                ->build();
        }

        if (!Hash::check($request->code, $otp->code)) {
            return $this->response
                ->message('OTP verification failed')
                ->error(
                    title: 'OTP verification failed',
                    detail: 'Invalid OTP',
                    code: Response::HTTP_UNAUTHORIZED,
                )
                ->build();
        }

        $otp->markAsVerified();

        $user = User::firstWhere('phone', $request->phone);
        if ($user) {
            $token = $user->createToken(config('app.name'))->plainTextToken;
            return TokenResource::make(new AuthToken($token, TokenType::PERMANENT));
        }

        $factory = JWTFactory::customClaims([
            'sub' => $request->phone,
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes(60)->timestamp,
        ]);

        $payload = $factory->make();
        $token = JWTAuth::encode($payload);

        return TokenResource::make(new AuthToken($token->get(), TokenType::TEMPORARY));
    }
}
