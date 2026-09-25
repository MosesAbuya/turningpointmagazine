<?php
session_start();
require_once ('inc/Database.php');
require_once ('inc/dynamic_elements.php');

$database = new Database();
$db = $database->conn;

$cart_items = [];
$total_price = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $cart_key => $qty) {
        $parts = explode('_', $cart_key);
        $product_id = (int)$parts[0];
        
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $cart_items[$cart_key] = [
                'product' => $row,
                'qty' => $qty
            ];
            $total_price += ($row['current_price'] * $qty);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<?php require_once ("inc/header.php"); ?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 font-weight-bold">My Cart</h2>
            
            <?php if (empty($cart_items)): ?>
                <div class="alert alert-info text-center py-5">
                    <h5>Your cart is currently empty.</h5>
                    <a href="index.php" class="btn btn-brand-red mt-3">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4" id="cart-container">
                        <?php 
                        foreach ($cart_items as $key => $item) {
                            cartItems($item['product'], $key, $item['qty']);
                        }
                        ?>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-bold mb-0">Total: Kes <span id="cart-total"><?= number_format($total_price, 2) ?></span></h4>
                        <a href="checkout.php" class="btn btn-brand-red px-5 py-2" style="font-size: 1.1rem; border-radius: 50px;">Proceed to Checkout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    function updateCartTotal() {
        $.ajax({
            url: 'ajax_cart_totals.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#cart-total').text(response.total);
                    $('#cart_count').text(response.count);
                    if (response.count == 0) {
                        location.reload();
                    }
                }
            }
        });
    }

    // Use event delegation so it works even if elements are dynamically added
    $(document).on('click', '.update-qty-btn', function() {
        var btn = $(this);
        var pid = String(btn.attr('data-pid'));
        var operation = btn.attr('data-operation');
        var input = $('[class*="qty-input-' + pid + '"]');
        if (!input.length) {
            input = document.querySelector('.qty-input-' + pid);
            input = input ? $(input) : null;
        }
        var currentQty = input ? parseInt(input.val()) : 1;

        if (operation === 'minus' && currentQty <= 1) return;

        var newQty = operation === 'add' ? currentQty + 1 : currentQty - 1;
        if (input) input.val(newQty);

        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: { action: 'update', pid: pid, operation: operation },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCartTotal();
                }
            }
        });
    });

    $(document).on('click', '.remove-item-btn', function() {
        var btn = $(this);
        var pid = String(btn.attr('data-id'));
        var cartItem = $('#cart-item-' + pid);

        Swal.fire({
            title: 'Remove item?',
            text: "Are you sure you want to remove this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax_cart.php',
                    type: 'POST',
                    data: { action: 'remove', pid: pid },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            btn.closest('.modern-cart-item').fadeOut(300, function() {
                                $(this).remove();
                                updateCartTotal();
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('change', '.change-size-select', function() {
        var select = $(this);
        var oldKey = String(select.attr('data-oldkey'));
        var pid = String(select.attr('data-pid'));
        var newSize = select.val();

        $.ajax({
            url: 'ajax_cart.php',
            type: 'POST',
            data: { action: 'change_size', old_key: oldKey, pid: pid, new_size: newSize },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                }
            }
        });
    });

});
</script>
</body>
</html>
