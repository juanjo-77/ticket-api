<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;
use App\Services\DiscordService;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\DiscordRateLimitAlert::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);

        $exceptions->report(function (\Throwable $e) {
            try {
                $request = request();
                app(DiscordService::class)->sendError(
                    $request->path(),
                    $request->method(),
                    $e->getMessage(),
                    $request->ip()
                );
            } catch (\Throwable $discordError) {
                // Si Discord falla no interrumpimos la app
            }
        });
    })->create();
