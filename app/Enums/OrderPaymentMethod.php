<?php

namespace App\Enums;

use Closure;
use Filament\Support\Colors\Color;

enum OrderPaymentMethod: string
{
    case DIRECT = 'DIRECT';
    case ONLINE = 'ONLINE';

    public function label(): string
    {
        return match ($this) {
            self::DIRECT => __('enums.order-payment-method.direct'),
            self::ONLINE => __('enums.order-payment-method.online'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DIRECT => 'heroicon-o-cash',
            self::ONLINE => 'heroicon-o-credit-card',
        };
    }

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::DIRECT => 'primary',
            self::ONLINE => 'info',
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
