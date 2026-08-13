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
$stmt = $pdo->prepare("SELECT * FROM personal_awards_won WHERE id = ?");
$stmt->execute([$award_id]);
$award = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date_awarded = htmlspecialchars($_POST['date_awarded']);
    $category = htmlspecialchars($_POST['category']);
    $thumbnail_url = $award['thumbnail_url'];
    $gallery_images = json_decode($award['gallery_images'] ?? '[]', true);

    $upload_dir = 'assets/uploads/awards/';

    // Handle thumbnail update
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $filename = uniqid() . '-' . basename($_FILES['thumbnail']['name']);
        $destination = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destination)) {
            if ($thumbnail_url && file_exists($thumbnail_url)) {
                unlink($thumbnail_url);
            }
            $thumbnail_url = $destination;
        }
    }

    // Handle gallery image removal
    if (isset($_POST['remove_gallery_image'])) {
        foreach ($_POST['remove_gallery_image'] as $image_to_remove) {
            if (($key = array_search($image_to_remove, $gallery_images)) !== false) {
                if (file_exists($image_to_remove)) {
                    unlink($image_to_remove);
                }
                unset($gallery_images[$key]);
            }
        }
    }

    // Handle new gallery images upload
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

    $stmt = $pdo->prepare("UPDATE personal_awards_won SET title = ?, description = ?, date_awarded = ?, category = ?, thumbnail_url = ?, gallery_images = ? WHERE id = ?");
    $stmt->execute([$title, $description, $date_awarded, $category, $thumbnail_url, json_encode(array_values($gallery_images)), $award_id]);
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
    <title>Edit Personal Award</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2>Edit Personal Award</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($award['title']) ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"
                    rows="5"><?= htmlspecialchars($award['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Date Awarded</label>
                <input type="date" name="date_awarded" class="form-control"
                    value="<?= htmlspecialchars($award['date_awarded']) ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control"
                    value="<?= htmlspecialchars($award['category']) ?>">
            </div>
            <div class="form-group">
                <label>Current Thumbnail</label><br>
                <img loading="lazy" src="<?= htmlspecialchars($award['thumbnail_url']) ?>" width="200">
            </div>
            <div class="form-group">
                <label>New Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control-file">
            </div>
            <div class="form-group">
                <label>Current Gallery</label><br>
                <?php $gallery = json_decode($award['gallery_images'] ?? '[]', true); ?>
                <?php foreach ($gallery as $image): ?>
                    <div style="display: inline-block; margin-right: 10px;">
                        <img loading="lazy" src="<?= htmlspecialchars($image) ?>" width="100">
                        <input type="checkbox" name="remove_gallery_image[]" value="<?= htmlspecialchars($image) ?>"> Remove
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-group">
                <label>Add to Gallery</label>
                <input type="file" name="gallery[]" class="form-control-file" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Update Award</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>
