<?php

namespace App\Models;

use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[ObservedBy(InvoiceObserver::class)]
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'payment_id'
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function filepath(): string
    {
        return storage_path("app/invoices/{$this->number}.pdf");
    }

    /**
     * Get the stripped number of the invoice.
     * 
     * @example INVOICE-1234 => 1234
     */
    public function strippedNumber(): Attribute
    {
        return Attribute::get(fn() => Str::replace('INVOICE-', '', $this->number));
    }
}
