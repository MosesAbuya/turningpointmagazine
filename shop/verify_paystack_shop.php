<?php
/**
 * API: Verify Paystack Transaction & Confirm Shop Order
 * Turning Point Magazine Africa - Shop
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/paystack_config.php';
require_once __DIR__ . '/../includes/shop_email_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

try {
    $pdo = connect();

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
    $orderId = isset($input['order_id']) ? intval($input['order_id']) : 0;
    $invoiceId = trim($input['invoice_id'] ?? '');

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
        error_log("Paystack Shop Verify cURL Error: " . $curlErr);
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
        $gatewayMsg = $data['gateway_response'] ?? 'Payment was not marked as successful by Paystack.';
        echo json_encode(['status' => 'error', 'message' => 'Paystack: ' . $gatewayMsg]);
        exit;
    }

    // 2. Locate Order Record
    $order = null;
    if ($orderId > 0) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$order && !empty($invoiceId)) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE invoice_id = ? LIMIT 1");
        $stmt->execute([$invoiceId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$order) {
        // Try locating by reference matching invoice_id or paystack_reference
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE invoice_id = ? OR paystack_reference = ? LIMIT 1");
        $stmt->execute([$reference, $reference]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$order) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Associated order record not found. Reference: ' . htmlspecialchars($reference)
        ]);
        exit;
    }

    // 3. Update Order Status
    $wasAlreadyPaid = ($order['status'] === 'paid');
    $paystackTransId = strval($data['id'] ?? '');
    $paystackChannel = strval($data['channel'] ?? 'paystack');
    $paidAt = !empty($data['paid_at']) ? date('Y-m-d H:i:s', strtotime($data['paid_at'])) : date('Y-m-d H:i:s');
    $amountPaid = floatval($data['amount'] ?? 0) / 100;

    if (!$wasAlreadyPaid) {
        $stmtUp = $pdo->prepare("
            UPDATE orders 
            SET status = 'paid',
                payment_method = 'PAYSTACK',
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
            $order['id']
        ]);

        // Insert or update payments record
        try {
            $stmtPay = $pdo->prepare("
                INSERT INTO payments 
                (invoice_id, first_name, last_name, organization, email, contact, category, amount, currency, payment_method, status, transaction_id, created_at, updated_at)
                VALUES (?, ?, ?, '', ?, ?, 'shop', ?, 'KES', 'paystack', 'complete', ?, NOW(), NOW())
            ");
            $stmtPay->execute([
                $order['invoice_id'],
                $order['first_name'],
                $order['last_name'],
                $order['email'],
                $order['contact'],
                $amountPaid > 0 ? $amountPaid : $order['total_amount'],
                $reference
            ]);
        } catch (Exception $pe) {
            error_log("Shop payments insert error: " . $pe->getMessage());
        }

        // Send order confirmation emails
        send_shop_order_confirmation_emails($pdo, $order['id']);
    }

    $redirectUrl = "status.php?status=success&invoice=" . urlencode($order['invoice_id']);

    echo json_encode([
        'status' => 'success',
        'message' => 'Payment verified successfully! Redirecting to receipt...',
        'invoice_id' => $order['invoice_id'],
        'order_id' => $order['id'],
        'redirect_url' => $redirectUrl
    ]);
    exit;

} catch (Exception $e) {
    error_log("Verify Paystack Shop Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
