<?php
include 'connection2.php';
session_start();
include 'consent.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connect();
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date_awarded = htmlspecialchars($_POST['date_awarded']);
    $category = htmlspecialchars($_POST['category']);
    $thumbnail_url = '';
    $gallery_images = [];

    $upload_dir = 'assets/uploads/awards/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . '-' . basename($_FILES['thumbnail']['name']);
        $destination = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destination)) {
            $thumbnail_url = $destination;
        }
    }

    // Handle gallery images upload
    if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
        foreach ($_FILES['gallery']['name'] as $key => $name) {
            if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                $filename = uniqid() . '-' . basename($name);
                $destination = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $destination)) {
                    $gallery_images[] = $destination;
                }
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO personal_awards_won (title, description, date_awarded, category, thumbnail_url, gallery_images) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $date_awarded, $category, $thumbnail_url, json_encode($gallery_images)]);
    header('Location: personal_awards_won.php');
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
    <title>Add New Personal Award</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Add New Personal Award</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label>Date Awarded</label>
                <input type="date" name="date_awarded" class="form-control">
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control">
            </div>
            <div class="form-group">
                <label>Thumbnail Image</label>
                <input type="file" name="thumbnail" class="form-control-file">
            </div>
            <div class="form-group">
                <label>Gallery Images</label>
                <input type="file" name="gallery[]" class="form-control-file" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Add Award</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>