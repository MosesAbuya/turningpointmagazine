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
            require_once 'includes/mailer.php';
            
            $itemsList = "<table style='width:100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;'>
                            <tr style='background:#f4f4f4; text-align: left;'>
                                <th style='padding:8px; border:1px solid #ddd;'>Item</th>
                                <th style='padding:8px; border:1px solid #ddd;'>Qty</th>
                                <th style='padding:8px; border:1px solid #ddd;'>Price</th>
                            </tr>";
            if ($order_items) {
                foreach ($order_items as $item) {
                    $itemName = !empty($item['product_name']) ? htmlspecialchars($item['product_name']) : 'Turning Point Item';
                    $itemsList .= "<tr>
                        <td style='padding:8px; border:1px solid #ddd;'>{$itemName}</td>
                        <td style='padding:8px; border:1px solid #ddd;'>{$item['quantity']}</td>
                        <td style='padding:8px; border:1px solid #ddd;'>Kes " . number_format($item['unit_price'], 2) . "</td>
                    </tr>";
                }
            } else {
                $itemsList .= "<tr><td colspan='3' style='padding:8px; border:1px solid #ddd; text-align:center;'>Standard Checkout Item</td></tr>";
            }
            $itemsList .= "</table>";

            $user_message = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
                <h2 style='color: #e8003d;'>Payment Successful!</h2>
                <p>Hello <strong>$user_name</strong>,</p>
                <p>Your payment of <strong>Kes " . number_format($transAmount, 2) . "</strong> has been received successfully!</p>
                <h3>Order Summary (Invoice: {$invoice_id})</h3>
                $itemsList
                <p>We will contact you shortly regarding the processing of your order.</p>
                <br>
                <p>Thank you for choosing Turning Point Magazine Africa!</p>
            </div>";

            $admin_message = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
                <h2 style='color: #0c1a30;'>New Purchase Alert (Paybill)</h2>
                <p>A new payment of <strong>Kes " . number_format($transAmount, 2) . "</strong> was received.</p>
                <p><strong>Customer:</strong> $user_name (<a href='mailto:$user_email'>$user_email</a>)</p>
                <p><strong>Invoice ID:</strong> {$invoice_id}</p>
                <h3>Order Items</h3>
                $itemsList
            </div>";

            // Send email to user
            sendGlobalMail($pdo, $user_email, $user_name, "Order Confirmation - Invoice $invoice_id", $user_message);
            
            // Send email to admin
            sendGlobalMail($pdo, "info@turningpointmagazine.africa", "Admin", "New Purchase Alert (Paybill) - $invoice_id", $admin_message);
