<?php
// Include the database connection file
include 'connection.php';

// Check if the 'id' parameter is set
if (isset($_POST['id'])) {
    // Get the ID of the story to be deleted
    $storyId = $_POST['id'];

    try {
        // Establish a connection to the database
        $pdo = connect();

        // Prepare the DELETE query to remove the story from the database
        $query = "DELETE FROM stories WHERE id = :id";
        $stmt = $pdo->prepare($query);

        // Bind the ID parameter to the query
        $stmt->bindParam(':id', $storyId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo "Story deleted successfully.";
        } else {
            echo "Failed to delete story.";
        }

        // Close the database connection
        closeConnection($pdo);
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No story ID provided.";
}
?>