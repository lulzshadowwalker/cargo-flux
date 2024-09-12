<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'code' => 'hashed',
            'phone' => E164PhoneNumberCast::class,
        ];
    }

    public function expired(): Attribute
    {
        return Attribute::get(fn(): bool => now()->greaterThan($this->expires_at));
    }

    public function verified(): Attribute
    {
        return Attribute::get(fn(): bool => $this->verified_at !== null);
    }

    public function markAsVerified(): void
    {
        $this->verified_at = now();
        $this->save();
    }
}
