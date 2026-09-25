<?php
require_once 'config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT id, name, summary, full_info FROM team_members");

while($m = $result->fetch_assoc()) {
    $hasIssue = false;
    // We check for common mojibake and literal question marks where apostrophes should be
    if (preg_match('/[\?â€™’]/', $m['summary']) || preg_match('/[\?â€™’]/', $m['full_info'])) {
        echo "ID: " . $m['id'] . " - " . $m['name'] . "\n";
        echo "  Summary: " . substr($m['summary'], 0, 100) . "...\n";
        echo "  Full Info: " . substr($m['full_info'], 0, 100) . "...\n";
        echo "\n";
    }
}
?>
