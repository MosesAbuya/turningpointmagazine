<?php
if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
        // Detect the subdirectory from the request URI
        $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        // Walk up to find the project root folder name
        $parts = explode('/', trim($script_dir, '/'));
        define('BASE_URL', '/' . $parts[0] . '/');
    } else {
        define('BASE_URL', '/');
    }
}
// connection2.php

require_once 'config.php'; // Include the new configuration file

/**
 * Establishes a database connection using PDO.
 * Credentials are loaded from config.php.
 *
 * @return PDO|null Returns a PDO object on success, null on failure.
 */
function connect()
{
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

/**
 * Closes a PDO database connection.
 *
 * @param PDO $pdo The PDO connection object to close.
 */
function closeConnection(&$pdo)
{
    $pdo = null; // Setting the PDO object to null closes the connection
}

/**
 * Generates a clean, SEO-friendly slug from a string.
 */
function generate_slug($string)
{
    if (empty($string)) return '';
    $s = strtolower(preg_replace('/\s+/', '-', $string));
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);
    $s = preg_replace('/-+/', '-', $s);
    return trim($s, '-');
}
