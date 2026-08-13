<?php
// Include the database connection
include 'connection.php';

// Check if the 'id' parameter is provided via POST request
if (isset($_POST['id'])) {
    $feedbackId = $_POST['id'];

    // Create a database connection
    $pdo = connect();

    // Prepare the query to delete feedback by ID
    $query = "DELETE FROM feedback WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $feedbackId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        echo "Feedback deleted successfully.";
    } else {
        echo "Failed to delete feedback.";
    }

    // Close the connection
    closeConnection($pdo);
}
?>