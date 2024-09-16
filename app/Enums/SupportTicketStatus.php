<?php

namespace App\Enums;

enum SupportTicketStatus: string
{
    case OPEN = 'OPEN';
    case IN_PROGRESS = 'IN_PROGRESS';
    case RESOLVED = 'RESOLVED';

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'info',
            self::IN_PROGRESS => 'warning',
            self::RESOLVED => 'success',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::OPEN => __('enums.support-ticket-status.open'),
            self::IN_PROGRESS => __('enums.support-ticket-status.in-progress'),
            self::RESOLVED => __('enums.support-ticket-status.resolved'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::OPEN => 'far-folder-open',
            self::IN_PROGRESS => 'fas-arrows-rotate',
            self::RESOLVED => 'far-folder',
        };
    }
}
