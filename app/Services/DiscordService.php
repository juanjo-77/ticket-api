<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DiscordService
{
    private string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    public function sendError(string $endpoint, string $method, string $message, string $ip): void
    {
        $this->send([
            'embeds' => [[
                'title'       => '🚨 Error 500 detectado',
                'color'       => 15158332, // rojo
                'fields'      => [
                    ['name' => 'Endpoint',  'value' => $endpoint,  'inline' => true],
                    ['name' => 'Método',    'value' => $method,    'inline' => true],
                    ['name' => 'IP',        'value' => $ip,        'inline' => true],
                    ['name' => 'Error',     'value' => $message,   'inline' => false],
                    ['name' => 'Fecha',     'value' => now()->toDateTimeString(), 'inline' => true],
                ],
            ]],
        ]);
    }

    public function sendRateLimit(string $endpoint, string $ip, int $attempts): void
    {
        $this->send([
            'embeds' => [[
                'title'  => '⚠️ Rate Limit excedido',
                'color'  => 16776960, // amarillo
                'fields' => [
                    ['name' => 'Endpoint',  'value' => $endpoint,          'inline' => true],
                    ['name' => 'IP',        'value' => $ip,                'inline' => true],
                    ['name' => 'Intentos',  'value' => (string)$attempts,  'inline' => true],
                    ['name' => 'Timestamp', 'value' => now()->toDateTimeString(), 'inline' => true],
                ],
            ]],
        ]);
    }

    private function send(array $payload): void
    {
        if (empty($this->webhookUrl)) return;

        Http::post($this->webhookUrl, $payload);
    }
}
