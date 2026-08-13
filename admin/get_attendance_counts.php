<?php
include 'connection.php';

$pdo = connect();

$totalQuery = $pdo->query("SELECT COUNT(*) FROM booking");
$total = $totalQuery->fetchColumn();

$presentQuery = $pdo->query("SELECT COUNT(*) FROM booking WHERE present_day1 = 1 OR present_day2 = 1");
$present = $presentQuery->fetchColumn();

$absent = $total - $present;

echo json_encode([
    'total' => $total,
    'present' => $present,
    'absent' => $absent
]);
?>
