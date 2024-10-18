<?php

namespace App\Enums;

use Closure;
use Filament\Support\Colors\Color;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case FAILED = 'FAILED';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('enums.payment-status.pending'),
            self::PAID => __('enums.payment-status.paid'),
            self::FAILED => __('enums.payment-status.failed'),
        };
    }

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::PENDING => Color::hex('#FFA500'),
            self::PAID => Color::hex('#28A745'),
            self::FAILED => Color::hex('#DC3545'),
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
