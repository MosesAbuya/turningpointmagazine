<?php
require_once 'e:\xampp\htdocs\turningpoint\connection2.php';
$pdo = connect();
$stmt = $pdo->query("SELECT id, name, summary, full_info FROM team_members");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($members as $m) {
    // Check for weird characters
    if (strpos($m['summary'], '') !== false || strpos($m['full_info'], '') !== false || 
        strpos($m['summary'], '') !== false || strpos($m['full_info'], '') !== false ||
        strpos($m['summary'], 'â€™') !== false || strpos($m['full_info'], 'â€™') !== false ||
        strpos($m['summary'], '’') !== false || strpos($m['full_info'], '’') !== false ||
        strpos($m['summary'], '?') !== false || strpos($m['full_info'], '?') !== false) {
        
        echo "ID: " . $m['id'] . " - " . $m['name'] . "\n";
        if (strpos($m['summary'], '') !== false || strpos($m['summary'], 'â€™') !== false || strpos($m['summary'], '’') !== false || strpos($m['summary'], '?') !== false) {
            echo "  Summary: " . substr($m['summary'], 0, 100) . "...\n";
        }
        if (strpos($m['full_info'], '') !== false || strpos($m['full_info'], 'â€™') !== false || strpos($m['full_info'], '’') !== false || strpos($m['full_info'], '?') !== false) {
            echo "  Full Info: " . substr($m['full_info'], 0, 100) . "...\n";
        }
        echo "\n";
    }
}
?>
