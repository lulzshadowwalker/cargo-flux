<?php

namespace App\Enums;

enum Audience: string
{
    case CUSTOMERS = 'CUSTOMERS';
    case DRIVERS = 'DRIVERS';
    case ALL = 'ALL';
}
