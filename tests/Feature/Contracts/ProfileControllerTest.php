<?php

namespace Tests\Feature\Contracts;

use App\Enums\Language;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_can_update_their_profile(): void
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer->user);

        $avatar = File::image('avatar.jpg', 100, 100);

        $this->patch(route('profile.index', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'email' => 'john@example.com',
                    'companyName' => 'ACME',
                    'avatar' => $avatar,
                ],
            ],
        ])->assertOk();

        $customer->refresh();

        $this->assertEquals('John Doe', $customer->fullName);
        $this->assertEquals('john@example.com', $customer->user->email);
        $this->assertEquals('ACME', $customer->company_name);
        $this->assertNotNull($customer->user->avatarFile);
        $this->assertEquals('avatar.jpg', $customer->user->avatarFile->file_name);
    }

    public function test_drivers_can_update_their_profile(): void
    {
        $driver = Driver::factory()->create();
        $this->actingAs($driver->user);

        $avatar = File::image('avatar.jpg', 100, 100);

        $this->patch(route('profile.index', ['lang' => Language::EN]), [
            'data' => [
                'attributes' => [
                    'firstName' => [
                        'ar' => 'John',
                    ],
                    'middleName' => [
                        'ar' => 'Middle',
                    ],
                    'lastName' => [
                        'ar' => 'Doe',
                    ],
                    'email' => 'john@example.com',
                    'dateOfBirth' => '1990-01-01',
                    'residenceAddress' => 'Neverland',
                    'secondaryPhone' => '+201234567890',
                    'avatar' => $avatar,
                ],
            ],
        ])->assertOk();

        $driver->refresh();

        $this->assertEquals('John', $driver->getTranslation('first_name', 'ar'));
        $this->assertEquals('Middle', $driver->getTranslation('middle_name', 'ar'));
        $this->assertEquals('Doe', $driver->getTranslation('last_name', 'ar'));
        $this->assertEquals('john@example.com', $driver->user->email);
        $this->assertNotNull($driver->user->avatarFile);
        $this->assertEquals('avatar.jpg', $driver->user->avatarFile->file_name);
        $this->assertEquals('1990-01-01', $driver->user->date_of_birth->format('Y-m-d'));
        $this->assertEquals('Neverland', $driver->residence_address);
        $this->assertEquals('+201234567890', $driver->secondary_phone);
    }
}
