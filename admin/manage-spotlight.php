<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Fetch all spotlight posts
$query = "SELECT * FROM spotlight_posts ORDER BY post_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$spotlight_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Spotlight Posts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
    <div class="container-fluid mt-5">
        <h2 class="fw-bold mb-4">Manage Spotlight Posts</h2>
        <div class="sep"></div>
        <a href="add-spotlight.php" class="btn btn-primary mb-4">Add New Spotlight Post</a>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Partner Name</th>
                        <th>Post Title</th>
                        <th>Post Type</th>
                        <th>Post Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($spotlight_posts)): ?>
                        <?php foreach ($spotlight_posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['partner_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($post['post_title'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($post['post_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td><?= htmlspecialchars($post['post_date'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <a href="edit-spotlight.php?id=<?= (int)$post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deletePost(<?= (int)$post['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No spotlight posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>

<script>
    function deletePost(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Delete this spotlight post? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e8003d',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_spotlight_post.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE) {
                        if (this.status === 200) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Spotlight post deleted successfully.',
                                icon: 'success',
                                confirmButtonColor: '#e8003d'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: this.responseText || 'Failed to delete post.',
                                icon: 'error',
                                confirmButtonColor: '#e8003d'
                            });
                        }
                    }
                };
                xhr.send("id=" + encodeURIComponent(id));
            }
        });
    }
</script>
</body>
</html>