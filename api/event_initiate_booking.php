<?php
/**
 * API: Initiate Event Booking for Paystack Payment Gateway
 * Turning Point Magazine - The Leadership Dinner 2026
 */

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Africa/Nairobi');

require_once __DIR__ . '/../connection2.php';
require_once __DIR__ . '/../includes/event_db_helper.php';
require_once __DIR__ . '/../includes/event_email_helper.php';
require_once __DIR__ . '/../includes/paystack_config.php';

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

    // Fetch Tier
    $stmtTier = $pdo->prepare("SELECT * FROM tp_ticket_tiers WHERE id = ? AND is_active = 1 LIMIT 1");
    $stmtTier->execute([$tierId]);
    $tier = $stmtTier->fetch(PDO::FETCH_ASSOC);

    if (!$tier) {
        // Fallback to default tier
        $stmtTier = $pdo->query("SELECT * FROM tp_ticket_tiers WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 1");
        $tier = $stmtTier->fetch(PDO::FETCH_ASSOC);
        if (!$tier) {
            echo json_encode(['status' => 'error', 'message' => 'Selected ticket package is not available.']);
            exit;
        }
    }

    $eventId = intval($tier['event_id']);
    $bookingType = $tier['tier_code'];
    $seatsPerUnit = intval($tier['seats_per_unit']);
    $totalSeats = $quantity * $seatsPerUnit;
    $unitPrice = floatval($tier['price_kes']);
    $totalAmount = $quantity * $unitPrice;
    $totalAmountSubunits = intval(round($totalAmount * 100)); // Paystack uses subunits (cents/kobo)

    // Corporate table requires organization name
    if ($bookingType === 'corporate_table' && empty($orgName)) {
        echo json_encode(['status' => 'error', 'message' => 'Organization Name is required for Corporate VIP Tables.']);
        exit;
    }

    // Generate unique booking reference and secure verification token
    $bookingRef = generate_booking_reference($pdo);
    $qrToken = generate_qr_token();

    // Insert booking record into tp_event_bookings
    $stmtInsert = $pdo->prepare("
        INSERT INTO tp_event_bookings (
            booking_reference, event_id, tier_id, booking_type, quantity, total_seats,
            unit_price_kes, total_amount_kes, organization_name, contact_name, contact_email,
            contact_phone, dietary_notes, payment_method, payment_status, qr_token, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?,
            ?, ?, 'paystack', 'pending', ?, NOW()
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
        $contactPhone,
        $dietaryNotes,
        $qrToken
    ]);

    $bookingId = $pdo->lastInsertId();

    // Insert delegated guests if provided
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

    // Return Paystack payload for inline popup initialization
    echo json_encode([
        'status' => 'success',
        'payment_method' => 'paystack',
        'booking_reference' => $bookingRef,
        'qr_token' => $qrToken,
        'public_key' => PAYSTACK_PUBLIC_KEY,
        'amount_kes' => $totalAmount,
        'amount_subunits' => $totalAmountSubunits,
        'currency' => PAYSTACK_CURRENCY,
        'email' => $contactEmail,
        'contact_name' => $contactName,
        'contact_phone' => $contactPhone,
        'tier_name' => $tier['tier_name'],
        'organization_name' => $orgName,
        'message' => 'Booking initialized. Launching Paystack payment popup...'
    ]);
    exit;

} catch (Exception $e) {
    error_log("Initiate Booking Exception: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
