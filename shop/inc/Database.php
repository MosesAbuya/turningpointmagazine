<?php
// shop/inc/Database.php

require_once __DIR__ . '/../../config.php'; // Include the root configuration file

class Database
{
    public $servername;
    public $username;
    public $password;
    public $dbname;
    public $tablename;
    public $conn; // Changed from $con to $conn for consistency

    // class constructor
    public function __construct(
        $dbname = DB_NAME,
        $tablename = "products",
        $servername = DB_HOST,
        $username = DB_USER,
        $password = DB_PASS
    ) {
        $this->dbname = $dbname;
        $this->tablename = $tablename;
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;

        // create connection
        $this->conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($this->conn->connect_error) { // Use connect_error for better error reporting
            die("Connection failed: " . $this->conn->connect_error);
        }

        // query to create database if not exists
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

        // execute query
        if ($this->conn->query($sql) === TRUE) {
            // Select the database
            $this->conn->select_db($dbname);

            // sql to create new table
            $sql = "CREATE TABLE IF NOT EXISTS `products` (
                `id` int(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `code` varchar(50) NOT NULL,
                `name` text NOT NULL,
                `description` text NOT NULL,
                `prev_price` float(12,2) NOT NULL DEFAULT 0.00,
                `current_price` float(12,2) NOT NULL DEFAULT 0.00,
                `img_path` text NOT NULL,
                `date_created` datetime NOT NULL DEFAULT current_timestamp(),
                `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            )";

            if (!$this->conn->query($sql)) {
                echo "Error creating table: " . $this->conn->error;
            }

            // Insert Default Products if db is empty:
            $check_db_data = $this->conn->query("SELECT `id` FROM `{$this->tablename}`")->num_rows;
            if ($check_db_data <= 0) {
                $insert_sql = "INSERT INTO `{$this->tablename}` (`code`, `name`, `description`, `prev_price`, `current_price`, `img_path`) VALUES
                            ('123456', 'Product 101', 'This is a sample Product 101 description only', 0, 145.23, '../assets/uploads/1.jpg')";
                $this->conn->query($insert_sql);
            }

        } else {
            echo "Error creating database: " . $this->conn->error;
        }
    }

    /**
     * Get products from the database.
     * Uses prepared statements to prevent SQL injection.
     */
    public function getData($pids = [])
    {
        if (empty($pids)) {
            $sql = "SELECT * FROM {$this->tablename}";
            $stmt = $this->conn->prepare($sql);
        } else {
            // Create placeholders for the IN clause
            $placeholders = implode(',', array_fill(0, count($pids), '?'));
            $sql = "SELECT * FROM {$this->tablename} WHERE id IN ({$placeholders})";

            // Prepare the statement
            $stmt = $this->conn->prepare($sql);

            // Bind the parameters
            $types = str_repeat('i', count($pids)); // Assuming IDs are integers
            $stmt->bind_param($types, ...$pids);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        }

        return null; // Return null if no results
    }
}
