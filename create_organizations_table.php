<?php
include 'connection2.php';

try {
    $pdo = connect();
    $sql = "CREATE TABLE organizations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        logo_url VARCHAR(500),
        type ENUM('Company', 'NGO', 'Government', 'Other') DEFAULT 'Other',
        sector VARCHAR(255),
        description TEXT,
        email VARCHAR(255),
        phone VARCHAR(50),
        website VARCHAR(255),
        facebook_url VARCHAR(255),
        linkedin_url VARCHAR(255),
        twitter_url VARCHAR(255),
        instagram_url VARCHAR(255),
        address TEXT,
        city VARCHAR(100),
        country VARCHAR(100),
        founded_year YEAR,
        registration_number VARCHAR(100),
        contact_person_name VARCHAR(255),
        contact_person_role VARCHAR(255),
        mission TEXT,
        vision TEXT,
        services TEXT,
        beneficiaries TEXT,
        partnership_interests TEXT,
        is_featured BOOLEAN DEFAULT FALSE,
        status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'organizations' created successfully.";
} catch (PDOException $e) {
    die("Could not create table: " . $e->getMessage());
} finally {
    closeConnection($pdo);
}
?>