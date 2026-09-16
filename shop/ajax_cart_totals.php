<?php
session_start();
require_once ("inc/Database.php");

$db = new Database();
$total = 0;
$count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

if ($count > 0) {
    $pids = array_keys($_SESSION['cart']);
    $result = $db->getData($pids);
    while ($row = $result->fetch_assoc()){
        $total += (floatval($row['current_price']) * intval($_SESSION['cart'][$row['id']]));
    }
}

echo json_encode([
    'status' => 'success',
    'total' => $total,
    'count' => $count
]);
?>
