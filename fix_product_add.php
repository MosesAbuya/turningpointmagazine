<?php
$c = file_get_contents('admin/shop_product_add.php');

$pattern = '/<div class="form-group">\s*<label>Product Image<\/label>/s';
$replacement = <<<EOT
                        <div class="form-group form-check">
                            <input type="checkbox" name="has_sizes" class="form-check-input" id="has_sizes" value="1">
                            <label class="form-check-label font-weight-bold" for="has_sizes">This product requires size selection (e.g., T-Shirts)</label>
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
EOT;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('admin/shop_product_add.php', $c);
echo "Replaced in admin/shop_product_add.php";
?>
