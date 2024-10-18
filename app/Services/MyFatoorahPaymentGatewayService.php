<?php

namespace App\Services;

use App\Contracts\PaymentGatewayService;
use App\Enums\Language;
use App\Enums\PaymentGateway;
use App\Models\Currency;
use App\Models\Order;
use App\Support\PaymentMethod;
use Brick\Money\Money;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberUtil;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use Illuminate\Support\Str;

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
    public function start(Money $price, string $paymentMethodId, object $payable): array
    {
        $user = Auth::user();;

        $countryCode = '+' . PhoneNumberUtil::getInstance()->getCountryCodeForRegion('JO');

        $fields = [
            'InvoiceValue' => (string) $price->getAmount(),
            'CustomerName' => $user->fullName,
            'CallBackUrl' => route('payment.callback.success', ['lang' => app()->getLocale()]),
            'ErrorUrl' => route('payment.callback.failure', ['lang' => app()->getLocale()]),
            'DisplayCurrencyIso' => $price->getCurrency()->getCurrencyCode(),

            // [ISO Lookups](https://docs.myfatoorah.com/docs/iso-lookups)
            'MobileCountryCode' => $countryCode,

            // String uses English letters ONLY and does not accept Arabic characters Its length is between 0 and 11
            // Regular expression pattern is ^(?:(+)|(00)|(*)|())[0-9]{3,14}((#)|())$
            'CustomerMobile' => Str::replace($countryCode, '', $user->phone->formatE164()),
            'CustomerEmail' => $user->email,
            'Language' => $user->preferences?->language ?: Language::EN,
            'CustomerReference' => null,
            'UserDefinedField' => null,
            'CustomerAddress' => [
                'Block' => null,
                'Street' => null,
                'HouseBuildingNo' => null,
                'Address' => null,
                'AddressInstructions' => null,
            ],
            // 'InvoiceItems' => $payable->items,
        ];

        $response = $this->client()->getInvoiceURL($fields, $paymentMethodId);

        $payment = Order::first()->payments()->create([
            'user_id' => Auth::user()->id,
            'external_reference' => $response['invoiceId'],
            'gateway' => PaymentGateway::MY_FATOORAH,
            'amount' => $price->getAmount(),
            'currency_id' => Currency::whereCode($price->getCurrency()->getCurrencyCode())->first()->id,

            //  NOTE: Payment details are provided from MyFatoorah via the success/failure callback
            //  or they can obtained via the [GetPaymentStatus](https://docs.myfatoorah.com/docs/get-payment-status) API
            'details' => null,
        ]);

        return [$payment, $response['invoiceURL']];
    }

    /**
     * Handle the success callback
     */
    public function success(): void
    {
        //
    }

    /**
     * Handle the failure callback
     */
    public function failure(): void
    {
        //
    }

    /**
     * Get the client instance
     */
    protected function client(): MyFatoorahPayment
    {
        if ($this->client) {
            return $this->client;
        }

        $config = [
            'apiKey' => config('services.myfatoorah.api_key'),
            'vcCode' => config('services.myfatoorah.vc_code'),
            'isTest' => config('services.myfatoorah.is_test'),
        ];

        return new MyFatoorahPayment($config);
    }
}
