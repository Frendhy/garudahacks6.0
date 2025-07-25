<?php
session_start();

$dsn = "mysql:host=localhost;dbname=garudahacks_equalizer";

try {
    $kunci = new PDO($dsn, "root", "");
    $kunci->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if POST data exists
if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['peran'])) {
    echo "<script type='text/javascript'>
        alert('Please fill all required fields.');
        window.location.href = 'login_form.php';
    </script>";
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];
$peran = $_POST['peran'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script type='text/javascript'>
        alert('Invalid email format.');
        window.location.href = 'login_form.php';
    </script>";
    exit;
}

// Determine table based on role
if ($peran == 'pelajar') {
    $sql = "SELECT * FROM students WHERE email = ?";
} elseif ($peran == 'donatur') {
    $sql = "SELECT * FROM donors WHERE email = ?";
} elseif ($peran == 'relawan') {
    $sql = "SELECT * FROM volunteers WHERE email = ?";
} else {
    echo "<script type='text/javascript'>
        alert('Invalid role selected.');
        window.location.href = 'login_form.php';
    </script>";
    exit;
}

try {
    $stmt = $kunci->prepare($sql);
    $stmt->execute([$email]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Clear any existing session data
            session_unset();
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['peran'] = $peran;
            $_SESSION['nama'] = $user['name']; // Store name in session
            $_SESSION['email'] = $user['email']; // Store email in session
            
            // Optional: Store additional user info based on role
            if ($peran == 'donatur') {
                $_SESSION['phone'] = isset($user['phone']) ? $user['phone'] : '';
            }
            
            // Debug: Uncomment to check session data
            // echo "User ID: " . $_SESSION['user_id'] . "<br>";
            // echo "Role: " . $_SESSION['peran'] . "<br>";
            // echo "Name: " . $_SESSION['nama'] . "<br>";
            
            // Redirect based on role
            if ($peran == 'pelajar') {
                header('Location: student.php');
            } elseif ($peran == 'donatur') {
                header('Location: donatur.php');
            } elseif ($peran == 'relawan') {
                header('Location: volunteer.php');
            }
            exit;
        } else {
            // Incorrect password
            echo "<script type='text/javascript'>
                alert('Incorrect password. Please try again.');
                window.location.href = 'login_form.php';
            </script>";
            exit;
        }
    } else {
        // User not found
        echo "<script type='text/javascript'>
            alert('User not found. Please check your email and role.');
            window.location.href = 'login_form.php';
        </script>";
        exit;
    }
} catch (PDOException $e) {
    echo "<script type='text/javascript'>
        alert('Database error: " . addslashes($e->getMessage()) . "');
        window.location.href = 'login_form.php';
    </script>";
    exit;
}
?>