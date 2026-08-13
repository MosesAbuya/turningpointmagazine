<?php
require_once 'connection2.php'; // Your database connection
header('Content-Type: application/json');

try {
    // 1. Validate and Sanitize Data (similar to process_payment.php)
    if (empty($_POST['f-name']) || empty($_POST['l-name']) || empty($_POST['organization']) ||
        empty($_POST['email']) || empty($_POST['contact']) || empty($_POST['category']) || empty($_POST['amount'])) {
        throw new Exception("All fields are required.");
    }

    // 2. Sanitize Data (Prevent SQL Injection)
    $firstName = htmlspecialchars($_POST['f-name']);
    $lastName = htmlspecialchars($_POST['l-name']);
    $organization = htmlspecialchars($_POST['organization']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contact = htmlspecialchars($_POST['contact']);
    $category = htmlspecialchars($_POST['category']);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); //Sanitize the amount

    if (!is_numeric($amount) || $amount <= 0) {
        throw new Exception("Invalid amount value.");
    }
    // 3. Generate Invoice ID (similar to process_payment.php)
    $pdo = connect();
    $stmt = $pdo->query("SELECT MAX(id) FROM payments");
    $lastId = $stmt->fetchColumn();
    $nextId = $lastId ? intval($lastId) + 1 : 1;
    $invoiceId = 'TPMP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);


    // 4. IntaSend Vendor API Call
    $apiKey = 'ISPubKey_test_25de78f5-9274-4a51-8f9f-a5986d87e93a'; // Replace with your secret API key
    $url = 'https://api.intasend.com/v1/payment-requests'; // Or the correct endpoint

    $data = [
        'amount' => $amount,
        'currency' => 'KES',
        'email' => $email,
        'phone_number' => $contact,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'api_ref' => $invoiceId,
        'redirect_url' => '#', // URL after successful payment
        'webhook_url' => 'https://www.turningpointmagazine.africa/booking1/update_payment_status.php' // Your webhook
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($httpCode === 200 || $httpCode === 201) {
        // Payment request was successful
        // You might need to adjust this based on the actual IntaSend response
        $paymentUrl = $responseData['payment_url']; // Or however the URL is returned

        // 5. Insert Data into Database
        $sql = "INSERT INTO payments (invoice_id, first_name, last_name, organization, email, contact, category, amount)
            VALUES (:invoice_id, :first_name, :last_name, :organization, :email, :contact, :category, :amount)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':invoice_id' => $invoiceId,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':organization' => $organization,
            ':email' => $email,
            ':contact' => $contact,
            ':category' => $category,
            ':amount' => $amount
        ]);


        echo json_encode(['success' => true, 'payment_url' => $paymentUrl]);
    } else {
        // Payment request failed
        error_log("IntaSend API Error: " . print_r($responseData, true));
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'IntaSend API Error: ' . $responseData['message']]);
    }

} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} finally {
    if (isset($pdo)) {
        closeConnection($pdo);
    }
}
?>
