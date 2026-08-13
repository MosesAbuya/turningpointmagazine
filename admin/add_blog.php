<?php
include 'connection2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_blog'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $venue = $_POST['venue'];
    $story = $_POST['story'];

    // Handle Top Photo Upload
    $top_photo = $_FILES['top_photo']['name'];
    $top_photo_target = "assets/uploads/" . basename($top_photo);
    move_uploaded_file($_FILES['top_photo']['tmp_name'], $top_photo_target);

    // Handle Document Upload (Optional)
    $document_target = null; // Default to null
    if (!empty($_FILES['document']['name'])) {
        $document_name = $_FILES['document']['name'];
        $document_target = "assets/uploads/" . basename($document_name);
        move_uploaded_file($_FILES['document']['tmp_name'], $document_target);
    }

    // Handle Other Photos Upload
    $other_photos = [];
    if (!empty($_FILES['other_photos']['name'][0])) {
        foreach ($_FILES['other_photos']['name'] as $index => $image_name) {
            $image_target = "assets/uploads/" . basename($image_name);
            move_uploaded_file($_FILES['other_photos']['tmp_name'][$index], $image_target);
            $other_photos[] = $image_target;
        }
    }

    // Insert into database
    $insertQuery = "INSERT INTO blog (title, date, venue, story, top_photo, other_photos, document) 
                    VALUES (:title, :date, :venue, :story, :top_photo, :other_photos, :document)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        'title' => $title,
        'date' => $date,
        'venue' => $venue,
        'story' => $story,
        'top_photo' => $top_photo_target,
        'other_photos' => json_encode($other_photos),
        'document' => $document_target,
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
    <title>Add Blog</title>
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
        <h2 class="underline">Add New Blog</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control" id="date" name="date" placeholder="e.g., November 29, 2024"
                    required>
            </div>
            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" class="form-control" id="venue" name="venue">
            </div>
            <div class="form-group">
                <label for="story">Story Content</label>
                <textarea id="story" name="story" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="top_photo">Top Photo</label>
                <input type="file" class="form-control-file" id="top_photo" name="top_photo" required>
            </div>
            <div class="form-group">
                <label for="document">Attach Document (PDF - Optional)</label>
                <input type="file" class="form-control-file" id="document" name="document" accept=".pdf,.doc,.docx">
            </div>
            <div class="form-group">
                <label for="other_photos">Other Photos</label>
                <input type="file" class="form-control-file" id="other_photos" name="other_photos[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary" name="add_blog">Add Blog</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>