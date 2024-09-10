<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case PENDING_DRIVER_ASSIGNMENT = 'PENDING_DRIVER_ASSIGNMENT';
    case SCHEDULED = 'SCHEDULED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';
}
