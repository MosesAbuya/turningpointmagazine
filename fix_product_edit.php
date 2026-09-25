<?php
$c = file_get_contents('admin/shop_product_edit.php');

// Fetch the `has_sizes` property inside PHP block
$pattern1 = '/\$cost_price = \$product\[\'cost_price\'\];/s';
$replacement1 = "\$cost_price = \$product['cost_price'];\n\$has_sizes = \$product['has_sizes'] ?? 0;";
$c = preg_replace($pattern1, $replacement1, $c);

// Add the checkbox before Upload New Image
$pattern2 = '/<div class="form-group">\s*<label>Upload New Image \(Optional\)<\/label>/s';
$replacement2 = <<<EOT
                        <div class="form-group form-check">
                            <input type="checkbox" name="has_sizes" class="form-check-input" id="has_sizes" value="1" <?= \$has_sizes ? 'checked' : '' ?>>
                            <label class="form-check-label font-weight-bold" for="has_sizes">This product requires size selection (e.g., T-Shirts)</label>
                        </div>
                        <div class="form-group">
                            <label>Upload New Image (Optional)</label>
EOT;

$c = preg_replace($pattern2, $replacement2, $c);
file_put_contents('admin/shop_product_edit.php', $c);
echo "Replaced in admin/shop_product_edit.php";
?>
