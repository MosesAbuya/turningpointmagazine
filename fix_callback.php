<?php
$c = file_get_contents('callback_url.php');

$pattern1 = '/SELECT oi.quantity, oi.price, p.name/s';
$replacement1 = 'SELECT oi.quantity, oi.price, oi.size, p.name';
$c = preg_replace($pattern1, $replacement1, $c);

$pattern2 = '/\$itemName = !empty\(\$item\[\'name\'\]\) \? htmlspecialchars\(\$item\[\'name\'\]\) : \'Turning Point Item\';/s';
$replacement2 = "\$itemName = !empty(\$item['name']) ? htmlspecialchars(\$item['name']) : 'Turning Point Item';\n                                if (!empty(\$item['size'])) { \$itemName .= ' (' . htmlspecialchars(\$item['size']) . ')'; }";
$c = preg_replace($pattern2, $replacement2, $c);

file_put_contents('callback_url.php', $c);
echo "Replaced in callback_url.php";
?>
