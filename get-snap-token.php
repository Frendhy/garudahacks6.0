<?php
require_once 'vendor/autoload.php'; // Composer Midtrans SDK

\Midtrans\Config::$serverKey = 'Mid-server-ZZhJBtusoGFccHZKrwTqp9vc';
\Midtrans\Config::$isProduction = false; // set to true on production

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$transactionDetails = [
    'order_id' => $input['item_id'],
    'gross_amount' => (int) $input['amount']
];

$itemDetails = [
    [
        'id' => $input['item_id'],
        'price' => (int) $input['amount'],
        'quantity' => 1,
        'name' => $input['item_name']
    ]
];

$customerDetails = [
    'first_name' => $input['donor_name'],
    'email' => $input['donor_email'],
    'phone' => $input['donor_phone']
];

$transaction = [
    'transaction_details' => $transactionDetails,
    'item_details' => $itemDetails,
    'customer_details' => $customerDetails
];

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    echo json_encode(['snapToken' => $snapToken]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
