<?php
session_start();
include('connection2.php');

$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    switch ($action) {
        case 'add':
            $quantity = $_POST['quantity'];
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = $quantity;
            }
            break;

        case 'update':
            $quantity = $_POST['quantity'];
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            }
            break;

        case 'remove':
            unset($_SESSION['cart'][$productId]);
            break;
    }

    // Recalculate Cart Totals
    $cart = $_SESSION['cart'];
    $totalPrice = 0;
    $subtotal = 0;
    $cartCount = array_sum($cart);

    if (!empty($cart)) {
        $placeholders = implode(',', array_fill(0, count($cart), '?'));
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute(array_keys($cart));
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $productId = $product['id'];
            $quantity = $cart[$productId];
            $subtotal = $product['current_price'] * $quantity;
            $totalPrice += $subtotal;
        }
    }

    echo json_encode([
        'cart_count' => $cartCount,
        'subtotal' => $subtotal,
        'total_price' => $totalPrice
    ]);
}
