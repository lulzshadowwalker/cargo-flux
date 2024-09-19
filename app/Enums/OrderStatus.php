<?php

namespace App\Enums;

use Closure;
use Filament\Support\Colors\Color;

enum OrderStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case PENDING_DRIVER_ASSIGNMENT = 'PENDING_DRIVER_ASSIGNMENT';
    case SCHEDULED = 'SCHEDULED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::PENDING_APPROVAL => Color::hex('#FFA500'),
            self::PENDING_DRIVER_ASSIGNMENT => Color::hex('#17A2B8'),
            self::SCHEDULED => Color::hex('#007BFF'),
            self::IN_PROGRESS => Color::hex('#28A745'),
            self::COMPLETED => Color::hex('#28A745'),
            self::CANCELED => Color::hex('#DC3545'),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => __('enums.order-status.pending-approval'),
            self::PENDING_DRIVER_ASSIGNMENT => __('enums.order-status.pending-driver-assignment'),
            self::SCHEDULED => __('enums.order-status.scheduled'),
            self::IN_PROGRESS => __('enums.order-status.in-progress'),
            self::COMPLETED => __('enums.order-status.completed'),
            self::CANCELED => __('enums.order-status.canceled'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'heroicon-o-clock',
            self::PENDING_DRIVER_ASSIGNMENT => 'heroicon-o-user-add',
            self::SCHEDULED => 'heroicon-o-calendar',
            self::IN_PROGRESS => 'heroicon-o-cog',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::CANCELED => 'heroicon-o-x-circle',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($e) => $e->label(), self::cases());
    }
}
