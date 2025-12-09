<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',

        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftar alias middleware di sini
        $middleware->alias([
            // middleware auth bawaan Laravel (pakai guard, misal auth:sanctum)
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,

            // middleware admin buatanmu
            'admin' => \App\Http\Middleware\AdminOnly::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
