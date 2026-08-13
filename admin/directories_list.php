<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Fetch all organizations
$query = "SELECT * FROM organizations ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Organizations</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    </style>
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container-fluid mt-5">
        <h2 class="text-center underline">Manage Organizations</h2>
        <div class="sep"></div>
        <a href="directories_add.php" class="btn btn-primary mb-4">Add New Organization</a>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Sector</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($organizations)): ?>
                        <?php foreach ($organizations as $org): ?>
                        <tr>
                            <td><?= htmlspecialchars($org['name']) ?></td>
                            <td><?= htmlspecialchars($org['type']) ?></td>
                            <td><?= htmlspecialchars($org['sector']) ?></td>
                            <td><?= htmlspecialchars($org['status']) ?></td>
                            <td>
                                <a href="directories_edit.php?id=<?= $org['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deleteOrganization(<?= $org['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No organizations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function deleteOrganization(id) {
        if (confirm("Are you sure you want to delete this organization?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_organization.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    location.reload();
                }
            }
            xhr.send("id=" + id);
        }
    }
    </script>
</body>
<?php include 'sidebar.php'; ?>
</html>