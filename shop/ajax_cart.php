<?php
session_start();

$action = isset($_POST['action']) ? $_POST['action'] : '';
$pid = isset($_POST['pid']) ? $_POST['pid'] : null;

if ($action === 'add') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += 1;
    } else {
        $_SESSION['cart'][$pid] = 1;
    }
    
    echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
    exit;
}

if ($action === 'remove') {
    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
    }
    echo json_encode(['status' => 'success', 'cart_count' => isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0]);
    exit;
}

if ($action === 'update') {
    $operation = isset($_POST['operation']) ? $_POST['operation'] : '';
    if ($operation === 'add') {
        $_SESSION['cart'][$pid] += 1;
    } else if ($operation === 'minus') {
        if ($_SESSION['cart'][$pid] > 1) {
            $_SESSION['cart'][$pid] -= 1;
        }
    }
    echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
