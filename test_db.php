<?php
require_once 'connection2.php';
$pdo = connect();
$stmt = $pdo->query("DESCRIBE blog");
print_r($stmt->fetchAll());
?>
