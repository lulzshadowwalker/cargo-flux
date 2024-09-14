<?php

namespace App\Models;

use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'status',
        'payment_method',
        'payment_status',
        'scheduled_at',
        'pickup_location_latitude',
        'pickup_location_longitude',
        'delivery_location_latitude',
        'delivery_location_longitude',
        'current_location_latitude',
        'current_location_longitude',
        'current_location_recorded_at',
        'customer_id',
        'driver_id',
        'currency_id',
        'truck_id',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'amount' => 'decimal:2',
            'scheduled_at' => 'datetime',
            'pickup_location_latitude' => 'decimal:7',
            'pickup_location_longitude' => 'decimal:7',
            'delivery_location_latitude' => 'decimal:7',
            'delivery_location_longitude' => 'decimal:7',
            'current_location_latitude' => 'decimal:7',
            'current_location_longitude' => 'decimal:7',
            'current_location_recorded_at' => 'datetime',
            'customer_id' => 'integer',
            'driver_id' => 'integer',
            'currency_id' => 'integer',
            'truck_id' => 'integer',
            'status' => OrderStatus::class,
            'payment_method' => OrderPaymentMethod::class,
            'payment_status' => OrderPaymentStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function tracking(): HasMany
    {
        return $this->hasMany(OrderTrackingEntry::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeScheduled(Builder $query)
    {
        return $query->where('status', OrderStatus::SCHEDULED);
    }

    public function scopeInProgress(Builder $query)
    {
        return $query->where('status', OrderStatus::IN_PROGRESS);
    }

    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', OrderStatus::COMPLETED);
    }

    public function scopeCanceled(Builder $query)
    {
        return $query->where('status', OrderStatus::CANCELED);
    }

    public function scopePendingApproval(Builder $query)
    {
        return $query->where('status', OrderStatus::PENDING_APPROVAL);
    }

    public function scopePendingDriverAssignment(Builder $query)
    {
        return $query->where('status', OrderStatus::PENDING_DRIVER_ASSIGNMENT);
    }
}
