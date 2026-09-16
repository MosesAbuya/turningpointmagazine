<?php
session_start();
require_once ("inc/Database.php");
require_once ("inc/dynamic_elements.php");

$db = new Database();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Cart</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-header { background: #f4f6f9; padding: 40px 0; border-radius: 0 0 30px 30px; margin-bottom: 40px; }
        .cart-title { font-weight: 800; font-size: 2.5rem; }
    </style>
</head>
<body>

<?php require_once ('inc/header.php'); ?>

<div class="cart-header text-center">
    <h1 class="cart-title">Your Shopping Cart</h1>
    <p class="text-muted">Review your items before proceeding to checkout</p>
</div>

<div class="container mb-5">
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="modern-container p-4">
                <h4 class="font-weight-bold border-bottom pb-3 mb-4">My Items</h4>
                <div id="cart-items-container">
                    <?php
                    $total = 0;
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        $pids = array_keys($_SESSION['cart']);
                        $result = $db->getData($pids);
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                cartItems($row);
                                $total += (floatval($row['current_price']) * intval($_SESSION['cart'][$row['id']]));
                            }
                        }
                    } else {
                        echo "<div class='text-center py-5'><h5 class='text-muted'>Your cart is currently empty!</h5><a href='index.php' class='btn-brand-red d-inline-block mt-3'>Continue Shopping</a></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <!-- Summary Widget -->
        <div class="col-lg-4">
            <div class="modern-container p-4 sticky-top" style="top: 100px;">
                <h5 class="font-weight-bold mb-4">Order Summary</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Subtotal (<span id="summary-count"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span> items)</span>
                    <span class="font-weight-bold">Kes <span id="summary-subtotal"><?= number_format($total, 2) ?></span></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Delivery</span>
                    <span class="text-muted text-right"><small>Calculated at checkout</small></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4 mt-3">
                    <h5 class="font-weight-bold">Total Amount</h5>
                    <h5 class="font-weight-bold text-brand-red">Kes <span id="summary-total"><?= number_format($total, 2) ?></span></h5>
                </div>
                
                <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                    <a href="checkout.php" class="btn-brand-red w-100 d-block text-center shadow-sm">Proceed to Checkout <i class="fas fa-arrow-right ml-1"></i></a>
                <?php endif; ?>
                
                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted small"><i class="fas fa-arrow-left mr-1"></i> Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    function updateCartTotals() {
        $.ajax({
            url: 'ajax_cart_totals',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('#summary-count, #cart_count').text(response.count);
                    
                    let formattedTotal = Number(response.total).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    $('#summary-subtotal, #summary-total').text(formattedTotal);
                    
                    if (response.count === 0) {
                        $('#cart-items-container').html("<div class='text-center py-5'><h5 class='text-muted'>Your cart is currently empty!</h5><a href='index.php' class='btn-brand-red d-inline-block mt-3'>Continue Shopping</a></div>");
                        $('.btn-brand-red[href="checkout.php"]').fadeOut();
                    }
                }
            }
        });
    }

    $('.remove-item-btn').on('click', function() {
        var btn = $(this);
        var pid = btn.data('id');
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: 'ajax_cart',
            type: 'POST',
            data: { action: 'remove', pid: pid },
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    $('#cart-item-' + pid).slideUp(300, function(){
                        $(this).remove();
                    });
                    updateCartTotals();
                }
            }
        });
    });

    $('.update-qty-btn').on('click', function() {
        var btn = $(this);
        var pid = btn.data('pid');
        var operation = btn.data('operation');
        var inputField = $('.qty-input-' + pid);
        var currentQty = parseInt(inputField.val());
        
        var newQty = (operation === 'add') ? currentQty + 1 : currentQty - 1;
        if (newQty < 1) newQty = 1;

        inputField.val(newQty);
        
        $.ajax({
            url: 'ajax_cart',
            type: 'POST',
            data: { action: 'update', pid: pid, qty: newQty },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    updateCartTotals();
                }
            }
        });
    });
});
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
