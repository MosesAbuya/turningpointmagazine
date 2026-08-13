<?php
include 'connection2.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Africa/Nairobi');

    $phone = $_POST['phone_number'] ?? null;
    if (!$phone) {
        echo json_encode(["status" => "error", "message" => "Phone number is required"]);
        exit;
    }

    $pdo = connect();
    $userId = $_SESSION['user_id'];

    // Fetch user details
    $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM customers WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // Generate Invoice ID
    $invoice_id = "INV" . time();
    $amount = 100; // Set total amount dynamically based on cart total

    // Save pending transaction
    $stmt = $pdo->prepare("INSERT INTO payments (invoice_id, user_id, phone, amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$invoice_id, $userId, $phone, $amount]);

    // MPESA Credentials
    $consumerKey = 'LuWEaIrjjCOwE6o7N6mwyl3mchiX2g0LE1qVRVTZk6dGhh8j';
    $consumerSecret = 'HBdpE2pMSZjhvSGCrwxWMYrzi65yd76okwcraACT4PF2GuBVXaaPBJA0RRAPvBk9';
    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);
    $CallBackURL = 'https://yourdomain.com/callback_url.php';

    // Get Access Token
    $curl = curl_init('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    $result = json_decode(curl_exec($curl), true);
    curl_close($curl);
    $access_token = $result['access_token'] ?? '';

    // STK Push Request
    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
    $stk_data = [
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phone,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $phone,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $invoice_id,
        'TransactionDesc' => 'Order Payment'
    ];

    $curl = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stk_data));
    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if (isset($response['CheckoutRequestID'])) {
        echo json_encode(["status" => "success", "message" => "STK push sent!", "invoice_id" => $invoice_id]);
    } else {
        echo json_encode(["status" => "error", "message" => "STK push failed"]);
    }
}
?>
