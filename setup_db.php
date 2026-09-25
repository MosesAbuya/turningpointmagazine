<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Mock HTTP_HOST if run via CLI
if (php_sapi_name() === 'cli' && !isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
require_once __DIR__ . '/connection2.php';

try {
    $pdo = connect();
    
    echo "Connected to database successfully.<br>\n";

    // 1. Create team_members table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `team_members` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `role` VARCHAR(255) NOT NULL,
        `group_name` VARCHAR(255) NOT NULL DEFAULT 'Management Team',
        `summary` TEXT,
        `full_info` TEXT,
        `image` VARCHAR(255),
        `order_index` INT(11) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Created `team_members` table.<br>\n";

    // 2. Create product_sizes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_sizes` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `size_name` VARCHAR(50) NOT NULL,
        `order_index` INT(11) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    // Insert default sizes if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM `product_sizes`");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `product_sizes` (`size_name`, `order_index`) VALUES 
            ('Small', 1), ('Medium', 2), ('Large', 3), ('XL', 4), ('XXL', 5),
            ('Child (4 Years)', 6), ('Child (6 Years)', 7), ('Child (8 Years)', 8)");
    }
    echo "Created `product_sizes` table.<br>\n";

    // 3. Alter products to add has_sizes
    try {
        $pdo->exec("ALTER TABLE `products` ADD COLUMN `has_sizes` TINYINT(1) DEFAULT 0;");
        echo "Added `has_sizes` column to `products`.<br>\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "`has_sizes` column already exists in `products`.<br>\n";
        } else {
            throw $e;
        }
    }

    // 4. Alter order_items to add size
    try {
        $pdo->exec("ALTER TABLE `order_items` ADD COLUMN `size` VARCHAR(50) NULL DEFAULT NULL;");
        echo "Added `size` column to `order_items`.<br>\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "`size` column already exists in `order_items`.<br>\n";
        } else {
            throw $e;
        }
    }

    // 5. Create shop_settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `shop_settings` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 150.00,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM `shop_settings`");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `shop_settings` (`delivery_fee`) VALUES (150.00)");
    }
    echo "Created `shop_settings` table.<br>\n";

    echo "<br><b>Database schema migration completed successfully!</b>";

} catch (Exception $e) {
    echo "<br><b>Error:</b> " . $e->getMessage();
}
?>
