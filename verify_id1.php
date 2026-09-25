<?php
$conn = new mysqli('localhost', 'root', '', 'turning2_turningpoint1');
$conn->set_charset("utf8mb4");

$result = $conn->query("SELECT id, name, summary, full_info FROM team_members WHERE id=1");

if ($m = $result->fetch_assoc()) {
    echo "ID: " . $m['id'] . " - " . $m['name'] . "\n";
    echo "  Summary: " . $m['summary'] . "\n";
    echo "  Full Info: " . $m['full_info'] . "\n";
}
?>
