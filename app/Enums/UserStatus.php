<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'ACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case BANNED = 'BANNED';

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::BANNED => 'danger',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('enums.user-status.active'),
            self::SUSPENDED => __('enums.user-status.suspended'),
            self::BANNED => __('enums.user-status.banned'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::SUSPENDED => 'heroicon-o-pause-circle',
            self::BANNED => 'heroicon-o-no-symbol',
        };
    }
}
