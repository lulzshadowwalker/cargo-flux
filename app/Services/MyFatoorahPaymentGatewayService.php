<?php

namespace App\Services;

use App\Contracts\Payable;
use App\Contracts\PaymentGatewayService;
use App\Enums\Language;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Events\PayablePaid;
use App\Events\PaymentPaid;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Payment;
use App\Support\PaymentMethod;
use Brick\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberUtil;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use Illuminate\Support\Str;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;

class MyFatoorahPaymentGatewayService implements PaymentGatewayService
{
    protected ?MyFatoorahPayment $client = null;

    /**
     * Get the available payment methods for the given price
     *
     * @param Money $price
     *
     * @return array<PaymentMethod>
     */
    public function paymentMethods(Money $price): array
    {
        $methods = $this->client()->initiatePayment(
            $price->getAmount(),
            $price->getCurrency()->getCurrencyCode(),
        );

        return array_map(fn($method) => PaymentMethod::fromMyFatoorah($method), $methods);
    }

    /**
     * Start the payment process
     */
    public function start(Payable $payable, string $paymentMethodId): array
    {
        $user = Auth::user();;

        $countryCode = '+' . PhoneNumberUtil::getInstance()->getCountryCodeForRegion($user->phone->getCountry());
        $amount = $payable->price()->getAmount();
        $currencyCode = $payable->price()->getCurrency()->getCurrencyCode();

        $fields = [
            'InvoiceValue' => (string) $amount,
            'CustomerName' => $user->fullName,
            'CallBackUrl' => route('payments.callback', ['lang' => app()->getLocale()]),
            'ErrorUrl' => route('payments.callback', ['lang' => app()->getLocale()]),

            'DisplayCurrencyIso' => $currencyCode,

            //  NOTE: [ISO Lookups](https://docs.myfatoorah.com/docs/iso-lookups)
            'MobileCountryCode' => $countryCode,

            //  NOTE: """
            //  String uses English letters ONLY and does not accept Arabic characters Its length is between 0 and 11
            //  Regular expression pattern is ^(?:(+)|(00)|(*)|())[0-9]{3,14}((#)|())$
            //  """
            'CustomerMobile' => Str::replace($countryCode, '', $user->phone->formatE164()),
            'CustomerEmail' => $user->email,
            'Language' => $user->preferences?->language ?: Language::EN,
            'UserDefinedField' => json_encode([
                'payable_type' => get_class($payable),
                'payable_id' => $payable->id,
            ]),
            'CustomerAddress' => [
                'Block' => null,
                'Street' => null,
                'HouseBuildingNo' => null,
                'Address' => null,
                'AddressInstructions' => null,
            ],
            'InvoiceItems' => Arr::map($payable->items(), fn($item) => [
                'ItemName' => $item->name(),
                'Quantity' => $item->quantity(),
                'UnitPrice' => $item->price()->getAmount(),
            ]),
        ];

        $response = $this->client()->getInvoiceURL($fields, $paymentMethodId);

        $payment = Order::first()->payments()->create([
            'user_id' => Auth::user()->id,
            'external_reference' => $response['invoiceId'],
            'gateway' => PaymentGateway::MY_FATOORAH,
            'amount' => $payable->price()->getAmount(),
            'currency_id' => Currency::whereCode($currencyCode)->first()->id,

            //  NOTE: Payment details are provided from MyFatoorah via the success/failure callback
            //  or they can obtained via the [GetPaymentStatus](https://docs.myfatoorah.com/docs/get-payment-status) API
            'details' => null,
        ]);

        return [$payment, $response['invoiceURL']];
    }

    /**
     * Handle the success/failure callbacks
     */
    public function callback(Request $request): void
    {
        Log::info('MyFatoorah callback', $request->all());

        $handler = new MyFatoorahPaymentStatus($this->config());

        $details = $handler->getPaymentStatus($request->paymentId, 'PaymentId');

        $payment = Payment::where('external_reference', $details->InvoiceId)->first();
        if (! $payment) {
            Log::critical('Payment not found', (array) $details);
        }

        $payment->details = $details;

        //  TODO: Thoroughly test the payment status handling
        switch ($details->InvoiceStatus) {
            case 'Failed':
                $payment->status = PaymentStatus::FAILED;
                break;
            case 'Paid':
                $payment->status = PaymentStatus::PAID;
                break;
            case 'Pending':
                $payment->status = PaymentStatus::PENDING;
                break;
            default:
                Log::critical('Unknown payment status', (array) $details);
        }

        $payment->save();

        Log::info('MyFatoorah callback handled successfully', [
            'payment_id' => $payment->id,
            'external_reference' => $details->InvoiceId,
        ]);
    }

    /**
     * Get the client instance
     */
    protected function client(): MyFatoorahPayment
    {
        if ($this->client) {
            return $this->client;
        }

        return new MyFatoorahPayment($this->config());
    }

    /**
     * Get the configuration
     */
    protected function config(): array
    {
        return [
            'apiKey' => config('services.myfatoorah.api_key'),
            'vcCode' => config('services.myfatoorah.vc_code'),
            'isTest' => config('services.myfatoorah.is_test'),
        ];
    }
}
