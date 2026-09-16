<?php
session_start();
include '../connection2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: shop_orders.php");
    exit();
}

$pdo = connect();
$order_id = intval($_GET['id']);

// Fetch Order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found.");
}

// Fetch Items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.img_path 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getBadgeClass($status) {
    switch ($status) {
        case 'pending': return 'badge-soft-warning';
        case 'paid': case 'processing': return 'badge-soft-info';
        case 'dispatched': return 'badge-soft-primary';
        case 'delivered': case 'complete': return 'badge-soft-success';
        case 'cancelled': return 'badge-soft-danger';
        default: return 'badge-soft-secondary';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail - <?= htmlspecialchars($order['invoice_id']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Order: <?= htmlspecialchars($order['invoice_id']) ?></h2>
            <a href="shop_orders.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Orders</a>
        </div>

        <div class="row">
            <!-- Left Column: Details -->
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header">Customer & Delivery Info</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>Name:</strong> <?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($order['email']) ?><br>
                                <strong>Phone:</strong> <?= htmlspecialchars($order['contact']) ?>
                            </div>
                            <div class="col-sm-6">
                                <strong>Delivery Option:</strong> <?= htmlspecialchars($order['delivery_option']) ?><br>
                                <strong>Address/Location:</strong> <?= nl2br(htmlspecialchars($order['delivery_address'])) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header">Order Items</div>
                    <div class="card-body p-0">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($items as $item): ?>
                                <tr>
                                    <td>
                                        <img src="../<?= htmlspecialchars($item['img_path']) ?>" alt="Img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>KES <?= number_format($item['price'], 2) ?></td>
                                    <td><strong>KES <?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Tracking -->
            <div class="col-lg-4">
                <div class="content-card">
                    <div class="card-header">Order Status</div>
                    <div class="card-body">
                        <h4 class="mb-3"><span class="badge-soft <?= getBadgeClass($order['status']) ?> fs-5"><?= ucfirst($order['status']) ?></span></h4>
                        
                        <form id="updateStatusForm">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Update Status:</label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
                                    <option value="paid" <?= $order['status']=='paid'?'selected':'' ?>>Paid</option>
                                    <option value="processing" <?= $order['status']=='processing'?'selected':'' ?>>Processing</option>
                                    <option value="dispatched" <?= $order['status']=='dispatched'?'selected':'' ?>>Dispatched</option>
                                    <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Delivered</option>
                                    <option value="cancelled" <?= $order['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted fw-bold">Internal Notes:</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Add tracking notes, etc..."><?= htmlspecialchars($order['notes'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger w-100">Update Order</button>
                        </form>
                    </div>
                </div>

                <div class="content-card mt-4">
                    <div class="card-header">Tracking Info</div>
                    <div class="card-body">
                        <form id="updateTrackingForm">
                            <input type="hidden" name="action" value="update_tracking">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <div class="input-group">
                                <input type="text" name="tracking_number" class="form-control" placeholder="Enter tracking #" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>">
                                <button class="btn btn-outline-danger" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="content-card mt-4">
                    <div class="card-body">
                        <p class="mb-1 text-muted"><strong>Created At:</strong> <?= date('d M Y, h:i A', strtotime($order['date_created'])) ?></p>
                        <?php if($order['dispatched_at']): ?>
                            <p class="mb-1 text-muted"><strong>Dispatched:</strong> <?= date('d M Y, h:i A', strtotime($order['dispatched_at'])) ?></p>
                        <?php endif; ?>
                        <?php if($order['delivered_at']): ?>
                            <p class="mb-0 text-muted"><strong>Delivered:</strong> <?= date('d M Y, h:i A', strtotime($order['delivered_at'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#updateStatusForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var statusVal = form.find('select[name="status"]').val();
            
            var msg = "Are you sure you want to update this order?";
            if (statusVal === 'dispatched') {
                msg = "Marking as dispatched will send an email notification to the customer. Continue?";
            }

            Swal.fire({
                title: 'Confirm Update',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e8003d',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('shop_order_action', form.serialize(), function(res) {
                        var response = JSON.parse(res);
                        if(response.status === 'success') {
                            Swal.fire('Updated!', response.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    });
                }
            });
        });

        $('#updateTrackingForm').submit(function(e) {
            e.preventDefault();
            $.post('shop_order_action', $(this).serialize(), function(res) {
                var response = JSON.parse(res);
                if(response.status === 'success') {
                    Swal.fire({icon:'success', title:'Saved', toast:true, position:'top-end', showConfirmButton:false, timer:3000});
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            });
        });
    });
    </script>
</body>
</html>
