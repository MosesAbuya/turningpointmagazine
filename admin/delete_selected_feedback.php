<?php
// Include the database connection
include 'connection.php';

// Check if the 'ids' parameter is provided via POST request
if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    $feedbackIds = $_POST['ids'];

    // Create a database connection
    $pdo = connect();

    // Prepare the query to delete feedback entries by IDs
    $query = "DELETE FROM feedback WHERE id IN (" . implode(',', array_fill(0, count($feedbackIds), '?')) . ")";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    foreach ($feedbackIds as $key => $id) {
        $stmt->bindValue(($key + 1), $id, PDO::PARAM_INT);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "Selected feedback deleted successfully.";
    } else {
        echo "Failed to delete selected feedback.";
    }

    // Close the connection
    closeConnection($pdo);
}
?>