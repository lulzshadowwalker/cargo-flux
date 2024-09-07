<?php

use App\Http\Response\JsonResponseBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render('handleValidationError')
            ->render('handleValidationError')
            ->render('handleHttpException')
            ->render('handleModelNotFound')
            ->render('handleInternalError');
    })->create();

function handleValidationError(ValidationException $exception, Request $request)
{
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
}

function handleModelNotFound(ModelNotFoundException $exception, Request $request)
{
    $builder = new JsonResponseBuilder();
    $builder->error(
        title: 'Resource Not Found',
        detail: 'The requested resource could not be found.',
        code: Response::HTTP_NOT_FOUND,
    );

    return $builder->build();
}

function handleHttpException(HttpException $exception, Request $request)
{
    $builder = new JsonResponseBuilder();
    $builder->error(
        title: $exception->getMessage() ?: 'HTTP Error',
        detail: $exception->getMessage(),
        code: $exception->getStatusCode()
    );

    return $builder->build();
}

function handleInternalError(Exception $exception, Request $request)
{
    $builder = new JsonResponseBuilder();
    $builder->error(
        title: 'Internal Server Error',
        detail: 'An unexpected error occurred on the server.',
        code: Response::HTTP_INTERNAL_SERVER_ERROR
    );

    return $builder->build();
}
