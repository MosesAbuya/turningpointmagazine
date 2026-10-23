<?php
session_start();
include '../connection2.php';
require_once '../includes/paystack_config.php';

$pdo = connect();

if (!isset($_GET['order_id'])) {
    die("Invalid access. No order ID provided.");
}

$order_id = intval($_GET['order_id']);

// Fetch the order based on order_id
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("No orders found.");
}

// If already paid, redirect straight to status
if ($order['status'] === 'paid') {
    header("Location: status.php?status=success&invoice=" . urlencode($order['invoice_id']));
    exit;
}

$invoice_id = $order['invoice_id'];
$first_name = $order['first_name'];
$last_name = $order['last_name'];
$email = $order['email'];
$total_amount = floatval($order['total_amount']);
$contact_phone = $order['contact'];

// Fetch active payment gateway from shop_settings
$active_gateway = 'paystack'; // Default
try {
    $stmt_set = $pdo->query("SELECT payment_gateway FROM shop_settings LIMIT 1");
    if ($row_set = $stmt_set->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row_set['payment_gateway'])) {
            $active_gateway = strtolower($row_set['payment_gateway']);
        }
    }
} catch (Exception $e) {}

// Paystack amount in subunits (cents/kobo)
$amount_subunits = intval(round($total_amount * 100));
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
    .invoice-box { background: #f4f6f9; border-radius: 15px; padding: 22px; margin-bottom: 25px; border: 1px solid #e2e8f0; }
    .invoice-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #e0e0e0; padding-bottom: 6px; font-size: 0.95rem; }
    .invoice-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    
    /* Paystack Channel Badges */
    .paystack-channels { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin: 15px 0 20px 0; }
    .channel-badge { display: inline-flex; align-items: center; background: #fff; border: 1px solid #d1d5db; border-radius: 20px; padding: 5px 12px; font-size: 0.8rem; font-weight: 600; color: #374151; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .channel-badge i { margin-right: 6px; }
    .paystack-brand-badge { background: #0ba4db; color: #fff; font-weight: 700; padding: 4px 10px; border-radius: 4px; font-size: 0.9rem; }
</style>

<!-- Paystack Pop SDKs -->
<script src="https://js.paystack.co/v2/inline.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
</head>
<body>

<?php require_once ('inc/header.php'); ?>

<div class="payment-header text-center">
    <h1 class="payment-title">Secure Payment</h1>
    <?php if ($active_gateway === 'paystack'): ?>
        <p class="text-light opacity-75">Instant settlement via Paystack (M-Pesa, Cards, Apple Pay, Bank Transfer)</p>
    <?php else: ?>
        <p class="text-light opacity-75">Pay securely using Safaricom M-Pesa</p>
    <?php endif; ?>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="modern-container text-center">
                
                <div class="invoice-box text-left">
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Invoice ID:</span>
                        <span class="font-weight-bold"><?= htmlspecialchars($invoice_id) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Customer Name:</span>
                        <span><?= htmlspecialchars($first_name . " " . $last_name) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Email:</span>
                        <span><?= htmlspecialchars($email) ?></span>
                    </div>
                    <div class="invoice-row">
                        <span class="font-weight-bold text-muted">Phone:</span>
                        <span><?= htmlspecialchars($contact_phone) ?></span>
                    </div>
                    <div class="invoice-row mt-2 pt-2" style="border-top: 2px solid #ccc;">
                        <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">Total Due:</span>
                        <span class="font-weight-bold text-brand-red" style="font-size: 1.3rem;">KES <?= number_format($total_amount, 2) ?></span>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- PAYSTACK PAYMENT INTERFACE (ACTIVE GATEWAY) -->
                <!-- ========================================== -->
                <?php if ($active_gateway === 'paystack'): ?>
                    <div id="paystack-payment-section">
                        <div class="text-center mb-3">
                            <span class="paystack-brand-badge"><i class="fas fa-lock"></i> Paystack</span>
                            <h4 class="font-weight-bold mt-2">Choose Your Payment Method</h4>
                            <p class="text-muted small">You will be presented with secure options including M-Pesa, Card, Apple Pay, and Bank Transfer.</p>
                        </div>

                        <div class="paystack-channels">
                            <span class="channel-badge"><i class="fas fa-mobile-alt text-success"></i> M-PESA</span>
                            <span class="channel-badge"><i class="fab fa-cc-visa text-primary"></i> Visa</span>
                            <span class="channel-badge"><i class="fab fa-cc-mastercard text-danger"></i> Mastercard</span>
                            <span class="channel-badge"><i class="fab fa-apple text-dark"></i> Apple Pay</span>
                            <span class="channel-badge"><i class="fas fa-university text-secondary"></i> Bank Transfer</span>
                        </div>

                        <button type="button" id="paystack-pay-btn" class="btn-brand-red w-100 py-3 mt-2 text-uppercase shadow font-weight-bold" style="font-size: 1.15rem;">
                            <i class="fas fa-shield-alt mr-2"></i> Pay KES <?= number_format($total_amount, 2) ?> with Paystack
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            <i class="fas fa-check-circle text-success"></i> Official Turning Point Store Merchant. Instant Order Confirmation.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ========================================== -->
                <!-- DARAJA M-PESA INTERFACE (DEACTIVATED/HIDDEN UNLESS TOGGLED) -->
                <!-- ========================================== -->
                <?php if ($active_gateway === 'daraja'): ?>
                    <div id="daraja-payment-section">
                        <h2 class="font-weight-bold text-success mb-4"><i class="fas fa-mobile-alt"></i> M-PESA (Daraja)</h2>

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

                            <button type="button" id="pay-btn" class="btn-brand-red w-100 py-3 mt-3 text-uppercase shadow-sm font-weight-bold" style="font-size:1.1rem;">
                                Pay Ksh <?= number_format($total_amount, 2) ?> via M-Pesa Now
                            </button>
                        </form>
                        
                        <div id="payment-status" class="mt-4"></div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Paystack Handling Script -->
<?php if ($active_gateway === 'paystack'): ?>
<script>
$(document).ready(function() {
    const paystackKey = <?= json_encode(PAYSTACK_PUBLIC_KEY) ?>;
    const userEmail = <?= json_encode($email) ?>;
    const amountSubunits = <?= json_encode($amount_subunits) ?>;
    const currency = <?= json_encode(PAYSTACK_CURRENCY) ?>;
    const invoiceId = <?= json_encode($invoice_id) ?>;
    const orderId = <?= json_encode($order_id) ?>;
    const customerName = <?= json_encode(trim($first_name . ' ' . $last_name)) ?>;
    const customerPhone = <?= json_encode($contact_phone) ?>;

    $("#paystack-pay-btn").click(function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin mr-2"></i> Opening Paystack...').prop('disabled', true);

        const customFields = [
            { display_name: "Invoice ID", variable_name: "invoice_id", value: invoiceId },
            { display_name: "Customer Name", variable_name: "customer_name", value: customerName },
            { display_name: "Phone Number", variable_name: "phone_number", value: customerPhone },
            { display_name: "Order Type", variable_name: "order_type", value: "Shop Purchase" }
        ];

        let launchedV2 = false;

        const onPaymentSuccess = function(ref) {
            Swal.fire({
                title: 'Confirming Payment...',
                text: 'Paystack authorized the payment. Verifying with order database...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Call verify API
            $.ajax({
                url: 'verify_paystack_shop.php',
                type: 'POST',
                data: {
                    reference: ref,
                    order_id: orderId,
                    invoice_id: invoiceId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            title: 'Payment Successful! 🎉',
                            text: 'Your order has been confirmed. Redirecting to receipt...',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = res.redirect_url || ("status.php?status=success&invoice=" + encodeURIComponent(invoiceId));
                        });
                    } else {
                        btn.html(originalText).prop('disabled', false);
                        Swal.fire('Verification Error', res.message || 'Payment received but confirmation failed. Please contact support.', 'error');
                    }
                },
                error: function() {
                    btn.html(originalText).prop('disabled', false);
                    Swal.fire('Notice', 'Payment processed. Finalizing your order confirmation...', 'info').then(() => {
                        window.location.href = "status.php?status=success&invoice=" + encodeURIComponent(invoiceId);
                    });
                }
            });
        };

        const onPaymentCancel = function() {
            btn.html(originalText).prop('disabled', false);
        };

        try {
            if (typeof PaystackPop !== 'undefined') {
                const paystack = new PaystackPop();
                if (typeof paystack.newTransaction === 'function') {
                    paystack.newTransaction({
                        key: paystackKey,
                        email: userEmail,
                        amount: amountSubunits,
                        currency: currency || 'KES',
                        ref: invoiceId,
                        metadata: { custom_fields: customFields },
                        onSuccess: function(transaction) {
                            onPaymentSuccess(transaction.reference || invoiceId);
                        },
                        onCancel: onPaymentCancel
                    });
                    launchedV2 = true;
                }
            }
        } catch (e) {
            console.warn("Paystack v2 inline error:", e);
        }

        if (!launchedV2) {
            // Fallback to Paystack v1 inline popup
            try {
                const handler = PaystackPop.setup({
                    key: paystackKey,
                    email: userEmail,
                    amount: amountSubunits,
                    currency: currency || 'KES',
                    ref: invoiceId,
                    metadata: { custom_fields: customFields },
                    callback: function(response) {
                        onPaymentSuccess(response.reference || invoiceId);
                    },
                    onClose: onPaymentCancel
                });
                handler.openIframe();
            } catch (err) {
                console.error("Paystack v1 fallback error:", err);
                btn.html(originalText).prop('disabled', false);
                Swal.fire('Error', 'Could not open Paystack payment window. Please check your connection.', 'error');
            }
        }
    });
});
</script>
<?php endif; ?>

<!-- Daraja M-Pesa Handling Script (Retained & functional when toggled active) -->
<?php if ($active_gateway === 'daraja'): ?>
<script>
    $(document).ready(function() {
        $("#pay-btn").click(function() {
            var contact = $("#contact").val().trim();
            
            if (contact.startsWith('0')) {
                contact = contact.substring(1);
            }
            if (contact.startsWith('254')) {
                contact = contact.substring(3);
            }
            
            if (contact.length < 9 || isNaN(contact)) {
                Swal.fire('Invalid Number', 'Please enter a valid M-Pesa phone number.', 'warning');
                return;
            }

            var formattedContact = '254' + contact;
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
                        btn.text("Pay Ksh <?= number_format($total_amount, 2) ?> via M-Pesa Now").prop("disabled", false);
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    btn.text("Pay Ksh <?= number_format($total_amount, 2) ?> via M-Pesa Now").prop("disabled", false);
                    Swal.fire('Network Error', 'Could not initiate payment.', 'error');
                }
            });
        });

        function checkPaymentStatus(invoice_id) {
            let attempts = 0;
            let interval = setInterval(function() {
                attempts++;
                if (attempts > 24) {
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
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body>
</html>