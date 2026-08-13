<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['day'])) {
    $pdo = connect();
    $id = $_POST['id'];
    $day = $_POST['day'];

    // Determine column to update
    $column = ($day == 1) ? 'present_day1' : 'present_day2';

    // Get current status
    $stmt = $pdo->prepare("SELECT $column FROM booking WHERE id = ?");
    $stmt->execute([$id]);
    $currentStatus = $stmt->fetchColumn();

    // Toggle attendance
    $newStatus = ($currentStatus == 1) ? 0 : 1;
    $stmt = $pdo->prepare("UPDATE booking SET $column = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    // Return response as JSON
    echo json_encode([
        'status' => 'success',
        'message' => $newStatus ? 'Marked Present' : 'Unmarked Present',
        'newStatus' => $newStatus
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
