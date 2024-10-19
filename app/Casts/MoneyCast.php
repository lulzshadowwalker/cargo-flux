<?php

namespace App\Casts;

use App\Models\Currency;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $amount = $attributes['amount'];
        $currency = $model->currency->code;

        return Money::of(
            $amount,
            $currency,
            roundingMode: RoundingMode::UP
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value instanceof Money) {
            throw new \InvalidArgumentException('The given value is not an instance of Brick\Money\Money.');
        }

        $currencyCode = $value->getCurrency()->getCurrencyCode();
        if (Schema::hasColumn($model->getTable(), 'currency_id')) {
            return [
                'amount' => $value->getAmount(),
                'currency_id' => Currency::where('code', $currencyCode)->firstOrFail()->id,
            ] ;
        }

        return [
            'amount' => $value->getAmount(),
            'currency' => $currencyCode,
        ];
    }
}
