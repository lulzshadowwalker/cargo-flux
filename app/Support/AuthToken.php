<?php

namespace App\Support;

use App\Enums\TokenType;

class AuthToken
{
    public function __construct(public string $token, public TokenType $type)
    {
        //
    }
}
