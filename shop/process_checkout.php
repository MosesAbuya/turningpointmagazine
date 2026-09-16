<?php
session_start();
include('../connection2.php');

$pdo = connect();

// Default to MPESA if not set
$paymentMethod = isset($_POST['payment_method']) ? strtoupper($_POST['payment_method']) : 'MPESA';

// Get User Details (Guest)
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];

$deliveryOption = $_POST['delivery_option'];
$deliveryAddress = isset($_POST['delivery_address']) ? $_POST['delivery_address'] : '';

// Calculate Total to ensure consistency backend-side
$totalAmount = 0;
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT id, current_price FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        $totalAmount += ($product['current_price'] * $cart[$product['id']]);
    }
}

if ($deliveryOption === 'Delivery') {
    $totalAmount += 150;
}

// Generate Unique Invoice
$invoiceNumber = "INV-" . time();

// Insert Order (user_id is NULL for guest)
$stmt = $pdo->prepare("INSERT INTO orders (user_id, invoice_id, first_name, last_name, email, contact, payment_method, total_amount, status, date_created, delivery_option, delivery_address) 
VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), ?, ?)");

$success = $stmt->execute([$invoiceNumber, $firstName, $lastName, $email, $contact, $paymentMethod, $totalAmount, $deliveryOption, $deliveryAddress]);

if ($success) {
    $orderId = $pdo->lastInsertId(); // Get inserted order ID

    // Insert Order Items
    if (isset($_POST['product_id'])) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        for ($i = 0; $i < count($_POST['product_id']); $i++) {
            $stmt->execute([$orderId, $_POST['product_id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]]);
        }
    }

    // Clear cart after successful order creation
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success', 'order_id' => $orderId]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Order could not be placed.']);
}
?>
