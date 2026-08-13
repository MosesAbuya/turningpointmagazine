<?php
// Include the connection script
require 'includes/conn.php'; // Make sure this file contains the connect() function

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $comments = htmlspecialchars(trim($_POST['comments']));
    
    // Capture the client's IP address automatically
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; // Default to '0.0.0.0' if no IP is found

    // Additional check for proxies, if needed
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // HTTP_X_FORWARDED_FOR can contain multiple IP addresses, take the first one
        $ipAddress = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }

    // Check if the comment is empty
    if (empty($comments)) {
        echo "Please enter your feedback.";
        exit;
    }

    try {
        // Connect to the database
        $pdo = connect();

        // Insert feedback into the database
        $sql = "INSERT INTO feedback (comments, ipAddress) VALUES (:comments, :ipAddress)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':comments', $comments);
        $stmt->bindParam(':ipAddress', $ipAddress);

        if ($stmt->execute()) {
            echo "Thank you for your feedback!";
        } else {
            echo "Error: Unable to save feedback.";
        }

        // Close the connection
        closeConnection($pdo);
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>