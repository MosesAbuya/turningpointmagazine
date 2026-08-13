<?php
include 'connection.php';

$pdo = connect();

// Get total attendees
$totalQuery = $pdo->query("SELECT COUNT(*) FROM booking");
$total = $totalQuery->fetchColumn();

// Get present attendees (either Day 1 or Day 2)
$presentQuery = $pdo->query("SELECT COUNT(*) FROM booking WHERE present_day1 = 1 OR present_day2 = 1");
$present = $presentQuery->fetchColumn();

// Calculate absent attendees
$absent = $total - $present;

// Return JSON response
echo json_encode([
    'total' => $total,
    'present' => $present,
    'absent' => $absent
]);
?>
