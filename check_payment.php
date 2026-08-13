<?php
include 'connection2.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_id = $_POST['invoice_id'] ?? '';

    $pdo = connect();
    $stmt = $pdo->prepare("SELECT status FROM payments WHERE invoice_id = ?");
    $stmt->execute([$invoice_id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($payment) {
        echo json_encode(["status" => $payment['status']]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }
}
?>
