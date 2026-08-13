<?php
include 'connection2.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

// Fetch article data for editing
$article_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$article_id) {
    header('Location: articles.php');
    exit;
}

$query = "SELECT * FROM articles WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: articles.php');
    exit;
}

// Fetch editions, categories, and subcategories
$editions = $pdo->query("SELECT * FROM editions")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$subcategories = $pdo->query("SELECT * FROM sub_category")->fetchAll(); // Fetch subcategories

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_article'])) {
    $title = $_POST['title'];
    $catch_phrase = $_POST['catch_phrase'];
    $edition_id = $_POST['edition_id'];
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id']; // New subcategory field
    $is_top_story = isset($_POST['is_top_story']) ? 1 : 0;
    $story_content = $_POST['story_content'];
    $writer = $_POST['writer'];

    // Handle Top Image Upload
    if (!empty($_FILES['top_image']['name'])) {
        $top_image = $_FILES['top_image']['name'];
        $top_image_target = "assets/uploads/" . basename($top_image);
        move_uploaded_file($_FILES['top_image']['tmp_name'], $top_image_target);
    } else {
        $top_image_target = $article['top_image'];
    }

    // Handle Other Images
    $other_images = json_decode($article['other_images'], true) ?? [];
    if (!empty($_FILES['other_images']['name'][0])) {
        foreach ($_FILES['other_images']['name'] as $index => $image_name) {
            $image_target = "assets/uploads/" . basename($image_name);
            move_uploaded_file($_FILES['other_images']['tmp_name'][$index], $image_target);
            $other_images[] = $image_target;
        }
    }

    $updateQuery = "UPDATE articles SET 
    edition_id = :edition_id,
    category_id = :category_id,
    subcategory_id = :subcategory_id,
    title = :title,
    catch_phrase = :catch_phrase,
    is_top_story = :is_top_story,
    story_content = :story_content,
    top_image = :top_image,
    other_images = :other_images,
    writer = :writer
    WHERE id = :id";

$stmt = $pdo->prepare($updateQuery);
$stmt->execute([
    'edition_id' => $edition_id,
    'category_id' => $category_id,
    'subcategory_id' => $subcategory_id, 
    'title' => $title,
    'catch_phrase' => $catch_phrase,
    'is_top_story' => $is_top_story,
    'story_content' => $story_content,
    'top_image' => $top_image_target,
    'other_images' => json_encode($other_images),
    'writer' => $writer,
    'id' => $article_id,
]);

header('Location: articles.php');
exit;

}

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
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
        <h2 class="underline">Edit Article</h2>
        <div class="sep"></div>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="edition_id">Edition</label>
                <select class="form-control" id="edition_id" name="edition_id" required>
                    <?php foreach ($editions as $edition): ?>
                    <option value="<?= $edition['id'] ?>"
                        <?= $edition['id'] == $article['edition_id'] ? 'selected' : '' ?>>
                        <?= $edition['edition_name'] ?> (<?= $edition['date'] ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"
                        <?= $category['id'] == $article['category_id'] ? 'selected' : '' ?>>
                        <?= $category['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="subcategory_id">Subcategory</label>
                <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                    <?php foreach ($subcategories as $subcategory): ?>
                    <option value="<?= $subcategory['id'] ?>"
                        <?= $subcategory['id'] == $article['subcategory_id'] ? 'selected' : '' ?>>
                        <?= $subcategory['name'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title"
                    value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="catch_phrase">Catch Phrase</label>
                <input type="text" class="form-control" id="catch_phrase" name="catch_phrase"
                    value="<?= htmlspecialchars($article['catch_phrase']) ?>">
            </div>
            <div class="form-group">
                <label for="is_top_story">Is Top Story?</label>
                <input type="checkbox" id="is_top_story" name="is_top_story"
                    <?= $article['is_top_story'] ? 'checked' : '' ?>>
            </div>
            <div class="form-group">
                <label for="story_content">Story Content</label>
                <textarea id="story_content"
                    name="story_content"><?= htmlspecialchars($article['story_content']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="writer">Writer</label>
                <input type="text" class="form-control" id="writer" name="writer"
                    value="<?= htmlspecialchars($article['writer']) ?>" required>
            </div>
            <div class="form-group">
                <label>Top Image</label>
                <input type="file" class="form-control-file" name="top_image">
                <img loading="lazy" src="<?= $article['top_image'] ?>" alt="Top Image" style="max-width: 150px; margin-top: 10px;">
            </div>
            <div class="form-group">
                <label>Other Images</label>
                <input type="file" class="form-control-file" name="other_images[]" multiple>
                <div class="other-image-preview">
                    <?php foreach (json_decode($article['other_images'], true) ?? [] as $image): ?>
                    <img loading="lazy" src="<?= $image ?>" alt="Other Image">
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="edit_article">Save Changes</button>
        </form>
    </div>
</body>
<?php include 'sidebar.php'; ?>

</html>
