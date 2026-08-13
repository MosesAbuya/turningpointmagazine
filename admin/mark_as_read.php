<?php
// Include the database connection
include 'connection.php';

// Check if the request has an 'id' parameter
if (isset($_POST['id'])) {
    // Get the story ID from the POST request
    $storyId = $_POST['id'];

    // Create a database connection
    $pdo = connect();

    // Prepare the query to mark the story as read
    $query = "UPDATE stories SET is_read = 1 WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $storyId, PDO::PARAM_INT);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        echo "Story marked as read successfully.";
    } else {
        echo "Error marking story as read.";
    }

    // Close the connection
    closeConnection($pdo);
}
?>