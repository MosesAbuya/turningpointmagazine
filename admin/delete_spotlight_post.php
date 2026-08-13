<?php
include 'connection2.php'; // Standardize connection

if (isset($_POST['id'])) {
    $postId = $_POST['id'];

    try {
        $pdo = connect();

        // 1. Fetch the file paths before deleting the record
        $query = "SELECT thumbnail_image, file_upload FROM spotlight_posts WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // 2. Delete the associated files from the server
            if (!empty($post['thumbnail_image']) && file_exists($post['thumbnail_image'])) {
                unlink($post['thumbnail_image']);
            }
            if (!empty($post['file_upload'])) {
                $files = json_decode($post['file_upload'], true);
                if (is_array($files)) {
                    foreach ($files as $file) {
                        if (file_exists($file)) {
                            unlink($file);
                        }
                    }
                }
            }
        }

        // 3. Prepare and execute the DELETE query
        $deleteQuery = "DELETE FROM spotlight_posts WHERE id = :id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $postId, PDO::PARAM_INT);

        if ($deleteStmt->execute()) {
            echo "Post and associated files deleted successfully.";
        } else {
            echo "Failed to delete post.";
        }

        closeConnection($pdo);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No post ID provided.";
}
?>
