<?php 

include 'connection.php';
session_start(); // Start the session

// If the session does not exist, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<?php
// Include the database connection


// Create a database connection
$pdo = connect();

// Fetch all stories from the 'stories' table
$query = "SELECT * FROM stories";
$stmt = $pdo->query($query);
$stories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the list of unique years and months for filtering
$years = [];
$monthsByYear = [];

foreach ($stories as $story) {
    $date = new DateTime($story['date']);
    $year = $date->format('Y');
    $month = $date->format('m');

    if (!in_array($year, $years)) {
        $years[] = $year;
    }

    if (!isset($monthsByYear[$year])) {
        $monthsByYear[$year] = [];
    }

    if (!in_array($month, $monthsByYear[$year])) {
        $monthsByYear[$year][] = $month;
    }
}

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

    .table td.text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table img {
        object-fit: cover;
        border-radius: 4px;
        max-height: 100px;
        width: auto;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table td button {
        margin-right: 5px;
    }

    .unread {
        background-color: #f8d7da;
        font-weight: bold;
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
        <h2 class="text-center">Editions Management Dashboard</h2>

        <div class="text-right mb-3 no-print">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
        </div>
    </div>

    <!-- View Story Modal -->
    <div class="modal fade" id="viewStoryModal" tabindex="-1" aria-labelledby="viewStoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStoryModalLabel">View Story</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="storyDetails">
                        <!-- Story details will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
<?php include 'sidebar.php'; ?>


</html>


<div class="ads">
    <?php foreach ($ads as $ad): ?>
    <?php if ($ad['edition_id'] == $edition['id']): ?>
    <div class="ad">
        <h6>Ad Company: <?= $ad['ad_company_name'] ?></h6>
        <p>Catch Phrase: <?= $ad['catch_phrase'] ?></p>
        <img loading="lazy" src="<?= $ad['ad_banner_image'] ?>" alt="Ad Banner" class="img-fluid">
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>

<p class="card-text">Back Page: <img loading="lazy" src="<?= $edition['back_page_image'] ?>" alt="Back Page" class="img-fluid"></p>
