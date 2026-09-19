<?php
session_start();
include '../connection2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = connect();

// Date range filters
$range = $_GET['range'] ?? 'this_month';
$dateCondition = "";
$params = [];

if ($range === 'this_month') {
    $dateCondition = "MONTH(o.date_created) = MONTH(CURRENT_DATE()) AND YEAR(o.date_created) = YEAR(CURRENT_DATE())";
} elseif ($range === 'last_month') {
    $dateCondition = "MONTH(o.date_created) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(o.date_created) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)";
} elseif ($range === 'this_year') {
    $dateCondition = "YEAR(o.date_created) = YEAR(CURRENT_DATE())";
} else {
    $dateCondition = "1=1"; // all time
}

// Auto-migrate database schema on the live server if cost_price is missing
try {
    $pdo->query("SELECT cost_price FROM products LIMIT 1");
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Unknown column 'cost_price'") !== false) {
        $pdo->exec("ALTER TABLE products ADD COLUMN cost_price DECIMAL(10,2) DEFAULT 0.00 AFTER prev_price");
    }
}

// Fetch KPIs
$kpiQuery = "
    SELECT 
        COUNT(DISTINCT o.id) as total_orders,
        SUM(o.total_amount) as total_revenue,
        SUM(oi.quantity * COALESCE(p.cost_price, 0)) as total_cogs
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE o.status NOT IN ('pending', 'cancelled') AND $dateCondition
";

$stmt = $pdo->prepare($kpiQuery);
$stmt->execute($params);
$kpis = $stmt->fetch(PDO::FETCH_ASSOC);

$total_revenue = $kpis['total_revenue'] ?? 0;
$total_cogs = $kpis['total_cogs'] ?? 0;
$total_orders = $kpis['total_orders'] ?? 0;
$gross_profit = $total_revenue - $total_cogs;
$profit_margin = $total_revenue > 0 ? ($gross_profit / $total_revenue) * 100 : 0;
$avg_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;

// Best Sellers
$bestSellersQuery = "
    SELECT 
        p.name, 
        SUM(oi.quantity) as units_sold, 
        SUM(oi.quantity * oi.price) as revenue,
        SUM(oi.quantity * (oi.price - COALESCE(p.cost_price, 0))) as profit
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    WHERE o.status NOT IN ('pending', 'cancelled') AND $dateCondition
    GROUP BY p.id
    ORDER BY units_sold DESC
    LIMIT 10
";
$stmt = $pdo->prepare($bestSellersQuery);
$stmt->execute($params);
$best_sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales & P&L Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Profit & Loss Dashboard</h2>
            <form class="d-flex" method="GET">
                <select name="range" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="this_month" <?= $range=='this_month'?'selected':'' ?>>This Month</option>
                    <option value="last_month" <?= $range=='last_month'?'selected':'' ?>>Last Month</option>
                    <option value="this_year" <?= $range=='this_year'?'selected':'' ?>>This Year</option>
                    <option value="all_time" <?= $range=='all_time'?'selected':'' ?>>All Time</option>
                </select>
            </form>
        </div>

        <!-- KPI Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-red">
                    <div class="card-body">
                        <h5>Gross Revenue</h5>
                        <h2>KES <?= number_format($total_revenue, 2) ?></h2>
                        <i class="fas fa-money-bill-wave stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-blue">
                    <div class="card-body">
                        <h5>Total COGS</h5>
                        <h2>KES <?= number_format($total_cogs, 2) ?></h2>
                        <i class="fas fa-boxes stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-green">
                    <div class="card-body">
                        <h5>Gross Profit</h5>
                        <h2>KES <?= number_format($gross_profit, 2) ?></h2>
                        <i class="fas fa-chart-line stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card bg-gradient-gold">
                    <div class="card-body text-dark">
                        <h5>Profit Margin</h5>
                        <h2><?= number_format($profit_margin, 1) ?>%</h2>
                        <i class="fas fa-percent stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="content-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h5 class="text-muted text-uppercase fw-bold mb-1" style="font-size:0.8rem">Total Orders</h5>
                            <h3 class="fw-bold mb-0"><?= number_format($total_orders) ?></h3>
                        </div>
                        <div class="fs-1 text-primary opacity-50"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="content-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">
                        <div>
                            <h5 class="text-muted text-uppercase fw-bold mb-1" style="font-size:0.8rem">Average Order Value</h5>
                            <h3 class="fw-bold mb-0">KES <?= number_format($avg_order_value, 2) ?></h3>
                        </div>
                        <div class="fs-1 text-success opacity-50"><i class="fas fa-receipt"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers -->
        <div class="content-card">
            <div class="card-header">Top Selling Products (By Volume)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Units Sold</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Estimated Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($best_sellers)): ?>
                                <tr><td colspan="4" class="text-center p-4">No sales data for this period.</td></tr>
                            <?php else: ?>
                                <?php foreach($best_sellers as $item): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                                        <td class="text-center"><?= number_format($item['units_sold']) ?></td>
                                        <td class="text-end">KES <?= number_format($item['revenue'], 2) ?></td>
                                        <td class="text-end text-success fw-bold">KES <?= number_format($item['profit'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
