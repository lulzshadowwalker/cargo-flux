<?php

namespace App\Jobs;

use App\Contracts\Payable;
use App\Models\Invoice;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Spatie\Browsershot\Browsershot;

class SendInvoice implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected Invoice $invoice)
    {
        //
    }

    public function handle(): void
    {
        if (! $this->invoice->payment->isPaid) {
            throw new InvalidArgumentException('The invoice has not been paid.');
        }

        $html = view('invoices.show')->render();

        $d = Browsershot::html($html)
            ->showBackground()
            ->margins(10, 10, 10, 10)
            ->save($invoicePath = storage_path('app/sample-invoice.pdf'));

        User::first()->notify(new InvoicePaid($invoicePath));
    }
}
