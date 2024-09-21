<?php

namespace App\Enums;

enum DriverStatus: string
{
    case UNDER_REVIEW = 'UNDER_REVIEW';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';

    public function color(): string
    {
        return match ($this) {
            self::UNDER_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::UNDER_REVIEW => __('enums.driver-status.under-review'),
            self::APPROVED => __('enums.driver-status.approved'),
            self::REJECTED => __('enums.driver-status.rejected'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::UNDER_REVIEW => 'heroicon-o-exclamation',
            self::APPROVED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
}
