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
    <title>Manage Team - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--brand-teal, #0f4c5c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            position: relative;
            overflow: hidden;
        }
        .avatar::before {
            content: attr(data-initials);
            position: absolute;
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <h2 class="mb-4 fw-bold">Manage Team Members</h2>
        
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#memberModal" onclick="resetForm()">Add New Member</button>

        <div class="content-card">
            <div class="card-body p-0">
                <table class="table table-modern mb-0">
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
                        <?php foreach ($members as $m): 
                            $nameParts = explode(' ', $m['name'] ?? ' ');
                            $initials = strtoupper(substr($m['name'], 0, 1) . substr($nameParts[1] ?? '', 0, 1));
                        ?>
                        <tr>
                            <td>
                                <div class="avatar" data-initials="<?= $initials ?>">
                                    <?php if(!empty($m['image'])): ?>
                                    <img src="../<?= htmlspecialchars($m['image']) ?>" alt="Img" onerror="this.style.display='none'">
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($m['name']) ?></td>
                            <td><?= htmlspecialchars($m['role']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($m['group_name']) ?></span></td>
                            <td><?= $m['order_index'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" data-member="<?= htmlspecialchars(json_encode($m), ENT_QUOTES, 'UTF-8') ?>" onclick="editMember(this)" data-bs-toggle="modal" data-bs-target="#memberModal"><i class="fas fa-edit"></i></button>
                                <a href="?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="memberModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTitle">Add Member</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="member_id">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="member_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role / Title</label>
                        <input type="text" name="role" id="member_role" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Group</label>
                        <select name="group_name" id="member_group" class="form-select" required>
                            <option value="Advisory Council">Advisory Council</option>
                            <option value="Management Team">Management Team</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Order Index</label>
                        <input type="number" name="order_index" id="member_order" class="form-control" value="0">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Short Summary</label>
                        <textarea name="summary" id="member_summary" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Full Info</label>
                        <textarea name="full_info" id="member_full_info" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Image (Upload new to replace)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">Save Member</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function resetForm() {
        document.getElementById('modalTitle').textContent = 'Add Member';
        document.getElementById('member_id').value = '';
        document.getElementById('member_name').value = '';
        document.getElementById('member_role').value = '';
        document.getElementById('member_group').value = 'Management Team';
        document.getElementById('member_order').value = '0';
        document.getElementById('member_summary').value = '';
        document.getElementById('member_full_info').value = '';
    }

    function editMember(btn) {
        let m = JSON.parse(btn.getAttribute('data-member'));
        document.getElementById('modalTitle').textContent = 'Edit Member';
        document.getElementById('member_id').value = m.id;
        document.getElementById('member_name').value = m.name;
        document.getElementById('member_role').value = m.role;
        
        let groupSelect = document.getElementById('member_group');
        let optionExists = false;
        for (let i = 0; i < groupSelect.options.length; i++) {
            if (groupSelect.options[i].value === m.group_name) {
                optionExists = true; break;
            }
        }
        if (!optionExists) {
            let newOption = new Option(m.group_name, m.group_name);
            groupSelect.add(newOption);
        }
        groupSelect.value = m.group_name;
        
        document.getElementById('member_order').value = m.order_index;
        document.getElementById('member_summary').value = m.summary;
        document.getElementById('member_full_info').value = m.full_info;
    }
    </script>
</body>
</html>