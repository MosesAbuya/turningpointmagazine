<?php

// $host = 'localhost';
// $db = 'turning2_turningpoint1';
// $user = 'turning2_turningpoint1';
// $pass = 'Amo20.03'; // Replace with a secure password


$host = 'localhost';
$db = 'salonone_turningpoint';
$user = 'salonone_turningpoint';
$pass = 'Turningpoint@2026'; // Replace with a secure password

function connect() {
    global $host, $db, $user, $pass;

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function closeConnection($pdo) {
    $pdo = null;
}
?>