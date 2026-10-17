<?php
/**
 * API: Get Event Guest Details for Admin Modal
 */

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../connection2.php';

$bookingId = intval($_GET['booking_id'] ?? 0);
if ($bookingId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid booking ID']);
    exit;
}

try {
    $pdo = connect();
    $stmt = $pdo->prepare("SELECT * FROM tp_event_guests WHERE booking_id = ? ORDER BY seat_number ASC");
    $stmt->execute([$bookingId]);
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'guests' => $guests]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
