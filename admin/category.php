<?php
include 'connection2.php';
session_start();


include 'consent.php';
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

// Handle deleting a category
if (isset($_GET['delete_id'])) {
    $category_id = $_GET['delete_id'];

    // Delete the category from the database
    $deleteCategoryQuery = "DELETE FROM categories WHERE id = :category_id";
    $stmt = $pdo->prepare($deleteCategoryQuery);
    $stmt->execute(['category_id' => $category_id]);

    header('Location: category.php');
    exit;
}

// Fetch all categories
$query = "SELECT * FROM categories";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
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
        <h2 class="text-center underline">Category Management</h2>
        <div class="sep"></div>

        <!-- Add New Category Form -->
        <form method="POST" class="mb-4">
            <h4 class="h4">Add New Category</h4>
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <div class="form-group">
                <label for="category_description">Category Description</label>
                <textarea class="form-control" id="category_description" name="category_description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
        </form>

        <!-- Categories List -->
        <h4 class="h4">Categories List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                    <td><?= htmlspecialchars($category['description']) ?></td>
                    <td>
                        <!-- Edit and Delete Buttons -->
                        <a href="edit_category.php?id=<?= $category['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="category.php?delete_id=<?= $category['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
<?php include 'sidebar.php'; ?>

</html>