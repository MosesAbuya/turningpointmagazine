<?php
$c = file_get_contents('shop/cart.php');

$c = str_replace("url: 'ajax_cart'", "url: 'ajax_cart.php'", $c);
$c = str_replace("url: 'ajax_cart_totals.php'", "url: 'ajax_cart_totals.php'", $c); // Already correct

// Update remove button selector and ajax
$c = str_replace("$('.remove-btn')", "$('.remove-item-btn')", $c);
$c = str_replace("var pid = btn.data('pid');", "var pid = btn.data('id');", $c);

// Inject size change logic before the end of document.ready
$pattern = "/\}\);\s*\<\/script>/s";
$replace = <<<EOT
});

    $('.change-size-select').on('change', function() {
        var select = $(this);
        var oldKey = select.data('oldkey');
        var pid = select.data('pid');
        var newSize = select.val();
        
        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: { action: 'change_size', old_key: oldKey, pid: pid, new_size: newSize },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Reload the cart to merge identical sizes if needed
                    location.reload();
                }
            }
        });
    });

});
</script>
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('shop/cart.php', $c);
echo "cart.php JS updated.";
?>
