<?php
include 'connection2.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('Africa/Nairobi');

    try {
        // Database connection
        $pdo = connect();

        // **NEW LOGIC: Handle Payment Status Checking**
        if (isset($_POST['check_status']) && isset($_POST['invoice_id'])) {
            $invoice_id = $_POST['invoice_id'];

            // Fetch the most recent payment attempt for the given invoice_id
            $stmt = $pdo->prepare("
                SELECT payment_id, status, email, first_name, last_name, contact
                FROM payments 
                WHERE invoice_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1
            ");
            $stmt->execute([$invoice_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $status = $result['status'];
                $payment_id = $result['payment_id'];
                $user_email = $result['email'];
                $user_name = $result['first_name'] . ' ' . $result['last_name'];
                $user_contact = $result['contact'];
                

                if ($status === "complete") {
                    // Retrieve order details
                    $stmt = $pdo->prepare("
                        SELECT 
                            o.id AS order_id,
                            o.total_amount,
                            oi.product_id,
                            p.name AS product_name,
                            oi.quantity,
                            oi.price AS unit_price,
                            (oi.quantity * oi.price) AS item_total
                        FROM 
                            orders o
                        JOIN 
                            order_items oi ON o.id = oi.order_id
                        JOIN 
                            products p ON oi.product_id = p.id
                        WHERE 
                            o.invoice_id = ?
                        ORDER BY 
                            oi.product_id ASC;

                    ");
                    $stmt->execute([$invoice_id]);
                    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($order_items) {
                        $order_details = "";
                        foreach ($order_items as $item) {
                            $order_details .= "{$item['product_name']} - Quantity: {$item['quantity']} - Price: {$item['price']}<br>";
                        }
                    } else {
                        $order_details = "No order items found.";
                    }

                    echo json_encode([
                        "status" => "complete",
                        "message" => "✅ Payment successful! Confirmation email will be sent shortly.",
                        "payment_id" => $payment_id
                    ]);

                    // Allow the script to continue running even if user disconnects
                    ignore_user_abort(true);
                    ob_end_flush();
                    flush();

                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'da8.host-ww.net';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'moses@turningpointmagazine.africa';
                        $mail->Password = 'Amo20.03';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->setFrom('info@turningpointmagazine.africa', 'Turning Point Magazine Africa');
                        $mail->isHTML(true);

                        // ===== Email 1: Send to User =====
                        $mail->addAddress($user_email);
                        $mail->Subject = 'Your Order Confirmation - Turning Point Magazine';
                        $mail->Body = "
                            <h2>Payment Successful ✅</h2>
                            <p>Dear $user_name,</p>
                            <p>Your payment has been received successfully! Here are your order details:</p>
                            <p><strong>Invoice ID:</strong> $invoice_id</p>
                            <p><strong>Total Amount:</strong> KES {$order_items[0]['total_amount']}</p>
                            <p><strong>Order Items:</strong><br> $order_details</p>
                            <p>You will be contacted ASAP for further processing.</p>
                            <p>Thank you for choosing Turning Point Magazine Africa!</p>
                        ";
                        $mail->send();

                        // ===== Email 2: Send to Admin =====
                        $mail->clearAddresses(); // Clear previous recipient
                        $mail->addAddress('info@turningpointmagazine.africa');
                        $mail->Subject = 'New Purchase Alert - Turning Point Magazine';
                        $mail->Body = "
                            <h2>New Purchase Notification 🛒</h2>
                            <p>A new order has been placed.</p>
                            <p><strong>Customer:</strong> $user_name</p>
                            <p><strong>Email:</strong> $user_email</p>
                            <p><strong>Invoice ID:</strong> $invoice_id</p>
                            <p><strong>Total Amount:</strong> KES {$order_items[0]['total_amount']}</p>
                            <p><strong>Order Items:</strong><br> $order_details</p>
                            <p><strong>Contact:</strong> $user_contact</p>
                        ";
                        $mail->send();
                    } catch (Exception $e) {
                        error_log("Email sending failed: " . $mail->ErrorInfo);
                    }

                    exit;
                } elseif ($status === "failed") {
                    echo json_encode([
                        "status" => "failed", 
                        "message" => "❌ Payment failed. Try again.", 
                        "payment_id" => $payment_id
                    ]);
                } else {
                    echo json_encode([
                        "status" => "pending", 
                        "message" => "⏳ Payment is still processing... do not refresh this page!",
                        "payment_id" => $payment_id
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "not_found", 
                    "message" => "⚠️ Payment record not found."
                ]);
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

            // ✅ **NEW CHECK: Check if payment is already complete**
            $check_stmt = $pdo->prepare("SELECT status FROM payments WHERE invoice_id = ? ORDER BY created_at DESC LIMIT 1");
            $check_stmt->execute([$invoice_id]);
            $payment_record = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($payment_record && $payment_record['status'] === 'complete') {
                echo json_encode([
                    "status" => "cancelled",
                    "message" => "🚫 This invoice has already been paid. Transaction cancelled."
                ]);
                exit;
            }

            // ✅ **Fix: Ensure `amount` is a valid decimal value**
            if (empty($amount) || !is_numeric($amount)) {
                echo json_encode(["status" => "error", "message" => "Invalid payment amount."]);
                exit;
            }
            $amount = floatval($amount); // Convert to float

            // MPESA Credentials
            $consumerKey = 'hrhSQiSbnPxy8mdlpXyypYNDoPuY3vEAUFN5r0EKzHKFdkJ5';
            $consumerSecret = 'T7MsTagmE08rtfz2pqpaWHSGAb0gOGYPQv7hbdb0pG282NME3DEN7aJUacDcPr20';
            $BusinessShortCode = '6566500'; // Ensure this is correct for your Till
            $TillNumber = '575555'; // Your Till Number
            $Passkey = 'dc4fd5a9bc338446ab75137301bde9a7fb96771a550ac099830c2fe744a058cc';

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
            $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
            $headers = ['Content-Type:application/json; charset=utf8'];
            $curl = curl_init($access_token_url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                echo json_encode(["status" => "error", "message" => "cURL Error: " . curl_error($curl)]);
                exit;
            }

            curl_close($curl);

            // Log or display raw response
            file_put_contents('access_token_log.txt', $response); // Logs to a file
            $result = json_decode($response, true);

            if (!isset($result['access_token'])) {
                echo json_encode(["status" => "error", "message" => "Failed to get access token", "details" => $response]);
                exit;
            }

            $access_token = $result['access_token'];

            // Initiate STK Push
            $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
            $stk_data = [
                'BusinessShortCode' => $BusinessShortCode,
                'Password' => $Password,
                'Timestamp' => $Timestamp,
                'TransactionType' => 'CustomerBuyGoodsOnline', // Change here
                'Amount' => $amount,
                'PartyA' => $PartyA,
                'PartyB' => $TillNumber, // Change here
                'PhoneNumber' => $PartyA,
                'CallBackURL' => $CallBackURL,
                'AccountReference' => $AccountReference,
                'TransactionDesc' => $TransactionDesc
            ];
            

            $curl = curl_init('https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
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
