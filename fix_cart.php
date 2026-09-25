<?php
$c = file_get_contents('shop/cart.php');

$pattern = '/\$pids = array_keys\(\$_SESSION\[\'cart\'\]\);.*?if \(\$result\) \{.*?while \(\$row = \$result->fetch_assoc\(\)\) \{.*?cartItems\(\$row\);.*?\}.*?\}/s';

$replacement = <<<EOT
                        \$pids = [];
                        foreach (array_keys(\$_SESSION['cart']) as \$k) {
                            \$parts = explode('_', \$k);
                            \$pids[] = intval(\$parts[0]);
                        }
                        \$pids = array_unique(\$pids);
                        \$result = \$db->getData(\$pids);
                        if (\$result) {
                            while (\$row = \$result->fetch_assoc()) {
                                foreach (\$_SESSION['cart'] as \$k => \$qty) {
                                    if (explode('_', \$k)[0] == \$row['id']) {
                                        cartItems(\$row, \$k, \$qty);
                                        \$total += (floatval(\$row['current_price']) * intval(\$qty));
                                    }
                                }
                            }
                        }
EOT;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('shop/cart.php', $c);
echo "Replaced in cart.php";
?>
