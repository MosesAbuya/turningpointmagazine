<?php
$c = file_get_contents('shop/inc/dynamic_elements.php');

$pattern = '/<form action="index\.php" method="post" class="add-to-cart-form w-50 m-0">\s*<input type=\'hidden\' name=\'product_id\' value=\'\{\$product_details\[\'id\'\]\}\'>\s*<button type="submit" class="btn-brand-red w-100" name="add" style="padding: 8px 10px; font-size: 0\.9rem;">\s*<i class="fas fa-cart-plus"><\/i> CART\s*<\/button>\s*<\/form>/s';

$replace = <<<EOT
" . (\$product_details['has_sizes'] == 1 ? "
                        <a href=\"product?id={\$product_details['id']}\" class=\"btn-brand-red w-50 text-center\" style=\"padding: 8px 10px; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; justify-content: center;\">
                            <i class=\"fas fa-list\"></i> OPTIONS
                        </a>
                    " : "
                        <form action=\"index.php\" method=\"post\" class=\"add-to-cart-form w-50 m-0\">
                            <input type='hidden' name='product_id' value='{\$product_details['id']}'>
                            <button type=\"submit\" class=\"btn-brand-red w-100\" name=\"add\" style=\"padding: 8px 10px; font-size: 0.9rem;\">
                                <i class=\"fas fa-cart-plus\"></i> CART
                            </button>
                        </form>
                    ") . "
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/inc/dynamic_elements.php', $c);
echo "dynamic_elements.php fixed!";
?>
