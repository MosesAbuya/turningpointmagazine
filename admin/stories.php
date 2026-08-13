<?php
include 'connection.php';
session_start();


include 'consent.php';
// Create a database connection
$pdo = connect();

// Fetch all stories
$query = "SELECT * FROM stories";
$stmt = $pdo->query($query);
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stories Management Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    <div class="container mt-5">
        <h2 class="text-center">Stories Management Dashboard</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Story</th>
                        <th>Category</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stories as $story) { 
                        // Determine if the story has images
                        $hasImages = !empty(trim($story['photo']));
                    ?>
                        <tr>
                            <td><?= $story['id']; ?></td>
                            <td><?= htmlspecialchars($story['email']); ?></td>
                            <td><?= htmlspecialchars($story['firstname']); ?></td>
                            <td><?= htmlspecialchars($story['lastname']); ?></td>
                            <td><?= htmlspecialchars($story['story']); ?></td>
                            <td><?= htmlspecialchars($story['category']); ?></td>
                            <td>
                                <?= $hasImages ? '<span class="text-success">Has Images</span>' : '<span class="text-danger">No Images</span>'; ?>
                            </td>
                            <td>
                                <a href="get_story_details.php?id=<?= $story['id']; ?>" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
<?php include 'sidebar.php'; ?>
</html>
