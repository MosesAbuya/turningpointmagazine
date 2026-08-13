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
