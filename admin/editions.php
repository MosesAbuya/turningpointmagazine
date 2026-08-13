<?php 
include 'connection2.php';
session_start(); // Start the session


include 'consent.php';
// Create a database connection
$pdo = connect();

// Fetch all editions from the 'editions' table
$query = "SELECT * FROM editions";
$stmt = $pdo->query($query);
$editions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all ads for each edition
$adsQuery = "SELECT * FROM ads";
$adsStmt = $pdo->query($adsQuery);
$ads = $adsStmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editions Management Dashboard</title>
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

    .no-print {
        border-bottom: 3px solid black;
    }

    .card-title {
        font-size: 2rem;
        color: red;
        font-weight: 600;
    }

    .card-text {
        font-size: 1rem;
        color: black;
        font-weight: 400;
    }

    .btn {
        margin: 10px;
        margin-left: 0;
        padding: 5px;
        align-self: center;
        font-size: small;
    }

    .card-img-top {
        width: 100%;
        height: auto;
    }

    .edition-card img {
        object-fit: cover;
        border-radius: 4px;
        height: auto;
        width: 100%;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .underline {
        text-decoration: underline;
        font-size: 3rem;
        font-weight: 700;
        color: red;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>
<?php include 'nav.php' ?>

<body id="body">

    <div class="container mt-5">
        <h2 class="text-center underline">Editions Management Dashboard</h2>

        <div class="text-right mb-3 no-print">
            <a href="edition.php" class="btn btn-success">Add New Edition</a>
            <button class="btn btn-info" onclick="window.print()">Print Table</button>
        </div>

        <div class="row">
            <?php foreach ($editions as $edition): ?>
            <div class="col-md-4">
                <div class="card edition-card">
                    <img loading="lazy" src="<?= $edition['front_page_image'] ?>" class="card-img-top" alt="Edition Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= $edition['edition_name'] ?></h5>
                        <p class="card-text">Date: <?= $edition['date'] ?></p>

                        <a href="edit_edition.php?id=<?= $edition['id'] ?>" class="btn btn-warning">Edit Edition</a>
                        <a href="articles.php?edition_id=<?= $edition['id'] ?>" class="btn btn-primary">Manage
                            Stories</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<?php include 'sidebar.php'; ?>

</html>
