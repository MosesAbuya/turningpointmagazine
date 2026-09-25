<?php
$c = file_get_contents('callback_paybill.php');

$pattern1 = '/SELECT o.id, o.total_amount, oi.product_id, oi.quantity, oi.price, p.name AS product_name/s';
$replacement1 = 'SELECT o.id, o.total_amount, oi.product_id, oi.quantity, oi.price, oi.size, p.name AS product_name';
$c = preg_replace($pattern1, $replacement1, $c);

$pattern2 = '/\$itemName = !empty\(\$item\[\'product_name\'\]\) \? htmlspecialchars\(\$item\[\'product_name\'\]\) : \'Turning Point Item\';/s';
$replacement2 = "\$itemName = !empty(\$item['product_name']) ? htmlspecialchars(\$item['product_name']) : 'Turning Point Item';\n                    if (!empty(\$item['size'])) { \$itemName .= ' (' . htmlspecialchars(\$item['size']) . ')'; }";
$c = preg_replace($pattern2, $replacement2, $c);

file_put_contents('callback_paybill.php', $c);
echo "Replaced in callback_paybill.php";
?>
