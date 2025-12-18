<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Charge the user.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return array
     */
    public function charge(float $amount, string $currency, array $options = []): array;
}
