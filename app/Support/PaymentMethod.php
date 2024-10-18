<?php

namespace App\Support;

use App\Enums\Language;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use stdClass;

class PaymentMethod
{
    public string $id;
    public string $name;
    public string $code;
    public Money $serviceCharge;
    public Money $total;
    public string $image;

    /**
     * Create a new instance of PaymentMethod from MyFatoorah response.
     */
    public static function fromMyFatoorah(mixed $data): self
    {
        if ($data instanceof stdClass) {
            $data = (array) $data;
        }

        $paymentMethod = new self();
        $paymentMethod->id = $data['PaymentMethodId'];
        $paymentMethod->name = Language::tryFrom(app()->getLocale()) === Language::AR ? $data['PaymentMethodAr'] : $data['PaymentMethodEn'];
        $paymentMethod->code = $data['PaymentMethodCode'];
        $paymentMethod->serviceCharge = Money::of($data['ServiceCharge'], $data['CurrencyIso'], roundingMode: RoundingMode::HALF_UP);
        $paymentMethod->total = Money::of($data['TotalAmount'], $data['CurrencyIso'], roundingMode: RoundingMode::HALF_UP);
        $paymentMethod->image = $data['ImageUrl'];

        return $paymentMethod;
    }
}
