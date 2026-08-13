<?php
// Remove session_start() and session-based logic
include('connection2.php');
$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];
    $cart = !empty($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

    switch ($action) {
        case 'add':
            $quantity = $_POST['quantity'];
            $itemIndex = array_search($productId, array_column($cart, 'product_id'));
            
            if ($itemIndex !== false) {
                $cart[$itemIndex]['quantity'] += $quantity;
            } else {
                $cart[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => // Get price from database
                ];
            }
            break;

        case 'update':
            $quantity = $_POST['quantity'];
            $itemIndex = array_search($productId, array_column($cart, 'product_id'));
            
            if ($itemIndex !== false && $quantity > 0) {
                $cart[$itemIndex]['quantity'] = $quantity;
            }
            break;

        case 'remove':
            $cart = array_filter($cart, fn($item) => $item['product_id'] != $productId);
            break;
    }

    // Recalculate Cart Totals
    $totalPrice = 0;
    $subtotal = 0;
    $cartCount = array_sum(array_column($cart, 'quantity'));

    if (!empty($cart)) {
        $productIds = array_column($cart, 'product_id');
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        
        $stmt = $pdo->prepare("SELECT id, current_price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $products = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        foreach ($cart as $item) {
            if (isset($products[$item['product_id']])) {
                $price = $products[$item['product_id']];
                $subtotal = $price * $item['quantity'];
                $totalPrice += $subtotal;
            }
        }
    }

    echo json_encode([
        'cart_count' => $cartCount,
        'subtotal' => $subtotal,
        'total_price' => $totalPrice,
        'cart' => $cart // Return updated cart
    ]);
}