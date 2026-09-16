<?php
session_start();
include '../connection2.php';

$inactiveTimeout = 600;
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity'])) {
        $timeDiff = time() - $_SESSION['last_activity'];
        if ($timeDiff > $inactiveTimeout) {
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true");
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
} else {
    header("Location: login.php");
    exit();
}

$pdo = connect();

// Fetch dashboard stats
$total_revenue = 0;
$total_orders = 0;
$pending_orders = 0;
$total_products = 0;

try {
    $stmt = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status != 'pending' AND status != 'cancelled'");
    $total_revenue = $stmt->fetchColumn() ?: 0;

    $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
    $total_orders = $stmt->fetchColumn() ?: 0;

    $stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
    $pending_orders = $stmt->fetchColumn() ?: 0;

    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $total_products = $stmt->fetchColumn() ?: 0;

    // Recent orders
    $stmt = $pdo->query("
        SELECT o.id, o.invoice_id, CONCAT(o.first_name, ' ', o.last_name) AS customer_name, 
               o.total_amount, o.status, o.date_created
        FROM orders o
        ORDER BY o.date_created DESC LIMIT 5
    ");
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // silently fail for now if tables don't exist yet
    $recent_orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <h2 class="mb-4 fw-bold">Dashboard Overview</h2>

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-red">
                    <div class="card-body">
                        <h5>Total Revenue</h5>
                        <h2>KES <?= number_format($total_revenue, 2) ?></h2>
                        <i class="fas fa-wallet stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-blue">
                    <div class="card-body">
                        <h5>Total Orders</h5>
                        <h2><?= number_format($total_orders) ?></h2>
                        <i class="fas fa-shopping-bag stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-gold">
                    <div class="card-body text-dark">
                        <h5>Pending Orders</h5>
                        <h2><?= number_format($pending_orders) ?></h2>
                        <i class="fas fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-green">
                    <div class="card-body">
                        <h5>Total Products</h5>
                        <h2><?= number_format($total_products) ?></h2>
                        <i class="fas fa-box stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Chart Area -->
            <div class="col-xl-8 mb-4">
                <div class="content-card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Revenue Trend (This Year)</span>
                        <a href="shop_sales.php" class="btn btn-sm btn-outline-danger" style="border-radius:20px;">View Report</a>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-xl-4 mb-4">
                <div class="content-card h-100">
                    <div class="card-header">Recent Orders</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-modern mb-0" style="font-size: 0.9rem;">
                                <tbody>
                                    <?php if(empty($recent_orders)): ?>
                                        <tr><td class="text-center p-4">No recent orders.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($recent_orders as $order): ?>
                                            <?php
                                            $badge_class = 'badge-soft-secondary';
                                            if ($order['status'] == 'pending') $badge_class = 'badge-soft-warning';
                                            if ($order['status'] == 'paid' || $order['status'] == 'processing') $badge_class = 'badge-soft-info';
                                            if ($order['status'] == 'dispatched') $badge_class = 'badge-soft-primary';
                                            if ($order['status'] == 'delivered' || $order['status'] == 'complete') $badge_class = 'badge-soft-success';
                                            ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <strong><?= htmlspecialchars($order['invoice_id']) ?></strong><br>
                                                    <span class="text-muted" style="font-size: 0.8rem;"><?= htmlspecialchars($order['customer_name']) ?></span>
                                                </td>
                                                <td>KES <?= number_format($order['total_amount'], 2) ?></td>
                                                <td class="pe-4 text-end">
                                                    <span class="badge-soft <?= $badge_class ?>"><?= ucfirst($order['status']) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 text-center border-top">
                            <a href="shop_orders.php" class="text-decoration-none text-danger fw-bold" style="font-size: 0.9rem;">View All Orders <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dummy data for the chart for now, to be populated dynamically later in shop_sales
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Revenue (KES)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                    backgroundColor: 'rgba(232, 0, 61, 0.1)',
                    borderColor: 'rgba(232, 0, 61, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(232, 0, 61, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });
    });
    </script>
</body>
</html>