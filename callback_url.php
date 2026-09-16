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
                    WHERE id = (
                        SELECT id FROM (
                            SELECT id FROM payments 
                            WHERE transaction_id = ? 
                            ORDER BY created_at DESC 
                            LIMIT 1
                        ) AS sub
                    )
                ");

                if ($stmt->execute(['complete', $mpesaReceiptNumber, $amount, $checkoutRequestID]))
 {
                    // Update orders table as well
                    $stmtOrder = $pdo->prepare("
                        UPDATE orders 
                        SET status = 'paid' 
                        WHERE invoice_id = (
                            SELECT invoice_id FROM payments WHERE transaction_id = ? LIMIT 1
                        )
                    ");
                    // Note: Since we changed transaction_id to $mpesaReceiptNumber above, we use it to find the invoice
                    $stmtOrder->execute([$mpesaReceiptNumber]);
                    
                    // Fetch order details for email
                    $stmtFetch = $pdo->prepare("SELECT * FROM orders WHERE invoice_id = (SELECT invoice_id FROM payments WHERE transaction_id = ? LIMIT 1)");
                    $stmtFetch->execute([$mpesaReceiptNumber]);
                    $order = $stmtFetch->fetch(PDO::FETCH_ASSOC);

                    if ($order) {
                        require_once 'includes/mailer.php';
                        $email = $order['email'];
                        $name = trim($order['first_name'] . ' ' . $order['last_name']);
                        
                        // Fetch order items
                        $stmtItems = $pdo->prepare("
                            SELECT oi.quantity, oi.price, p.name 
                            FROM order_items oi 
                            LEFT JOIN products p ON oi.product_id = p.id 
                            WHERE oi.order_id = ?
                        ");
                        $stmtItems->execute([$order['id']]);
                        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

                        $itemsList = "<table style='width:100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;'>
                                        <tr style='background:#f4f4f4; text-align: left;'>
                                            <th style='padding:8px; border:1px solid #ddd;'>Item</th>
                                            <th style='padding:8px; border:1px solid #ddd;'>Qty</th>
                                            <th style='padding:8px; border:1px solid #ddd;'>Price</th>
                                        </tr>";
                        if ($items) {
                            foreach ($items as $item) {
                                $itemName = !empty($item['name']) ? htmlspecialchars($item['name']) : 'Turning Point Item';
                                $itemsList .= "<tr>
                                    <td style='padding:8px; border:1px solid #ddd;'>{$itemName}</td>
                                    <td style='padding:8px; border:1px solid #ddd;'>{$item['quantity']}</td>
                                    <td style='padding:8px; border:1px solid #ddd;'>Kes " . number_format($item['price'], 2) . "</td>
                                </tr>";
                            }
                        } else {
                            $itemsList .= "<tr><td colspan='3' style='padding:8px; border:1px solid #ddd; text-align:center;'>Standard Checkout Item</td></tr>";
                        }
                        $itemsList .= "</table>";

                        // Email to Buyer
                        $buyerSubject = "Order Confirmation - Invoice " . $order['invoice_id'];
                        $buyerBody = "
                        <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
                            <h2 style='color: #e8003d;'>Payment Received!</h2>
                            <p>Hello <strong>$name</strong>,</p>
                            <p>Your payment of <strong>Kes " . number_format($amount, 2) . "</strong> has been received successfully. We are now processing your order.</p>
                            
                            <h3>Order Summary (Invoice: {$order['invoice_id']})</h3>
                            $itemsList
                            
                            <p><strong>Delivery Option:</strong> " . htmlspecialchars($order['delivery_option'] ?? 'N/A') . "</p>
                            <p><strong>Delivery Address:</strong> " . htmlspecialchars($order['delivery_address'] ?? 'N/A') . "</p>
                            <p>We will contact you as soon as your order is dispatched.</p>
                            <br>
                            <p>Thank you for choosing Turning Point Magazine Africa!</p>
                        </div>";
                        sendGlobalMail($pdo, $email, $name, $buyerSubject, $buyerBody);
                        
                        // Email to Admin
                        $adminSubject = "New Paid Order - " . $order['invoice_id'];
                        $adminBody = "
                        <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
                            <h2 style='color: #0c1a30;'>New Purchase Alert</h2>
                            <p>A new payment of <strong>Kes " . number_format($amount, 2) . "</strong> was received.</p>
                            <p><strong>Customer:</strong> $name (<a href='mailto:$email'>$email</a>)</p>
                            <p><strong>Phone:</strong> {$order['contact']}</p>
                            <p><strong>Invoice ID:</strong> {$order['invoice_id']}</p>
                            
                            <h3>Order Items</h3>
                            $itemsList
                        </div>";
                        sendGlobalMail($pdo, 'info@turningpointmagazine.africa', 'Turning Point Admin', $adminSubject, $adminBody);
                    }

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
