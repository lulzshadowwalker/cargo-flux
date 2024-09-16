<?php

namespace App\Enums;

/**
 * Base user type.
 * 
 * Admins may have further roles and permissions in the admin dashboard using Filament Shield.
 */
enum UserType: string
{
    case CUSTOMER = 'CUSTOMER';
    case DRIVER = 'DRIVER';
    case ADMIN = 'ADMIN';
}
