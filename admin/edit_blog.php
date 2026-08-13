<?php
include 'connection2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Fetch blog data for editing
$blog_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$blog_id) {
    header('Location: blog.php');
    exit;
}

$query = "SELECT * FROM blog WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $blog_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header('Location: blog.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_blog'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $venue = $_POST['venue'];
    $story = $_POST['story'];

    // Handle Top Photo Upload
    if (!empty($_FILES['top_photo']['name'])) {
        $top_photo = $_FILES['top_photo']['name'];
        $top_photo_target = "assets/uploads/" . basename($top_photo);
        move_uploaded_file($_FILES['top_photo']['tmp_name'], $top_photo_target);
    } else {
        $top_photo_target = $blog['top_photo'];
    }

    // Handle Document Upload
    if (!empty($_FILES['document']['name'])) {
        $document_name = $_FILES['document']['name'];
        $document_target = "assets/uploads/" . basename($document_name);
        move_uploaded_file($_FILES['document']['tmp_name'], $document_target);
    } else {
        // Keep existing document if no new file is selected
        $document_target = $blog['document']; 
    }

    // Handle Other Photos
    $other_photos = json_decode($blog['other_photos'], true) ?? [];
    if (!empty($_FILES['other_photos']['name'][0])) {
        foreach ($_FILES['other_photos']['name'] as $index => $image_name) {
            $image_target = "assets/uploads/" . basename($image_name);
            move_uploaded_file($_FILES['other_photos']['tmp_name'][$index], $image_target);
            $other_photos[] = $image_target;
        }
    }

    // Update blog in the database
    $updateQuery = "UPDATE blog SET 
        title = :title,
        date = :date,
        venue = :venue,
        story = :story,
        top_photo = :top_photo,
        other_photos = :other_photos,
        document = :document
        WHERE id = :id";

    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'venue' => $venue,
        'story' => $story,
        'top_photo' => $top_photo_target,
        'other_photos' => json_encode($other_photos),
        'document' => $document_target,
        'id' => $blog_id,
    ]);

    header('Location: blog.php');
    exit;
}

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: 'textarea#story',
        plugins: 'advlist autolink lists link image charmap print preview anchor pagebreak',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
        height: 400,
        branding: false
    });
    </script>
    <style>
    .other-image-preview img {
        max-width: 150px;
        margin: 5px;
    }

    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>
<?php include 'nav.php'; ?>

<body id="body">
    <div class="container mt-5">
        <h2 class="underline">Edit Blog</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="<?= htmlspecialchars($blog['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control" id="date" name="date"
                    value="<?= htmlspecialchars($blog['date']) ?>" required>
            </div>
            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" class="form-control" id="venue" name="venue"
                    value="<?= htmlspecialchars($blog['venue']) ?>">
            </div>
            <div class="form-group">
                <label for="story">Story Content</label>
                <textarea id="story" name="story"><?= htmlspecialchars($blog['story']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Top Photo</label>
                <input type="file" class="form-control-file" name="top_photo">
                <img loading="lazy" src="<?= $blog['top_photo'] ?>" alt="Top Photo" style="max-width: 150px; margin-top: 10px;">
            </div>
            
            <div class="form-group">
                <label>Attached Document (Optional)</label>
                <input type="file" class="form-control-file" name="document" accept=".pdf,.doc,.docx">
                <?php if (!empty($blog['document'])): ?>
                    <p class="mt-2">Current Document: <a href="<?= $blog['document'] ?>" target="_blank">View File</a></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Other Photos</label>
                <input type="file" class="form-control-file" name="other_photos[]" multiple>
                <div class="other-image-preview">
                    <?php foreach (json_decode($blog['other_photos'], true) ?? [] as $image): ?>
                    <img loading="lazy" src="<?= $image ?>" alt="Other Photo">
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="edit_blog">Save Changes</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>
