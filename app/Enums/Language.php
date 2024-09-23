<?php

namespace App\Enums;

/**
 * @method static static EN()
 * @method static static AR()
 */
enum Language: string
{
    case EN = 'en';
    case AR = 'ar';

    public static function values(): array
    {
        return array_map(fn ($e) => $e->value, self::cases());
    }
}
