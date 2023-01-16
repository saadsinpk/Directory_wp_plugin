<?php

require_once('vendor/autoload.php');

$stripe = new \Stripe\StripeClient('pk_test_51MChAQFLCK58qaYONmbfDQVoQYGa1DLxYBceC5K2NBT4NYmaufmEQfzc6etCsTPE9c9HefFWY8zbV4TZlui3A5Ds00GXB6nnG1');

$checkout_session = $stripe->checkout->payment->create([
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => [
        'name' => 'Premium Package',
      ],
      'unit_amount' => 2000,
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'http://localhost:4242/success',
  'cancel_url' => 'http://localhost:4242/cancel',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
