<?php
$c = file_get_contents('shop/inc/dynamic_elements.php');

// We need to carefully replace the form inside the string.
$pattern = '/<form action="index\.php" method="post" class="add-to-cart-form w-50 m-0">\s*<input type=\'hidden\' name=\'product_id\' value=\'\{\$product_details\[\'id\'\]\}\'>\s*<button type="submit" class="btn-brand-red w-100" name="add" style="padding: 8px 10px; font-size: 0\.9rem;">\s*<i class="fas fa-cart-plus"><\/i> CART\s*<\/button>\s*<\/form>/s';
// Wait, the previous preg_replace already ran? Let me check if it succeeded.
// Since the string wasn't properly formed, let's restore it.
$c = file_get_contents('shop/inc/dynamic_elements.php');
// Remove the broken php tags inside the string
// Or just let's see what is there now.
?>
