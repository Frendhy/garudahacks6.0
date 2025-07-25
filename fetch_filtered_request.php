<?php
include('db_connection.php');

// Default query to fetch all donation requests
$query = "SELECT * FROM donation_requests WHERE 1";

// Add filters if status or student name is set
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status = $_GET['status'];
    $query .= " AND status = ?";
}

if (isset($_GET['student_name']) && !empty($_GET['student_name'])) {
    $student_name = '%' . $_GET['student_name'] . '%';
    $query .= " AND student_id LIKE ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (isset($status) && isset($student_name)) {
    $stmt->bind_param("ss", $status, $student_name);
} elseif (isset($status)) {
    $stmt->bind_param("s", $status);
} elseif (isset($student_name)) {
    $stmt->bind_param("s", $student_name);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch the results and convert them to an array
$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($requests);
?>
