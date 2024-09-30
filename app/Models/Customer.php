<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notification;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Get the support tickets for the customer.
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(SupportTicket::class, User::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::get(fn() => $this->user->fullName);
    }

    public function phone(): Attribute
    {
        return Attribute::get(fn() => $this->user->phone);
    }

    public function deviceTokens(): HasMany
    {
        return $this->user->deviceTokens();
    }
}
