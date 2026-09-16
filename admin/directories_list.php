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
    
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>


<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
    <div class="container-fluid mt-5">
        <h2 class="fw-bold mb-4">Manage Organizations</h2>
        <div class="sep"></div>
        <a href="directories_add.php" class="btn btn-primary mb-4">Add New Organization</a>
        <div class="table-responsive">
            <table class="table table-modern">
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

        </div>
<script>
    function deleteOrganization(id) {
        Swal.fire({title: 'Are you sure?', text: 'Delete this organization?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {
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

</html>