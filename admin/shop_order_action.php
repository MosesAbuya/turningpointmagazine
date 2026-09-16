<?php
include 'connection2.php';
require_once '../includes/mailer.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$pdo = connect();
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'update_status') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $status = $_POST['status'] ?? '';
    $notes = $_POST['notes'] ?? '';
    
    if ($order_id <= 0 || empty($status)) {
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
        exit;
    }

    try {
        $updateFields = ["status = ?", "notes = ?"];
        $params = [$status, $notes];

        if ($status === 'dispatched') {
            $updateFields[] = "dispatched_at = NOW()";
        } elseif ($status === 'delivered') {
            $updateFields[] = "delivered_at = NOW()";
        }

        $query = "UPDATE orders SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $params[] = $order_id;

        $stmt = $pdo->prepare($query);
        if ($stmt->execute($params)) {
            // If status is dispatched, send email
            if ($status === 'dispatched') {
                $orderStmt = $pdo->prepare("SELECT invoice_id, first_name, last_name, email, tracking_number FROM orders WHERE id = ?");
                $orderStmt->execute([$order_id]);
                $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

                if ($order && !empty($order['email'])) {
                    $tracking = $order['tracking_number'] ? "<p><strong>Tracking Number:</strong> {$order['tracking_number']}</p>" : "";
                    $subject = "Your Order has been Dispatched! 🚚";
                    $body = "
                        <h2>Good News!</h2>
                        <p>Dear {$order['first_name']},</p>
                        <p>Your order (Invoice: <strong>{$order['invoice_id']}</strong>) has been dispatched and is on its way to you.</p>
                        $tracking
                        <p>Thank you for shopping with Turning Point Magazine!</p>
                    ";
                    sendGlobalMail($pdo, $order['email'], $order['first_name'], $subject, $body);
                }
            }

            echo json_encode(["status" => "success", "message" => "Order updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update order"]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} elseif ($action === 'update_tracking') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $tracking_number = $_POST['tracking_number'] ?? '';
    
    if ($order_id > 0) {
        $stmt = $pdo->prepare("UPDATE orders SET tracking_number = ? WHERE id = ?");
        if ($stmt->execute([$tracking_number, $order_id])) {
            echo json_encode(["status" => "success", "message" => "Tracking number updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error"]);
        }
    }
} elseif ($action === 'delete') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    if ($order_id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            if ($stmt->execute([$order_id])) {
                echo json_encode(["status" => "success", "message" => "Order deleted"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete order"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    }
} elseif ($action === 'bulk_delete') {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
    if (!empty($ids) && is_array($ids)) {
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id IN ($placeholders)");
            if ($stmt->execute($ids)) {
                echo json_encode(["status" => "success", "message" => "Orders deleted"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete orders"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No IDs provided"]);
    }
}
?>
