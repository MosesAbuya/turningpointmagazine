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
    $id = $_GET['delete'];
    // Delete image if exists
    $stmt = $pdo->prepare("SELECT image FROM team_members WHERE id = ?");
    $stmt->execute([$id]);
    $team = $stmt->fetch();
    if ($team && !empty($team['image']) && file_exists('../' . $team['image'])) {
        unlink('../' . $team['image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Member deleted successfully.";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $role = $_POST['role'];
    $group_name = $_POST['group_name'];
    $summary = $_POST['summary'];
    $full_info = $_POST['full_info'];
    $order_index = $_POST['order_index'] ?? 0;
    
    $imagePath = '';
    
    // Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "../assets/team/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        
        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imagePath = "assets/team/" . $fileName;
            }
        }
    }

    if (empty($id)) {
        // Add
        if (empty($imagePath)) $imagePath = "assets/team/default.png";
        $stmt = $pdo->prepare("INSERT INTO team_members (name, role, group_name, summary, full_info, image, order_index) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $role, $group_name, $summary, $full_info, $imagePath, $order_index])) {
            $success = "Member added successfully.";
        } else {
            $error = "Failed to add member.";
        }
    } else {
        // Edit
        if (!empty($imagePath)) {
            $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, group_name=?, summary=?, full_info=?, image=?, order_index=? WHERE id=?");
            $stmt->execute([$name, $role, $group_name, $summary, $full_info, $imagePath, $order_index, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE team_members SET name=?, role=?, group_name=?, summary=?, full_info=?, order_index=? WHERE id=?");
            $stmt->execute([$name, $role, $group_name, $summary, $full_info, $order_index, $id]);
        }
        $success = "Member updated successfully.";
    }
}

// Fetch members
$stmt = $pdo->query("SELECT * FROM team_members ORDER BY group_name ASC, order_index ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team - Admin Dashboard</title>
    <link rel="stylesheet" href="../includes/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .sidebar { height: 100vh; position: fixed; width: 250px; background-color: #343a40; }
        .main-content { margin-left: 250px; padding: 20px; }
        .member-img { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2>Manage Team Members</h2>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#memberModal" onclick="resetForm()">Add New Member</button>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Role</th>
                <th>Group</th>
                <th>Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $m): ?>
            <tr>
                <td><img src="../<?= $m['image'] ?>" class="member-img" onerror="this.src='../assets/team/default.png'"></td>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['role']) ?></td>
                <td><?= htmlspecialchars($m['group_name']) ?></td>
                <td><?= $m['order_index'] ?></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick='editMember(<?= json_encode($m) ?>)' data-toggle="modal" data-target="#memberModal"><i class="fas fa-edit"></i></button>
                    <a href="?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="memberModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Member</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" id="member_id">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="member_name" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Role / Title</label>
                    <input type="text" name="role" id="member_role" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Group (e.g., Advisory Council, Management Team)</label>
                    <input type="text" name="group_name" id="member_group" class="form-control" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>Order Index (0 for first)</label>
                    <input type="number" name="order_index" id="member_order" class="form-control" value="0">
                </div>
                <div class="col-md-12 form-group">
                    <label>Short Summary (appears on card)</label>
                    <textarea name="summary" id="member_summary" class="form-control" rows="2" required></textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label>Full Info (appears when clicked)</label>
                    <textarea name="full_info" id="member_full_info" class="form-control" rows="4"></textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label>Image (Upload new to replace)</label>
                    <input type="file" name="image" class="form-control-file" accept="image/*">
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Member</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../includes/js/jquery.min.js"></script>
<script src="../includes/js/bootstrap.min.js"></script>
<script>
function resetForm() {
    $('#modalTitle').text('Add Member');
    $('#member_id').val('');
    $('#member_name').val('');
    $('#member_role').val('');
    $('#member_group').val('Management Team');
    $('#member_order').val('0');
    $('#member_summary').val('');
    $('#member_full_info').val('');
}

function editMember(m) {
    $('#modalTitle').text('Edit Member');
    $('#member_id').val(m.id);
    $('#member_name').val(m.name);
    $('#member_role').val(m.role);
    $('#member_group').val(m.group_name);
    $('#member_order').val(m.order_index);
    $('#member_summary').val(m.summary);
    $('#member_full_info').val(m.full_info);
}
</script>
</body>
</html>
