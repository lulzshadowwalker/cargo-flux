<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ResponseBuilder;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function __construct(protected ResponseBuilder $response)
    {
        //
    }
}
