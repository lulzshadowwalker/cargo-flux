<?php

namespace App\Enums;

use Closure;
use Filament\Support\Colors\Color;

enum PaymentGateway: string
{
    case MY_FATOORAH = 'MY_FATOORAH';

    public function label(): string
    {
        return match ($this) {
            self::MY_FATOORAH => __('enums.payment-gateway.my-fatoorah'),
        };
    }

    public function icons(): string
    {
        return match ($this) {
            self::MY_FATOORAH => 'heroicon-o-credit-card',
        };
    }

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::MY_FATOORAH => Color::hex('#FFA500'),
        };
    }

    public static function labels(): array
    {
        return array_map(fn($e) => $e->label(), self::cases());
    }

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
