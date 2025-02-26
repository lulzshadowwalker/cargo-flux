<?php

namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\DriverObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(DriverObserver::class)]
class Driver extends Model implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, HasTranslations;

    public $translatable = ['first_name', 'middle_name', 'last_name'];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'status',
        'iban',
        'user_id',
        'residence_address',
        'secondary_phone',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'status' => DriverStatus::class,
            'secondary_phone' => E164PhoneNumberCast::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function truck(): HasOne
    {
        return $this->hasOne(Truck::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewer');
    }

    /**
     * Get the support tickets for the driver.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(SupportTicket::class, User::class);
    }

    /**
     * Returns the localized name of the driver
     * of two parts as stored in the user model.
     */
    public function fullName(): Attribute
    {
        return Attribute::get(fn(): string => $this->first_name . ' ' . $this->last_name);
    }

    /**
     * Returns the localized full legal name of the driver
     * of three parts as stated in the driver's passport.
     */
    public function fullLegalName(): Attribute
    {
        return Attribute::get(
            fn(): string  => $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name
        );
    }

    public function phone(): Attribute
    {
        return Attribute::get(fn() => $this->user->phone);
    }

    public function isApproved(): Attribute
    {
        return Attribute::get(fn() => $this->status === DriverStatus::APPROVED);
    }

    public function isRejected(): Attribute
    {
        return Attribute::get(fn() => $this->status === DriverStatus::REJECTED);
    }

    public function isUnderReview(): Attribute
    {
        return Attribute::get(fn() => $this->status === DriverStatus::UNDER_REVIEW);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', DriverStatus::APPROVED);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', DriverStatus::REJECTED);
    }

    public function scopeUnderReview(Builder $query): Builder
    {
        return $query->where('status', DriverStatus::UNDER_REVIEW);
    }

    public function approve(): void
    {
        // TODO: Dispatch event ..
        $this->status = DriverStatus::APPROVED;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = DriverStatus::REJECTED;
        $this->save();
    }

    public function deviceTokens(): HasMany
    {
        return $this->user->deviceTokens();
    }

    const MEDIA_COLLECTION_PASSPORT = "passport";
    const MEDIA_COLLECTION_LICENSE = "license";

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION_PASSPORT)
            ->singleFile();

        $this->addMediaCollection(self::MEDIA_COLLECTION_LICENSE)
            ->singleFile();
    }

    /**
     *  TODO: Proxy unresolved PROPERTIES to the user model.
     *  as many of the user's attributes are needed in the customer model
     */

    /**
     * Get the driver's avatar URL.
     */
    public function avatar(): Attribute
    {
        return Attribute::get(fn() => $this->user->avatar);
    }

    /**
     * Get the driver's avatar file.
     */
    public function avatarFile(): Attribute
    {
        return Attribute::get(fn() => $this->user->avatarFile);
    }

    /**
     * Get the driver's passport URL.
     */
    public function passport(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_PASSPORT) ?: null
        );
    }

    /**
     * Get the driver's passport file.
     */
    public function passportFile(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMedia(self::MEDIA_COLLECTION_PASSPORT) ?: null
        );
    }

    /**
     * Get the driver's driver license URL.
     */
    public function license(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMediaUrl(self::MEDIA_COLLECTION_LICENSE) ?: null
        );
    }

    /**
     * Get the driver's driver license file.
     */
    public function licenseFile(): Attribute
    {
        return Attribute::get(
            fn() => $this->getFirstMedia(self::MEDIA_COLLECTION_LICENSE) ?: null
        );
    }
}
