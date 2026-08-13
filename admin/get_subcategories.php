<?php
include 'connection2.php';

// Check if category_id is passed in the GET request
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Connect to the database
    $pdo = connect();

    // Prepare SQL query to fetch subcategories based on the category_id
    $stmt = $pdo->prepare("SELECT id, name FROM subcategories WHERE category_id = :category_id");
    $stmt->execute(['category_id' => $category_id]);

    // Fetch the subcategories
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the subcategories as a JSON response
    echo json_encode($subcategories);

    // Close the database connection
    closeConnection($pdo);
} else {
    // Return an empty array if category_id is not provided
    echo json_encode([]);
}
?>