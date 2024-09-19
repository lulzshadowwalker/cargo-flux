<?php

namespace App\Enums;

use Closure;

enum OrderPaymentStatus: string
{
    case PENDING = 'PENDING';
        // case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('enums.order-payment-status.pending'),
            self::APPROVED => __('enums.order-payment-status.approved'),
            self::REJECTED => __('enums.order-payment-status.rejected'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::APPROVED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($e) => $e->label(), self::cases());
    }
}
