<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        if (! $invoice->number) {
            $invoice->number = strtoupper(uniqid('INVOICE-'));
        }
    }

    public function created(Invoice $invoice): void
    {
        //
    }

    public function updated(Invoice $invoice): void
    {
        //
    }

    public function deleted(Invoice $invoice): void
    {
        //
    }

    public function restored(Invoice $invoice): void
    {
        //
    }

    public function forceDeleted(Invoice $invoice): void
    {
        //
    }
}
