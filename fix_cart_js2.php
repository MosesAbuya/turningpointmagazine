<?php
$c = file_get_contents('shop/cart.php');

$pattern = '/\$\(\'\.update-qty-btn\'\)\.on\(\'click\', function\(\) \{\s*var btn = \$\(this\);\s*var pid = btn\.data\(\'id\'\);/s';
$replace = <<<EOT
\$('.update-qty-btn').on('click', function() {
        var btn = \$(this);
        var pid = btn.data('pid');
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/cart.php', $c);
echo "update-qty-btn fixed back to data-pid.";
?>
