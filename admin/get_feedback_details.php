<style>
/* Style for feedback table */
.feedback-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
}

.feedback-table th,
.feedback-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.feedback-table th {
    background-color: #f2f2f2;
    color: #333;
}

.feedback-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.feedback-table tr:hover {
    background-color: #f1f1f1;
}
</style>
<?php
// Include the database connection
include 'connection.php';

// Check if the 'id' parameter is provided via GET request
if (isset($_GET['id'])) {
    $feedbackId = $_GET['id'];

    // Create a database connection
    $pdo = connect();

    // Prepare the query to fetch feedback details by ID
    $query = "SELECT * FROM feedback WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $feedbackId, PDO::PARAM_INT);
    $stmt->execute();
    $feedback = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if feedback exists
    if ($feedback) {
        // Display the feedback details in a styled table
        echo "<table class='feedback-table'>";
        echo "<tr><th>ID</th><td>" . $feedback['id'] . "</td></tr>";
        echo "<tr><th>Comments</th><td>" . $feedback['comments'] . "</td></tr>";
        echo "<tr><th>IP Address</th><td>" . $feedback['ipAddress'] . "</td></tr>";
        echo "<tr><th>Date</th><td>" . $feedback['date'] . "</td></tr>";
        echo "</table>";
    } else {
        echo "Feedback not found.";
    }

    // Close the connection
    closeConnection($pdo);
}
?>