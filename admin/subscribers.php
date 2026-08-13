<?php
// Include database connection
include 'connection.php';

session_start(); // Start the session


include 'consent.php';

$pdo = connect();

// Fetch all records from the subscribers table
$query = "SELECT * FROM subscribers";
$stmt = $pdo->query($query);
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Data analysis calculations
$totalSubscribers = count($subscribers);
$categories = array_unique(array_column($subscribers, 'category'));
$totalCategories = count($categories);

// Time-based analysis for all data
$timeAnalysis = [
    'daily' => 0,
    'monthly' => 0,
    'annual' => 0
    
];

// Time-based analysis for each year
$yearlyAnalysis = [];
$monthlyAnalysis = [];

$years = [];
$monthsByYear = [];

foreach ($subscribers as $subscriber) {
    $date = new DateTime($subscriber['date']);
    $year = $date->format('Y');
    $month = $date->format('m');

    // Store the unique years
    if (!in_array($year, $years)) {
        $years[] = $year;
    }

    // Store months for each year
    if (!isset($monthsByYear[$year])) {
        $monthsByYear[$year] = [];
    }
    if (!in_array($month, $monthsByYear[$year])) {
        $monthsByYear[$year][] = $month;
    }

    // Time-based analysis for all data
    if ($date->format('Y-m-d') === (new DateTime())->format('Y-m-d')) {
        $timeAnalysis['daily']++;
    }
    
    if ($date->format('Y-m') === (new DateTime())->format('Y-m')) {
        $timeAnalysis['monthly']++;
    }
    
    if ($date->format('Y') === (new DateTime())->format('Y')) {
        $timeAnalysis['annual']++;
    }

    // Yearly Analysis
    if (!isset($yearlyAnalysis[$year])) {
        $yearlyAnalysis[$year] = [
            'total' => 0
        ];
    }

    $yearlyAnalysis[$year]['total']++;

    // Monthly Analysis
    if (!isset($monthlyAnalysis[$year][$month])) {
        $monthlyAnalysis[$year][$month] = [
            'total' => 0
        ];
    }

    $monthlyAnalysis[$year][$month]['total']++;
}

closeConnection($pdo);



// Find month with the most subscribers
$monthWithMostSubscribers = [];
foreach ($monthlyAnalysis as $year => $months) {
    $monthWithMostSubscribers[$year] = array_keys($months, max($months))[0];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers Management Dashboard</title>
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
        <h2 class="text-center underline">Subscribers Management Dashboard</h2>
        <div class="sep"></div>
        <div class="text-right mb-3 no-print">
            <button class="btn btn-success" onclick="window.print()">Print Table</button>
            <button class="btn btn-info" onclick="printAnalysis()">Print Analysis</button>
            <button class="btn btn-danger" id="deleteAllBtn">Delete All</button>
        </div>

        <!-- Analysis Section -->
        <div id="analysisSection" class="mb-4">
            <h4 class="h4">General Data Analysis (All Data)</h4>
            <ul>
                <li>Total Subscribers: <strong><?php echo $totalSubscribers; ?></strong></li>

            </ul>

            <h4 class="h4">Select Year and Month for Analysis</h4>
            <select id="yearSelect" class="form-control mb-3">
                <option value="">Select Year</option>
                <?php foreach ($years as $year) { ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php } ?>
            </select>

            <select id="monthSelect" class="form-control mb-3" disabled>
                <option value="">Select Month</option>
            </select>

            <div id="yearMonthAnalysis" class="mt-4">
                <!-- Data analysis tables for selected year and month will appear here -->
            </div>
        </div>

        <!-- Subscribers Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Comments</th>
                        <th>Category</th>
                        <th>IP Address</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber) { ?>
                    <tr>
                        <td><input type="checkbox" class="selectRow" data-id="<?php echo $subscriber['id']; ?>"></td>
                        <td><?php echo $subscriber['id']; ?></td>
                        <td><?php echo $subscriber['email']; ?></td>
                        <td><?php echo $subscriber['firstname']; ?></td>
                        <td><?php echo $subscriber['lastname']; ?></td>
                        <td><?php echo $subscriber['address']; ?></td>
                        <td><?php echo $subscriber['comments']; ?></td>
                        <td><?php echo $subscriber['category']; ?></td>
                        <td><?php echo $subscriber['ipAddress']; ?></td>
                        <td><?php echo $subscriber['time']; ?></td>
                        <td><?php echo $subscriber['date']; ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="<?php echo $subscriber['id']; ?>">Delete</button>
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

        if (confirm('Are you sure you want to delete this subscriber?')) {
            $.ajax({
                url: 'delete_subscriber.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function() {
                    alert('Subscriber deleted successfully!');
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

    // Handle delete selected subscribers
    $('#deleteAllBtn').on('click', function() {
        const selectedIds = [];
        $('.selectRow:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length > 0 && confirm('Are you sure you want to delete the selected subscribers?')) {
            $.ajax({
                url: 'delete_selected_subscribers.php',
                type: 'POST',
                data: {
                    ids: selectedIds
                },
                success: function() {
                    alert('Selected subscribers deleted successfully!');
                    location.reload();
                }
            });
        } else {
            alert('Please select subscribers to delete.');
        }
    });

    // Handle year and month selection for analysis
    $('#yearSelect').on('change', function() {
        const selectedYear = $(this).val();
        const selectedMonth = $('#monthSelect').val();
        populateMonthSelect(selectedYear);

        if (selectedYear) {
            displayYearAnalysis(selectedYear);
        } else {
            $('#yearMonthAnalysis').empty();
        }
    });

    // Handle month selection for analysis
    $('#monthSelect').on('change', function() {
        const selectedYear = $('#yearSelect').val();
        const selectedMonth = $(this).val();
        if (selectedYear && selectedMonth) {
            displayMonthAnalysis(selectedYear, selectedMonth);
        }
    });

    function populateMonthSelect(year) {
        const monthsByYear = <?php echo json_encode($monthsByYear); ?>;
        const months = monthsByYear[year] || [];
        const monthSelect = $('#monthSelect');
        monthSelect.empty();
        monthSelect.append('<option value="">Select Month</option>');
        months.forEach(function(month) {
            monthSelect.append(`<option value="${month}">${month}</option>`);
        });
        monthSelect.prop('disabled', months.length === 0);
    }

    function displayYearAnalysis(year) {
        const yearlyAnalysis = <?php echo json_encode($yearlyAnalysis); ?>;
        const analysis = yearlyAnalysis[year] || {};
        let html = `
                <h5 class="h4">Yearly Analysis for ${year}</h5>
                <ul>
                    <li>Total Subscribers: <strong>${analysis.total || 0}</strong></li>
                    <li>Month with Most Subscribers: <strong>${<?php echo json_encode($monthWithMostSubscribers); ?>[year]}</strong></li>
                </ul>
            `;
        $('#yearMonthAnalysis').html(html);
    }

    function displayMonthAnalysis(year, month) {
        const monthlyAnalysis = <?php echo json_encode($monthlyAnalysis); ?>;
        const analysis = monthlyAnalysis[year] && monthlyAnalysis[year][month] || {};
        let html = `
                <h5 class="h4">Monthly Analysis for ${year}-${month}</h5>
                <ul>
                    <li>Total Subscribers: <strong>${analysis.total || 0}</strong></li>
                </ul>
            `;
        $('#yearMonthAnalysis').html(html);
    }

    function printAnalysis() {
        const printContents = document.getElementById('analysisSection').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<?php include 'sidebar.php'; ?>

</html>