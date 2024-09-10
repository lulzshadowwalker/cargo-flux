<?php

namespace App\Enums;

enum OrderPaymentMethod: string
{
    case DIRECT = 'DIRECT';
    case ONLINE = 'ONLINE';
}
