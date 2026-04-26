<?php

use App\Http\Middleware\CheckBusNoPartido;
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
        // Alias de middleware de roles (Spatie)
        $middleware->alias([
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'bus.no_partido' => CheckBusNoPartido::class,
        ]);

        // Livewire necesita estas excepciones CSRF
        $middleware->validateCsrfTokens(except: [
            'livewire/upload-file',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
