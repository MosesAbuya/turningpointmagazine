<?php
/**
 * API: Verify Paystack Transaction & Confirm Event Ticket
 * Turning Point Magazine - The Leadership Dinner 2026
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';
require_once __DIR__ . '/../includes/paystack_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

try {
    $pdo = connect();
    ensure_event_tables_exist($pdo);

    // Support JSON body, POST, and GET parameters
    $input = $_POST;
    if (empty($input)) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $input = $json;
        } else {
            $input = $_GET;
        }
    }

    $reference = trim($input['reference'] ?? ($input['trxref'] ?? ''));
    $bookingRef = trim($input['booking_reference'] ?? ($input['ref'] ?? ''));
    $token = trim($input['token'] ?? '');

    if (empty($reference)) {
        echo json_encode(['status' => 'error', 'message' => 'Transaction reference is missing.']);
        exit;
    }

    // 1. Verify with Paystack API
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

    if ($curlErr) {
        error_log("Paystack Verify cURL Error: " . $curlErr);
        echo json_encode(['status' => 'error', 'message' => 'Network error communicating with Paystack: ' . $curlErr]);
        exit;
    }

    $res = json_decode($response, true);
    if (!isset($res['status']) || $res['status'] !== true) {
        $errMsg = $res['message'] ?? 'Transaction verification failed with Paystack.';
        echo json_encode(['status' => 'error', 'message' => $errMsg]);
        exit;
    }

    $data = $res['data'] ?? [];
    $paystackStatus = strtolower($data['status'] ?? '');

    if ($paystackStatus !== 'success') {
        $gatewayMsg = $data['gateway_response'] ?? 'Payment was not completed successfully.';
        echo json_encode(['status' => 'error', 'message' => 'Paystack: ' . $gatewayMsg]);
        exit;
    }

    // 2. Find Booking Record
    $booking = null;
    if (!empty($bookingRef)) {
        $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE booking_reference = ? LIMIT 1");
        $stmt->execute([$bookingRef]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$booking) {
        // Try searching by paystack_reference or booking_reference matching $reference
        $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE booking_reference = ? OR paystack_reference = ? LIMIT 1");
        $stmt->execute([$reference, $reference]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$booking) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Associated booking record not found in system. Reference: ' . htmlspecialchars($reference)
        ]);
        exit;
    }

    // 3. Update Booking Record to Paid
    $wasAlreadyPaid = ($booking['payment_status'] === 'paid');
    $paystackTransId = strval($data['id'] ?? '');
    $paystackChannel = strval($data['channel'] ?? 'paystack');
    $paidAt = !empty($data['paid_at']) ? date('Y-m-d H:i:s', strtotime($data['paid_at'])) : date('Y-m-d H:i:s');

    if (!$wasAlreadyPaid) {
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

        // Dispatch instant VIP ticket pass email
        send_event_ticket_email($pdo, $booking['id']);
    }

    $redirectUrl = "ticket-pass.php?ref=" . urlencode($booking['booking_reference']) . "&token=" . urlencode($booking['qr_token']);

    echo json_encode([
        'status' => 'success',
        'message' => 'Payment verified successfully! Redirecting to your official event pass...',
        'booking_reference' => $booking['booking_reference'],
        'qr_token' => $booking['qr_token'],
        'redirect_url' => $redirectUrl
    ]);
    exit;

} catch (Exception $e) {
    error_log("Verify Paystack Payment Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
