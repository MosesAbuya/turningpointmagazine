<?php
/**
 * API: Initiate Event Booking & M-Pesa STK Push / Corporate Invoice
 * Turning Point Magazine - The Leadership Dinner 2026
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

try {
    $pdo = connect();
    ensure_event_tables_exist($pdo);

    // Support both raw JSON and FormData
    $input = $_POST;
    if (empty($input)) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $input = $json;
        }
    }

    $tierId = intval($input['tier_id'] ?? 0);
    $quantity = max(1, intval($input['quantity'] ?? 1));
    $orgName = trim($input['organization_name'] ?? '');
    $contactName = trim($input['contact_name'] ?? '');
    $contactEmail = trim($input['contact_email'] ?? '');
    $contactPhone = trim($input['contact_phone'] ?? '');
    $dietaryNotes = trim($input['dietary_notes'] ?? '');
    $paymentMethod = trim($input['payment_method'] ?? 'mpesa_stk');
    $dataConsent = !empty($input['data_consent']);

    if (!$dataConsent) {
        echo json_encode(['status' => 'error', 'message' => 'Please accept the Data Protection notice to proceed.']);
        exit;
    }

    if (empty($contactName) || empty($contactEmail) || empty($contactPhone)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required contact details (Name, Email, Phone).']);
        exit;
    }

    if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address.']);
        exit;
    }

    $contactPhone = trim($input['contact_phone'] ?? '');
    $mpesaPhone = trim($input['mpesa_payment_phone'] ?? '');
    if (empty($mpesaPhone)) {
        $mpesaPhone = $contactPhone;
    }

    $normalizedContactPhone = normalize_kenya_phone($contactPhone);
    $normalizedMpesaPhone = normalize_kenya_phone($mpesaPhone);

    if (!preg_match('/^254(7|1)[0-9]{8}$/', $normalizedContactPhone)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid contact phone number (e.g., 0712345678 or 254712345678).']);
        exit;
    }

    if ($paymentMethod === 'mpesa_stk' && !preg_match('/^254(7|1)[0-9]{8}$/', $normalizedMpesaPhone)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid Safaricom/Airtel M-Pesa number to receive the PIN prompt (e.g., 0712345678 or 254712345678).']);
        exit;
    }

    // Fetch Tier
    $stmtTier = $pdo->prepare("SELECT * FROM tp_ticket_tiers WHERE id = ? AND is_active = 1 LIMIT 1");
    $stmtTier->execute([$tierId]);
    $tier = $stmtTier->fetch(PDO::FETCH_ASSOC);

    if (!$tier) {
        // Fallback to default tier
        $stmtTier = $pdo->query("SELECT * FROM tp_ticket_tiers WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 1");
        $tier = $stmtTier->fetch(PDO::FETCH_ASSOC);
        if (!$tier) {
            echo json_encode(['status' => 'error', 'message' => 'Ticket tier not found.']);
            exit;
        }
    }

    $eventId = intval($tier['event_id']);
    $bookingType = $tier['tier_code'];
    $seatsPerUnit = intval($tier['seats_per_unit']);
    $totalSeats = $quantity * $seatsPerUnit;
    $unitPrice = floatval($tier['price_kes']);
    $totalAmount = $quantity * $unitPrice;

    // Corporate table requires organization name
    if ($bookingType === 'corporate_table' && empty($orgName)) {
        echo json_encode(['status' => 'error', 'message' => 'Organization Name is required for Corporate VIP Tables.']);
        exit;
    }

    // Generate unique booking reference and secure verification token
    $bookingRef = generate_booking_reference($pdo);
    $qrToken = generate_qr_token();

    // Insert booking record
    $initialStatus = ($paymentMethod === 'corporate_invoice') ? 'invoice_requested' : 'pending';

    $stmtInsert = $pdo->prepare("
        INSERT INTO tp_event_bookings (
            booking_reference, event_id, tier_id, booking_type, quantity, total_seats,
            unit_price_kes, total_amount_kes, organization_name, contact_name, contact_email,
            contact_phone, dietary_notes, payment_method, payment_status, qr_token, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, NOW()
        )
    ");

    $stmtInsert->execute([
        $bookingRef,
        $eventId,
        $tier['id'],
        $bookingType,
        $quantity,
        $totalSeats,
        $unitPrice,
        $totalAmount,
        $orgName,
        $contactName,
        $contactEmail,
        $normalizedContactPhone,
        $dietaryNotes,
        $paymentMethod,
        $initialStatus,
        $qrToken
    ]);

    $bookingId = $pdo->lastInsertId();

    // Insert guests if provided (e.g. from the 5 guest accordion)
    if (!empty($input['guests']) && is_array($input['guests'])) {
        $seatNum = 1;
        $stmtGuest = $pdo->prepare("
            INSERT INTO tp_event_guests (booking_id, seat_number, guest_name, guest_email, dietary_requirements)
            VALUES (?, ?, ?, ?, ?)
        ");
        foreach ($input['guests'] as $g) {
            if ($seatNum > $totalSeats) break;
            $gName = trim($g['name'] ?? '');
            $gEmail = trim($g['email'] ?? '');
            $gDiet = trim($g['dietary'] ?? '');
            if (!empty($gName) || !empty($gDiet)) {
                $stmtGuest->execute([$bookingId, $seatNum, $gName, $gEmail, $gDiet]);
            }
            $seatNum++;
        }
    }

    // Branch 1: Corporate Proforma Invoice
    if ($paymentMethod === 'corporate_invoice') {
        // Send email immediately
        send_event_invoice_email($pdo, $bookingId);

        echo json_encode([
            'status' => 'success',
            'payment_method' => 'corporate_invoice',
            'booking_reference' => $bookingRef,
            'qr_token' => $qrToken,
            'redirect_url' => "event-invoice.php?ref=" . urlencode($bookingRef) . "&token=" . urlencode($qrToken),
            'message' => 'Corporate proforma invoice generated successfully. An official copy has also been sent to your email.'
        ]);
        exit;
    }

    // Branch 2: Instant M-Pesa STK Push
    // Reuse Daraja credentials from /shop
    $consumerKey = 'hrhSQiSbnPxy8mdlpXyypYNDoPuY3vEAUFN5r0EKzHKFdkJ5';
    $consumerSecret = 'T7MsTagmE08rtfz2pqpaWHSGAb0gOGYPQv7hbdb0pG282NME3DEN7aJUacDcPr20';
    $BusinessShortCode = '6566500';
    $TillNumber = '575555';
    $Passkey = 'dc4fd5a9bc338446ab75137301bde9a7fb96771a550ac099830c2fe744a058cc';
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);
    $CallBackURL = 'https://www.turningpointmagazine.africa/callback_url.php';

    $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json; charset=utf8']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    $token_response = curl_exec($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);

    $token_result = json_decode($token_response, true);
    $accessToken = $token_result['access_token'] ?? null;

    if (!$accessToken) {
        // Log error and return user-friendly message
        error_log("Daraja Token Error: " . $curl_error . " Response: " . $token_response);
        echo json_encode([
            'status' => 'error',
            'message' => 'Could not connect to Safaricom Daraja API. You may select "Corporate Invoice / Pay via Paybill" to reserve your seat and pay directly.',
            'booking_reference' => $bookingRef
        ]);
        exit;
    }

    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $accessToken];
    $stk_data = [
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerBuyGoodsOnline',
        'Amount' => $totalAmount,
        'PartyA' => $normalizedMpesaPhone,
        'PartyB' => $TillNumber,
        'PhoneNumber' => $normalizedMpesaPhone,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $bookingRef,
        'TransactionDesc' => 'Leadership Dinner 2026'
    ];

    $curlStk = curl_init('https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
    curl_setopt($curlStk, CURLOPT_HTTPHEADER, $stkheader);
    curl_setopt($curlStk, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlStk, CURLOPT_POST, true);
    curl_setopt($curlStk, CURLOPT_POSTFIELDS, json_encode($stk_data));
    curl_setopt($curlStk, CURLOPT_TIMEOUT, 30);
    $stk_response = json_decode(curl_exec($curlStk), true);
    curl_close($curlStk);

    if (isset($stk_response['CheckoutRequestID'])) {
        $checkoutRequestId = $stk_response['CheckoutRequestID'];
        $stmtUp = $pdo->prepare("UPDATE tp_event_bookings SET mpesa_checkout_request_id = ? WHERE id = ?");
        $stmtUp->execute([$checkoutRequestId, $bookingId]);

        echo json_encode([
            'status' => 'success',
            'payment_method' => 'mpesa_stk',
            'booking_reference' => $bookingRef,
            'qr_token' => $qrToken,
            'checkout_request_id' => $checkoutRequestId,
            'amount_kes' => $totalAmount,
            'phone' => $normalizedMpesaPhone,
            'message' => 'M-Pesa STK Prompt sent to ' . $normalizedMpesaPhone . '. Please check your handset and enter your PIN.'
        ]);
        exit;
    } else {
        $errMsg = $stk_response['errorMessage'] ?? ($stk_response['ResponseDescription'] ?? 'Failed to initiate M-Pesa push.');
        echo json_encode([
            'status' => 'error',
            'message' => 'M-Pesa Gateway: ' . $errMsg,
            'booking_reference' => $bookingRef
        ]);
        exit;
    }

} catch (Exception $e) {
    error_log("Initiate Booking Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
