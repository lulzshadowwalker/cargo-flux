<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check if the request is coming from an allowed domain.
 */
class DomainMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $subdomain = explode('.', $request->getHost())[0];
        if ($subdomain !== config('app.sandbox_subdomain')) {
            abort(HttpResponse::HTTP_FORBIDDEN, "Unauthorized domain $subdomain");
        }

        return $next($request);
    }
}
