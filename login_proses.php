<?php
$dsn = "mysql:host=localhost;dbname=garudahacks_equalizer";

$kunci = new PDO($dsn, "root", "");

$email = $_POST['email'];
$password = $_POST['password'];
$peran = $_POST['peran'];

if ($peran == 'pelajar') {
    $sql = "SELECT * FROM students WHERE email = ?";
} elseif ($peran == 'donatur') {
    $sql = "SELECT * FROM donors WHERE email = ?";
} elseif ($peran == 'relawan') {
    $sql = "SELECT * FROM volunteers WHERE email = ?";
} else {
    echo "Invalid role selected.";
    exit;
}

$stmt = $kunci->prepare($sql);
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['peran'] = $peran;
        
        if ($peran == 'pelajar') {
            header('Location: student.php');
        } elseif ($peran == 'donatur') {
            header('Location: donatur.php');
        } elseif ($peran == 'relawan') {
            header('Location: volunteer.php');
        }
    } else {
        // Incorrect password
        echo "<script type='text/javascript'>alert('Incorrect password. Please try again.');</script>";
    }
} else {
    // User not found
    echo "<script type='text/javascript'>alert('User not found. Please check your email and peran.');</script>";
}
?>
