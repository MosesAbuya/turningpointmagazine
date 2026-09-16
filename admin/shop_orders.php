<?php
session_start();
include '../connection2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = connect();

$filter = $_GET['filter'] ?? 'all';
$query = "SELECT id, invoice_id, first_name, last_name, contact, total_amount, status, date_created FROM orders";
$params = [];

if ($filter !== 'all') {
    $query .= " WHERE status = ?";
    $params[] = $filter;
}
$query .= " ORDER BY date_created DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Order Management</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-danger d-none" id="bulkDeleteBtn"><i class="fas fa-trash"></i> Delete Selected</button>
                <div class="btn-group">
                    <a href="?filter=all" class="btn btn-sm <?= $filter == 'all' ? 'btn-danger' : 'btn-outline-secondary' ?>">All</a>
                    <a href="?filter=pending" class="btn btn-sm <?= $filter == 'pending' ? 'btn-danger' : 'btn-outline-secondary' ?>">Pending</a>
                    <a href="?filter=processing" class="btn btn-sm <?= $filter == 'processing' ? 'btn-danger' : 'btn-outline-secondary' ?>">Processing</a>
                    <a href="?filter=dispatched" class="btn btn-sm <?= $filter == 'dispatched' ? 'btn-danger' : 'btn-outline-secondary' ?>">Dispatched</a>
                    <a href="?filter=delivered" class="btn btn-sm <?= $filter == 'delivered' ? 'btn-danger' : 'btn-outline-secondary' ?>">Delivered</a>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr><td colspan="8" class="text-center p-4">No orders found.</td></tr>
                            <?php else: ?>
                                <?php foreach($orders as $o): ?>
                                    <tr>
                                        <td><input type="checkbox" class="row-checkbox" value="<?= $o['id'] ?>"></td>
                                        <td><strong><?= htmlspecialchars($o['invoice_id']) ?></strong></td>
                                        <td><?= htmlspecialchars($o['first_name'] . ' ' . $o['last_name']) ?></td>
                                        <td><?= htmlspecialchars($o['contact']) ?></td>
                                        <td>KES <?= number_format($o['total_amount'], 2) ?></td>
                                        <td><span class="badge-soft <?= getBadgeClass($o['status']) ?>"><?= ucfirst($o['status']) ?></span></td>
                                        <td><?= date('d M Y, h:i A', strtotime($o['date_created'])) ?></td>
                                        <td>
                                            <a href="shop_order_detail.php?id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Manage</a>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $o['id'] ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        function toggleBulkDelete() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                toggleBulkDelete();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkDelete);
        });

        // Bulk Delete
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                Swal.fire({title: 'Are you sure?', text: 'Delete selected orders?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => {
                    if (result.isConfirmed) {
                        const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                        $.post('shop_order_action.php', { action: 'bulk_delete', ids: ids }, function(res) {
                            try {
                                res = typeof res === 'string' ? JSON.parse(res) : res;
                                if (res.status === 'success') {
                                    Swal.fire({title: 'Deleted!', text: 'Orders deleted successfully.', icon: 'success', confirmButtonColor: '#e8003d'}).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', res.message || 'Failed to delete orders.', 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Server returned: ' + String(res), 'error');
                            }
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to delete orders.', 'error');
                        });
                    }
                });
            });
        }

        // Single Delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = btn.getAttribute('data-id');
                Swal.fire({title: 'Are you sure?', text: 'Delete this order?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => {
                    if (result.isConfirmed) {
                        $.post('shop_order_action.php', { action: 'delete', order_id: id }, function(res) {
                            try {
                                res = typeof res === 'string' ? JSON.parse(res) : res;
                                if (res.status === 'success') {
                                    Swal.fire({title: 'Deleted!', text: 'Order deleted successfully.', icon: 'success', confirmButtonColor: '#e8003d'}).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', res.message || 'Failed to delete order.', 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Server returned: ' + String(res), 'error');
                            }
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to delete order.', 'error');
                        });
                    }
                });
            });
        });
    });
    </script>
</body>
</html>
