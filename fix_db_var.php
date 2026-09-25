<?php
$productFile = file_get_contents('shop/product.php');
$productFile = str_replace('$database->con->query', '$database->conn->query', $productFile);
file_put_contents('shop/product.php', $productFile);

$cartFile = file_get_contents('shop/cart.php');
$cartFile = str_replace('$database->con', '$database->conn', $cartFile);
file_put_contents('shop/cart.php', $cartFile);

echo "Fixed connection variables!";
?>
