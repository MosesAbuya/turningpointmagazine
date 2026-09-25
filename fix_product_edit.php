<?php
$c = file_get_contents('admin/shop_product_edit.php');

$pattern = '/(<\/div>\s*<div class="form-group">\s*<label>Current Image<\/label>)/s';
$replace = <<<EOT
</div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="has_sizes" class="form-check-input" id="has_sizes" value="1" <?= (!empty(\$product['has_sizes'])) ? 'checked' : '' ?>>
                            <label class="form-check-label font-weight-bold" for="has_sizes">This product requires size selection (e.g., T-Shirts)</label>
                        </div>
                        <div class="form-group">
                            <label>Current Image</label>
EOT;

$c = preg_replace($pattern, $replace, $c);

file_put_contents('admin/shop_product_edit.php', $c);
echo "Added has_sizes checkbox to shop_product_edit.php";
?>
