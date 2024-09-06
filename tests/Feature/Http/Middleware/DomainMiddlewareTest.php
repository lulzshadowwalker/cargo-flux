<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\DomainMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Throwable;

class DomainMiddlewareTest extends TestCase
{
    public function test_it_returns_forbidden_when_subdomain_is_not_allowed()
    {
        $subdomain = 'fake-domain';
        $request = Request::create("http://$subdomain.example.com", 'GET');
        $next = fn($request) => response('hello, world.');

        try {
            (new DomainMiddleware)->handle($request, $next);
        } catch (Throwable $e) {
        }

        $this->assertEquals(
            new HttpException(Response::HTTP_FORBIDDEN, "Unauthorized domain $subdomain"),
            $e
        );
    }
}
