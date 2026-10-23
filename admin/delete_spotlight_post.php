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
    $postId = intval($_POST['id']);
    if ($postId <= 0) {
        http_response_code(400);
        echo "Invalid spotlight post ID";
        exit;
    }

    try {
        $pdo = connect();

        function removeFile($filePath) {
            if (empty($filePath)) return;
            $p1 = __DIR__ . '/' . $filePath;
            $p2 = __DIR__ . '/../' . $filePath;
            if (file_exists($p1) && !is_dir($p1)) @unlink($p1);
            if (file_exists($p2) && !is_dir($p2)) @unlink($p2);
        }

        // 1. Fetch the file paths before deleting the record
        $query = "SELECT thumbnail_image, file_upload, document FROM spotlight_posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // Delete thumbnail image
            if (!empty($post['thumbnail_image'])) {
                removeFile($post['thumbnail_image']);
            }
            // Delete gallery files
            if (!empty($post['file_upload'])) {
                $files = json_decode($post['file_upload'], true);
                if (is_array($files)) {
                    foreach ($files as $file) {
                        removeFile($file);
                    }
                }
            }
            // Delete attached document
            if (!empty($post['document'])) {
                removeFile($post['document']);
            }
        }

        // 2. Delete record from database
        $deleteQuery = "DELETE FROM spotlight_posts WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $deleteStmt->execute();

        closeConnection($pdo);
        echo "Post and associated files deleted successfully.";

    } catch (Exception $e) {
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "No post ID provided.";
}
?>