<?php

require_once '../config.php'; // Include the new configuration file

function connect() {
    try {
        // Use constants from config.php
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Trigger exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Set default fetch mode to associative array
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulated prepared statements
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;

    } catch (PDOException $e) {
        // In a real-world application, you would log this error instead of displaying it
        // For development, die() is okay, but for production, it's better to show a generic error message
        error_log("Database Connection Error: " . $e->getMessage()); // Log the error
        die("Database connection failed. Please try again later."); // Show a user-friendly message
    }
}

function closeConnection($pdo) {
    $pdo = null;
}

?>