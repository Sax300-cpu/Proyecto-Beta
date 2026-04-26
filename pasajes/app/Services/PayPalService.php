<?php

namespace App\Services;

use App\Models\Boleto;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    public function createOrder(Boleto $boleto, string $returnUrl, string $cancelUrl): array
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($this->baseUrl() . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'custom_id' => (string) $boleto->id,
                        'amount' => [
                            'currency_code' => config('services.paypal.currency', 'USD'),
                            'value' => number_format((float) $boleto->precio, 2, '.', ''),
                        ],
                        'description' => sprintf(
                            'Boleto #%d %s-%s',
                            $boleto->id,
                            $boleto->origen_abordaje,
                            $boleto->destino_desembarque
                        ),
                    ],
                ],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'brand_name' => config('app.name'),
                    'landing_page' => 'NO_PREFERENCE',
                    'user_action' => 'PAY_NOW',
                    'shipping_preference' => 'NO_SHIPPING',
                ],
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('No se pudo crear la orden en PayPal Sandbox. Verifica tus credenciales.');
        }

        $payload = $response->json();
        $approveUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        if (! $approveUrl || empty($payload['id'])) {
            throw new RuntimeException('PayPal no devolvio una URL de aprobacion valida.');
        }

        return [
            'id' => $payload['id'],
            'approve_url' => $approveUrl,
        ];
    }

    public function captureOrder(string $orderId): array
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($this->baseUrl() . '/v2/checkout/orders/' . $orderId . '/capture');

        if (! $response->successful()) {
            throw new RuntimeException('No se pudo capturar el pago en PayPal Sandbox.');
        }

        $payload = $response->json();
        $captureId = $payload['purchase_units'][0]['payments']['captures'][0]['id'] ?? null;

        return [
            'status' => $payload['status'] ?? null,
            'capture_id' => $captureId,
            'payload' => $payload,
        ];
    }

    private function getAccessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        if (! $clientId || ! $clientSecret) {
            throw new RuntimeException('Faltan PAYPAL_CLIENT_ID o PAYPAL_CLIENT_SECRET en .env');
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->acceptJson()
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('No se pudo autenticar contra PayPal Sandbox.');
        }

        $token = $response->json('access_token');

        if (! $token) {
            throw new RuntimeException('PayPal no devolvio access_token.');
        }

        return $token;
    }

    private function baseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }
}