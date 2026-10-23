<?php
/**
 * Shop Order Email Helper
 * Turning Point Magazine Africa
 */

require_once __DIR__ . '/mailer.php';

/**
 * Send order confirmation email to customer and notification email to admin.
 *
 * @param PDO $pdo
 * @param int $orderId
 * @return bool
 */
function send_shop_order_confirmation_emails($pdo, $orderId) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            error_log("Shop Email Helper: Order ID {$orderId} not found.");
            return false;
        }

        $email = $order['email'];
        $name = trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''));
        if (empty($name)) {
            $name = 'Valued Customer';
        }
        $invoiceId = $order['invoice_id'];
        $totalAmount = floatval($order['total_amount']);
        $paymentMethod = strtoupper($order['payment_method'] ?? 'PAYSTACK');
        $payRef = $order['paystack_reference'] ?? ($order['paystack_transaction_id'] ?? 'N/A');

        // Fetch Order Items
        $stmtItems = $pdo->prepare("
            SELECT oi.quantity, oi.price, oi.size, p.name 
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmtItems->execute([$orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        $itemsList = "<table style='width:100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 15px;'>
                        <tr style='background:#f4f4f4; text-align: left;'>
                            <th style='padding:10px; border:1px solid #ddd;'>Item</th>
                            <th style='padding:10px; border:1px solid #ddd; text-align:center;'>Qty</th>
                            <th style='padding:10px; border:1px solid #ddd; text-align:right;'>Price</th>
                            <th style='padding:10px; border:1px solid #ddd; text-align:right;'>Total</th>
                        </tr>";

        if ($items) {
            foreach ($items as $item) {
                $itemName = !empty($item['name']) ? htmlspecialchars($item['name']) : 'Turning Point Item';
                if (!empty($item['size'])) {
                    $itemName .= ' (' . htmlspecialchars($item['size']) . ')';
                }
                $itemTotal = floatval($item['price']) * intval($item['quantity']);
                $itemsList .= "<tr>
                    <td style='padding:10px; border:1px solid #ddd;'>{$itemName}</td>
                    <td style='padding:10px; border:1px solid #ddd; text-align:center;'>{$item['quantity']}</td>
                    <td style='padding:10px; border:1px solid #ddd; text-align:right;'>KES " . number_format($item['price'], 2) . "</td>
                    <td style='padding:10px; border:1px solid #ddd; text-align:right;'>KES " . number_format($itemTotal, 2) . "</td>
                </tr>";
            }
        } else {
            $itemsList .= "<tr><td colspan='4' style='padding:10px; border:1px solid #ddd; text-align:center;'>Standard Checkout Item</td></tr>";
        }
        $itemsList .= "</table>";

        // Buyer Email
        $buyerSubject = "Order Confirmed - Invoice " . $invoiceId;
        $buyerBody = "
        <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
            <div style='text-align: center; padding: 20px 0;'>
                <h1 style='color: #e8003d; margin: 0;'>Turning Point Magazine</h1>
                <p style='color: #777; margin: 5px 0 0;'>Official Store Receipt</p>
            </div>
            <div style='background: #fdfdfd; border: 1px solid #eee; border-radius: 8px; padding: 25px;'>
                <h2 style='color: #28a745; margin-top: 0;'>Payment Confirmed! 🎉</h2>
                <p>Hello <strong>" . htmlspecialchars($name) . "</strong>,</p>
                <p>Thank you for your purchase. We have received your payment of <strong>KES " . number_format($totalAmount, 2) . "</strong> via <strong>" . htmlspecialchars($paymentMethod) . "</strong>.</p>
                
                <h3 style='border-bottom: 2px solid #eee; padding-bottom: 8px;'>Order Summary</h3>
                <p><strong>Invoice ID:</strong> " . htmlspecialchars($invoiceId) . "<br>
                   <strong>Payment Reference:</strong> " . htmlspecialchars($payRef) . "<br>
                   <strong>Delivery Option:</strong> " . htmlspecialchars($order['delivery_option'] ?? 'Pick up') . "</p>
                " . (!empty($order['delivery_address']) ? "<p><strong>Delivery Address:</strong><br>" . nl2br(htmlspecialchars($order['delivery_address'])) . "</p>" : "") . "
                
                $itemsList
                
                <p style='font-size: 1.1rem; text-align: right; margin-top: 10px;'><strong>Grand Total: KES " . number_format($totalAmount, 2) . "</strong></p>
                
                <p>Our team is now preparing your order. We will reach out to you directly when it is ready or dispatched.</p>
                <br>
                <p style='color: #777; font-size: 0.9rem;'>If you have any questions, reply to this email or contact us at <a href='mailto:info@turningpointmagazine.africa'>info@turningpointmagazine.africa</a>.</p>
            </div>
        </div>";

        sendGlobalMail($pdo, $email, $name, $buyerSubject, $buyerBody);

        // Admin Email
        $adminSubject = "New Paid Shop Order - " . $invoiceId . " (KES " . number_format($totalAmount, 2) . ")";
        $adminBody = "
        <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;'>
            <div style='background: #0c1a30; color: #fff; padding: 15px 20px; border-radius: 8px 8px 0 0;'>
                <h2 style='margin: 0; color: #fff;'>New Paid Shop Order Alert</h2>
            </div>
            <div style='background: #fff; border: 1px solid #eee; border-radius: 0 0 8px 8px; padding: 25px;'>
                <p>A new payment of <strong>KES " . number_format($totalAmount, 2) . "</strong> was processed via <strong>" . htmlspecialchars($paymentMethod) . "</strong>.</p>
                <p><strong>Customer:</strong> " . htmlspecialchars($name) . " (<a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a>)<br>
                   <strong>Phone:</strong> " . htmlspecialchars($order['contact'] ?? 'N/A') . "<br>
                   <strong>Invoice ID:</strong> " . htmlspecialchars($invoiceId) . "<br>
                   <strong>Paystack / Gateway Reference:</strong> " . htmlspecialchars($payRef) . "<br>
                   <strong>Delivery Option:</strong> " . htmlspecialchars($order['delivery_option'] ?? 'N/A') . "</p>
                " . (!empty($order['delivery_address']) ? "<p><strong>Delivery Address:</strong><br>" . nl2br(htmlspecialchars($order['delivery_address'])) . "</p>" : "") . "
                
                <h3>Purchased Items</h3>
                $itemsList
                
                <p><a href='https://www.turningpointmagazine.africa/admin/shop_order_detail.php?id=" . intval($orderId) . "' style='display:inline-block; padding:10px 20px; background:#e8003d; color:#fff; text-decoration:none; border-radius:4px;'>View in Admin Dashboard</a></p>
            </div>
        </div>";

        sendGlobalMail($pdo, 'info@turningpointmagazine.africa', 'Turning Point Store Admin', $adminSubject, $adminBody);

        return true;
    } catch (Exception $e) {
        error_log("Shop Email Helper Error: " . $e->getMessage());
        return false;
    }
}
