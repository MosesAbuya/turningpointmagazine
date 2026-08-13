<?php
include 'connection2.php';

try {
    $pdo = connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 1: Rename the existing 'image_url' column to 'thumbnail_url'
    // We check if the old column exists before trying to rename it.
    $checkColumnStmt = $pdo->query("SHOW COLUMNS FROM `personal_awards_won` LIKE 'image_url'");
    if ($checkColumnStmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE `personal_awards_won` CHANGE `image_url` `thumbnail_url` VARCHAR(255);");
        echo "Column 'image_url' was successfully renamed to 'thumbnail_url'.<br>";
    } else {
        // If 'image_url' doesn't exist, maybe it was already renamed. Check for 'thumbnail_url'.
        $checkThumbnailStmt = $pdo->query("SHOW COLUMNS FROM `personal_awards_won` LIKE 'thumbnail_url'");
        if ($checkThumbnailStmt->rowCount() == 0) {
             // If neither exist, add the thumbnail column.
            $pdo->exec("ALTER TABLE `personal_awards_won` ADD `thumbnail_url` VARCHAR(255) NULL AFTER `description`;");
            echo "Column 'thumbnail_url' was added as it did not previously exist.<br>";
        } else {
            echo "Column 'thumbnail_url' already exists. No changes made to it.<br>";
        }
    }

    // Step 2: Add the new 'gallery_images' column if it doesn't already exist
    $checkGalleryStmt = $pdo->query("SHOW COLUMNS FROM `personal_awards_won` LIKE 'gallery_images'");
    if ($checkGalleryStmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `personal_awards_won` ADD `gallery_images` TEXT NULL AFTER `thumbnail_url`;");
        echo "Column 'gallery_images' was successfully added.<br>";
    } else {
        echo "Column 'gallery_images' already exists. No changes made.<br>";
    }

    echo "Database schema update is complete.";

} catch (PDOException $e) {
    die("Error updating table: " . $e->getMessage());
} finally {
    closeConnection($pdo);
}
?>
