<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Base query
$query = "
    SELECT aa.*, ata.title AS award_title
    FROM award_applicants aa
    JOIN awards_to_apply ata ON aa.award_id = ata.id
";

// Filtering logic
$where_clauses = [];
$params = [];

if (!empty($_GET['award_id'])) {
    $where_clauses[] = "aa.award_id = :award_id";
    $params[':award_id'] = $_GET['award_id'];
}
if (!empty($_GET['status'])) {
    $where_clauses[] = "aa.status = :status";
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['search'])) {
    $where_clauses[] = "(aa.applicant_name LIKE :search OR aa.applicant_email LIKE :search)";
    $params[':search'] = '%' . $_GET['search'] . '%';
}

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}

$query .= " ORDER BY aa.applied_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch awards for the filter dropdown
$awards_stmt = $pdo->query("SELECT id, title FROM awards_to_apply ORDER BY title");
$awards_list = $awards_stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>
<style>
#body {
    background-color: #f8f9fa;
    width: calc(100% - 250px);
    margin-left: 250px;
    margin-top: 100px;
}
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Award Applicants</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Manage Award Applicants</h2>

        <!-- Filter Form -->
        <form method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search Name/Email..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="award_id" class="form-control">
                        <option value="">All Awards</option>
                        <?php foreach ($awards_list as $award): ?>
                        <option value="<?= $award['id'] ?>"
                            <?= ($_GET['award_id'] ?? '') == $award['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($award['title']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Pending" <?= ($_GET['status'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending
                        </option>
                        <option value="Reviewed" <?= ($_GET['status'] ?? '') == 'Reviewed' ? 'selected' : '' ?>>Reviewed
                        </option>
                        <option value="Accepted" <?= ($_GET['status'] ?? '') == 'Accepted' ? 'selected' : '' ?>>Accepted
                        </option>
                        <option value="Rejected" <?= ($_GET['status'] ?? '') == 'Rejected' ? 'selected' : '' ?>>Rejected
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="award_applicants.php" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Applicant Name</th>
                    <th>Award Title</th>
                    <th>Applied At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applicants)): ?>
                <tr>
                    <td colspan="5" class="text-center">No applicants found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?= htmlspecialchars($applicant['applicant_name']) ?></td>
                    <td><?= htmlspecialchars($applicant['award_title']) ?></td>
                    <td><?= htmlspecialchars($applicant['applied_at']) ?></td>
                    <td><?= htmlspecialchars($applicant['status']) ?></td>
                    <td>
                        <a href="view_applicant.php?id=<?= $applicant['id'] ?>" class="btn btn-info btn-sm">View
                            Details</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>