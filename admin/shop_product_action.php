<?php
include 'connection2.php';
session_start();

$pdo = connect();
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'sync_magazines') {
    try {
        $stmt = $pdo->query("
            SELECT e.id, e.edition_name, e.price, e.front_page_image 
            FROM editions e 
            LEFT JOIN products p ON e.id = p.edition_id 
            WHERE p.id IS NULL
        ");
        $missing_editions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($missing_editions) > 0) {
            $insertStmt = $pdo->prepare("
                INSERT INTO products (code, name, description, current_price, img_path, edition_id) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $added = 0;
            foreach ($missing_editions as $ed) {
                $code = 'MAG-' . time() . '-' . $ed['id'];
                $desc = "Official Turning Point Magazine: " . $ed['edition_name'];
                $imgPath = $ed['front_page_image'] ? $ed['front_page_image'] : 'images/placeholder.jpg';
                $insertStmt->execute([$code, $ed['edition_name'], $desc, $ed['price'], $imgPath, $ed['id']]);
                $added++;
            }
            echo json_encode(["status" => "success", "message" => "$added missing magazine(s) synced to shop successfully!"]);
        } else {
            echo json_encode(["status" => "success", "message" => "All magazines are already in the shop!"]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    closeConnection($pdo);
    exit;
}

if ($action === 'delete_product') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            if ($stmt->execute([$id])) {
                echo json_encode(["status" => "success", "message" => "Product deleted successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete product."]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID."]);
    }
    closeConnection($pdo);
    exit;
}

if ($action === 'edit_product') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = $_POST['current_price'] ?? 0;
    $edition_id = isset($_POST['edition_id']) && $_POST['edition_id'] !== '' ? intval($_POST['edition_id']) : null;
    
    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid product ID."]);
        closeConnection($pdo);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT img_path FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $imgPath = $product['img_path'];

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'upload/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            $fileName = time() . '_' . basename($_FILES['product_image']['name']);
            $targetFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
                $imgPath = $targetFilePath;
            }
        }

        $updateStmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, current_price = ?, img_path = ? WHERE id = ?");
        if ($updateStmt->execute([$name, $desc, $price, $imgPath, $id])) {
            // If it's a magazine, also update the editions table
            if ($edition_id) {
                $edStmt = $pdo->prepare("UPDATE editions SET price = ?, front_page_image = ? WHERE id = ?");
                $edStmt->execute([$price, $imgPath, $edition_id]);
            }
            echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update product."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    closeConnection($pdo);
    exit;
}

if ($action === 'add_product') {
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $price = $_POST['current_price'] ?? 0;
    
    $imgPath = 'images/placeholder.jpg';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'upload/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        $fileName = time() . '_' . basename($_FILES['product_image']['name']);
        $targetFilePath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
            $imgPath = $targetFilePath;
        }
    }

    $code = 'MERCH-' . time();
    try {
        $stmt = $pdo->prepare("INSERT INTO products (code, name, description, current_price, img_path, edition_id) VALUES (?, ?, ?, ?, ?, NULL)");
        if ($stmt->execute([$code, $name, $desc, $price, $imgPath])) {
            echo json_encode(["status" => "success", "message" => "Merchandise added successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add merchandise."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    closeConnection($pdo);
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid action."]);
?>
