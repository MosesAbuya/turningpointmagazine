<?php
/**
 * Paystack Webhook Handler for Event Ticketing
 * Turning Point Magazine - The Leadership Dinner 2026
 * 
 * Webhook URL to configure in Paystack Dashboard:
 * https://www.turningpointmagazine.africa/api/paystack_event_webhook.php
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';
require_once __DIR__ . '/../includes/paystack_config.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

$input = file_get_contents('php://input');
$paystackSignature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';

// Verify webhook signature with secret key
if (!$paystackSignature || $paystackSignature !== hash_hmac('sha512', $input, PAYSTACK_SECRET_KEY)) {
    http_response_code(401);
    error_log("Paystack Webhook: Invalid signature received.");
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

// Acknowledge receipt immediately to Paystack with 200 OK
http_response_code(200);

try {
    $event = json_decode($input, true);
    if (!is_array($event)) {
        echo json_encode(['status' => 'ignored', 'message' => 'Invalid payload']);
        exit;
    }

    $eventName = $event['event'] ?? '';
    
    if ($eventName === 'charge.success') {
        $data = $event['data'] ?? [];
        $reference = trim($data['reference'] ?? '');
        $status = strtolower($data['status'] ?? '');
        $channel = strval($data['channel'] ?? 'paystack');
        $transId = strval($data['id'] ?? '');
        $paidAt = !empty($data['paid_at']) ? date('Y-m-d H:i:s', strtotime($data['paid_at'])) : date('Y-m-d H:i:s');

        if ($status === 'success' && !empty($reference)) {
            $pdo = connect();
            ensure_event_tables_exist($pdo);

            // Locate booking by reference
            $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE booking_reference = ? OR paystack_reference = ? LIMIT 1");
            $stmt->execute([$reference, $reference]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
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
                        $transId,
                        $channel,
                        $paidAt,
                        $booking['id']
                    ]);

                    // Dispatch confirmation email with QR code
                    send_event_ticket_email($pdo, $booking['id']);
                    error_log("Paystack Webhook: Booking {$booking['booking_reference']} successfully marked as paid.");
                }
            } else {
                error_log("Paystack Webhook: Booking not found for reference: " . $reference);
            }
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Webhook processed']);
    exit;

} catch (Exception $e) {
    error_log("Paystack Webhook Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
