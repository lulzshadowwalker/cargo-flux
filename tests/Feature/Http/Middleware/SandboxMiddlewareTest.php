<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\SandboxMiddleware;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class SandboxMiddlewareTest extends TestCase
{
    public function test_it_sets_the_database_connection_to_sandbox()
    {
        $subdomain = config('app.sandbox_subdomain');
        $request = Request::create("http://$subdomain.example.com");
        $next = fn($request) => response('Welcome to the sandbox!');

        (new SandboxMiddleware)->handle($request, $next);

        $this->assertEquals('sandbox', config('database.default'));
    }

    public function test_it_does_not_set_the_database_connection_to_sandbox()
    {
        $request = Request::create('http://example.com');
        $next = fn($request) => response('Welcome to the sandbox!');

        (new SandboxMiddleware)->handle($request, $next);

        $this->assertNotEquals('sandbox', config('database.default'));
    }
}
