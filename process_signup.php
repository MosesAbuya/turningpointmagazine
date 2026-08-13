<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connection2.php'); // Include PDO connection

$pdo = connect(); // Establish PDO connection

if (!$pdo) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

// Ensure it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

// Get user input
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email) || empty($contact) || empty($password) || empty($confirm_password)) {
    echo json_encode(["status" => "error", "message" => "<p style='color: red;'>All fields are required.</p>"]);
    exit;
}

// Check if passwords match
if ($password !== $confirm_password) {
    echo json_encode(["status" => "error", "message" => "<p style='color: red;'>Passwords do not match.</p>"]);
    exit;
}

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "<p style='color: red;'>Email already exists.</p>"]);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$stmt = $pdo->prepare("INSERT INTO customers (first_name, last_name, email, contact, password) VALUES (?, ?, ?, ?, ?)");

if ($stmt->execute([$first_name, $last_name, $email, $contact, $hashed_password])) {
    echo json_encode(["status" => "success", "message" => "<p style='color: green;'>Registration successful!</p>"]);
} else {
    echo json_encode(["status" => "error", "message" => "<p style='color: red;'>Error: Could not register.</p>"]);
}

// Close PDO connection
closeConnection($pdo);
?>
