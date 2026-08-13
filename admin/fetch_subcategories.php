<?php
include 'connection2.php';

$category_id = $_GET['category_id'];

$pdo = connect();
$stmt = $pdo->prepare("SELECT id, name FROM sub_category WHERE category_id = :category_id");
$stmt->execute(['category_id' => $category_id]);
$subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($subcategories);

closeConnection($pdo);
?>