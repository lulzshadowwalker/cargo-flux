<?php

use App\Contracts\ResponseBuilder;
use App\Http\Middleware\DomainMiddleware;
use App\Http\Middleware\SandboxMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware([
    DomainMiddleware::class,
    SandboxMiddleware::class,
])->group(function () {
    Route::get('/', function (ResponseBuilder $builder) {
        $posts = [
            ['title' => 'Post 1'],
            ['title' => 'Post 2'],
            ['title' => 'Post 3'],
        ];

        return $builder
            ->data($posts)
            ->meta(['version' => '1.0'])
            ->message('Welcome to the API')
            ->build();
    });
});
