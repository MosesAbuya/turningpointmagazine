<?php
include 'connection2.php'; // Ensure this file includes the correct database connection logic
header("Content-Type: application/json");

// Log file for debugging
$logFile = "M_PESAConfirmationResponse.txt";

// Read the incoming M-PESA response
$mpesaResponse = file_get_contents('php://input');

// Log the raw response for debugging
file_put_contents($logFile, "Raw Response: " . $mpesaResponse . PHP_EOL, FILE_APPEND);

// Decode the JSON response
$responseData = json_decode($mpesaResponse, true);

// Check if the response contains the expected structure
if (isset($responseData['Body']['stkCallback'])) {
    $callback = $responseData['Body']['stkCallback'];
    $resultCode = $callback['ResultCode']; // Result code (0 = success)
    $checkoutRequestID = $callback['CheckoutRequestID']; // Unique transaction ID for this request

    // Log the decoded response for debugging
    file_put_contents($logFile, "Decoded Response: " . print_r($callback, true) . PHP_EOL, FILE_APPEND);

    // Check if payment was successful
    if ($resultCode == 0) {
        // Extract payment details from CallbackMetadata
        $callbackMetadata = $callback['CallbackMetadata']['Item'];
        $amount = null;
        $mpesaReceiptNumber = null;

        // Loop through metadata to extract required fields
        foreach ($callbackMetadata as $item) {
            if ($item['Name'] == 'Amount') {
                $amount = $item['Value'];
            }
            if ($item['Name'] == 'MpesaReceiptNumber') {
                $mpesaReceiptNumber = $item['Value'];
            }
        }

        if ($amount && $mpesaReceiptNumber) {
            try {
                // Update the database with payment success details
                $pdo = connect(); // Ensure this function connects to your database properly

                // Update payment status to 'complete'
                $stmt = $pdo->prepare("
                    UPDATE payments 
                    SET status = ?, transaction_id = ?, amount = ?, updated_at = NOW() 
                    WHERE transaction_id = ?
                ");
                if ($stmt->execute(['complete', $mpesaReceiptNumber, $amount, $checkoutRequestID])) {
                    file_put_contents($logFile, "Payment updated successfully for Transaction ID: {$checkoutRequestID}" . PHP_EOL, FILE_APPEND);
                } else {
                    file_put_contents($logFile, "Database Update Error: " . implode(", ", $stmt->errorInfo()) . PHP_EOL, FILE_APPEND);
                }

                closeConnection($pdo); // Close DB connection safely
            } catch (Exception $e) {
                // Log any exceptions during database update
                file_put_contents($logFile, "Exception during DB Update: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
        } else {
            file_put_contents($logFile, "Missing Amount or MpesaReceiptNumber in CallbackMetadata" . PHP_EOL, FILE_APPEND);
        }
    } else {
        // Handle failed transactions (ResultCode != 0)
        try {
            $pdo = connect();

            // Update payment status to 'failed'
            $stmt = $pdo->prepare("
                UPDATE payments 
                SET status = 'failed', updated_at = NOW() 
                WHERE transaction_id = ?
            ");
            if ($stmt->execute([$checkoutRequestID])) {
                file_put_contents($logFile, "Payment marked as failed for Transaction ID: {$checkoutRequestID}" . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents($logFile, "Database Update Error (Failed Payment): " . implode(", ", $stmt->errorInfo()) . PHP_EOL, FILE_APPEND);
            }

            closeConnection($pdo);
        } catch (Exception $e) {
            file_put_contents($logFile, "Exception during Failed Payment DB Update: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }
} else {
    // Log invalid or unexpected responses
    file_put_contents($logFile, "Invalid Response Structure: " . print_r($responseData, true) . PHP_EOL, FILE_APPEND);
}

// Respond to Safaricom API to acknowledge receipt of the callback
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Confirmation Received Successfully"]);
?>






 // Retrieve order details
            $stmt = $pdo->prepare("
                SELECT o.id, o.total_amount, oi.product_id, oi.quantity, oi.price, p.name AS product_name
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE o.invoice_id = ?
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

            // Email content
            $user_message = "
                <h2>Payment Successful ✅</h2>
                <p>Dear $user_name,</p>
                <p>Your payment has been received successfully! Here are your order details:</p>
                <p><strong>Invoice ID:</strong> $invoice_id</p>
                <p><strong>Total Amount:</strong> KES {$order_items[0]['total_amount']}</p>
                <p><strong>Order Items:</strong><br> $order_details</p>
                <p>You will be contacted ASAP for further processing.</p>
                <p>Thank you for choosing Turning Point Magazine Africa!</p>";

            $admin_message = "
                <h2>New Purchase Notification 🛒</h2>
                <p>A new order has been placed.</p>
                <p><strong>Customer:</strong> $user_name</p>
                <p><strong>Email:</strong> $user_email</p>
                <p><strong>Invoice ID:</strong> $invoice_id</p>
                <p><strong>Total Amount:</strong> KES {$order_items[0]['total_amount']}</p>
                <p><strong>Order Items:</strong><br> $order_details</p>";

            // Send email to user
            sendEmail($user_email, "Your Order Confirmation - Turning Point Magazine", $user_message);
            
            // Send email to admin
            sendEmail("info@turningpointmagazine.africa", "New Purchase Alert - Turning Point Magazine", $admin_message);
