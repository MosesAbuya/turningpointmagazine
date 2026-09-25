<?php
session_start();
require '../connection2.php';

try {
    $pdo = connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create team_members table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `team_members` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `role` varchar(255) NOT NULL,
        `summary` text NOT NULL,
        `full_info` text NOT NULL,
        `image` varchar(255) NOT NULL,
        `group_name` varchar(100) NOT NULL DEFAULT 'Management Team',
        `order_index` int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "team_members table created.<br>";

    // 2. Create product_sizes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_sizes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `size_name` varchar(100) NOT NULL,
        `order_index` int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "product_sizes table created.<br>";

    // Insert default sizes if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM product_sizes");
    if ($stmt->fetchColumn() == 0) {
        $defaultSizes = [
            'Size Small (S)', 'Size Medium (M)', 'Size Large (L)', 
            'Size Extra Large (XL)', 'Size 2XL - 3XL',
            'Child Size (Age 2-4)', 'Child Size (Age 5-7)', 
            'Child Size (Age 8-10)', 'Child Size (Age 11-14)'
        ];
        $insert = $pdo->prepare("INSERT INTO product_sizes (size_name, order_index) VALUES (?, ?)");
        foreach ($defaultSizes as $index => $size) {
            $insert->execute([$size, $index]);
        }
        echo "Default product sizes inserted.<br>";
    }

    // 3. Create shop_settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `shop_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `delivery_fee` decimal(10,2) NOT NULL DEFAULT 150.00,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "shop_settings table created.<br>";

    // Insert default setting if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM shop_settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO shop_settings (id, delivery_fee) VALUES (1, 150.00)");
        echo "Default shop settings inserted.<br>";
    }

    // 4. Alter products table to add has_sizes
    try {
        $pdo->exec("ALTER TABLE `products` ADD COLUMN `has_sizes` tinyint(1) NOT NULL DEFAULT 0");
        echo "Column has_sizes added to products.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "Column has_sizes already exists.<br>";
        } else {
            throw $e;
        }
    }

    // 5. Alter order_items table to add size
    // Check if order_items exists first
    try {
        $stmt = $pdo->query("SELECT 1 FROM `order_items` LIMIT 1");
        try {
            $pdo->exec("ALTER TABLE `order_items` ADD COLUMN `size` varchar(100) NULL DEFAULT NULL");
            echo "Column size added to order_items.<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "Column size already exists in order_items.<br>";
            } else {
                throw $e;
            }
        }
    } catch (PDOException $e) {
        echo "Table order_items does not exist yet (or other error).<br>";
    }

    echo "<h3>Database updates completed successfully!</h3>";

} catch (Exception $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>
