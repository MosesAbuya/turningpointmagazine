<?php
require 'connection2.php';
$pdo = connect();
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
$refs = [];
foreach ($tables as $t) {
    try {
        $stmt2 = $pdo->query("SHOW CREATE TABLE `$t`");
        $create = $stmt2->fetch(PDO::FETCH_ASSOC)['Create Table'];
        if (strpos($create, 'REFERENCES `orders`') !== false) {
            $refs[] = $t;
        }
    } catch(Exception $e) {}
}
echo "Tables referencing orders: " . implode(', ', $refs);
?>
