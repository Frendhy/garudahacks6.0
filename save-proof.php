<?php
session_start();
include('db_connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['donor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['donationId']) || !isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

$donationId = (int)$_POST['donationId'];
$donor_id = $_SESSION['donor_id'];

// Verify that this donation belongs to the current donor
$verifyQuery = "SELECT id FROM donations WHERE id = ? AND donor_id = ?";
$verifyStmt = $conn->prepare($verifyQuery);
$verifyStmt->bind_param("ii", $donationId, $donor_id);
$verifyStmt->execute();
$verifyResult = $verifyStmt->get_result();

if ($verifyResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Donation not found or access denied']);
    exit;
}

$file = $_FILES['file'];

// Validate file
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
$maxSize = 5 * 1024 * 1024; // 5MB

if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'File type not allowed. Please use JPG, PNG, or PDF']);
    exit;
}

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File size too large. Maximum 5MB allowed']);
    exit;
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'File upload error']);
    exit;
}

// Create upload directory if it doesn't exist
$uploadDir = 'uploads/proofs/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = 'proof_' . $donationId . '_' . time() . '.' . $fileExtension;
$filePath = $uploadDir . $fileName;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filePath)) {
    // Update database with proof URL
    $updateQuery = "UPDATE donations SET proof_url = ?, status = 'pending' WHERE id = ? AND donor_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sii", $filePath, $donationId, $donor_id);
    
    if ($updateStmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Proof uploaded successfully',
            'proof_url' => $filePath
        ]);
    } else {
        // Delete uploaded file if database update fails
        unlink($filePath);
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
    
    $updateStmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save file']);
}

$verifyStmt->close();
$conn->close();
?>