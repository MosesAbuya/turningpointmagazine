<?php
// Include the database connection
include 'connection.php';

session_start(); // Start the session


include 'consent.php';
// Create a database connection
$pdo = connect();

// Fetch all feedback entries
$query = "SELECT * FROM feedback ORDER BY date DESC";
$stmt = $pdo->query($query);
$feedbackEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    /* Highlight unread feedback */
    .unread {
        background-color: #ffcccb;
    }

    /* Style for table and buttons */
    .table td button {
        margin-right: 5px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<?php include 'nav.php' ?>

<body id="body">




    <div class="container mt-5">
        <h2 class="text-center underline">Feedback Management Dashboard</h2>
        <div class="sep"></div>
        <div class="text-right mb-3 no-print">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
        </div>

        <!-- Feedback Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Comments</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbackEntries as $feedback) { ?>
                    <tr class="<?php echo ($feedback['status'] == 'unread') ? 'unread' : ''; ?>">
                        <td><input type="checkbox" class="selectRow" data-id="<?php echo $feedback['id']; ?>"></td>
                        <td><?php echo $feedback['id']; ?></td>
                        <td><?php echo $feedback['comments']; ?></td>
                        <td><?php echo $feedback['ipAddress']; ?></td>
                        <td><?php echo $feedback['date']; ?></td>
                        <td><?php echo ucfirst($feedback['status']); ?></td>
                        <td>
                            <button class="btn btn-info btn-sm viewBtn"
                                data-id="<?php echo $feedback['id']; ?>">View</button>
                            <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="<?php echo $feedback['id']; ?>">Delete</button>
                            <?php if ($feedback['status'] == 'unread') { ?>
                            <button class="btn btn-primary btn-sm markAsReadBtn"
                                data-id="<?php echo $feedback['id']; ?>">Mark as Read</button>
                            <?php } ?>
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
    // Handle delete feedback
    $(document).on('click', '.deleteBtn', function() {
        const feedbackId = $(this).data('id');
        if (confirm('Are you sure you want to delete this feedback?')) {
            $.ajax({
                url: 'delete_feedback.php',
                type: 'POST',
                data: {
                    id: feedbackId
                },
                success: function(response) {
                    alert(response);
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

    // Handle mark as read
    $(document).on('click', '.markAsReadBtn', function() {
        const feedbackId = $(this).data('id');
        $.ajax({
            url: 'mark_as_read_feedback.php',
            type: 'POST',
            data: {
                id: feedbackId
            },
            success: function(response) {
                alert(response);
                location.reload();
            }
        });
    });

    // Handle view feedback details
    $(document).on('click', '.viewBtn', function() {
        const feedbackId = $(this).data('id');
        window.location.href = 'get_feedback_details.php?id=' + feedbackId;
    });
    </script>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include 'sidebar.php'; ?>

</html>