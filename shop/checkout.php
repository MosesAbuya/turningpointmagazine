<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Turning Point Shop - Checkout</title>
    <!-- Global Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link rel="stylesheet" href="../global.css">
<link rel="stylesheet" href="style.css">
<style>
    .checkout-header { background: #f4f6f9; padding: 40px 0; border-radius: 0 0 30px 30px; margin-bottom: 40px; }
    .checkout-title { font-weight: 800; font-size: 2.5rem; }
</style>
</head>
<body>

<?php require_once ('inc/header.php'); ?>

<div class="checkout-header text-center">
    <h1 class="checkout-title">Checkout</h1>
    <p class="text-muted">Fill out your details to finalize the order</p>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="modern-container">
                <h4 class="font-weight-bold border-bottom pb-3 mb-4">Billing & Delivery Details</h4>
                <form id="checkout-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">First Name</label>
                            <input type="text" name="first_name" class="form-control form-control-modern" required placeholder="John">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Last Name</label>
                            <input type="text" name="last_name" class="form-control form-control-modern" required placeholder="Doe">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-modern" required placeholder="johndoe@example.com">
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Contact Phone Number</label>
                        <input type="tel" name="contact" class="form-control form-control-modern" required placeholder="2547XXXXXXXX">
                    </div>

                    <div class="mb-4">
                        <label class="font-weight-bold">Delivery Option</label>
                        <select name="delivery_option" id="delivery_option" class="form-control form-control-modern" required>
                            <option value="Pick up">Pick Up (Free)</option>
                            <option value="Delivery">Delivery (+ Ksh 150)</option>
                        </select>
                    </div>

                    <div id="delivery_address_group" class="mb-4" style="display: none;">
                        <label class="font-weight-bold">Delivery Address</label>
                        <textarea name="delivery_address" class="form-control form-control-modern" rows="3" placeholder="Enter your full physical address..."></textarea>
                    </div>
                    
                    <!-- Payment method is hidden because M-Pesa is standard -->
                    <input type="hidden" name="payment_method" value="MPESA">
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <button type="button" id="proceed-btn" class="btn-brand-red w-100 py-3 text-uppercase shadow-sm">Proceed to Payment <i class="fas fa-lock ml-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#delivery_option').change(function() {
            if ($(this).val() === 'Delivery') {
                $('#delivery_address_group').slideDown();
                $('textarea[name="delivery_address"]').prop('required', true);
            } else {
                $('#delivery_address_group').slideUp();
                $('textarea[name="delivery_address"]').prop('required', false);
            }
        });

        $("#proceed-btn").click(function() {
            var form = document.getElementById('checkout-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            var btn = $(this);
            var originalText = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...').prop('disabled', true);

            $.ajax({
                url: "process_checkout",
                type: "POST",
                data: $("#checkout-form").serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        window.location.href = "payment?order_id=" + response.order_id;
                    } else {
                        Swal.fire('Error', response.message, 'error');
                        btn.html(originalText).prop("disabled", false);
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Could not process checkout at this time.', 'error');
                    btn.html(originalText).prop("disabled", false);
                }
            });
        });
    });
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
