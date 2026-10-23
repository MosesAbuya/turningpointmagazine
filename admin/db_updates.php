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
        `payment_gateway` varchar(50) NOT NULL DEFAULT 'paystack',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "shop_settings table created.<br>";

    // Insert default setting if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM shop_settings");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO shop_settings (id, delivery_fee, payment_gateway) VALUES (1, 150.00, 'paystack')");
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

    // 6. Alter spotlight_posts table to add document column
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM `spotlight_posts` LIKE 'document'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `spotlight_posts` ADD COLUMN `document` varchar(255) DEFAULT NULL AFTER `external_link`");
            echo "Column document added to spotlight_posts.<br>";
        } else {
            echo "Column document already exists in spotlight_posts.<br>";
        }
    } catch (PDOException $e) {
        echo "Error checking spotlight_posts: " . $e->getMessage() . "<br>";
    }

    // 7. Alter shop_settings to add payment_gateway column
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM `shop_settings` LIKE 'payment_gateway'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `shop_settings` ADD COLUMN `payment_gateway` varchar(50) NOT NULL DEFAULT 'paystack' AFTER `delivery_fee`");
            echo "Column payment_gateway added to shop_settings.<br>";
        } else {
            echo "Column payment_gateway already exists in shop_settings.<br>";
        }
    } catch (PDOException $e) {
        echo "Error checking shop_settings: " . $e->getMessage() . "<br>";
    }

    // 8. Alter orders table for paystack support
    try {
        $pdo->exec("ALTER TABLE `orders` MODIFY COLUMN `payment_method` varchar(50) NOT NULL DEFAULT 'PAYSTACK'");
        $cols = [
            'paystack_reference' => "varchar(100) DEFAULT NULL AFTER `invoice_id`",
            'paystack_transaction_id' => "varchar(100) DEFAULT NULL AFTER `paystack_reference`",
            'paystack_channel' => "varchar(50) DEFAULT NULL AFTER `paystack_transaction_id`",
            'paid_at' => "datetime DEFAULT NULL AFTER `paystack_channel`"
        ];
        foreach ($cols as $col => $def) {
            $stmt = $pdo->query("SHOW COLUMNS FROM `orders` LIKE '$col'");
            if ($stmt->rowCount() == 0) {
                $pdo->exec("ALTER TABLE `orders` ADD COLUMN `$col` $def");
                echo "Column $col added to orders.<br>";
            }
        }
    } catch (PDOException $e) {
        echo "Error updating orders table: " . $e->getMessage() . "<br>";
    }

    echo "<h3>Database updates completed successfully!</h3>";

} catch (Exception $e) {
    echo "<h3>Error: " . $e->getMessage() . "</h3>";
}
?>