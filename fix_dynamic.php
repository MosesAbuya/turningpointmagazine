<?php
$c = file_get_contents('shop/inc/dynamic_elements.php');

$pattern = '/<form action="index\.php" method="post" class="add-to-cart-form w-50 m-0">.*?<\/form>/s';
$replacement = <<<EOT
                    <form action="index.php" method="post" class="add-to-cart-form w-50 m-0">
                        <input type='hidden' name='product_id' value='{\$product_details['id']}'>
                        <?php if (!empty(\$product_details['has_sizes'])): 
                            global \$database;
                            \$sizes = [];
                            if (isset(\$database->conn)) {
                                \$res = \$database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
                                if (\$res) {
                                    while (\$r = \$res->fetch_assoc()) {
                                        \$sizes[] = \$r['size_name'];
                                    }
                                }
                            }
                        ?>
                        <select name="size" class="form-control form-control-sm mb-1" required style="font-size: 0.8rem; padding: 2px 5px; height: 26px;">
                            <option value="">Size</option>
                            <?php foreach (\$sizes as \$s): ?>
                                <option value="<?= htmlspecialchars(\$s) ?>"><?= htmlspecialchars(\$s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                        <button type="submit" class="btn-brand-red w-100" name="add" style="padding: 8px 10px; font-size: 0.9rem;">
                            <i class="fas fa-cart-plus"></i> CART
                        </button>
                    </form>
EOT;

// Wait, the replacement needs to be valid PHP code that works inside a PHP function returning a string.
// `dynamic_elements.php` does:
// $element = " ... ";
// echo $element;
// So I can't inject raw PHP <?php tags into the string. I need to close the string, append, and reopen.

$replacement = <<<EOT
                    <form action="index.php" method="post" class="add-to-cart-form w-50 m-0">
                        <input type='hidden' name='product_id' value='{\$product_details['id']}'>
                        " . (!empty(\$product_details['has_sizes']) ? "
                        <select name='size' class='form-control form-control-sm mb-1' required style='font-size: 0.8rem; padding: 2px 5px; height: 26px;'>
                            <option value=''>Size</option>
                            " . (function() {
                                global \$database;
                                \$s = '';
                                if (isset(\$database->conn)) {
                                    \$res = \$database->conn->query("SELECT size_name FROM product_sizes ORDER BY order_index ASC");
                                    if (\$res) {
                                        while (\$r = \$res->fetch_assoc()) {
                                            \$s .= "<option value='" . htmlspecialchars(\$r['size_name'], ENT_QUOTES) . "'>" . htmlspecialchars(\$r['size_name'], ENT_QUOTES) . "</option>";
                                        }
                                    }
                                }
                                return \$s;
                            })() . "
                        </select>
                        " : "") . "
                        <button type="submit" class="btn-brand-red w-100" name="add" style="padding: 8px 10px; font-size: 0.9rem;">
                            <i class="fas fa-cart-plus"></i> CART
                        </button>
                    </form>
EOT;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('shop/inc/dynamic_elements.php', $c);
echo "Replaced in dynamic_elements.php";
?>
