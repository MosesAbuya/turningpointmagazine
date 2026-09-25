<?php
$c = file_get_contents('shop/checkout.php');

$pattern = '/<option value="Delivery">Delivery \(\+ Ksh 150\)<\/option>/s';
$replacement = <<<EOT
                            <?php 
                                require_once '../connection2.php';
                                \$pdo_fee = connect();
                                \$delivery_fee = 150;
                                try {
                                    \$stmt_fee = \$pdo_fee->query("SELECT delivery_fee FROM shop_settings LIMIT 1");
                                    if (\$row_fee = \$stmt_fee->fetch(PDO::FETCH_ASSOC)) {
                                        \$delivery_fee = floatval(\$row_fee['delivery_fee']);
                                    }
                                } catch (Exception \$e) {}
                            ?>
                            <option value="Delivery">Delivery (+ Ksh <?= number_format(\$delivery_fee, 2) ?>)</option>
EOT;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('shop/checkout.php', $c);
echo "Replaced in shop/checkout.php";
?>
