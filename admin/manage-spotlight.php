<?php
include 'connection2.php';
session_start();
include 'consent.php';

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
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="text-center underline">Manage Spotlight Posts</h2>
        <div class="sep"></div>
        <a href="add-spotlight.php" class="btn btn-primary mb-4">Add New Spotlight Post</a>
        <table class="table table-bordered">
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
                        <td><?= htmlspecialchars($post['partner_name']) ?></td>
                        <td><?= htmlspecialchars($post['post_title']) ?></td>
                        <td><?= htmlspecialchars($post['post_type']) ?></td>
                        <td><?= htmlspecialchars($post['post_date']) ?></td>
                        <td>
                            <a href="edit-spotlight.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="deletePost(<?= $post['id'] ?>)">Delete</button>
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

    <script>
    function deletePost(id) {
        if (confirm("Are you sure you want to delete this post?")) {
            // Use AJAX to call a deletion script
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_spotlight_post.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    // Reload the page to show the updated list
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