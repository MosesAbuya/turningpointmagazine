<?php
include 'connection2.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$editionId = filter_input(INPUT_GET, 'edition_id', FILTER_VALIDATE_INT);

$pdo = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_article'])) {
    $title = $_POST['title'];
    $catch_phrase = $_POST['catch_phrase'];
    $edition_id = $_POST['edition_id'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $is_top_story = isset($_POST['is_top_story']) ? 1 : 0;
    $story_content = $_POST['story_content'];
    $writer = $_POST['writer'];

    // Handle Top Image Upload
    $top_image = $_FILES['top_image']['name'];
    $top_image_target = "assets/uploads/" . basename($top_image);
    if ($_FILES['top_image']['error'] === UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['top_image']['tmp_name'], $top_image_target);
    } else {
        // If no file uploaded, you could keep it as null or existing file
        $top_image_target = null;
    }

    // Handle Other Images Upload
    $other_images = [];
    if (!empty($_FILES['other_images']['name'][0])) {
        foreach ($_FILES['other_images']['name'] as $index => $image_name) {
            $image_target = "assets/uploads/" . basename($image_name);
            if ($_FILES['other_images']['error'][$index] === UPLOAD_ERR_OK) {
                move_uploaded_file($_FILES['other_images']['tmp_name'][$index], $image_target);
                $other_images[] = $image_target;
            }
        }
    }

    // Insert new article into the database
    $insertQuery = "INSERT INTO articles 
        (edition_id, category_id, subcategory_id, title, catch_phrase, is_top_story, story_content, top_image, other_images, writer) 
        VALUES (:edition_id, :category_id, :subcategory_id, :title, :catch_phrase, :is_top_story, :story_content, :top_image, :other_images, :writer)";
    
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute([
        'edition_id' => $edition_id,
        'category_id' => $category_id,
        'subcategory_id' => $subcategory_id,
        'title' => $title,
        'catch_phrase' => $catch_phrase,
        'is_top_story' => $is_top_story,
        'story_content' => $story_content,
        'top_image' => $top_image_target,  // Could be null
        'other_images' => json_encode($other_images),  // Stored as JSON
        'writer' => $writer,
    ]);

    header('Location: articles.php?edition_id=18');
    exit;
}

// Fetch editions, categories, and subcategories
$editions = $pdo->query("SELECT * FROM editions")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$subcategories = [];  // This will be populated dynamically

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
    tinymce.init({
        selector: 'textarea#story_content',
        plugins: 'advlist autolink lists link image charmap print preview anchor pagebreak',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
        height: 400,
        branding: false
    });

    // Fetch subcategories when category is selected
    function fetchSubcategories(categoryId) {
        fetch('fetch_subcategories.php?category_id=' + categoryId)
            .then(response => response.json())
            .then(data => {
                const subcategorySelect = document.getElementById('subcategory_id');
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                data.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
            });
    }
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
        <h2 class="underline">Add New Article</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="edition_id">Edition</label>
                <select class="form-control" id="edition_id" name="edition_id" required>
                    <?php foreach ($editions as $edition): ?>
                    <option value="<?= $edition['id'] ?>"><?= $edition['edition_name'] ?> (<?= $edition['date'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required onchange="fetchSubcategories(this.value)">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="subcategory_id">Subcategory</label>
                <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                    <option value="">Select Subcategory</option>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="catch_phrase">Catch Phrase</label>
                <input type="text" class="form-control" id="catch_phrase" name="catch_phrase">
            </div>
            <div class="form-group">
                <label for="is_top_story">Is Top Story?</label>
                <input type="checkbox" id="is_top_story" name="is_top_story">
            </div>
            <div class="form-group">
                <label for="story_content">Story Content</label>
                <textarea id="story_content" name="story_content" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="writer">Writer</label>
                <input type="text" class="form-control" id="writer" name="writer" required>
            </div>
            <div class="form-group">
                <label for="top_image">Top Image</label>
                <input type="file" class="form-control-file" id="top_image" name="top_image" required>
            </div>
            <div class="form-group">
                <label for="other_images">Other Images</label>
                <input type="file" class="form-control-file" id="other_images" name="other_images[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary" name="add_article">Add Article</button>
        </form>
    </div>
</body>

<?php include 'sidebar.php'; ?>

</html>
