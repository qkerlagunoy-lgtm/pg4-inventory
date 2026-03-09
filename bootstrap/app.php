<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\SuperAdminOnly;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add CORS middleware globally
        $middleware->web(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        // Add middleware aliases
        $middleware->alias([
            'admin'         => AdminMiddleware::class,
            'user'          => UserMiddleware::class,
            'active.user'   => \App\Http\Middleware\CheckUserActive::class,
            'superadmin.only' => SuperAdminOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();