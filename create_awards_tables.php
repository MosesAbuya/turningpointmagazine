<?php
include 'connection2.php';

try {
    $pdo = connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Table: awards_to_apply
    $sqlAwardsToApply = "
    CREATE TABLE IF NOT EXISTS `awards_to_apply` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `short_description` TEXT,
      `full_description` LONGTEXT,
      `eligibility_criteria` LONGTEXT,
      `application_deadline` DATE,
      `image_url` VARCHAR(255),
      `status` ENUM('Active', 'Archived') DEFAULT 'Active',
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlAwardsToApply);
    echo "Table 'awards_to_apply' created successfully.<br>";

    // Table: personal_awards_won
    $sqlPersonalAwardsWon = "
    CREATE TABLE IF NOT EXISTS `personal_awards_won` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `description` TEXT,
      `image_url` VARCHAR(255),
      `date_awarded` DATE,
      `category` VARCHAR(255) NULL,
      `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlPersonalAwardsWon);
    echo "Table 'personal_awards_won' created successfully.<br>";

    // Table: award_applicants
    $sqlAwardApplicants = "
    CREATE TABLE IF NOT EXISTS `award_applicants` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `award_id` INT,
      `applicant_name` VARCHAR(255) NOT NULL,
      `applicant_email` VARCHAR(255) NOT NULL,
      `applicant_phone` VARCHAR(50) NULL,
      `organization_name` VARCHAR(255) NULL,
      `application_text` LONGTEXT,
      `attachment_url` VARCHAR(255) NULL,
      `status` ENUM('Pending', 'Reviewed', 'Accepted', 'Rejected') DEFAULT 'Pending',
      `applied_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`award_id`) REFERENCES `awards_to_apply`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sqlAwardApplicants);
    echo "Table 'award_applicants' created successfully.<br>";

} catch (PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
} finally {
    closeConnection($pdo);
}
?>