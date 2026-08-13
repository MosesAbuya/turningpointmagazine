<?php
include 'connection2.php'; // Ensure correct database connection

header("Content-Type: application/json");

if (isset($_POST['invoice_id'])) {
    $invoiceId = $_POST['invoice_id'];

    try {
        $pdo = connect(); // Ensure this function connects to your database properly

        // Check payment status from the database
        $stmt = $pdo->prepare("SELECT status FROM payments WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            if ($payment['status'] === "complete") {
                $response = [
                    "status" => "complete",
                    "message" => "Your payment was successful! 🎉 Your registration is confirmed."
                ];
            } elseif ($payment['status'] === "failed") {
                $response = [
                    "status" => "failed",
                    "message" => "Your transaction was cancelled or failed. Please try again."
                ];
            } else {
                $response = [
                    "status" => "pending",
                    "message" => "Your payment is still being processed. Please wait..."
                ];
            }
        } else {
            $response = [
                "status" => "not_found",
                "message" => "No payment record found for this invoice. Please check your details."
            ];
        }

        closeConnection($pdo); // Close database connection safely
    } catch (Exception $e) {
        $response = [
            "status" => "error",
            "message" => "An error occurred while checking payment status: " . $e->getMessage()
        ];
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Invalid request. Invoice ID is required."
    ];
}

echo json_encode($response);
?>

