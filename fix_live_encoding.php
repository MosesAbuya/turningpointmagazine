<?php
// Include your live database credentials
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Enforce UTF-8 encoding for the connection
$conn->set_charset("utf8mb4");

$result = $conn->query("SELECT id, summary, full_info FROM team_members");

while($m = $result->fetch_assoc()) {
    $summary = $m['summary'];
    $full_info = $m['full_info'];
    
    // Replace the specific broken character (U+FFFD) with a standard apostrophe
    $summary = str_replace("\xEF\xBF\xBD", "'", $summary);
    $full_info = str_replace("\xEF\xBF\xBD", "'", $full_info);
    
    // Also replace any curly smart quotes just in case
    $summary = str_replace(['’', '‘', '´', '`'], "'", $summary);
    $full_info = str_replace(['’', '‘', '´', '`'], "'", $full_info);
    
    $stmt = $conn->prepare("UPDATE team_members SET summary=?, full_info=? WHERE id=?");
    $stmt->bind_param("ssi", $summary, $full_info, $m['id']);
    $stmt->execute();
}

echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
echo "<h1 style='color: green;'>✅ Success!</h1>";
echo "<h3>All broken apostrophes have been fixed on the live database!</h3>";
echo "<p style='color: red;'><strong>Important:</strong> You can now safely delete this file (fix_live_encoding.php) from your server.</p>";
echo "</div>";
?>
