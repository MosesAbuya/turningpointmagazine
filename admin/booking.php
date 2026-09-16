<?php
// Include database connection
include 'connection.php';

session_start(); // Start the session


include 'consent.php';
$pdo = connect();

// Fetch all records from the bookings table
$query = "SELECT * FROM bookings";
$stmt = $pdo->query($query);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalBookings = count($bookings);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>


<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">

    <div class="container-fluid">
        <h2 class="fw-bold mb-4">Bookings Management Dashboard</h2>
        <div class="sep"></div>
        <div class="text-right mb-3 no-print">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
            <button class="btn btn-danger" id="deleteAllBtn">Delete All</button>
        </div>

        <!-- Analysis Section -->
        <div id="analysisSection" class="mb-4">
            <h4 class="h4">General Data Analysis (All Data)</h4>
            <ul>
                <li>Total Bookings: <strong><?php echo $totalBookings; ?></strong></li>
            </ul>
        </div>

        <!-- Bookings Table -->
        <div class="table-responsive">
            <table class="table table-modern">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Organization</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Invoice</th>
                        <th>IP Address</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking) { ?>
                    <tr>
                        <td><input type="checkbox" class="selectRow" data-id="<?php echo $booking['id']; ?>"></td>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo $booking['firstname']; ?></td>
                        <td><?php echo $booking['lastname']; ?></td>
                        <td><?php echo $booking['organization']; ?></td>
                        <td><?php echo $booking['contact']; ?></td>
                        <td><?php echo $booking['email']; ?></td>
                        <td><?php echo $booking['invoice']; ?></td>
                        <td><?php echo $booking['ipAddress']; ?></td>
                        <td><?php echo $booking['created_at']; ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="<?php echo $booking['id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

        </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');

        Swal.fire({title: 'Are you sure?', text: 'Are you sure you want to delete this booking?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d', confirmButtonText: 'Yes, delete it!'}).then((result) => { if (result.isConfirmed) {
            $.ajax({
                url: 'delete_booking.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function() {
                    Swal.fire({title: 'Success', text: 'Booking deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});
                    location.reload();
                }
            });
        } });
    });

    // Handle select/deselect all rows
    $('#selectAll').on('click', function() {
        const isChecked = $(this).prop('checked');
        $('.selectRow').prop('checked', isChecked);
    });

    // Handle delete selected bookings
    $('#deleteAllBtn').on('click', function() {
        const selectedIds = [];
        $('.selectRow:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) { Swal.fire('Error', 'Please select bookings to delete.', 'error'); return; } Swal.fire({title: 'Are you sure?', text: 'Delete selected bookings?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e8003d', cancelButtonColor: '#6c757d'}).then((result) => { if (result.isConfirmed) {
            $.ajax({
                url: 'delete_selected_bookings.php',
                type: 'POST',
                data: {
                    ids: selectedIds
                },
                success: function() {
                    Swal.fire({title: 'Success', text: 'Selected bookings deleted successfully!', icon: 'success', confirmButtonColor: '#e8003d'});
                    location.reload();
                }
            });
        } else {
            
        }
    });
    </script>

</body>


</html>
