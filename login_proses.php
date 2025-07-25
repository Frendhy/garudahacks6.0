<?php
session_start();
header('Content-Type: application/json');

// Database connection (adjust according to your database configuration)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $peran = $_POST['peran'] ?? '';
    
    // Validate input
    if (empty($nama) || empty($email) || empty($password) || empty($peran)) {
        echo json_encode([
            'success' => false,
            'message' => 'Semua field harus diisi'
        ]);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false,
            'message' => 'Format email tidak valid'
        ]);
        exit;
    }
    
    // Validate role
    $valid_roles = ['pelajar', 'donatur', 'relawan'];
    if (!in_array($peran, $valid_roles)) {
        echo json_encode([
            'success' => false,
            'message' => 'Role tidak valid'
        ]);
        exit;
    }
    
    try {
        // Check if user exists with the given email and role
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND peran = ?");
        $stmt->execute([$email, $peran]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password (assuming you're using password_hash)
            if (password_verify($password, $user['password'])) {
                // Check if the name matches (for additional security)
                if (strtolower(trim($user['nama'])) === strtolower($nama)) {
                    // Login successful - create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['peran'] = $user['peran'];
                    $_SESSION['login_time'] = date('Y-m-d H:i:s');
                    
                    // Update last login time in database
                    $update_stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $update_stmt->execute([$user['id']]);
                    
                    // Return success response with user data
                    echo json_encode([
                        'success' => true,
                        'message' => 'Login berhasil',
                        'nama' => $user['nama'],
                        'email' => $user['email'],
                        'peran' => $user['peran'],
                        'user_id' => $user['id'],
                        'redirect_url' => getRoleRedirectUrl($user['peran'], $user)
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Nama tidak sesuai dengan data yang terdaftar'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Password salah'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email atau role tidak ditemukan'
            ]);
        }
        
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
}

// Function to determine redirect URL based on role
function getRoleRedirectUrl($peran, $user) {
    $userParams = http_build_query([
        'user' => $user['nama'],
        'email' => $user['email'],
        'id' => $user['id']
    ]);
    
    switch($peran) {
        case 'pelajar':
            return "student.php?" . $userParams;
        case 'donatur':
            return "donatur.php?" . $userParams;
        case 'relawan':
            return "volunteer.php?" . $userParams;
        default:
            return "dashboard.php?" . $userParams;
    }
}

// Alternative function for direct redirect (without AJAX)
function redirectBasedOnRole($peran, $user) {
    $userParams = http_build_query([
        'user' => $user['nama'],
        'email' => $user['email'],
        'id' => $user['id']
    ]);
    
    switch($peran) {
        case 'pelajar':
            header("Location: student.php?" . $userParams);
            break;
        case 'donatur':
            header("Location: donatur.php?" . $userParams);
            break;
        case 'relawan':
            header("Location: volunteer.php?" . $userParams);
            break;
        default:
            header("Location: dashboard.php?" . $userParams);
    }
    exit;
}

/*
DATABASE SCHEMA EXAMPLE:

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    peran ENUM('pelajar', 'donatur', 'relawan') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Example data insertion:
INSERT INTO users (nama, email, password, peran) VALUES 
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pelajar'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'donatur'),
('Bob Wilson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'relawan');

*/
?>