<?php
session_start();
include('db_connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['donor_id'])) {
    echo json_encode(['error' => 'Please log in first']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$request_id = $input['request_id'];
$item_name = $input['item_name'];
$amount = $input['amount'];
$donor_name = $input['donor_name'];
$donor_email = $input['donor_email'];
$donor_phone = $input['donor_phone'];
$donor_id = $_SESSION['donor_id'];

// Validate required fields
if (!$request_id || !$item_name || !$amount || !$donor_name || !$donor_email) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// First, create the donation record in database
$insertQuery = "INSERT INTO donations (donor_id, request_id, amount, date, status, note) VALUES (?, ?, ?, CURDATE(), 'waiting_proof', 'Donasi melalui Midtrans')";
$insertStmt = $conn->prepare($insertQuery);
$insertStmt->bind_param("iii", $donor_id, $request_id, $amount);

if (!$insertStmt->execute()) {
    echo json_encode(['error' => 'Failed to create donation record']);
    exit;
}

$donation_id = $conn->insert_id;

// Midtrans configuration
// Replace with your actual Midtrans credentials
$server_key = 'Mid-server-ZZhJBtusoGFccHZKrwTqp9vc'; // Replace with your actual server key
$is_production = false; // Set to true for production

// Set Midtrans endpoint
$midtrans_url = $is_production ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

// Create order ID
$order_id = 'DONATION-' . $donation_id . '-' . time();

// Prepare transaction details
$transaction_details = [
    'order_id' => $order_id,
    'gross_amount' => $amount
];

$item_details = [
    [
        'id' => 'donation-' . $request_id,
        'price' => $amount,
        'quantity' => 1,
        'name' => $item_name
    ]
];

$customer_details = [
    'first_name' => $donor_name,
    'email' => $donor_email,
    'phone' => $donor_phone
];

$transaction_data = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details,
    'callbacks' => [
        'finish' => 'http://your-domain.com/donatur.php'
    ]
];

// Make request to Midtrans
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $midtrans_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transaction_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($server_key . ':')
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 201) {
    $result = json_decode($response, true);
    
    // Update donation record with order ID
    $updateQuery = "UPDATE donations SET note = CONCAT(note, ' - Order ID: " . $order_id . "') WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $donation_id);
    $updateStmt->execute();
    
    echo json_encode([
        'snapToken' => $result['token'],
        'order_id' => $order_id,
        'donation_id' => $donation_id
    ]);
} else {
    // Delete the donation record if Midtrans request failed
    $deleteQuery = "DELETE FROM donations WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $donation_id);
    $deleteStmt->execute();
    
    $error_response = json_decode($response, true);
    echo json_encode(['error' => 'Midtrans error: ' . ($error_response['error_messages'][0] ?? 'Unknown error')]);
}

$conn->close();
?>