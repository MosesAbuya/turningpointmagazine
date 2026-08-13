<?php
$mysqli = new mysqli("localhost", "root", "", "turning2_turningpoint1");
$result = $mysqli->query("SHOW COLUMNS FROM blog");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
