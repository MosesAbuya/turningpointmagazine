<?php
session_start();
include '../connection2.php';

$pdo = connect();

if (!isset($_GET['order_id'])) {
    die("Invalid access. No order ID provided.");
}

$order_id = $_GET['order_id'];

// Fetch the order based on order_id
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("No orders found.");
}

$invoice_id = $order['invoice_id'];
$first_name = $order['first_name'];
$last_name = $order['last_name'];
$email = $order['email'];
$total_amount = $order['total_amount'];
$contact_phone = $order['contact'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Turning Point Magazine - Secure Payment</title>
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
    .payment-header { background: #111; color: #fff; padding: 40px 0; border-radius: 0 0 30px 30px; margin-bottom: 40px; }
    .payment-title { font-weight: 800; font-size: 2.5rem; }
    .mpesa-logo { width: 120px; margin-bottom: 20px; }
    .invoice-box { background: #f4f6f9; border-radius: 15px; padding: 20px; margin-bottom: 25px; }
    .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px; }
    .invoice-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
</style>
</head>
<body>

<?php require_once ('inc/header.php'); ?>

<div class="payment-header text-center">
    <h1 class="payment-title">Secure Payment</h1>
    <p class="text-light opacity-75">Pay securely using Safaricom M-Pesa</p>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="modern-container text-center">
                <!-- Using a generic mpesa text or icon since external logo link might break -->
                <h2 class="font-weight-bold text-success mb-4"><i class="fas fa-mobile-alt"></i> M-PESA</h2>
                
                <div class="invoice-box text-left">
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Invoice ID:</span>
                        <span class="font-weight-bold"><?= htmlspecialchars($invoice_id) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Name:</span>
                        <span><?= htmlspecialchars($first_name . " " . $last_name) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Email:</span>
                        <span><?= htmlspecialchars($email) ?></span>
                    </div>
                    <div class="invoice-row mt-2 pt-2" style="border-top: 2px solid #ccc;">
                        <span class="font-weight-bold text-dark">Total Amount:</span>
                        <span class="font-weight-bold text-brand-red" style="font-size: 1.2rem;">Ksh <?= number_format($total_amount, 2) ?></span>
                    </div>
                </div>

                <form id="payment-form">
                    <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoice_id) ?>">
                    <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name) ?>">
                    <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name) ?>">
                    <input type="hidden" name="organization" value="">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <input type="hidden" name="category" value="magazine">
                    <input type="hidden" name="amount" value="<?= $total_amount ?>">
                    <input type="hidden" name="currency" value="KES">
                    <input type="hidden" name="payment_method" value="mpesa">
                    
                    <div class="form-group text-left mt-4">
                        <label for="contact" class="font-weight-bold">M-Pesa Phone Number:</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <select class="custom-select" style="font-weight: bold; background: #eee; border-top-right-radius: 0; border-bottom-right-radius: 0; padding-right: 5px;">
                                    <option value="254">🇰🇪 +254</option>
                                </select>
                            </div>
                            <?php
                            $display_phone = $contact_phone;
                            if (strpos($display_phone, '254') === 0) {
                                $display_phone = substr($display_phone, 3);
                            } elseif (strpos($display_phone, '0') === 0) {
                                $display_phone = substr($display_phone, 1);
                            }
                            ?>
                            <input type="tel" name="contact" id="contact" class="form-control form-control-modern text-center" style="font-size: 1.2rem; border-top-left-radius: 0; border-bottom-left-radius: 0;" required placeholder="e.g. 712345678" value="<?= htmlspecialchars($display_phone) ?>">
                        </div>
                        <small class="form-text text-muted mt-2 text-center">We will send an STK push prompt to this number.</small>
                    </div>

                    <button type="button" id="pay-btn" class="btn-brand-red w-100 py-3 mt-3 text-uppercase shadow-sm" style="font-size:1.1rem;">Pay Ksh <?= number_format($total_amount, 2) ?> Now</button>
                </form>
                
                <div id="payment-status" class="mt-4"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $("#pay-btn").click(function() {
            var contact = $("#contact").val().trim();
            
            // Strip leading zero if present
            if (contact.startsWith('0')) {
                contact = contact.substring(1);
            }
            // Strip 254 if they somehow pasted it
            if (contact.startsWith('254')) {
                contact = contact.substring(3);
            }
            
            if (contact.length < 9 || isNaN(contact)) {
                Swal.fire('Invalid Number', 'Please enter a valid M-Pesa phone number.', 'warning');
                return;
            }

            // Append 254
            var formattedContact = '254' + contact;
            // Temporarily set it back so serialize captures it correctly
            $("#contact").val(formattedContact);

            var btn = $(this);
            btn.html('<i class="fa fa-spinner fa-spin"></i> Sending Prompt...').prop("disabled", true);

            $.ajax({
                url: 'stk_initiate',
                type: 'POST',
                data: $("#payment-form").serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        btn.html('<i class="fa fa-spinner fa-spin"></i> Waiting for PIN...');
                        
                        Swal.fire({
                            title: 'Check your phone!',
                            text: response.message,
                            icon: 'info',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        
                        checkPaymentStatus(response.invoice_id);
                    } else {
                        btn.text("Pay Ksh <?= number_format($total_amount, 2) ?> Now").prop("disabled", false);
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    btn.text("Pay Ksh <?= number_format($total_amount, 2) ?> Now").prop("disabled", false);
                    Swal.fire('Network Error', 'Could not initiate payment.', 'error');
                }
            });
        });

        function checkPaymentStatus(invoice_id) {
            let attempts = 0;
            let interval = setInterval(function() {
                attempts++;
                if (attempts > 24) { // Timeout after 2 mins
                    clearInterval(interval);
                    Swal.fire('Timeout', 'Payment verification timed out. If you paid, please contact support.', 'warning');
                    $("#pay-btn").text("Retry Payment").prop("disabled", false);
                    return;
                }
                
                $.ajax({
                    url: 'stk_initiate',
                    type: 'POST',
                    data: { check_status: true, invoice_id: invoice_id },
                    dataType: 'json',
                    success: function(response) {
                       if (response.status === "complete") {
                           clearInterval(interval);
                           Swal.fire({
                               title: 'Payment Successful!',
                               text: 'Redirecting you to your receipt...',
                               icon: 'success',
                               showConfirmButton: false,
                               timer: 2000
                           }).then(() => {
                               window.location.href = "status?status=success&invoice=" + invoice_id;
                           });
                       } else if (response.status === "failed") {
                           clearInterval(interval);
                           Swal.fire('Payment Failed', 'The payment request was cancelled or failed.', 'error');
                           $("#pay-btn").text("Retry Payment").prop("disabled", false);
                       }
                    }
                });
            }, 5000);
        }
    });
</script>
<?php include '../includes/footer.php'; ?>
</body>
</html>
