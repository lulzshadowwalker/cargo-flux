<?php

namespace App\Enums;

use Closure;

enum OrderPaymentStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case UNPAID = 'UNPAID';

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::UNPAID => 'info',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => __('enums.order-payment-status.pending-approval'),
            self::APPROVED => __('enums.order-payment-status.approved'),
            self::REJECTED => __('enums.order-payment-status.rejected'),
            self::UNPAID => __('enums.order-payment-status.unpaid'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'heroicon-o-clock',
            self::APPROVED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
            self::UNPAID => 'heroicon-o-currency-dollar',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($e) => $e->label(), self::cases());
    }
}
