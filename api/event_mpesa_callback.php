<?php
/**
 * Dedicated Event M-Pesa Callback Endpoint
 * Turning Point Magazine - The Leadership Dinner 2026
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';

$logFile = __DIR__ . "/event_mpesa_callback_log.txt";
$raw = file_get_contents('php://input');
file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Raw: " . $raw . PHP_EOL, FILE_APPEND);

$data = json_decode($raw, true);

if (isset($data['Body']['stkCallback'])) {
    $callback = $data['Body']['stkCallback'];
    $resultCode = intval($callback['ResultCode'] ?? -1);
    $checkoutId = $callback['CheckoutRequestID'] ?? '';

    try {
        $pdo = connect();
        ensure_event_tables_exist($pdo);

        if ($resultCode === 0) {
            $metadata = $callback['CallbackMetadata']['Item'] ?? [];
            $amount = null;
            $receipt = null;

            foreach ($metadata as $item) {
                if (($item['Name'] ?? '') === 'Amount') {
                    $amount = $item['Value'];
                }
                if (($item['Name'] ?? '') === 'MpesaReceiptNumber') {
                    $receipt = $item['Value'];
                }
            }

            $stmt = $pdo->prepare("SELECT * FROM tp_event_bookings WHERE mpesa_checkout_request_id = ? LIMIT 1");
            $stmt->execute([$checkoutId]);
            $b = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($b) {
                $stmtUp = $pdo->prepare("
                    UPDATE tp_event_bookings 
                    SET payment_status = 'paid', mpesa_receipt_number = ?, paid_at = NOW(), updated_at = NOW()
                    WHERE id = ?
                ");
                $stmtUp->execute([$receipt, $b['id']]);

                send_event_booking_confirmation_email($pdo, $b['id']);
                file_put_contents($logFile, "Paid confirmed for booking " . $b['booking_reference'] . " with receipt " . $receipt . PHP_EOL, FILE_APPEND);
            }
        } else {
            $stmtFail = $pdo->prepare("UPDATE tp_event_bookings SET payment_status = 'failed', updated_at = NOW() WHERE mpesa_checkout_request_id = ?");
            $stmtFail->execute([$checkoutId]);
            file_put_contents($logFile, "Failed for checkout " . $checkoutId . PHP_EOL, FILE_APPEND);
        }
    } catch (Exception $e) {
        file_put_contents($logFile, "Exception: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}

echo json_encode(["ResultCode" => 0, "ResultDesc" => "Event Callback Processed Successfully"]);
