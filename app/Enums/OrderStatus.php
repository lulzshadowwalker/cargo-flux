<?php

namespace App\Enums;

use Closure;
use Filament\Support\Colors\Color;

enum OrderStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case PENDING_DRIVER_ASSIGNMENT = 'PENDING_DRIVER_ASSIGNMENT';
    case DRIVER_ASSIGNED = 'DRIVER_ASSIGNED';
    case SCHEDULED = 'SCHEDULED';
    case HEADING_TO_PICKUP = 'HEADING_TO_PICKUP';
    case PICKUP_STARTED = 'PICKUP_STARTED';
    case PICKUP_COMPLETED = 'PICKUP_COMPLETED';
    case CUSTOMS_PROCESSING_STARTED = 'CUSTOMS_PROCESSING_STARTED';
    case CUSTOMS_PROCESSING_COMPLETED = 'CUSTOMS_PROCESSING_COMPLETED';
    case DROP_OFF_STARTED = 'DROP_OFF_STARTED';
    case DROP_OFF_COMPLETED = 'DROP_OFF_COMPLETED';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';

    public function color(): string|array|bool|Closure|null
    {
        return match ($this) {
            self::PENDING_APPROVAL => Color::hex('#FFA500'),
            self::PENDING_DRIVER_ASSIGNMENT => Color::hex('#17A2B8'),
            self::DRIVER_ASSIGNED => Color::hex('#6C757D'),
            self::SCHEDULED => Color::hex('#007BFF'),
            self::HEADING_TO_PICKUP => Color::hex('#FFC107'),
            self::PICKUP_STARTED => Color::hex('#17A2B8'),
            self::PICKUP_COMPLETED => Color::hex('#007BFF'),
            self::CUSTOMS_PROCESSING_STARTED => Color::hex('#FF6347'),
            self::CUSTOMS_PROCESSING_COMPLETED => Color::hex('#20C997'),
            self::DROP_OFF_STARTED => Color::hex('#17A2B8'),
            self::DROP_OFF_COMPLETED => Color::hex('#28A745'),
            self::COMPLETED => Color::hex('#28A745'),
            self::CANCELED => Color::hex('#DC3545'),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => __('enums.order-status.pending-approval'),
            self::PENDING_DRIVER_ASSIGNMENT => __('enums.order-status.pending-driver-assignment'),
            self::DRIVER_ASSIGNED => __('enums.order-status.driver-assigned'),
            self::SCHEDULED => __('enums.order-status.scheduled'),
            self::HEADING_TO_PICKUP => __('enums.order-status.heading-to-pickup'),
            self::PICKUP_STARTED => __('enums.order-status.pickup-started'),
            self::PICKUP_COMPLETED => __('enums.order-status.pickup-completed'),
            self::CUSTOMS_PROCESSING_STARTED => __('enums.order-status.customs-processing-started'),
            self::CUSTOMS_PROCESSING_COMPLETED => __('enums.order-status.customs-processing-completed'),
            self::DROP_OFF_STARTED => __('enums.order-status.drop-off-started'),
            self::DROP_OFF_COMPLETED => __('enums.order-status.drop-off-completed'),
            self::COMPLETED => __('enums.order-status.completed'),
            self::CANCELED => __('enums.order-status.canceled'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'heroicon-o-clock',
            self::PENDING_DRIVER_ASSIGNMENT => 'heroicon-o-magnifying-glass',
            self::DRIVER_ASSIGNED => 'heroicon-o-check-badge',
            self::SCHEDULED => 'heroicon-o-calendar',
            self::HEADING_TO_PICKUP => 'heroicon-o-truck',
            self::PICKUP_STARTED => 'heroicon-o-upload',
            self::PICKUP_COMPLETED => 'heroicon-o-check-circle',
            self::CUSTOMS_PROCESSING_STARTED => 'heroicon-o-scale',
            self::CUSTOMS_PROCESSING_COMPLETED => 'heroicon-o-check',
            self::DROP_OFF_STARTED => 'heroicon-o-inbox-arrow-down',
            self::DROP_OFF_COMPLETED => 'heroicon-o-check-circle',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::CANCELED => 'heroicon-o-x-circle',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($e) => $e->label(), self::cases());
    }

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}
