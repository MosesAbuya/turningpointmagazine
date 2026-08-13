<?php
require_once 'connection2.php';

header('Content-Type: application/json');

// IMPORTANT: Replace with your actual secret challenge string
$expectedChallenge = "aBcDeFg1hIjK2LmNoP3QrStUvWxYz0987"; // Replace with your actual secret challenge string

error_log("Received POST data: " . print_r($_POST, true));

try {
    // 1. Validate Challenge
    if (!isset($_POST['challenge']) || $_POST['challenge'] !== $expectedChallenge) {
        error_log("Invalid challenge received. Request rejected.");
        http_response_code(403); // Forbidden
        echo json_encode(['success' => false, 'message' => 'Invalid challenge.']);
        exit; // Stop processing
    }

    // 2. Validate Data
    if (!isset($_POST['api_ref']) || empty($_POST['api_ref'])) {
        throw new Exception("Invoice ID is missing or empty.");
    }
    if (!isset($_POST['status']) || empty($_POST['status'])) {
        throw new Exception("Status is missing or empty.");
    }

    // 3. Sanitize Data
    $invoiceId = htmlspecialchars($_POST['api_ref']);
    $status = htmlspecialchars($_POST['status']);

    // Handle optional parameters, providing default null values
    $paymentMethod = isset($_POST['payment_method']) ? htmlspecialchars($_POST['payment_method']) : null;
    $transactionId = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : null;


    // 4. Update Database
    $pdo = connect();

    $sql = "UPDATE payments SET status = :status, payment_method = :payment_method, transaction_id = :transaction_id WHERE invoice_id = :invoice_id";
    $stmt = $pdo->prepare($sql);

    //Bind the parameters
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':invoice_id', $invoiceId, PDO::PARAM_STR);

    //These can be null if no value present, or a string if present
    if ($paymentMethod !== null){
        $stmt->bindParam(':payment_method', $paymentMethod, PDO::PARAM_STR);
    }
    else{
        $stmt->bindValue(':payment_method', null, PDO::PARAM_NULL);
    }
    if ($transactionId !== null){
        $stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_STR);
    }
    else{
        $stmt->bindValue(':transaction_id', null, PDO::PARAM_NULL);
    }
    $stmt->execute();

    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] !== '00000') {
        error_log("SQL Error: " . print_r($errorInfo, true));
        throw new Exception("Database error: " . $errorInfo[2]);
    }
    // 5. Return JSON Response (Success)
    echo json_encode([
        'success' => true,
        'message' => 'Payment status updated successfully.'
    ]);
} catch (Exception $e) {
    // 6. Return JSON Response (Error)
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($pdo)) {
        closeConnection($pdo);
    }
}
?>
