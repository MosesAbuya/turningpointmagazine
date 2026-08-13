<?php 
include 'connection2.php';
session_start();


include 'consent.php';
// Create a database connection
$pdo = connect();

// Fetch all blog entries
$query = "SELECT * FROM blog";
$stmt = $pdo->query($query);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    .card {
        margin-bottom: 20px;
        padding: 10px;
        box-shadow: 0 2px 4px black;
    }

    .card-title {
        font-size: 1.5rem;
        color: red;
        font-weight: 600;
    }

    .btn {
        margin: 5px;
    }

    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .underline {
        text-decoration: underline;
        font-size: 2rem;
        font-weight: 700;
        color: red;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="text-center underline">Blog Management Dashboard</h2>
        <div class="sep"></div>

        <div class="text-right mb-3">
            <a href="add_blog.php" class="btn btn-success">Add New Blog</a>
            <button class="btn btn-info" onclick="window.print()">Print Table</button>
        </div>
        <div class="sep"></div>

        <div class="row">
            <?php foreach ($blogs as $blog): ?>
            <div class="col-md-4">
                <div class="card">
                    <img loading="lazy" src="<?= htmlspecialchars($blog['top_photo']) ?>" class="card-img-top" alt="Top Photo">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($blog['title']) ?></h5>
                        <p>Date: <?= htmlspecialchars($blog['date']) ?></p>
                        <a href="edit_blog.php?id=<?= $blog['id'] ?>" class="btn btn-warning">Edit Blog</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

<?php include 'sidebar.php'; ?>

</html>
