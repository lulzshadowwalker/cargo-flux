<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Enums\TokenType;
use App\Enums\UserType;
use App\Http\Controllers\Api\OtpController;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Response\JsonResponseBuilder;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_otp()
    {
        $request = SendOtpRequest::create('/api/send-otp', 'POST', [
            'phone' => '+1234567890',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->send($request);

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
        $this->assertDatabaseHas('otps', [
            'phone' => '+1234567890',
        ]);
    }

    public function test_verify_otp_success_non_existent_user()
    {
        Otp::create([
            'phone' => '+1234567890',
            'code' => Hash::make('111111'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $request = VerifyOtpRequest::create('/api/auth/otp/verify', 'POST', [
            'phone' => '+1234567890',
            'code' => '111111',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertNotEmpty($response->resource->token);
        $this->assertEquals(
            TokenType::TEMPORARY,
            $response->resource->type
        );
    }

    public function test_verify_otp_success_existing_user()
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'type' => UserType::CUSTOMER,
        ]);

        Otp::create([
            'phone' => '+1234567890',
            'code' => Hash::make('111111'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $request = VerifyOtpRequest::create('/api/auth/otp/verify', 'POST', [
            'phone' => '+1234567890',
            'code' => '111111',
            'type' => 'CUSTOMER',
            'deviceToken' => 'abc',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertNotEmpty($response->resource->token);
        $this->assertEquals(
            TokenType::PERMANENT,
            $response->resource->type
        );

        $this->assertEquals('abc', $user->deviceTokens?->first()?->token);
    }

    public function test_verify_otp_not_found()
    {
        $request = VerifyOtpRequest::create('/api/auth/otp/verify', 'POST', [
            'phone' => '+1234567890',
            'code' => '111111',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertEquals('OTP not found', $response->getData()->errors[0]->detail);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function test_verify_otp_expired()
    {
        Otp::create([
            'phone' => '+1234567890',
            'code' => Hash::make('111111'),
            'expires_at' => now()->subMinutes(5),
        ]);

        $request = VerifyOtpRequest::create('/api/verify-otp', 'POST', [
            'phone' => '+1234567890',
            'code' => '111111',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertEquals('OTP expired', $response->getData()->errors[0]->title);
        $this->assertEquals(Response::HTTP_GONE, $response->getStatusCode());
    }

    public function test_verify_otp_already_verified()
    {
        Otp::create([
            'phone' => '+1234567890',
            'code' => Hash::make('111111'),
            'expires_at' => now()->addMinutes(5),
            'verified_at' => now(),
        ]);

        $request = VerifyOtpRequest::create('/api/verify-otp', 'POST', [
            'phone' => '+1234567890',
            'code' => '111111',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertEquals('OTP already verified', $response->getData()->errors[0]->title);
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    public function test_verify_otp_invalid_code()
    {
        Otp::create([
            'phone' => '+1234567890',
            'code' => Hash::make('111111'),
            'expires_at' => now()->addMinutes(5),
        ]);

        $request = VerifyOtpRequest::create('/api/verify-otp', 'POST', [
            'phone' => '+1234567890',
            'code' => '222222',
        ]);

        $controller = new OtpController(new JsonResponseBuilder);
        $response = $controller->verify($request);

        $this->assertEquals('Invalid OTP', $response->getData()->errors[0]->detail);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
