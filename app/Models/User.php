<?php

namespace App\Models;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Observers\UserObserver;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\Wallet;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements
    JWTSubject,
    HasName,
    FilamentUser,
    HasMedia,
    HasLocalePreference,
    Wallet
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        HasRoles,
        HasPanelShield,
        Notifiable,
        InteractsWithMedia,
        HasWallet,
        HasWallets;

    const WALLET_REWARDS = 'rewards';

    protected $fillable = [
        "first_name",
        "last_name",
        "phone",
        "date_of_birth",
        "email",
        "password",
        "status",
        "type",
    ];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "password" => "hashed",
            "date_of_birth" => "date",
            "status" => UserStatus::class,
            "phone" => E164PhoneNumberCast::class,
            "type" => UserType::class,
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

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function isCustomer(): Attribute
    {
        return Attribute::get(fn(): bool => $this->type === UserType::CUSTOMER);
    }

    public function isDriver(): Attribute
    {
        return Attribute::get(fn(): bool => $this->type === UserType::DRIVER);
    }

    /**
     * Checks if the user's base type is admin.
     *
     * Further roles and permissions may be assigned in the admin dashboard using Filament Shield.
     */
    public function isAdmin(): Attribute
    {
        return Attribute::get(fn(): bool => $this->type === UserType::ADMIN);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where("type", UserType::ADMIN);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function fullName(): Attribute
    {
        return Attribute::get(
            fn(): string => "{$this->first_name} {$this->last_name}"
        );
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
        return Attribute::get(
            fn(): bool => $this->status === UserStatus::ACTIVE
        );
    }

    public function isSuspended(): Attribute
    {
        return Attribute::get(
            fn(): bool => $this->status === UserStatus::SUSPENDED
        );
    }

    public function isBanned(): Attribute
    {
        return Attribute::get(
            fn(): bool => $this->status === UserStatus::BANNED
        );
    }

    /**
     * Get the user's support tickets.
     * @return HasMany<SupportTicket,User>
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function orders(): HasManyThrough
    {
        if ($this->isCustomer) {
            return $this->hasManyThrough(Order::class, Customer::class);
        } elseif ($this->isDriver) {
            return $this->hasManyThrough(Order::class, Driver::class);
        }

        throw new \Exception("User is neither a customer nor a driver");
    }
    /**
     * @return HasMany<DeviceToken,User>
     */
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    const MEDIA_COLLECTION_AVATAR = "avatar";

    public function registerMediaCollections(): void
    {
        $name = Str::replace(' ', '+', $this->fullName);

        $this->addMediaCollection(self::MEDIA_COLLECTION_AVATAR)
            ->singleFile()
            ->useFallbackUrl("https://ui-avatars.com/api/?name={$name}");
    }

    /**
     * Get the user's avatar URL.
     */
    public function avatar(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_AVATAR) ?: null
        );
    }

    /**
     * Get the user's avatar file.
     */
    public function avatarFile(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMedia(self::MEDIA_COLLECTION_AVATAR) ?: null
        );
    }

    public function preferredLocale(): ?string
    {
        return $this->preferences?->language;
    }

    public function routeNotificationForPush(Notification $notification): array
    {
        return $this->deviceTokens->pluck("token")->toArray();
    }

    /**
     * Get a collection of the the referrals made by the user.
     * e.g. the friends that has signed up using the user's referral code.
     */
    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get the the user that referred the user to the platform.
     */
    public function referral()
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }
}
