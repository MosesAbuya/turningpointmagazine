<?php
/**
 * Browser Callback URL Handler for Paystack Event Payments
 * Turning Point Magazine - The Leadership Dinner 2026
 * 
 * Callback URL to configure in Paystack Dashboard:
 * https://www.turningpointmagazine.africa/verify_event_payment.php
 */

require_once __DIR__ . '/connection2.php';
require_once __DIR__ . '/includes/event_db_helper.php';
require_once __DIR__ . '/includes/event_email_helper.php';
require_once __DIR__ . '/includes/paystack_config.php';

$reference = trim($_GET['reference'] ?? ($_GET['trxref'] ?? ''));

if (empty($reference)) {
    header("Location: leadership-dinner-2026.php");
    exit;
}

$pdo = connect();
ensure_event_tables_exist($pdo);

// Verify with Paystack API
$url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
    'Cache-Control: no-cache',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$curlErr = curl_error($ch);
curl_close($ch);

$verified = false;
$booking = null;
$errorMessage = '';

if (!$curlErr && $response) {
    $res = json_decode($response, true);
    if (isset($res['status']) && $res['status'] === true) {
        $data = $res['data'] ?? [];
        if (strtolower($data['status'] ?? '') === 'success') {
            $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE booking_reference = ? OR paystack_reference = ? LIMIT 1");
            $stmt->execute([$reference, $reference]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                $paystackTransId = strval($data['id'] ?? '');
                $paystackChannel = strval($data['channel'] ?? 'paystack');
                $paidAt = !empty($data['paid_at']) ? date('Y-m-d H:i:s', strtotime($data['paid_at'])) : date('Y-m-d H:i:s');

                if ($booking['payment_status'] !== 'paid') {
                    $stmtUp = $pdo->prepare("
                        UPDATE tp_event_bookings 
                        SET payment_status = 'paid',
                            payment_method = 'paystack',
                            paystack_reference = ?,
                            paystack_transaction_id = ?,
                            paystack_channel = ?,
                            paid_at = ?
                        WHERE id = ?
                    ");
                    $stmtUp->execute([
                        $reference,
                        $paystackTransId,
                        $paystackChannel,
                        $paidAt,
                        $booking['id']
                    ]);
                    send_event_ticket_email($pdo, $booking['id']);
                }

                // Redirect directly to the digital ticket pass!
                header("Location: ticket-pass.php?ref=" . urlencode($booking['booking_reference']) . "&token=" . urlencode($booking['qr_token']));
                exit;
            } else {
                $errorMessage = "Booking reference could not be matched with our system records.";
            }
        } else {
            $errorMessage = $data['gateway_response'] ?? 'Payment was not marked as successful by Paystack.';
        }
    } else {
        $errorMessage = $res['message'] ?? 'Transaction could not be verified.';
    }
} else {
    $errorMessage = 'Network connection issue with Paystack verification.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status | The Leadership Dinner 2026 - Turning Point Magazine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="global.css">
    <style>
        body {
            background-color: #0d0d0d;
            color: #faf7f2;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .status-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .status-card {
            background: #141414;
            border: 1px solid rgba(212, 175, 55, 0.4);
            border-radius: 16px;
            max-width: 540px;
            width: 100%;
            padding: 45px 35px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(200, 16, 46, 0.15);
            border: 2px solid #c8102e;
            color: #c8102e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }
        .status-title {
            font-family: 'florania', serif;
            font-size: 26px;
            color: #ffffff;
            margin-bottom: 12px;
        }
        .status-desc {
            font-size: 14.5px;
            color: #a3a3a3;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #d4af37 0%, #aa8421 100%);
            color: #0d0d0d;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-retry:hover {
            transform: translateY(-2px);
            color: #0d0d0d;
        }
    </style>
</head>
<body>
    <?php include 'includes/new-navbar.php'; ?>

    <div class="status-container">
        <div class="status-card">
            <div class="icon-circle">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="status-title">Payment Verification Notice</h2>
            <p class="status-desc">
                <?= htmlspecialchars($errorMessage ?: 'We were unable to complete verification for this transaction automatically.') ?>
            </p>
            <div style="font-family: monospace; font-size: 13px; color: #d4af37; margin-bottom: 25px;">
                Reference: <?= htmlspecialchars($reference) ?>
            </div>
            <div>
                <a href="event-checkout.php" class="btn-retry">
                    <i class="fas fa-redo"></i> Return to Checkout
                </a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
