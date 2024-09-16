<?php

namespace App\Enums;

/**
 * Base user type.
 * 
 * Admins may have further roles and permissions in the admin dashboard using Filament Shield.
 */
enum UserType: string
{
    case CUSTOMER = 'CUSTOMER';
    case DRIVER = 'DRIVER';
    case ADMIN = 'ADMIN';

    public function color(): string
    {
        return match ($this) {
            self::CUSTOMER => 'success',
            self::DRIVER => 'info',
            self::ADMIN => 'primary',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER => __('enums.user-type.customer'),
            self::DRIVER => __('enums.user-type.driver'),
            self::ADMIN => __('enums.user-type.admin'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::CUSTOMER => 'heroicon-o-user-circle',
            self::DRIVER => 'heroicon-o-truck',
            self::ADMIN => 'heroicon-o-shield-check',
        };
    }
}
