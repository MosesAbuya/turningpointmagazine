<?php
require 'connection2.php';
$pdo = connect();
try {
    $pdo->exec("ALTER TABLE orders CHANGE COLUMN otes notes text DEFAULT NULL");
    $pdo->exec("ALTER TABLE orders CHANGE COLUMN racking_number tracking_number varchar(100) DEFAULT NULL");
    echo "Columns fixed successfully.";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
