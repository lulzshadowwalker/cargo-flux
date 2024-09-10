<?php

namespace App\Enums;

enum DriverStatus: string
{
    case UNDER_REVIEW = 'UNDER_REVIEW';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}
