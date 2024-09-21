<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Support\SystemActor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderTrackingEntry extends Model
{
    use HasFactory;

    protected $table = 'order_tracking';

    protected $fillable = [
        'order_id',
        'status',
        'actor_type',
        'actor_id',
        'note'
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the actor that triggered the order tracking entry.
     */
    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    public function isSystemActor(): Attribute
    {
        return Attribute::get(fn() => $this->actor_type === SystemActor::class);
    }

    public function isCustomer(): Attribute
    {
        return Attribute::get(fn() => $this->actor_type === Customer::class);
    }

    public function isDriver(): Attribute
    {
        return Attribute::get(fn() => $this->actor_type === Driver::class);
    }
}
