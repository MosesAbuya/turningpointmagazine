<?php
header('Content-Type: application/json'); // Ensure JSON response
session_start();
include('connection2.php');

$pdo = connect(); // Get PDO connection

if (!$pdo) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// Fetch input data
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input fields
if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email and password are required."]);
    exit;
}

// Check if user exists
$stmt = $pdo->prepare("SELECT id, password FROM customers WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
    exit;
}

// Start session and store user data
$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $email;

echo json_encode(["status" => "success", "message" => "Login successful! Redirecting..."]);
exit;
?>
