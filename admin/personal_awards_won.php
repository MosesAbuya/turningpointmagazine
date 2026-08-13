<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Fetch all personal awards
$query = "SELECT * FROM personal_awards_won ORDER BY date_awarded DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>
<style>
#body {
    background-color: #f8f9fa;
    width: calc(100% - 250px);
    margin-left: 250px;
    margin-top: 100px;
}
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Personal Awards Won</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Manage Personal Awards Won</h2>
        <a href="add_personal_award.php" class="btn btn-primary mb-4">Add New Personal Award</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Awarded</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($awards as $award): ?>
                <tr>
                    <td><?= htmlspecialchars($award['title']) ?></td>
                    <td><?= htmlspecialchars($award['date_awarded']) ?></td>
                    <td><?= htmlspecialchars($award['category']) ?></td>
                    <td>
                        <a href="edit_personal_award.php?id=<?= $award['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_personal_award.php?id=<?= $award['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>