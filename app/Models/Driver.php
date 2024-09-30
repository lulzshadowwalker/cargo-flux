<?php

namespace App\Models;

use App\Enums\DriverStatus;
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
use Illuminate\Notifications\Notification;

#[ObservedBy(DriverObserver::class)]
class Driver extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'status',
        'iban',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'status' => DriverStatus::class,
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

    public function fullName(): Attribute
    {
        return Attribute::get(fn(): string => $this->user->fullName);
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
}
