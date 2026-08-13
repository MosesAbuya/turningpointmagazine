<?php
// Include the database connection
include 'connection2.php';

// Get the search term from the query string
$searchTerm = isset($_GET['query']) ? trim($_GET['query']) : '';

// Log the received search term (for debugging purposes)
error_log("Search term received: " . $searchTerm);

// If there's no search term, return an empty array
if (empty($searchTerm)) {
    echo json_encode([]);
    exit;
}

// Create a database connection using the function from connection2.php
$pdo = connect();

// Search categories table for the name column
$categoryQuery = "SELECT id, name FROM categories WHERE name LIKE :searchTerm LIMIT 5";
$articleQuery = "SELECT id, title, edition_id FROM articles WHERE title LIKE :searchTerm OR story_content LIKE :searchTerm LIMIT 5";

// Prepare and execute the category query
$categoryStmt = $pdo->prepare($categoryQuery);
$categoryStmt->execute(['searchTerm' => "%$searchTerm%"]);
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare and execute the article query
$articleStmt = $pdo->prepare($articleQuery);
$articleStmt->execute(['searchTerm' => "%$searchTerm%"]);
$articles = $articleStmt->fetchAll(PDO::FETCH_ASSOC);

// Combine the results into one array
$results = array_merge($categories, $articles);

// Log the results before returning (for debugging purposes)
error_log("Results: " . json_encode($results));

// Close the database connection
closeConnection($pdo);

// Return the results as a JSON object
echo json_encode($results);
?>