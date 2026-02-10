<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'active.user' => \App\Http\Middleware\CheckUserActive::class,
    ]);
})
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
            'admin' => AdminMiddleware::class,
            'user'  => UserMiddleware::class,
        ]);
        
        // For testing, you can temporarily disable CSRF protection
        // $middleware->validateCsrfTokens(except: [
        //     'login',
        //     'logout',
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    