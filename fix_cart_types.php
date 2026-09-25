<?php
$c = file_get_contents('shop/cart.php');

// Fix jQuery .data() parsing numbers by explicitly casting to String
$c = str_replace("var pid = btn.data('pid');", "var pid = String(btn.data('pid'));", $c);
$c = str_replace("var pid = btn.data('id');", "var pid = String(btn.data('id'));", $c);

file_put_contents('shop/cart.php', $c);
echo "cart.php JS pid types fixed.";
?>
