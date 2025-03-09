<?php

namespace App\Enums;

use Closure;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Nationality: string implements HasLabel, HasColor
{
    //  NOTE: standard ISO 3166-1 alpha-2 country codes
    case JO = 'JO';
    case SA = 'SA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::JO => __('enums.nationality.jo'),
            self::SA => __('enums.nationality.sa'),
        };
    }

    public function label(): ?string
    {
        return $this->getLabel();
    }

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::JO => 'primary',
            self::SA => 'info',
        };
    }

    public function color(): string|array|bool|Closure|null
    {
        return $this->getColor();
    }

}
