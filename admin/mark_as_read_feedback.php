<?php
// Include the database connection
include 'connection.php';

// Check if the 'id' parameter is provided via POST request
if (isset($_POST['id'])) {
    $feedbackId = $_POST['id'];

    // Create a database connection
    $pdo = connect();

    // Prepare the query to update the feedback status to "read"
    $query = "UPDATE feedback SET status = 'read' WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $feedbackId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        echo "Feedback marked as read successfully.";
    } else {
        echo "Failed to mark feedback as read.";
    }

    // Close the connection
    closeConnection($pdo);
}
?>