<?php
$c = file_get_contents('admin/manage_sizes.php');
$htmlPos = strpos($c, '<!DOCTYPE html>');
$phpCode = substr($c, 0, $htmlPos);

$newHtml = <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sizes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <h2 class="mb-2 fw-bold">Manage Product Sizes</h2>
        <p class="text-muted mb-4">These sizes will be shown in the dropdown for products that require size selection.</p>

        <?php if (\$success): ?><div class="alert alert-success"><?= \$success ?></div><?php endif; ?>
        <?php if (\$error): ?><div class="alert alert-danger"><?= \$error ?></div><?php endif; ?>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#sizeModal" onclick="resetForm()">Add New Size</button>

        <div class="content-card" style="max-width: 600px;">
            <div class="card-body p-0">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Size Name</th>
                            <th>Order Index</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (\$sizes as \$s): ?>
                        <tr>
                            <td><?= htmlspecialchars(\$s['size_name']) ?></td>
                            <td><?= \$s['order_index'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" onclick='editSize(<?= json_encode(\$s) ?>)' data-bs-toggle="modal" data-bs-target="#sizeModal"><i class="fas fa-edit"></i></button>
                                <a href="?delete=<?= \$s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sizeModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTitle">Add Size</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="size_id">
                <div class="mb-3">
                    <label class="form-label">Size Name (e.g. Small, Medium, XL, 4 Years)</label>
                    <input type="text" name="size_name" id="size_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Order Index (Lower numbers appear first)</label>
                    <input type="number" name="order_index" id="size_order" class="form-control" value="0">
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">Save Size</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function resetForm() {
        document.getElementById('modalTitle').textContent = 'Add Size';
        document.getElementById('size_id').value = '';
        document.getElementById('size_name').value = '';
        document.getElementById('size_order').value = '0';
    }

    function editSize(s) {
        document.getElementById('modalTitle').textContent = 'Edit Size';
        document.getElementById('size_id').value = s.id;
        document.getElementById('size_name').value = s.size_name;
        document.getElementById('size_order').value = s.order_index;
    }
    </script>
</body>
</html>
EOT;

file_put_contents('admin/manage_sizes.php', $phpCode . $newHtml);
echo "Replaced manage_sizes.php HTML\\n";

$c2 = file_get_contents('admin/shop_settings.php');
$htmlPos2 = strpos($c2, '<!DOCTYPE html>');
$phpCode2 = substr($c2, 0, $htmlPos2);

$newHtml2 = <<<EOT
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

        <?php if (\$success): ?><div class="alert alert-success"><?= \$success ?></div><?php endif; ?>
        <?php if (\$error): ?><div class="alert alert-danger"><?= \$error ?></div><?php endif; ?>

        <div class="content-card" style="max-width: 500px;">
            <div class="card-header">General Delivery Fee</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Delivery Fee (Ksh)</label>
                        <input type="number" step="0.01" name="delivery_fee" class="form-control" value="<?= htmlspecialchars(\$delivery_fee) ?>" required>
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
EOT;

file_put_contents('admin/shop_settings.php', $phpCode2 . $newHtml2);
echo "Replaced shop_settings.php HTML\\n";

?>
