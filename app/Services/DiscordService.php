<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordService
{
    private string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url', '');
    }

    public function send(string $title, string $message, string $color = '3447003'): void
    {
        if (empty($this->webhookUrl)) {
            Log::warning('Discord webhook URL no configurada');
            return;
        }

        try {
            Http::post($this->webhookUrl, [
                'embeds' => [[
                    'title'       => $title,
                    'description' => $message,
                    'color'       => (int) $color,
                    'timestamp'   => now()->toIso8601String(),
                ]]
            ]);
        } catch (\Exception $e) {
            Log::error('Error enviando mensaje a Discord: ' . $e->getMessage());
        }
    }

    public function sendRateLimitAlert(string $ip, string $route): void
    {
        $this->send(
            '⚠️ Rate Limit Excedido',
            "**IP:** `{$ip}`\n**Ruta:** `{$route}`\n**Hora:** " . now()->format('Y-m-d H:i:s'),
            '16711680'
        );
    }

    public function sendTicketAlert(string $action, array $data): void
    {
        $this->send(
            "🎫 Ticket {$action}",
            "**Título:** {$data['title']}\n**Estado:** {$data['status']}\n**Prioridad:** {$data['priority']}",
            '3066993'
        );
    }
}
