<?php
session_start();
include('../connection2.php');

header('Content-Type: application/json; charset=utf-8');

$pdo = connect();

// Get active payment gateway setting
$active_gateway = 'PAYSTACK';
try {
    $stmt_set = $pdo->query("SELECT payment_gateway FROM shop_settings LIMIT 1");
    if ($row_set = $stmt_set->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row_set['payment_gateway']) && strtolower($row_set['payment_gateway']) === 'daraja') {
            $active_gateway = 'MPESA';
        }
    }
} catch (Exception $e) {}

$paymentMethod = isset($_POST['payment_method']) && !empty($_POST['payment_method']) ? strtoupper($_POST['payment_method']) : $active_gateway;

// Get User Details
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');

$deliveryOption = trim($_POST['delivery_option'] ?? 'Pick up');
$deliveryAddress = trim($_POST['delivery_address'] ?? '');

if (empty($firstName) || empty($lastName) || empty($email) || empty($contact)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required customer details.']);
    exit;
}

// Calculate Total to ensure consistency backend-side
$totalAmount = 0;
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    echo json_encode(['status' => 'error', 'message' => 'Your cart is empty.']);
    exit;
}

$pids = [];
foreach (array_keys($cart) as $k) {
    $parts = explode('_', $k);
    $pids[] = intval($parts[0]);
}
$pids = array_unique($pids);

$products_map = [];
if (!empty($pids)) {
    $placeholders = implode(',', array_fill(0, count($pids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, current_price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($pids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $p) {
        $products_map[$p['id']] = $p;
    }
    
    foreach ($cart as $k => $qty) {
        $pid = intval(explode('_', $k)[0]);
        if (isset($products_map[$pid])) {
            $totalAmount += ($products_map[$pid]['current_price'] * intval($qty));
        }
    }
}

// Fetch dynamic delivery fee
$delivery_fee = 150; // default
try {
    $stmt = $pdo->query("SELECT delivery_fee FROM shop_settings LIMIT 1");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $delivery_fee = floatval($row['delivery_fee']);
    }
} catch (Exception $e) {}

if ($deliveryOption === 'Delivery') {
    $totalAmount += $delivery_fee;
}

// Generate Unique Invoice
$invoiceNumber = "INV-" . time() . "-" . rand(100, 999);

// Insert Order
$stmt = $pdo->prepare("INSERT INTO orders 
    (user_id, invoice_id, first_name, last_name, email, contact, payment_method, total_amount, status, date_created, delivery_option, delivery_address) 
    VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), ?, ?)");

$success = $stmt->execute([$invoiceNumber, $firstName, $lastName, $email, $contact, $paymentMethod, $totalAmount, $deliveryOption, $deliveryAddress]);

if ($success) {
    $orderId = $pdo->lastInsertId(); // Get inserted order ID

    // Insert Order Items directly from session cart
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($cart as $k => $qty) {
        $parts = explode('_', $k);
        $pid = intval($parts[0]);
        $size = isset($parts[1]) ? $parts[1] : null;
        
        if (isset($products_map[$pid])) {
            $stmt->execute([$orderId, $pid, intval($qty), $products_map[$pid]['current_price'], $size]);
        }
    }

    // Clear cart after successful order creation
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success', 'order_id' => $orderId, 'invoice_id' => $invoiceNumber]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Order could not be placed.']);
}
?>