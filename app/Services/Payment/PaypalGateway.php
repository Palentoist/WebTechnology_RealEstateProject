<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Exception;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $mode = config('services.paypal.mode', 'sandbox');
        $this->baseUrl = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
        $this->clientId = config("services.paypal.{$mode}.client_id");
        $this->clientSecret = config("services.paypal.{$mode}.client_secret");
    }

    protected function getAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new Exception('Could not get PayPal access token: ' . $response->body());
    }

    public function charge(float $amount, string $currency, array $options = []): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $payload = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => strtoupper($currency),
                            "value" => number_format($amount, 2, '.', '')
                        ],
                        "description" => $options['description'] ?? 'Payment'
                    ]
                ],
                "application_context" => [
                    "return_url" => $options['return_url'] ?? route('home'),
                    "cancel_url" => $options['cancel_url'] ?? route('home'),
                    "user_action" => "PAY_NOW"
                ]
            ];

            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/v2/checkout/orders", $payload);

            if ($response->successful()) {
                $order = $response->json();

                foreach ($order['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return [
                            'success' => true,
                            'redirect_url' => $link['href'],
                            'transaction_id' => $order['id'],
                            'pending' => true
                        ];
                    }
                }
            }

            return [
                'success' => false,
                'message' => 'Could not create PayPal order: ' . $response->body()
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function capture($orderId)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture", [
                    'headers' => ['Content-Type' => 'application/json']
                ]);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => true,
                    'transaction_id' => $result['id'],
                    'data' => $result
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Payment not completed or failed capture.'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
