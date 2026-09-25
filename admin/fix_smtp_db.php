<?php
session_start();
include '../connection2.php';

try {
    $pdo = connect();
    $host = 'cs1.hpcnoc.com';
    $port = 465;
    $username = 'info@turningpointmagazine.africa';
    $password = 'Turningpoint@2026';
    $encryption = 'ssl';
    $from_email = 'info@turningpointmagazine.africa';
    $from_name = 'Turning Point Magazine';

    $stmt = $pdo->prepare("UPDATE smtp_settings SET host = ?, port = ?, username = ?, password = ?, encryption = ?, from_email = ?, from_name = ? WHERE id = 1");
    $result = $stmt->execute([$host, $port, $username, $password, $encryption, $from_email, $from_name]);

    if ($result) {
        echo "<h2 style='color:green'>Success! SMTP Settings have been updated in the database.</h2>";
        echo "<p>Host: $host</p>";
        echo "<p>Port: $port</p>";
        echo "<p>Encryption: $encryption</p>";
        echo "<p>Please go back to your dashboard and click <b>Send Test</b> to verify.</p>";
    } else {
        echo "<h2 style='color:red'>Failed to update settings.</h2>";
    }
} catch (Exception $e) {
    echo "<h2>Error: " . $e->getMessage() . "</h2>";
}
?>
