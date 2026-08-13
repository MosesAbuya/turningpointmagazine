<?php
include 'connection.php';

$pdo = connect();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$whereClauses = [];
$params = [];

// Search condition
if (!empty($search)) {
    $whereClauses[] = "(firstname LIKE :search OR lastname LIKE :search OR contact LIKE :search OR email LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

// Filter condition
if ($filter === 'present') {
    $whereClauses[] = "(present_day1 = 1 OR present_day2 = 1)";
} elseif ($filter === 'absent') {
    $whereClauses[] = "(present_day1 = 0 AND present_day2 = 0)";
}

$where = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$sql = "SELECT * FROM booking $where";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = '';
if ($attendees) {
    foreach ($attendees as $attendee) {
        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($attendee['id']) . '</td>';
        $output .= '<td>' . htmlspecialchars($attendee['firstname']) . '</td>';
        $output .= '<td>' . htmlspecialchars($attendee['lastname']) . '</td>';
        $output .= '<td>' . htmlspecialchars($attendee['contact']) . '</td>';
        $output .= '<td>' . htmlspecialchars($attendee['email']) . '</td>';

        // Day 1 Attendance Button
        $day1PresentClass = $attendee['present_day1'] == 1 ? 'btn-success' : 'btn-outline-success';
        $day1ButtonText = $attendee['present_day1'] == 1 ? 'Unmark Present' : 'Mark Present';

        // Day 2 Attendance Button
        $day2PresentClass = $attendee['present_day2'] == 1 ? 'btn-success' : 'btn-outline-success';
        $day2ButtonText = $attendee['present_day2'] == 1 ? 'Unmark Present' : 'Mark Present';

         $output .= '<td><button class="btn btn-sm mark-present ' . $day1PresentClass . '" data-id="' . htmlspecialchars($attendee['id']) . '" data-day="1">' . $day1ButtonText . '</button></td>';
        $output .= '<td><button class="btn btn-sm mark-present ' . $day2PresentClass . '" data-id="' . htmlspecialchars($attendee['id']) . '" data-day="2">' . $day2ButtonText . '</button></td>';

        $output .= '<td>' . ($attendee['added_during_attendance'] == 1 ? 'Yes' : 'No') . '</td>';
        $output .= '</tr>';
    }
} else {
    $output = '<tr><td colspan="8">No attendees found.</td></tr>';
}

echo $output;
?>
