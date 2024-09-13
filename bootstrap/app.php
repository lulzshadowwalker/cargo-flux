<?php

use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\SandboxMiddleware;
use App\Http\Response\JsonResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '/api/{lang}',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            LanguageMiddleware::class,
            SandboxMiddleware::class,
            SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Unauthenticated',
                detail: 'The user is not authenticated.',
                code: Response::HTTP_UNAUTHORIZED,
            );
            return $builder->build();
        });

        $exceptions->render(function (ValidationException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            foreach ($exception->errors() as $field => $messages) {
                $builder->error(
                    title: 'Invalid Attribute',
                    detail: $messages[0],
                    code: Response::HTTP_UNPROCESSABLE_ENTITY,
                    meta: ['info' => 'Ensure that the attribute meets the required validation rules.'],
                    pointer: "/data/attributes/{$field}"
                );
            }
            return $builder->build();
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Resource Not Found',
                detail: 'The requested resource could not be found.',
                code: Response::HTTP_NOT_FOUND,
            );
            return $builder->build();
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Not Found',
                detail: 'The requested resource could not be found.',
                code: Response::HTTP_NOT_FOUND,
            );
            return $builder->build();
        });

        $exceptions->render(function (HttpException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: $exception->getMessage() ?: 'HTTP Error',
                detail: $exception->getMessage(),
                code: $exception->getStatusCode()
            );
            return $builder->build();
        });

        $exceptions->render(function (TokenExpiredException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Token Expired',
                detail: 'The provided token has expired.',
                code: Response::HTTP_UNAUTHORIZED,
            );
            return $builder->build();
        });

        $exceptions->render(function (TokenInvalidException $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Invalid Token',
                detail: 'The provided token is invalid.',
                code: Response::HTTP_UNAUTHORIZED
            );
            return $builder->build();
        });

        $exceptions->render(function (Exception $exception, Request $request) {
            $builder = new JsonResponseBuilder();
            $builder->error(
                title: 'Internal Server Error',
                detail: 'An unexpected error occurred on the server.',
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
            return $builder->build();
        });
    })->create();
