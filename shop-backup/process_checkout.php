<?php
session_start();
include('connection2.php');

$pdo = connect();

if (!isset($_POST['user_id']) || !isset($_POST['payment_method'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please Select A Payment Method!']);
exit;
}

// Get User Details
$userId = $_POST['user_id'];
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$paymentMethod = $_POST['payment_method'];
$totalAmount = $_POST['total_amount'];

// Generate Unique Invoice
$invoiceNumber = "INV-" . time();

// Insert Order
$stmt = $pdo->prepare("INSERT INTO orders (user_id, invoice_id, first_name, last_name, email, contact, payment_method, total_amount, status, date_created) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

$success = $stmt->execute([$userId, $invoiceNumber, $firstName, $lastName, $email, $contact, $paymentMethod, $totalAmount]);

if ($success) {
    $orderId = $pdo->lastInsertId(); // Get inserted order ID

    // Insert Order Items
    if (isset($_POST['product_id'])) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        for ($i = 0; $i < count($_POST['product_id']); $i++) {
            $stmt->execute([$orderId, $_POST['product_id'][$i], $_POST['quantity'][$i], $_POST['price'][$i]]);
        }
    }

    // Clear cart after successful order
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success', 'order_id' => $orderId]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Order could not be placed.']);
}
?>
