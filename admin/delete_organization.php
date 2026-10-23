<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    if ($id <= 0) {
        http_response_code(400);
        echo "Invalid organization ID";
        exit;
    }

    try {
        $pdo = connect();

        // First, get the logo_url to delete the file
        $stmt = $pdo->prepare("SELECT logo_url FROM organizations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $organization = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($organization && !empty($organization['logo_url'])) {
            $file_path = __DIR__ . '/../' . $organization['logo_url'];
            if (file_exists($file_path) && !is_dir($file_path)) {
                @unlink($file_path);
            }
        }

        // Then, delete the record from the database
        $deleteStmt = $pdo->prepare("DELETE FROM organizations WHERE id = :id");
        $deleteStmt->execute(['id' => $id]);

        closeConnection($pdo);
        echo "Organization deleted successfully";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Invalid request";
}
?>