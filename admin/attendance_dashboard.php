<?php
include 'connection.php';
session_start();




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



$pdo = connect();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #body {
            background-color: #f8f9fa;
            width: calc(100% - 250px);
            margin-left: 250px;
            margin-top: 100px;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .search-box {
            max-width: 400px;
            margin-bottom: 20px;
        }

        .modal-content {
            padding: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- jQuery (if not included already) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="text-center">Attendance Dashboard</h2>
        <div class="sep"></div>

        
 <!-- Filter Options -->
  <!-- Attendance Stats -->
        <div class="row my-3">
    <div class="col-md-4">
        <div class="alert alert-primary">Total Attendees: <span id="totalAttendees">0</span></div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-success">Present: <span id="presentAttendees">0</span></div>
    </div>
    <div class="col-md-4">
        <div class="alert alert-danger">Not Present: <span id="absentAttendees">0</span></div>
    </div>
</div>

<script>
    function updateAttendeeCounts() {
    $.ajax({
        url: 'get_attendance_count.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#totalAttendees').text(data.total);
            $('#presentAttendees').text(data.present);
            $('#absentAttendees').text(data.absent);
        },
        error: function() {
            console.error('Error fetching attendee counts.');
        }
    });
}

$(document).ready(function() {
    // Initial load
    updateAttendeeCounts();

    // Update counts whenever an action is performed
   // Mark attendee as present/unmark
$(document).on('click', '.mark-present', function() {
    let button = $(this);
    let id = button.data('id');
    let day = button.data('day');

    $.ajax({
        url: 'update_attendance.php',
        type: 'POST',
        data: { id: id, day: day },
        dataType: 'json',
        success: function(response) {
            if (response.status === "success") {
                if (response.newStatus === 1) {
                    button.text('Present')
                          .removeClass('btn-outline-success btn-warning')
                          .addClass('btn-success'); // Green when present
                } else {
                    button.text('Mark Present')
                          .removeClass('btn-success btn-warning')
                          .addClass('btn-outline-success'); // Outlined green when absent
                }
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Error updating attendance.');
        }
    });
});



    // Refresh count every 10 seconds (optional)
    setInterval(updateAttendeeCounts, 10000);
});

</script>

        

        <!-- Search Box -->
        <input type="text" id="search" class="form-control search-box" placeholder="Search by Name, Contact, or Email">

        <!-- Button to Add New Attendee -->
        <button class="btn btn-primary my-3" data-toggle="modal" data-target="#addAttendeeModal">Add New Attendee</button>

        <!-- Attendees Table -->
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Present (Day 1)</th>
                        <th>Present (Day 2)</th>
                        <th>Added On-Site</th>
                    </tr>
                </thead>
                <tbody id="attendeesTable">
                    <!-- Dynamic Data from AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Attendee Modal -->
    <div class="modal fade" id="addAttendeeModal" tabindex="-1" aria-labelledby="addAttendeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendeeModalLabel">Add New Attendee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAttendeeForm">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" required>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" class="form-control" id="contact">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <button type="submit" class="btn btn-success">Add Attendee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
       function loadAttendees(query = '') {
    $.ajax({
        url: 'fetch_attendees.php',
        type: 'GET',
        data: { search: query },
        success: function(response) {
            $('#attendeesTable').html(response);
        },
        error: function() {
            toastr.error('Error loading attendees.');
        }
    });
}




$(document).ready(function() {
    loadAttendees();

    // Live search for attendees
    $('#search').on('keyup', function() {
        let query = $(this).val();
        loadAttendees(query);
    });

    // Mark attendee as present
 
    // Add new attendee
    $('#addAttendeeForm').on('submit', function(event) {
        event.preventDefault();

        let firstname = $('#firstname').val().trim();
        let lastname = $('#lastname').val().trim();
        let contact = $('#contact').val().trim();
        let email = $('#email').val().trim();

        // Validate required fields
        if (firstname === '' || lastname === '') {
            toastr.warning('First name and last name are required.');
            return;
        }

        // AJAX request to add attendee
        $.ajax({
            url: 'add_attendee.php',
            type: 'POST',
            data: { firstname, lastname, contact, email },
            dataType: 'json', // Ensure JSON response
            success: function(response) {
                if (response.status === "success") {
                    toastr.success(response.message);
                    $('#addAttendeeModal').modal('hide'); // Close modal
                    $('#addAttendeeForm')[0].reset(); // Reset form
                    loadAttendees(); // Refresh attendee list
                } else if (response.status === "exists") {
                    toastr.warning(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Error adding attendee. Please try again.');
            }
        });
    });
});

    </script>
</body>

<?php include 'sidebar.php'; ?>

</html>
