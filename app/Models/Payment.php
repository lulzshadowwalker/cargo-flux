<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_reference',
        'gateway',
        'status',
        'user_id',
        'payable_type',
        'payable_id',
        'details',
        'amount',
        'currency_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'gateway' => PaymentGateway::class,
            'details' => 'array',
            'price' => MoneyCast::class,
            'external_reference' => 'string',
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
