<?php
session_start();
$action = isset($_POST['action']) ? $_POST['action'] : '';
$pid = isset($_POST['pid']) ? $_POST['pid'] : null;
$size = isset($_POST['size']) ? trim($_POST['size']) : '';
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

if ($qty < 1) $qty = 1;

// Create a composite key like "5_Large" or just "5"
$cart_key = $size ? $pid . '_' . $size : $pid;

if ($action === 'add') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key] += $qty;
    } else {
        $_SESSION['cart'][$cart_key] = $qty;
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

if ($action === 'change_size') {
    $old_key = isset($_POST['old_key']) ? $_POST['old_key'] : '';
    $pid = isset($_POST['pid']) ? $_POST['pid'] : '';
    $new_size = isset($_POST['new_size']) ? trim($_POST['new_size']) : '';
    $new_key = $new_size ? $pid . '_' . $new_size : $pid;
    
    if (isset($_SESSION['cart'][$old_key]) && $old_key !== $new_key) {
        $qty = $_SESSION['cart'][$old_key];
        unset($_SESSION['cart'][$old_key]);
        
        if (isset($_SESSION['cart'][$new_key])) {
            $_SESSION['cart'][$new_key] += $qty;
        } else {
            $_SESSION['cart'][$new_key] = $qty;
        }
    }
    echo json_encode(['status' => 'success', 'cart_count' => count($_SESSION['cart'])]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
