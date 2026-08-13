<?php
// Include the connection script
require 'includes/conn.php'; // Make sure this file contains the connect() function

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comments = $_POST['comments'];
    $ipAddress = $_POST['ipAddress'] ?? null; // Use null coalescing operator to handle undefined key

    if ($ipAddress === null) {
        die("IP address is required.");
    }

    // Connect to the database
    $pdo = connect();

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (comments, ipAddress) VALUES (:comments, :ipAddress)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':comments', $comments);
    $stmt->bindParam(':ipAddress', $ipAddress);

    if ($stmt->execute()) {
        header("Location: index.php?feedback=success");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    // Close the connection
    closeConnection($pdo);
}
?>
