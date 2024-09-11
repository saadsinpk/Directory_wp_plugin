<?php

require 'vendor/autoload.php';

$stripe = new \Stripe\StripeClient('sk_test_51MChAQFLCK58qaYOCydBTKOMW0EDQez10xv8Zqeqp9dU5cHZTK2zZbM1cz6ULSBTyBgp4EuHiqktU72yDOAM4GIE00XgD7LaB7');

$amount = array(
    'unit_amount' => 20 * 100,
);

$checkout_session = $stripe->checkout->sessions->create([
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Premium Package',
            ],
            $amount,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost:4242/success',
    'cancel_url' => 'http://localhost:4242/cancel',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
