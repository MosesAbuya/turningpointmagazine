<?php
$c = file_get_contents('shop/index.php');

$pattern = '/var productId = form\.find\(\'input\[name="product_id"\]\'\)\.val\(\);\s*var btn = form\.find\(\'button\[type="submit"\]\'\);\s*var originalText = btn\.html\(\);\s*btn\.html\(\'<i class="fa fa-spinner fa-spin"><\/i> Adding\.\.\.\'\);\s*btn\.prop\(\'disabled\', true\);\s*\$\.ajax\(\{\s*url: \'ajax_cart\',\s*type: \'POST\',\s*data: \{ action: \'add\', pid: productId \},/s';

$replace = <<<EOT
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

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/index.php', $c);
echo "index.php js updated.\n";
?>
