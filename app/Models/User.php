<?php

namespace App\Models;

use App\Enums\UserStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasName, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'status' => UserStatus::class,
            'phone' => E164PhoneNumberCast::class,
        ];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function isCustomer(): Attribute
    {
        return Attribute::get(fn(): bool => $this->customer !== null);
    }

    public function isDriver(): Attribute
    {
        return Attribute::get(fn(): bool => $this->driver !== null);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn(): string => "{$this->first_name} {$this->last_name}");
    }

    public function getFilamentName(): string
    {
        return $this->fullName;
    }

    public function activate(): void
    {
        $this->status = UserStatus::ACTIVE;
        $this->save();
    }

    public function suspend(): void
    {
        $this->status = UserStatus::SUSPENDED;
        $this->save();
    }

    public function ban(): void
    {
        $this->status = UserStatus::BANNED;
        $this->save();
    }

    public function isActive(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === UserStatus::ACTIVE);
    }

    public function isSuspended(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === UserStatus::SUSPENDED);
    }

    public function isBanned(): Attribute
    {
        return Attribute::get(fn(): bool => $this->status === UserStatus::BANNED);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // TODO: Only admins should be able to access the panel. 
        return true;
    }

    /**
     * Get the user's support tickets.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
