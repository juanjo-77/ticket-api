<?php

namespace App\Http\Middleware;

use App\Services\DiscordService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscordRateLimitAlert
{
    public function __construct(private DiscordService $discordService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() === 429) {
            $this->discordService->sendRateLimit(
                $request->path(),
                $request->ip(),
                10
            );
        }

        return $response;
    }
}
