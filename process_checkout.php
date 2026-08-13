<?php
session_start();
include('connection2.php');
$pdo = connect();

// Add this after session_regenerate_id(true);
$_SESSION['checkout_time'] = time(); // Add expiration timestamp

// Modify the session data storage to include ALL required fields:
$_SESSION['checkout_data'] = [
    'cart' => $_POST['cart'],
    'first_name' => $firstName,
    'last_name' => $lastName,
    'email' => $email,
    'invoice_id' => $invoiceNumber,
    'order_id' => $orderId,
    'total_amount' => $totalAmount,
    'checkout_time' => time() // MUST MATCH VALIDATION REQUIREMENTS
];


// Validate required fields
$required = ['first_name', 'last_name', 'email', 'payment_method', 'total_amount'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Extract and sanitize data
$firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
$totalAmount = filter_input(INPUT_POST, 'total_amount', FILTER_VALIDATE_FLOAT);

// Validate numeric values
if (!$totalAmount || $totalAmount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid total amount']);
    exit;
}

// Cart validation
if (empty($_POST['cart'])) {
    echo json_encode(['status' => 'error', 'message' => 'Empty cart data']);
    exit;
}

$cart = json_decode($_POST['cart'], true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart format']);
    exit;
}

// Product validation
if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !isset($_POST['price'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid cart data']);
    exit;
}

// Validate cart items match database prices
try {
    $placeholders = implode(',', array_fill(0, count($_POST['product_id']), '?'));
    $stmt = $pdo->prepare("SELECT id, current_price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($_POST['product_id']);
    $validProducts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    foreach ($_POST['product_id'] as $index => $productId) {
        if (!isset($validProducts[$productId]) || 
            (float)$validProducts[$productId] !== (float)$_POST['price'][$index]) {
            throw new Exception("Price validation failed for product $productId");
        }
    }
} catch (Exception $e) {
    error_log("Price validation error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Cart validation failed']);
    exit;
}

// Generate invoice ID with more entropy
$invoiceNumber = "INV-" . bin2hex(random_bytes(4)) . "-" . time();

try {
    $pdo->beginTransaction();

    // Insert Order
    $stmt = $pdo->prepare("INSERT INTO orders 
        (user_id, invoice_id, first_name, last_name, email, payment_method, total_amount, status, date_created) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

    $success = $stmt->execute([
        $_POST['user_id'] ?? null,
        $invoiceNumber,
        $firstName,
        $lastName,
        $email,
        $paymentMethod,
        $totalAmount
    ]);

    if (!$success) {
        throw new Exception("Failed to create order");
    }

    $orderId = $pdo->lastInsertId();

    // Insert Order Items
    $stmt = $pdo->prepare("INSERT INTO order_items 
        (order_id, product_id, quantity, price) 
        VALUES (?, ?, ?, ?)");

    foreach ($_POST['product_id'] as $index => $productId) {
        $stmt->execute([
            $orderId,
            $productId,
            $_POST['quantity'][$index],
            $_POST['price'][$index]
        ]);
    }

    // Store critical data in session
    $_SESSION['checkout_data'] = [
        'cart' => $_POST['cart'],
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'invoice_id' => $invoiceNumber,
        'order_id' => $orderId,
        'total_amount' => $totalAmount
    ];

    // Security enhancements
    session_regenerate_id(true);
    $_SESSION['checkout_time'] = time();

    $pdo->commit();

    echo json_encode([
        'status' => 'success', 
        'order_id' => $orderId,
        'invoice_id' => $invoiceNumber
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Checkout Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Order processing failed']);
}
