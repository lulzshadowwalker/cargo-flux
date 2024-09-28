<?php

namespace App\Enums;

enum Audience: string
{
    case CUSTOMERS = 'CUSTOMERS';
    case DRIVERS = 'DRIVERS';
    case ALL = 'ALL';

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMERS => __('enums.audience.customers'),
            self::DRIVERS => __('enums.audience.drivers'),
            self::ALL => __('enums.audience.all'),
        };
    }
}
