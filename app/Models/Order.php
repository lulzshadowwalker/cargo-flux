<?php

namespace App\Models;

use App\Casts\GeoPointCast;
use App\Casts\MoneyCast;
use App\Contracts\Payable;
use App\Enums\OrderPaymentMethod;
use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use App\Filters\QueryFilter;
use App\Observers\OrderObserver;
use App\Support\PayableItem;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

#[ObservedBy(OrderObserver::class)]
class Order extends Model implements Payable
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
        'cargo',
        'truck_category_id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
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
            'currentLocation' => GeoPointCast::class . ':current_location_latitude,current_location_longitude',
            'pickupLocation' => GeoPointCast::class . ':pickup_location_latitude,pickup_location_longitude',
            'deliveryLocation' => GeoPointCast::class . ':delivery_location_latitude,delivery_location_longitude',
            'price' => MoneyCast::class,
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

    public function scopeDriverAssigned(Builder $query)
    {
        return $query->where('status', OrderStatus::DRIVER_ASSIGNED);
    }

    public function scopeScheduled(Builder $query)
    {
        return $query->where('status', OrderStatus::SCHEDULED);
    }

    public function scopeHeadingToPickup(Builder $query)
    {
        return $query->where('status', OrderStatus::HEADING_TO_PICKUP);
    }

    public function scopePickupStarted(Builder $query)
    {
        return $query->where('status', OrderStatus::PICKUP_STARTED);
    }

    public function scopePickupCompleted(Builder $query)
    {
        return $query->where('status', OrderStatus::PICKUP_COMPLETED);
    }

    public function scopeCustomsProcessingStarted(Builder $query)
    {
        return $query->where('status', OrderStatus::CUSTOMS_PROCESSING_STARTED);
    }

    public function scopeCustomsProcessingCompleted(Builder $query)
    {
        return $query->where('status', OrderStatus::CUSTOMS_PROCESSING_COMPLETED);
    }

    public function scopeDropOffStarted(Builder $query)
    {
        return $query->where('status', OrderStatus::DROP_OFF_STARTED);
    }

    public function scopeDropOffCompleted(Builder $query)
    {
        return $query->where('status', OrderStatus::DROP_OFF_COMPLETED);
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

    public function scopeActive(Builder $query)
    {
        return $query->whereNotIn('status', [
            OrderStatus::PENDING_APPROVAL,
            OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            OrderStatus::DRIVER_ASSIGNED,
            OrderStatus::SCHEDULED,
            OrderStatus::CANCELED,
            OrderStatus::COMPLETED,
        ]);
    }

    public function scopeActiveOrScheduled(Builder $query)
    {
        return $query->whereNotIn('status', [
            OrderStatus::PENDING_APPROVAL,
            OrderStatus::PENDING_DRIVER_ASSIGNMENT,
            OrderStatus::CANCELED,
            OrderStatus::COMPLETED,
        ]);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters): Builder
    {
        return $filters->apply($builder);
    }

    public function isScheduled(): Attribute
    {
        return Attribute::get(fn() => isset($this->scheduled_at));
    }

    public function truckCategory(): BelongsTo
    {
        return $this->belongsTo(TruckCategory::class, 'truck_category_id');
    }

    public function stages(): Attribute
    {
        return Attribute::get(fn() => Arr::map(OrderStatus::cases(), function ($status) {
            $entry = $this->tracking()->where('status', $status)->latest()->first();
            return [
                'status' => $status,
                'is_completed' => isset($entry),
                'completed_at' => $entry?->created_at,
            ];
        }));
    }

    public function routeGroup(): BelongsTo
    {
        //  TODO: implement order.routeGroup relationship
        throw new \Exception('Unimplemented method order.routeGroup');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function items(): array
    {
        return [
            new PayableItem(
                //  TODO: Better payable item name for orders
                name: 'Shipment Order',
                price: $this->price,

                //  NOTE: Make sure quantity aligns with bulk orders.
                //   As it currently stands, "bulk orders" are simply multiple completely separate orders.
                //   So this should be fine, but something to consider.
                quantity: 1,
            ),
        ];
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function payer(): User
    {
        return $this->customer->user;
    }
}
