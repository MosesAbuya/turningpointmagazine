<?php
$conn = new mysqli('localhost', 'root', '', 'turning2_turningpoint1');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$result = $conn->query("SELECT id, name, summary, full_info FROM team_members");

while($m = $result->fetch_assoc()) {
    // We check for common mojibake and literal question marks where apostrophes should be
    if (preg_match('/[?â€™’]/', $m['summary']) || preg_match('/[?â€™’]/', $m['full_info'])) {
        echo "ID: " . $m['id'] . " - " . $m['name'] . "\n";
        echo "  Summary: " . substr($m['summary'], 0, 100) . "...\n";
        echo "  Full Info: " . substr($m['full_info'], 0, 100) . "...\n";
        echo "\n";
    }
}
?>
