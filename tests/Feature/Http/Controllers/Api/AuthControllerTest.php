<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\DriverStatus;
use App\Enums\Language;
use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Models\DeviceToken;
use App\Models\Driver;
use App\Models\TruckCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $truckCategory = TruckCategory::factory()->create();

        $deviceToken = 'abc';
        $user = User::factory()->make(['type' => UserType::DRIVER]);
        $driver = Driver::factory()->make();
        $avatar = File::image('avatar.jpg', 200, 200);
        $passport = File::image('passport.jpg', 200, 200);
        $driverLicense = File::image('driver-license.jpg', 200, 200);
        $truckLicense = File::image('truck-license.jpg', 200, 200);
        $truckImages = [
            File::image('truck-front.jpg', 200, 200),
            File::image('truck-back.jpg', 200, 200),
            File::image('truck-left.jpg', 200, 200),
            File::image('truck-right.jpg', 200, 200),
        ];
        $authorizationClause = File::image('authorization-clause.jpg', 200, 200);

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
                    'firstName' => [
                        'en' => $driver->getTranslation('first_name', 'en'),
                        'ar' => $driver->getTranslation('first_name', 'ar'),
                    ],
                    'middleName' => [
                        'en' => $driver->getTranslation('middle_name', 'en'),
                        'ar' => $driver->getTranslation('middle_name', 'ar'),
                    ],
                    'lastName' => [
                        'en' => $driver->getTranslation('last_name', 'en'),
                        'ar' => $driver->getTranslation('last_name', 'ar'),
                    ],
                    'phone' => $user->phone,
                    //  NOTE: It should accept numbers without a + prefix or a double leading zeros:
                    'secondaryPhone' => '00201234567890',
                    'dateOfBirth' => $user->date_of_birth,
                    'email' => $user->email,
                    'type' => 'DRIVER',
                    'avatar' => $avatar,
                    'passport' => $passport,
                    'license' => $driverLicense,
                    'truckLicense' => $truckLicense,
                    'truckImages' => $truckImages,
                    'residenceAddress' => $driver->residence_address,
                ],
                'relationships' => [
                    'deviceTokens' => [
                        'data' => [
                            'token' => $deviceToken,
                        ],
                    ],
                    'truck' => [
                        'data' => [
                            'licensePlate' => 'ABC123',
                            'truckCategory' => $truckCategory->id,
                            'isPersonalProperty' => false,
                            'authorizationClause' => $authorizationClause,
                            'nationality' => 'JO',
                        ],
                    ],
                ]
            ],
        ], ['Authorization' => "Bearer $token"])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'first_name' => $driver->getTranslation('first_name', 'ar'),
            'last_name' => $driver->getTranslation('last_name', 'ar'),
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
            'residence_address' => $driver->residence_address,
            'secondary_phone' => '+201234567890',
        ]);

        $model = $user->driver;
        $this->assertEquals($driver->getTranslation('first_name', 'en'), $model->first_name);
        $this->assertEquals($driver->getTranslation('middle_name', 'en'), $model->middle_name);
        $this->assertEquals($driver->getTranslation('last_name', 'en'), $model->last_name);

        $this->assertEquals($driver->getTranslation('first_name', 'ar'), $model->getTranslation('first_name', 'ar'));
        $this->assertEquals($driver->getTranslation('middle_name', 'ar'), $model->getTranslation('middle_name', 'ar'));
        $this->assertEquals($driver->getTranslation('last_name', 'ar'), $model->getTranslation('last_name', 'ar'));

        $this->assertDatabaseHas('trucks', [
            'driver_id' => $driver->id,
            'license_plate' => 'ABC123',
            'truck_category_id' => $truckCategory->id,
            'is_personal_property' => false,
            'nationality' => 'JO',
        ]);

        $this->assertDatabaseHas('device_tokens', [
            'user_id' => $user->id,
            'token' => $deviceToken,
        ]);

        $this->assertNotNull($user->avatar);
        $this->assertFileExists($user->avatarFile?->getPath() ?? '');

        $this->assertNotNull($driver->passport);
        $this->assertFileExists($driver->passportFile?->getPath() ?? '');

        $this->assertNotNull($driver->license);
        $this->assertFileExists($driver->licenseFile?->getPath() ?? '');

        $this->assertNotNull($driver->truck->license);
        $this->assertFileExists($driver->truck->licenseFile?->getPath() ?? '');

        $this->assertCount(count($truckImages), $driver->truck->images);
        foreach ($driver->truck->images as $key => $file) {
            $this->assertEquals($truckImages[$key]->name, $file->file_name);
            $this->assertFileExists($file->getPath());
        }

        $this->assertNotNull($driver->truck->authorizationClause);
        $this->assertFileExists($driver->truck->authorizationClauseFile?->getPath() ?? '');
    }

    public function test_authorization_clause_is_required_when_truck_is_not_personal_property()
    {
        $truckCategory = TruckCategory::factory()->create();
        $user = User::factory()->make(['type' => UserType::DRIVER]);
        $driver = Driver::factory()->make();
        $avatar = File::image('avatar.jpg', 200, 200);
        $passport = File::image('passport.jpg', 200, 200);
        $driverLicense = File::image('driver-license.jpg', 200, 200);
        $truckLicense = File::image('truck-license.jpg', 200, 200);
        $truckImages = [
            File::image('truck-front.jpg', 200, 200),
            File::image('truck-back.jpg', 200, 200),
            File::image('truck-left.jpg', 200, 200),
            File::image('truck-right.jpg', 200, 200),
        ];
        //  NOTE: 'authorizationClause' is intentionally omitted.

        $factory = JWTFactory::customClaims([
            'sub' => $user->phone,
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes(60)->timestamp,
        ]);
        $payload = $factory->make();
        $token = JWTAuth::encode($payload)->get();

        $response = $this->postJson(route('auth.register', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'firstName' => [
                        'en' => $driver->getTranslation('first_name', 'en'),
                        'ar' => $driver->getTranslation('first_name', 'ar'),
                    ],
                    'middleName' => [
                        'en' => $driver->getTranslation('middle_name', 'en'),
                        'ar' => $driver->getTranslation('middle_name', 'ar'),
                    ],
                    'lastName' => [
                        'en' => $driver->getTranslation('last_name', 'en'),
                        'ar' => $driver->getTranslation('last_name', 'ar'),
                    ],
                    'phone' => $user->phone,
                    'secondaryPhone' => '+201234567890',
                    'dateOfBirth' => $user->date_of_birth,
                    'email' => $user->email,
                    'type' => 'DRIVER',
                    'avatar' => $avatar,
                    'passport' => $passport,
                    'license' => $driverLicense,
                    'truckLicense' => $truckLicense,
                    'truckImages' => $truckImages,
                    'residenceAddress' => $driver->residence_address,
                ],
                'relationships' => [
                    'deviceTokens' => [
                        'data' => [
                            'token' => 'abc',
                        ],
                    ],
                    'truck' => [
                        'data' => [
                            'licensePlate' => 'ABC123',
                            'truckCategory' => $truckCategory->id,
                            'isPersonalProperty' => false,
                            // Missing 'authorizationClause' should trigger validation error.
                        ],
                    ],
                ]
            ],
        ], ['Authorization' => "Bearer $token"]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['data.relationships.truck.data.authorizationClause']);
    }
}
