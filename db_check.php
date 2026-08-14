<?php
require 'connection2.php';
$pdo = connect();
$stmt = $pdo->query("SHOW COLUMNS FROM articles");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
