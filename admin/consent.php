<?php


// Set the inactivity timeout (in seconds)
$inactiveTimeout = 6000; // 10 minutes = 600 seconds

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



// Check if the user is logged in and has the correct username
if (isset($_SESSION['username']) && $_SESSION['username'] === 'mosesabuya812@gmail.com') {
    // User is authorized, continue displaying the content
} else {
    // User is not authorized
    // Display a Toastr error message and exit
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>'; // Ensure jQuery is loaded
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">'; // Include Toastr CSS
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>'; // Include Toastr JS
    echo "<script>
        $(document).ready(function() {
            toastr.error('You are not authorized to view this page.');
        });
    </script>";
    exit();
}

?>
