<?php
// Include the database connection file
include 'connection.php';

if (isset($_POST['id'])) {
    $storyId = $_POST['id'];
    try {
        $pdo = connect();
        $query = "DELETE FROM stories WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $storyId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Story deleted successfully.";
        } else {
            echo "Failed to delete story.";
        }
        closeConnection($pdo);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} elseif (isset($_POST['ids']) && is_array($_POST['ids'])) {
    $ids = $_POST['ids'];
    try {
        $pdo = connect();
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM stories WHERE id IN ($placeholders)");
        if ($stmt->execute($ids)) {
            echo "Stories deleted successfully.";
        } else {
            echo "Failed to delete stories.";
        }
        closeConnection($pdo);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No story ID provided.";
}
?>