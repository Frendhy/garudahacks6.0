<?php
include('db_connection.php');

// Midtrans server key - replace with your actual server key
$server_key = 'Mid-server-ZZhJBtusoGFccHZKrwTqp9vc';

// Read the raw POST data
$json_result = file_get_contents('php://input');
$result = json_decode($json_result, true);

// Verify signature key
$signature_key = hash('sha512', $result['order_id'] . $result['status_code'] . $result['gross_amount'] . $server_key);

if ($signature_key !== $result['signature_key']) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

$order_id = $result['order_id'];
$transaction_status = $result['transaction_status'];
$fraud_status = $result['fraud_status'] ?? '';

// Extract donation ID from order ID
$order_parts = explode('-', $order_id);
if (count($order_parts) >= 2) {
    $donation_id = $order_parts[1];
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid order ID format']);
    exit;
}

// Update donation status based on transaction status
$new_status = '';
$update_collected = false;

switch ($transaction_status) {
    case 'capture':
        if ($fraud_status == 'challenge') {
            $new_status = 'pending';
        } else if ($fraud_status == 'accept') {
            $new_status = 'verified';
            $update_collected = true;
        }
        break;
    case 'settlement':
        $new_status = 'verified';
        $update_collected = true;
        break;
    case 'pending':
        $new_status = 'waiting_proof';
        break;
    case 'deny':
    case 'expire':
    case 'cancel':
        $new_status = 'failed';
        break;
    default:
        $new_status = 'pending';
}

// Update donation status
$updateQuery = "UPDATE donations SET status = ? WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("si", $new_status, $donation_id);
$updateStmt->execute();

// If payment is successful, update the collection amount in donation_requests
if ($update_collected) {
    // Get donation details
    $donationQuery = "SELECT d.amount, d.request_id FROM donations d WHERE d.id = ?";
    $donationStmt = $conn->prepare($donationQuery);
    $donationStmt->bind_param("i", $donation_id);
    $donationStmt->execute();
    $donationResult = $donationStmt->get_result();
    
    if ($donationRow = $donationResult->fetch_assoc()) {
        $amount = $donationRow['amount'];
        $request_id = $donationRow['request_id'];
        
        // Update collected amount and progress
        $updateRequestQuery = "UPDATE donation_requests 
                              SET collected = collected + ?, 
                                  progress = ROUND((collected + ?) / amount * 100)
                              WHERE id = ?";
        $updateRequestStmt = $conn->prepare($updateRequestQuery);
        $updateRequestStmt->bind_param("iii", $amount, $amount, $request_id);
        $updateRequestStmt->execute();
        
        // Check if request is fully funded
        $checkFullyFundedQuery = "SELECT amount, collected FROM donation_requests WHERE id = ?";
        $checkStmt = $conn->prepare($checkFullyFundedQuery);
        $checkStmt->bind_param("i", $request_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkRow = $checkResult->fetch_assoc()) {
            if ($checkRow['collected'] >= $checkRow['amount']) {
                // Mark as verified if fully funded
                $markVerifiedQuery = "UPDATE donation_requests SET status = 'verified' WHERE id = ?";
                $markVerifiedStmt = $conn->prepare($markVerifiedQuery);
                $markVerifiedStmt->bind_param("i", $request_id);
                $markVerifiedStmt->execute();
            }
        }
    }
}

// Log the webhook for debugging
$logQuery = "INSERT INTO webhook_logs (order_id, transaction_status, fraud_status, raw_data, processed_at) 
             VALUES (?, ?, ?, ?, NOW())";
$logStmt = $conn->prepare($logQuery);
$logStmt->bind_param("ssss", $order_id, $transaction_status, $fraud_status, $json_result);
$logStmt->execute();

// Send success response
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Webhook processed successfully']);

$conn->close();
?>