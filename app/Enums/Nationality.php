<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Nationality: string implements HasLabel
{
    //  NOTE: standard ISO 3166-1 alpha-2 country codes
    case JO = 'JO';
    case SA = 'SA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::JO => __('enums/nationality.jo'),
            self::SA => __('enums/nationality.sa'),
        };
    }

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
