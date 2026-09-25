<?php
session_start();
require_once ("inc/Database.php");

$db = new Database();
$total = 0;
$count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

if ($count > 0) {
    $pids = [];
    foreach (array_keys($_SESSION['cart']) as $k) {
        $parts = explode('_', $k);
        $pids[] = intval($parts[0]);
    }
    $pids = array_unique($pids);

    $result = $db->getData($pids);
    if ($result) {
        while ($row = $result->fetch_assoc()){
            foreach ($_SESSION['cart'] as $k => $qty) {
                if (explode('_', $k)[0] == $row['id']) {
                    $total += (floatval($row['current_price']) * intval($qty));
                }
            }
        }
    }
}

echo json_encode([
    'status' => 'success',
    'total' => $total,
    'count' => $count
]);
?>
