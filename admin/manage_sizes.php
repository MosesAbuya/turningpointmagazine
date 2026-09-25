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

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM product_sizes WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Size deleted successfully.";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $size_name = $_POST['size_name'];
    $order_index = $_POST['order_index'] ?? 0;
    
    if (empty($id)) {
        // Add
        $stmt = $pdo->prepare("INSERT INTO product_sizes (size_name, order_index) VALUES (?, ?)");
        if ($stmt->execute([$size_name, $order_index])) {
            $success = "Size added successfully.";
        } else {
            $error = "Failed to add size.";
        }
    } else {
        // Edit
        $stmt = $pdo->prepare("UPDATE product_sizes SET size_name=?, order_index=? WHERE id=?");
        if ($stmt->execute([$size_name, $order_index, $id])) {
            $success = "Size updated successfully.";
        } else {
            $error = "Failed to update size.";
        }
    }
}

// Fetch sizes
$stmt = $pdo->query("SELECT * FROM product_sizes ORDER BY order_index ASC");
$sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sizes - Admin Dashboard</title>
    <link rel="stylesheet" href="../includes/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .sidebar { height: 100vh; position: fixed; width: 250px; background-color: #343a40; }
        .main-content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Manage Product Sizes</h2>
    <p class="text-muted">These sizes will be shown in the dropdown for products that require size selection.</p>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#sizeModal" onclick="resetForm()">Add New Size</button>

    <table class="table table-bordered table-striped" style="max-width: 600px;">
        <thead>
            <tr>
                <th>Size Name</th>
                <th>Order Index</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sizes as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['size_name']) ?></td>
                <td><?= $s['order_index'] ?></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick='editSize(<?= json_encode($s) ?>)' data-toggle="modal" data-target="#sizeModal"><i class="fas fa-edit"></i></button>
                    <a href="?delete=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="sizeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Size</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" id="size_id">
            <div class="form-group">
                <label>Size Name (e.g. Small, Medium, Large, XL, 4 Years)</label>
                <input type="text" name="size_name" id="size_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Order Index (Lower numbers appear first)</label>
                <input type="number" name="order_index" id="size_order" class="form-control" value="0">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Size</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../includes/js/jquery.min.js"></script>
<script src="../includes/js/bootstrap.min.js"></script>
<script>
function resetForm() {
    $('#modalTitle').text('Add Size');
    $('#size_id').val('');
    $('#size_name').val('');
    $('#size_order').val('0');
}

function editSize(s) {
    $('#modalTitle').text('Edit Size');
    $('#size_id').val(s.id);
    $('#size_name').val(s.size_name);
    $('#size_order').val(s.order_index);
}
</script>
</body>
</html>
