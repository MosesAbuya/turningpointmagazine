<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include '../connection2.php';
$pdo = connect();
$error = '';
$success = '';

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $delivery_fee = floatval($_POST['delivery_fee']);
    
    // Check if row exists
    $stmt = $pdo->query("SELECT id FROM shop_settings LIMIT 1");
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE shop_settings SET delivery_fee = ?");
        $stmt->execute([$delivery_fee]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO shop_settings (delivery_fee) VALUES (?)");
        $stmt->execute([$delivery_fee]);
    }
    $success = "Shop settings updated successfully.";
}

// Fetch
$delivery_fee = 150;
$stmt = $pdo->query("SELECT delivery_fee FROM shop_settings LIMIT 1");
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $delivery_fee = $row['delivery_fee'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Settings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <h2 class="mb-4 fw-bold">Shop Settings</h2>

        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

        <div class="content-card" style="max-width: 500px;">
            <div class="card-header">General Delivery Fee</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Delivery Fee (Ksh)</label>
                        <input type="number" step="0.01" name="delivery_fee" class="form-control" value="<?= htmlspecialchars($delivery_fee) ?>" required>
                        <small class="text-muted">This flat rate will be added to the customer's total at checkout if they choose Delivery.</small>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>