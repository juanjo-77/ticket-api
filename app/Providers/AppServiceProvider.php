<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    // Notificar a Discord cuando se excede el límite
                    app(\App\Services\DiscordService::class)->sendRateLimit(
                        $request->path(),
                        $request->ip(),
                        $headers['X-RateLimit-Limit']
                    );

                    return response()->json([
                        'success' => false,
                        'message' => 'Demasiadas solicitudes. Intenta de nuevo en un minuto.',
                    ], 429);
                });
        });
    }
}
