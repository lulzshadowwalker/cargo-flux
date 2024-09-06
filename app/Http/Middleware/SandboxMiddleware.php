<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to set the database connection to the sandbox environment
 * for requests coming from the sandbox subdomain.
 */
class SandboxMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = explode('.', $request->getHost())[0];
        if ($subdomain === config('app.sandbox_subdomain')) {
            Config::set('database.default', 'sandbox');
        }

        return $next($request);
    }
}
