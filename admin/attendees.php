<?php
// Include database connection
include 'connection.php';

session_start(); // Start the session


include 'consent.php';
$pdo = connect();

// Fetch all records from the booking table
$query = "SELECT * FROM booking";
$stmt = $pdo->query($query);
$attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalAttendees = count($attendees);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees Management Dashboard</title>
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
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .table td button {
        margin-right: 5px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php' ?>

<body id="body">

    <div class="container mt-5">
        <h2 class="text-center underline">Turning Point International Women's Day & Trade & Exhibition Expo Attendees Management</h2>
        <div class="sep"></div>
        <div class="text-right mb-3 no-print">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
            <button class="btn btn-danger" id="deleteAllBtn">Delete All</button>
        </div>

        <!-- Analysis Section -->
        <div id="analysisSection" class="mb-4">
            <h4 class="h4">General Data Analysis (All Data)</h4>
            <ul>
                <li>Total Attendees: <strong><?php echo $totalAttendees; ?></strong></li>
            </ul>
        </div>

        <!-- Attendees Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <!-- <th>ID</th> -->
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Attendance ID</th>
                        <!-- <th>IP Address</th>
                        <th>Created At</th>
                        <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendees as $attendee) { ?>
                    <tr>
                        <td><input type="checkbox" class="selectRow" data-id="<?php echo $attendee['id']; ?>"></td>
                        <!-- <td><?php echo $attendee['id']; ?></td> -->
                        <td><?php echo $attendee['firstname']; ?></td>
                        <td><?php echo $attendee['lastname']; ?></td>
                        <td><?php echo $attendee['contact']; ?></td>
                        <td><?php echo $attendee['email']; ?></td>
                        <td><?php echo $attendee['invoice']; ?></td>
                        <!-- <td><?php echo $attendee['ipAddress']; ?></td>
                        <td><?php echo $attendee['created_at']; ?></td> -->
                        <td>
                            <!-- <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="<?php echo $attendee['id']; ?>">Delete</button> -->
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');

        if (confirm('Are you sure you want to delete this attendee?')) {
            $.ajax({
                url: 'delete_attendee.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function() {
                    alert('Attendee deleted successfully!');
                    location.reload();
                }
            });
        }
    });

    // Handle select/deselect all rows
    $('#selectAll').on('click', function() {
        const isChecked = $(this).prop('checked');
        $('.selectRow').prop('checked', isChecked);
    });

    // Handle delete selected attendees
    $('#deleteAllBtn').on('click', function() {
        const selectedIds = [];
        $('.selectRow:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete the selected attendees?')) {
            $.ajax({
                url: 'delete_selected_attendees.php',
                type: 'POST',
                data: {
                    ids: selectedIds
                },
                success: function() {
                    alert('Selected attendees deleted successfully!');
                    location.reload();
                }
            });
        } else {
            alert('Please select attendees to delete.');
        }
    });
    </script>

</body>
<?php include 'sidebar.php'; ?>

</html>
