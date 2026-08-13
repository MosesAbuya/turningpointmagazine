<?php
include 'connection2.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Africa/Nairobi');

    try {
        // Database connection
        $pdo = connect();

        // **NEW LOGIC: Handle Payment Status Checking**
        if (isset($_POST['check_status']) && isset($_POST['invoice_id'])) {
            $invoice_id = $_POST['invoice_id'];

            $stmt = $pdo->prepare("SELECT status FROM payments WHERE invoice_id = ?");
            $stmt->execute([$invoice_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $status = $result['status'];

                if ($status === "complete") {
                    echo json_encode(["status" => "complete", "message" => "✅ Payment successful! An email has been sent you with the details. Thank you"]);
                } elseif ($status === "failed") {
                    echo json_encode(["status" => "failed", "message" => "❌ Payment failed. Try again."]);
                } else {
                    echo json_encode(["status" => "pending", "message" => "⏳ Payment is still processing...do not refresh this page!"]);
                }
            } else {
                echo json_encode(["status" => "not_found", "message" => "⚠️ Payment record not found."]);
            }

            exit;
        }

        // **ORIGINAL LOGIC: Handle Payment Initiation**
        if (isset($_POST['first_name'])) {
            // Capture Form Data
            $invoice_id = $_POST['invoice_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $organization = $_POST['organization'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $category = $_POST['category'];
            $amount = $_POST['amount'];
            $currency = $_POST['currency'];
            $payment_method = $_POST['payment_method'];

            // ✅ **Fix: Ensure `amount` is a valid decimal value**
            if (empty($amount) || !is_numeric($amount)) {
                echo json_encode(["status" => "error", "message" => "Invalid payment amount."]);
                exit;
            }
            $amount = floatval($amount); // Convert to float

            // ✅ **Check for duplicate invoice_id before inserting**
            $stmt = $pdo->prepare("SELECT invoice_id FROM payments WHERE invoice_id = ?");
            $stmt->execute([$invoice_id]);
            if ($stmt->fetch()) {
                // Duplicate found — throw the refresh message
                echo json_encode(["status" => "error", "message" => "🔄 Please refresh the page to try again."]);

                exit;
            }

            // MPESA Credentials
            $consumerKey = 'LuWEaIrjjCOwE6o7N6mwyl3mchiX2g0LE1qVRVTZk6dGhh8j';
            $consumerSecret = 'HBdpE2pMSZjhvSGCrwxWMYrzi65yd76okwcraACT4PF2GuBVXaaPBJA0RRAPvBk9';
            $BusinessShortCode = '174379';
            $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

            $PartyA = $contact; // Client's phone number
            $AccountReference = $invoice_id;
            $TransactionDesc = 'Payment for Booking';
            $Timestamp = date('YmdHis');
            $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);
            $CallBackURL = 'https://www.turningpointmagazine.africa/callback_url.php';

            // Insert into database with "pending" status
            $stmt = $pdo->prepare("
                INSERT INTO payments (invoice_id, first_name, last_name, organization, email, contact, category, amount, currency, payment_method, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->execute([$invoice_id, $first_name, $last_name, $organization, $email, $contact, $category, $amount, $currency, $payment_method]);

            // Generate Access Token
            $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
            $headers = ['Content-Type:application/json; charset=utf8'];

            $curl = curl_init($access_token_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
            $result = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if (!isset($result['access_token'])) {
                echo json_encode(["status" => "error", "message" => "Failed to get access token"]);
                exit;
            }

            $access_token = $result['access_token'];

            // Initiate STK Push
            $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
            $stk_data = [
                'BusinessShortCode' => $BusinessShortCode,
                'Password' => $Password,
                'Timestamp' => $Timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $PartyA,
                'PartyB' => $BusinessShortCode,
                'PhoneNumber' => $PartyA,
                'CallBackURL' => $CallBackURL,
                'AccountReference' => $AccountReference,
                'TransactionDesc' => $TransactionDesc
            ];

            $curl = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($stk_data));
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);

            // Handle M-PESA Response
            if (isset($response['errorMessage'])) {
                echo json_encode(["status" => "error", "message" => $response['errorMessage']]);
                exit;
            }

            if (isset($response['CheckoutRequestID'])) {
                // Store transaction ID
                $stmt = $pdo->prepare("UPDATE payments SET transaction_id = ? WHERE invoice_id = ?");
                $stmt->execute([$response['CheckoutRequestID'], $invoice_id]);

                echo json_encode(["status" => "success", "message" => "Payment request sent. Check your phone.", "invoice_id" => $invoice_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to initiate payment"]);
            }
        }

        closeConnection($pdo);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
