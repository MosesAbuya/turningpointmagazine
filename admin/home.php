<?php
session_start(); // Start the session


// Set the inactivity timeout (in seconds)
$inactiveTimeout = 600; // 10 minutes = 600 seconds

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Check if the last activity timestamp is set
    if (isset($_SESSION['last_activity'])) {
        // Calculate the time difference between the last activity and now
        $timeDiff = time() - $_SESSION['last_activity'];

        // Check if the inactivity timeout has been exceeded
        if ($timeDiff > $inactiveTimeout) {
            // Destroy the session and redirect to the login page
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=true"); // Redirect with timeout message
            exit();
        }
    }

    // Update the last activity timestamp
    $_SESSION['last_activity'] = time();
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Additional Custom Styles */
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    .navbar {
        background-color: #343a40;
    }

    .navbar-brand {
        color: #fff;
    }

    .card {
        color: white;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-weight: bold;
    }

    .card-container {
        margin-top: 50px;
    }

    /* Custom Card Colors */
    .card-1 {
        background-color: #007bff;
        /* Blue */
    }

    .card-2 {
        background-color: #28a745;
        /* Green */
    }

    .card-3 {
        background-color: #ffc107;
        /* Yellow */
        color: black;
    }

    .card-4 {
        background-color: #dc3545;
        /* Red */
    }

    .card-5 {
        background-color: #6f42c1;
        /* Purple */
    }

    .card-6 {
        background-color: rgb(11, 7, 255);
        /* Yellow */
        color: black;
    }

    .card-7 {
        background-color: rgb(53, 220, 201);
        /* Red */
    }

    .card-8 {
        background-color: rgb(189, 66, 193);
        /* Purple */
    }
    </style>
</head>

<?php include 'nav.php' ?>

<body id="body">


    <!-- Navbar -->


    <!-- Card Section -->
    <div class="container card-container">
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-4">
                <a href="subscribers.php" class="text-decoration-none text-white">
                    <div class="card card-8">
                        <div class="card-body text-center">
                            <h5 class="card-title">Subscribers</h5>
                            <p class="card-text">Manage Subscribers</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2 -->
            <div class="col-md-4">
                <a href="attendees.php" class="text-decoration-none text-white">
                    <div class="card card-2">
                        <div class="card-body text-center">
                            <h5 class="card-title">Attendees Management</h5>
                            <p class="card-text">Manage Attendees</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3 -->
            <div class="col-md-4">
                <a href="feedback.php" class="text-decoration-none text-dark">
                    <div class="card card-3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Feedback</h5>
                            <p class="card-text">Manage Feedback</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4 -->
            <div class="col-md-4">
                <a href="editions.php" class="text-decoration-none text-white">
                    <div class="card card-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Editions</h5>
                            <p class="card-text">Manage Editions</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 5 -->
            <div class="col-md-4">
                <a href="stories.php" class="text-decoration-none text-white">
                    <div class="card card-5">
                        <div class="card-body text-center">
                            <h5 class="card-title">Stories</h5>
                            <p class="card-text">Manage stories</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="index.php" class="text-decoration-none text-white">
                    <div class="card card-6">
                        <div class="card-body text-center">
                            <h5 class="card-title">Blog</h5>
                            <p class="card-text">Manage Blog Stories</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="category.php" class="text-decoration-none text-white">
                    <div class="card card-7">
                        <div class="card-body text-center">
                            <h5 class="card-title">Story Categories</h5>
                            <p class="card-text">Manage Categories</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="directories_list.php" class="text-decoration-none text-white">
                    <div class="card card-6">
                        <div class="card-body text-center">
                            <h5 class="card-title">Directories</h5>
                            <p class="card-text">Manage Directories</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="manage-spotlight.php" class="text-decoration-none text-white">
                    <div class="card card-5">
                        <div class="card-body text-center">
                            <h5 class="card-title">Spotlight</h5>
                            <p class="card-text">Manage Spotlight</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="booking.php" class="text-decoration-none text-white">
                    <div class="card card-1">
                        <div class="card-body text-center">
                            <h5 class="card-title">Booths Management</h5>
                            <p class="card-text">Manage Booths</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="awards_to_apply.php" class="text-decoration-none text-white">
                    <div class="card card-6">
                        <div class="card-body text-center">
                            <h5 class="card-title">Awards to Apply For</h5>
                            <p class="card-text">Manage Awards</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="personal_awards_won.php" class="text-decoration-none text-white">
                    <div class="card card-7">
                        <div class="card-body text-center">
                            <h5 class="card-title">Personal Awards Won</h5>
                            <p class="card-text">Manage Personal Awards</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="award_applicants.php" class="text-decoration-none text-white">
                    <div class="card card-8">
                        <div class="card-body text-center">
                            <h5 class="card-title">Award Applicants</h5>
                            <p class="card-text">Manage Applicants</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'scripts.php' ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<?php include 'sidebar.php'; ?>

</html>