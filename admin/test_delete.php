<?php
require 'connection2.php';
$pdo = connect();
try {
    $pdo->query('DELETE FROM orders WHERE id = 1');
    echo "Deleted";
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
