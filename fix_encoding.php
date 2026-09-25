<?php
$conn = new mysqli('localhost', 'root', '', 'turning2_turningpoint1');
$conn->set_charset("utf8mb4");

$result = $conn->query("SELECT id, summary, full_info FROM team_members");

while($m = $result->fetch_assoc()) {
    $summary = $m['summary'];
    $full_info = $m['full_info'];
    
    // Replace typical mojibake or U+FFFD with standard apostrophe
    // UTF-8 Replacement Character is \xEF\xBF\xBD
    $summary = str_replace("\xEF\xBF\xBD", "'", $summary);
    $full_info = str_replace("\xEF\xBF\xBD", "'", $full_info);
    
    // Also try replacing single curly quotes just in case
    $summary = str_replace(['’', '‘', '´', '`'], "'", $summary);
    $full_info = str_replace(['’', '‘', '´', '`'], "'", $full_info);
    
    $stmt = $conn->prepare("UPDATE team_members SET summary=?, full_info=? WHERE id=?");
    $stmt->bind_param("ssi", $summary, $full_info, $m['id']);
    $stmt->execute();
}
echo "Fixed encoding issues.\n";
?>
