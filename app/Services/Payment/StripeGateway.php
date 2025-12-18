<?php

namespace App\Services\Payment;

use Stripe\StripeClient;
use Exception;

class StripeGateway implements PaymentGatewayInterface
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(float $amount, string $currency, array $options = []): array
    {
        try {
            // Stripe expects amount in cents for USD
            $amountInCents = (int) ($amount * 100);

            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => $currency,
                'payment_method' => $options['token'], // payment method ID from frontend
                'confirm' => true,
                'description' => $options['description'] ?? 'Payment',
                'return_url' => $options['return_url'] ?? route('home'), // Required for automatic_payment_methods
            ]);

            return [
                'success' => true,
                'transaction_id' => $paymentIntent->id,
                'data' => $paymentIntent,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
