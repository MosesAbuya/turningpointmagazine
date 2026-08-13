<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $pdo = connect();
    $query = "DELETE FROM subscribers WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);

    closeConnection($pdo);
}
?>