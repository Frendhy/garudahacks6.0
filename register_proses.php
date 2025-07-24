<?php

$dsn = "mysql:host=localhost;dbname=garudahacks_equalizer";

$kunci = new PDO($dsn, "root", "");

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$peran = $_POST['peran'];
$password = $_POST['password'];

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

if ($peran == 'pelajar') {
    $sql = "INSERT INTO students (name, email, phone, password, status, created_at)
        VALUES (?, ?, ?, ?, 'active', NOW())";
    $stmt = $kunci->prepare($sql);
    $data = [$name, $email, $phone, $hashedPassword];
    if ($stmt->execute($data)) {
        header('Location: student.php');
        echo "Error saving data.";
    }

} elseif ($peran == 'donatur') {
    $sql = "INSERT INTO donors (name, email, phone, password, created_at)
        VALUES (?, ?, ?, ?, NOW())";
    $stmt = $kunci->prepare($sql);
    $data = [$name, $email, $phone, $hashedPassword];
    if ($stmt->execute($data)) {
        header('Location: donatur.php');
        echo "Error saving data.";
    }
} elseif ($peran == 'relawan') {
    $sql = "INSERT INTO volunteers (name, email, phone, password, created_at)
        VALUES (?, ?, ?, ?, NOW())";
    $stmt = $kunci->prepare($sql);
    $data = [$name, $email, $phone, $hashedPassword];
    if ($stmt->execute($data)) {
        header('Location: volunteer.php');
        echo "Error saving data.";
    }
}
?>