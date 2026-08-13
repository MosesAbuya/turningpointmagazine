<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filter'])) {
    $pdo = connect();
    $filter = $_GET['filter'];

    // Query to fetch attendees based on filter
    if ($filter === 'all') {
        $stmt = $pdo->prepare("SELECT * FROM booking");
    } elseif ($filter === 'present') {
        $stmt = $pdo->prepare("SELECT * FROM booking WHERE present_day1 = 1 OR present_day2 = 1");
    } elseif ($filter === 'absent') {
        $stmt = $pdo->prepare("SELECT * FROM booking WHERE present_day1 = 0 AND present_day2 = 0");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid filter']);
        exit;
    }

    $stmt->execute();
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count present and absent for each day
    $presentDay1 = 0;
    $presentDay2 = 0;
    $absentDay1 = 0;
    $absentDay2 = 0;

    foreach ($attendees as $attendee) {
        if ($attendee['present_day1'] == 1) $presentDay1++;
        else $absentDay1++;
        if ($attendee['present_day2'] == 1) $presentDay2++;
        else $absentDay2++;
    }

    // Return response as JSON
    echo json_encode([
        'status' => 'success',
        'attendees' => $attendees,
        'presentDay1' => $presentDay1,
        'presentDay2' => $presentDay2,
        'absentDay1' => $absentDay1,
        'absentDay2' => $absentDay2,
        'totalCount' => count($attendees)
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
