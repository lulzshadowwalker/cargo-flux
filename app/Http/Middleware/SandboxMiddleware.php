<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
            Config::set('env', 'sandbox');
            Config::set('session.driver', 'file');
            Config::set('mail.driver', 'log');
            Config::set('database.default', 'sandbox');
            Config::set('telescope.storage.database.connection', 'sandbox');

            if (Cache::get('sandbox-seeded') !== true) {
                $result = Artisan::call('migrate:fresh', ['--seed' => true]);
                if ($result !== 0) {
                    abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to seed the sandbox database.');
                }

                Cache::put('sandbox-seeded', true);
            }
        }

        return $next($request);
    }
}
