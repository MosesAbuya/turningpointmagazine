<?php
$c = file_get_contents('shop/ajax_cart.php');

$pattern = "/echo json_encode\(\['status' => 'error'/s";
$replace = <<<EOT
if (\$action === 'change_size') {
    \$old_key = isset(\$_POST['old_key']) ? \$_POST['old_key'] : '';
    \$pid = isset(\$_POST['pid']) ? \$_POST['pid'] : '';
    \$new_size = isset(\$_POST['new_size']) ? trim(\$_POST['new_size']) : '';
    \$new_key = \$new_size ? \$pid . '_' . \$new_size : \$pid;
    
    if (isset(\$_SESSION['cart'][\$old_key]) && \$old_key !== \$new_key) {
        \$qty = \$_SESSION['cart'][\$old_key];
        unset(\$_SESSION['cart'][\$old_key]);
        
        if (isset(\$_SESSION['cart'][\$new_key])) {
            \$_SESSION['cart'][\$new_key] += \$qty;
        } else {
            \$_SESSION['cart'][\$new_key] = \$qty;
        }
    }
    echo json_encode(['status' => 'success', 'cart_count' => count(\$_SESSION['cart'])]);
    exit;
}

echo json_encode(['status' => 'error'
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/ajax_cart.php', $c);
echo "ajax_cart.php updated.";
?>
