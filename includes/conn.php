<?php

require_once __DIR__ . '/../config.php';

$host = DB_HOST;
$db = DB_NAME;
$user = DB_USER;
$pass = DB_PASS;

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