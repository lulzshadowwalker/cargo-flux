<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\DriverStatus;
use App\Enums\Language;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_out()
    {
        $user = User::factory()->has(DeviceToken::factory())->create();
        $token = $user->createToken(config('app.name'))->plainTextToken;

        $route =  route('auth.logout', ['lang' => Language::EN]);
        $headers = ['Authorization' => "Bearer $token"];
        $this->post($route, [
            // assert it deletes the attached device token on logout
            'deviceToken' => $user->deviceTokens->first()->token,
        ], $headers)->assertOk();

        $user->refresh();
        $this->assertEmpty($user->deviceTokens);
    }

    public function test_it_registers_a_customer()
    {
        $deviceToken = 'abc';
        $user = User::factory()->make(['type' => UserType::CUSTOMER]);
        $avatar = File::image('avatar.jpg', 200, 200);

        //
        {
            // JWT Token that should be returned from the verify otp response
            $factory = JWTFactory::customClaims([
                'sub' => $user->phone,
                'iat' => now()->timestamp,
                'exp' => now()->addMinutes(60)->timestamp,
            ]);

            $payload = $factory->make();
            $token = JWTAuth::encode($payload)->get();
        }

        $this->postJson(route('auth.register', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'phone' => $user->phone,
                    'dateOfBirth' => $user->date_of_birth,
                    'email' => $user->email,
                    'companyName' => 'Netflix',
                    'type' => 'CUSTOMER',
                    'avatar' => $avatar,
                ],
                'relationships' => [
                    'deviceTokens' => [
                        'data' => [
                            'token' => $deviceToken,
                        ],
                    ]
                ]
            ],
        ], ['Authorization' => "Bearer $token"])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'date_of_birth' => $user->date_of_birth,
            'phone' => $user->phone,
            'email' => $user->email,
            'status' => UserStatus::ACTIVE,
            'type' => UserType::CUSTOMER,
        ]);

        $user = User::wherePhone($user->phone)->first();
        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'company_name' => 'Netflix',
        ]);

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->id,
            'token' => $deviceToken,
        ]);

        $this->assertNotNull($user->avatar);
        $this->assertFileExists($user->avatarFile?->getPath() ?? '');
    }

    public function test_it_registers_a_driver()
    {
        $deviceToken = 'abc';
        $user = User::factory()->make(['type' => UserType::DRIVER]);
        $avatar = File::image('avatar.jpg', 200, 200);
        $passport = File::image('passport.jpg', 200, 200);
        $driverLicense = File::image('driver-license.jpg', 200, 200);
        $carLicense = File::image('car-license.jpg', 200, 200);
        $car = [
            File::image('car-front.jpg', 200, 200),
            File::image('car-back.jpg', 200, 200),
            File::image('car-left.jpg', 200, 200),
            File::image('car-right.jpg', 200, 200),
        ];

        //
        {
            // JWT Token that should be returned from the verify otp response
            $factory = JWTFactory::customClaims([
                'sub' => $user->phone,
                'iat' => now()->timestamp,
                'exp' => now()->addMinutes(60)->timestamp,
            ]);

            $payload = $factory->make();
            $token = JWTAuth::encode($payload)->get();
        }

        $this->postJson(route('auth.register', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'phone' => $user->phone,
                    'dateOfBirth' => $user->date_of_birth,
                    'email' => $user->email,
                    'type' => 'DRIVER',
                    'avatar' => $avatar,
                    'passport' => $passport,
                    'driverLicense' => $driverLicense,
                    'carLicense' => $carLicense,
                    'car' => $car,
                ],
                'relationships' => [
                    'deviceTokens' => [
                        'data' => [
                            'token' => $deviceToken,
                        ],
                    ]
                ]
            ],
        ], ['Authorization' => "Bearer $token"])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'date_of_birth' => $user->date_of_birth,
            'phone' => $user->phone,
            'email' => $user->email,
            'status' => UserStatus::ACTIVE,
            'type' => UserType::DRIVER,
        ]);

        $user = User::wherePhone($user->phone)->first();
        $driver = $user->driver;
        $this->assertDatabaseHas('drivers', [
            'user_id' => $user->id,
            'status' => DriverStatus::UNDER_REVIEW,
        ]);

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->id,
            'token' => $deviceToken,
        ]);

        $this->assertNotNull($user->avatar);
        $this->assertFileExists($user->avatarFile?->getPath() ?? '');

        $this->assertNotNull($driver->passport);
        $this->assertFileExists($driver->passportFile?->getPath() ?? '');

        $this->assertNotNull($driver->driverLicense);
        $this->assertFileExists($driver->driverLicenseFile?->getPath() ?? '');

        $this->assertNotNull($driver->carLicense);
        $this->assertFileExists($driver->carLicenseFile?->getPath() ?? '');

        $this->assertCount(count($car), $driver->carFilesMedia);
        foreach ($driver->carFilesMedia as $key => $file) {
            $this->assertEquals($car[$key]->name, $file->file_name);
            $this->assertFileExists($file->getPath());
        }
    }
}
