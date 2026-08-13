<?php
header('Content-Type: application/json'); // Ensure JSON response

$response = ["status" => "error", "message" => "Something went wrong!"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include database connection
    require 'connection.php';

    try {
        $pdo = connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get and sanitize input
        $firstname = htmlspecialchars(trim($_POST['firstname'] ?? ''));
        $lastname = htmlspecialchars(trim($_POST['lastname'] ?? ''));
        $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

        // Get user IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Validate required fields
        if (!$firstname || !$lastname) {
            echo json_encode(["status" => "error", "message" => "First name and last name are required."]);
            exit;
        }

        // Validate email format
        if (!$email) {
            echo json_encode(["status" => "error", "message" => "Invalid email address."]);
            exit;
        }

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo json_encode(["status" => "exists", "message" => "This email is already in the system."]);
            exit;
        }

        // Generate invoice number (similar to registration process)
        $stmt = $pdo->query("SELECT invoice FROM booking ORDER BY id DESC LIMIT 1");
        $lastInvoice = $stmt->fetchColumn();
        $newNumber = $lastInvoice ? (int)substr($lastInvoice, 5) + 1 : 1;
        $invoice = "TPM02" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO booking (firstname, lastname, contact, email, invoice, ipAddress, added_during_attendance) 
                               VALUES (:firstname, :lastname, :contact, :email, :invoice, :ipAddress, 1)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':invoice', $invoice);
        $stmt->bindParam(':ipAddress', $ipAddress);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Attendee added successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Failed to add attendee."];
        }

    } catch (PDOException $e) {
        $response = ["status" => "error", "message" => "Database error: " . $e->getMessage()];
    } finally {
        closeConnection($pdo);
    }
}

echo json_encode($response);
exit;
