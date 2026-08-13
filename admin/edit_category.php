<?php
include 'connection2.php';
session_start();

// If the session does not exist, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Create a database connection
$pdo = connect();

// Handle adding a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];

    // Insert the new category into the database
    $insertCategoryQuery = "INSERT INTO categories (name, description) VALUES (:category_name, :category_description)";
    $stmt = $pdo->prepare($insertCategoryQuery);
    $stmt->execute([
        'category_name' => $category_name,
        'category_description' => $category_description,
    ]);

    header('Location: category.php');
    exit;
}

// Handle editing an existing category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];

    // Update the category in the database
    $updateCategoryQuery = "UPDATE categories SET name = :category_name, description = :category_description WHERE id = :category_id";
    $stmt = $pdo->prepare($updateCategoryQuery);
    $stmt->execute([
        'category_id' => $category_id,
        'category_name' => $category_name,
        'category_description' => $category_description,
    ]);

    header('Location: category.php');
    exit;
}

// Fetch the category to be edited (if "id" is set in the URL)
$category = null;
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $query = "SELECT * FROM categories WHERE id = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['category_id' => $category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    #body {
        background-color: #f8f9fa;
        width: calc(100% - 250px);
        margin-left: 250px;
        margin-top: 100px;
    }

    .table {
        margin-top: 50px;
    }
    </style>
    <link rel="stylesheet" href="form.css">
</head>

<body id="body">

    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center underline"><?= isset($category) ? 'Edit Category' : 'Add New Category' ?></h2>
        <div class="sep"></div>

        <!-- Category Form (Add or Edit) -->
        <form method="POST" class="mb-4">
            <h4 class="h4"><?= isset($category) ? 'Edit Category' : 'Add New Category' ?></h4>

            <?php if (isset($category)): ?>
            <!-- Hidden field to store the category id when editing -->
            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name"
                    value="<?= isset($category) ? htmlspecialchars($category['name']) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="category_description">Category Description</label>
                <textarea class="form-control" id="category_description"
                    name="category_description"><?= isset($category) ? htmlspecialchars($category['description']) : '' ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"
                name="<?= isset($category) ? 'edit_category' : 'add_category' ?>">
                <?= isset($category) ? 'Update Category' : 'Add Category' ?>
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<?php include 'sidebar.php'; ?>

</html>