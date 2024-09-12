<?php

namespace Tests\Unit\Models;

use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_attribute(): void
    {
        $otp = Otp::create([
            'phone' => '+1234567890',
            'code' => '123456',
            'expires_at' => now()->subMinute(),
        ]);

        $this->assertTrue($otp->expired);
    }

    public function test_not_expired_attribute(): void
    {
        $otp = Otp::create([
            'phone' => '+1234567890',
            'code' => '123456',
            'expires_at' => now()->addMinute(),
        ]);

        $this->assertFalse($otp->expired);
    }

    public function test_verifed_attribute(): void
    {
        $otp = Otp::create([
            'phone' => '+1234567890',
            'code' => '123456',
            'expires_at' => now()->addMinute(),
        ]);

        $this->assertFalse($otp->verified);

        $otp->verified_at = now();;
        $otp->save();

        $this->assertTrue($otp->verified);
    }

    public function test_marked_as_verified(): void
    {
        $otp = Otp::create([
            'phone' => '+1234567890',
            'code' => '123456',
            'expires_at' => now()->addMinute(),
        ]);

        $otp->markAsVerified();

        $this->assertTrue($otp->verified);
    }

    public function test_code_is_hashed(): void
    {
        $otp = Otp::create([
            'phone' => '+1234567890',
            'code' => '123456',
            'expires_at' => now()->addMinute(),
        ]);

        $this->assertNotEquals('123456', $otp->code);
        $this->assertTrue(Hash::check('123456', $otp->code));
    }
}
