<?php
/**
 * API: Check Event Payment Status
 * Turning Point Magazine - The Leadership Dinner 2026
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';

try {
    $pdo = connect();
    ensure_event_tables_exist($pdo);

    $ref = trim($_GET['ref'] ?? ($_POST['ref'] ?? ''));
    $token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));
    $checkoutId = trim($_GET['checkout_id'] ?? ($_POST['checkout_id'] ?? ''));

    if (empty($ref) && empty($checkoutId)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing booking reference or checkout ID.']);
        exit;
    }

    if (!empty($ref)) {
        $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE booking_reference = ? LIMIT 1");
        $stmt->execute([$ref]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE mpesa_checkout_request_id = ? LIMIT 1");
        $stmt->execute([$checkoutId]);
    }

    $b = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$b) {
        echo json_encode(['status' => 'not_found', 'message' => 'Booking record not found.']);
        exit;
    }

    $status = $b['payment_status'];

    if ($status === 'paid') {
        echo json_encode([
            'status' => 'paid',
            'booking_reference' => $b['booking_reference'],
            'qr_token' => $b['qr_token'],
            'mpesa_receipt' => $b['mpesa_receipt_number'],
            'paid_at' => $b['paid_at'],
            'redirect_url' => "ticket-pass.php?ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']),
            'message' => 'Payment verified successfully! Redirecting to your official reservation pass...'
        ]);
        exit;
    } elseif ($status === 'failed' || $status === 'cancelled') {
        echo json_encode([
            'status' => 'failed',
            'booking_reference' => $b['booking_reference'],
            'message' => 'Payment was unsuccessful or cancelled. Please try again.'
        ]);
        exit;
    } elseif ($status === 'invoice_requested') {
        echo json_encode([
            'status' => 'invoice_requested',
            'booking_reference' => $b['booking_reference'],
            'redirect_url' => "event-invoice.php?ref=" . urlencode($b['booking_reference']) . "&token=" . urlencode($b['qr_token']),
            'message' => 'Corporate proforma invoice active.'
        ]);
        exit;
    } else {
        echo json_encode([
            'status' => 'pending',
            'booking_reference' => $b['booking_reference'],
            'message' => 'Payment is still processing. Please check your phone for the M-Pesa PIN prompt.'
        ]);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
