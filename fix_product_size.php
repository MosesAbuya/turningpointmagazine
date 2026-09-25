<?php
$c = file_get_contents('shop/product.php');

$formPattern = '/<form action="index\.php" method="post" class="add-to-cart-form">\s*<input type="hidden" name="product_id" value="<\?= \$product\[\'id\'\] \?>">\s*<button type="submit" class="btn-brand-red py-3 px-5 text-uppercase w-100" style="font-size: 1.1rem; border-radius: 50px;">\s*Add to Cart <i class="fas fa-cart-plus ml-2"><\/i>\s*<\/button>\s*<\/form>/s';

$formReplace = <<<EOT
<form action="index.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?= \$product['id'] ?>">
                        
                        <?php if (\$product['has_sizes'] == 1): 
                            \$stmtSizes = \$database->con->query("SELECT * FROM product_sizes ORDER BY order_index ASC");
                        ?>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Select Size:</label>
                                <select name="size" class="form-control" required>
                                    <option value="">-- Choose Size --</option>
                                    <?php while(\$s = \$stmtSizes->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars(\$s['size_name']) ?>"><?= htmlspecialchars(\$s['size_name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Quantity:</label>
                            <input type="number" name="qty" class="form-control" value="1" min="1" required style="max-width: 150px;">
                        </div>

                        <button type="submit" class="btn-brand-red py-3 px-5 text-uppercase w-100 mt-2" style="font-size: 1.1rem; border-radius: 50px;">
                            Add to Cart <i class="fas fa-cart-plus ml-2"></i>
                        </button>
                    </form>
EOT;
$c = preg_replace($formPattern, $formReplace, $c);

// Also need to update the Javascript to send size and qty
$jsPattern = '/var productId = form\.find\(\'input\[name="product_id"\]\'\)\.val\(\);\s*var btn = form\.find\(\'button\[type="submit"\]\'\);\s*var originalText = btn\.html\(\);\s*btn\.html\(\'<i class="fa fa-spinner fa-spin"><\/i> Adding\.\.\.\'\);\s*btn\.prop\(\'disabled\', true\);\s*\$\.ajax\(\{\s*url: \'ajax_cart\',\s*type: \'POST\',\s*data: \{ action: \'add\', pid: productId \},/s';

$jsReplace = <<<EOT
var productId = form.find('input[name="product_id"]').val();
        var sizeSelect = form.find('select[name="size"]');
        var qtyInput = form.find('input[name="qty"]');
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        
        var sizeVal = sizeSelect.length ? sizeSelect.val() : '';
        var qtyVal = qtyInput.length ? qtyInput.val() : 1;

        if (sizeSelect.length && !sizeVal) {
            Swal.fire('Error', 'Please select a size first.', 'warning');
            return;
        }
        
        btn.html('<i class="fa fa-spinner fa-spin"></i> Adding...');
        btn.prop('disabled', true);

        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: { action: 'add', pid: productId, size: sizeVal, qty: qtyVal },
EOT;

$c = preg_replace($jsPattern, $jsReplace, $c);

file_put_contents('shop/product.php', $c);
echo "Updated product.php for size and qty.";
?>
