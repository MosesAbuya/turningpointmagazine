<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();
$award_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM awards_to_apply WHERE id = ?");
$stmt->execute([$award_id]);
$award = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $short_description = htmlspecialchars($_POST['short_description']);
    $full_description = htmlspecialchars($_POST['full_description']);
    $eligibility_criteria = htmlspecialchars($_POST['eligibility_criteria']);
    $application_deadline = htmlspecialchars($_POST['application_deadline']);
    $status = htmlspecialchars($_POST['status']);
    $image_url = $award['image_url'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/uploads/awards/';
        $filename = uniqid() . '-' . basename($_FILES['image']['name']);
        $destination = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
            $image_url = $destination;
        }
    }

    $stmt = $pdo->prepare("UPDATE awards_to_apply SET title = ?, short_description = ?, full_description = ?, eligibility_criteria = ?, application_deadline = ?, image_url = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $short_description, $full_description, $eligibility_criteria, $application_deadline, $image_url, $status, $award_id]);
    header('Location: awards_to_apply.php');
    exit;
}
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
    <title>Edit Award</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Edit Award to Apply For</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($award['title']) ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Short Description</label>
                <textarea name="short_description"
                    class="form-control"><?= htmlspecialchars($award['short_description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Full Description</label>
                <textarea name="full_description" class="form-control"
                    rows="5"><?= htmlspecialchars($award['full_description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Eligibility Criteria</label>
                <textarea name="eligibility_criteria" class="form-control"
                    rows="3"><?= htmlspecialchars($award['eligibility_criteria']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Application Deadline</label>
                <input type="date" name="application_deadline" class="form-control"
                    value="<?= htmlspecialchars($award['application_deadline']) ?>">
            </div>
            <div class="form-group">
                <label>Current Image</label><br>
                <img loading="lazy" src="<?= htmlspecialchars($award['image_url']) ?>" width="200">
            </div>
            <div class="form-group">
                <label>New Image</label>
                <input type="file" name="image" class="form-control-file">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Active" <?= $award['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Archived" <?= $award['status'] == 'Archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Award</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>
