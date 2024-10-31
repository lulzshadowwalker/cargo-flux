<?php

namespace App\Exceptions;

use Exception;

/**
 * Indicates that the shipping route between two states is not supported. 
 */
class UnsupportedRouteException extends Exception
{
    public function __construct($message = 'Unsupported route', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
