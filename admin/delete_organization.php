<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, you might want to send an error response
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $pdo = connect();

    // First, get the logo_url to delete the file
    $stmt = $pdo->prepare("SELECT logo_url FROM organizations WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $organization = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($organization && !empty($organization['logo_url'])) {
        // The path is stored relative to the root, so we need to go up one level from /admin
        $file_path = __DIR__ . '/../' . $organization['logo_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Then, delete the record from the database
    $deleteStmt = $pdo->prepare("DELETE FROM organizations WHERE id = :id");
    $deleteStmt->execute(['id' => $id]);

    closeConnection($pdo);
    // Send a success response
    echo "Organization deleted successfully";
} else {
    // If it's not a POST request or id is not set, send a bad request response
    http_response_code(400);
    echo "Invalid request";
}
?>