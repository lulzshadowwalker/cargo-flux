<?php

namespace App\Listeners;

use App\Events\PaymentPaid;
use App\Notifications\InvoicePaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;

class SendPayableInvoice implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(PaymentPaid $event): void
    {
        DB::transaction(function () use ($event) {
            $payment = $event->payment;

            $invoice = $payment->invoice()->create();

            $html = view('invoices.show', compact('invoice'))->render();

            if (! app()->environment('testing')) {
                Browsershot::html($html)
                    ->showBackground()
                    ->margins(10, 10, 10, 10)
                    ->save($invoice->filepath());
            }

            $payment->payable->payer()->notify(new InvoicePaid($invoice));
        });
    }
}
