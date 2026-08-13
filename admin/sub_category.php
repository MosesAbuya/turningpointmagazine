<?php
include 'connection2.php';
session_start();

include 'consent.php';

// Create a database connection
$pdo = connect();

// Handle adding a new subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subcategory'])) {
    $subcategory_name = $_POST['subcategory_name'];
    $subcategory_description = $_POST['subcategory_description'];
    $category_id = $_POST['category_id'];

    // Insert the new subcategory into the database
    $insertSubcategoryQuery = "INSERT INTO sub_category (name, description, category_id) VALUES (:subcategory_name, :subcategory_description, :category_id)";
    $stmt = $pdo->prepare($insertSubcategoryQuery);
    $stmt->execute([
        'subcategory_name' => $subcategory_name,
        'subcategory_description' => $subcategory_description,
        'category_id' => $category_id
    ]);

    header('Location: sub_category.php');
    exit;
}

// Handle editing an existing subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_subcategory'])) {
    $subcategory_id = $_POST['subcategory_id'];
    $subcategory_name = $_POST['subcategory_name'];
    $subcategory_description = $_POST['subcategory_description'];
    $category_id = $_POST['category_id'];

    // Update the subcategory in the database
    $updateSubcategoryQuery = "UPDATE sub_category SET name = :subcategory_name, description = :subcategory_description, category_id = :category_id WHERE id = :subcategory_id";
    $stmt = $pdo->prepare($updateSubcategoryQuery);
    $stmt->execute([
        'subcategory_id' => $subcategory_id,
        'subcategory_name' => $subcategory_name,
        'subcategory_description' => $subcategory_description,
        'category_id' => $category_id
    ]);

    header('Location: sub_category.php');
    exit;
}

// Handle deleting a subcategory
if (isset($_GET['delete_id'])) {
    $subcategory_id = $_GET['delete_id'];

    // Delete the subcategory from the database
    $deleteSubcategoryQuery = "DELETE FROM sub_category WHERE id = :subcategory_id";
    $stmt = $pdo->prepare($deleteSubcategoryQuery);
    $stmt->execute(['subcategory_id' => $subcategory_id]);

    header('Location: sub_category.php');
    exit;
}

// Fetch all categories
$query = "SELECT * FROM categories";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all subcategories
$query = "SELECT sub_category.*, categories.name AS category_name FROM sub_category JOIN categories ON sub_category.category_id = categories.id";
$stmt = $pdo->query($query);
$subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the connection
closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subcategory Management</title>
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
        <h2 class="text-center underline">Subcategory Management</h2>
        <div class="sep"></div>

        <!-- Add New Subcategory Form -->
        <form method="POST" class="mb-4">
            <h4 class="h4">Add New Subcategory</h4>
            <div class="form-group">
                <label for="subcategory_name">Subcategory Name</label>
                <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" required>
            </div>
            <div class="form-group">
                <label for="subcategory_description">Subcategory Description</label>
                <textarea class="form-control" id="subcategory_description" name="subcategory_description"></textarea>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_subcategory">Add Subcategory</button>
        </form>

        <!-- Subcategories List -->
        <h4 class="h4">Subcategories List</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subcategories as $subcategory): ?>
                <tr>
                    <td><?= htmlspecialchars($subcategory['name']) ?></td>
                    <td><?= htmlspecialchars($subcategory['description']) ?></td>
                    <td><?= htmlspecialchars($subcategory['category_name']) ?></td>
                    <td>
                        <!-- Edit and Delete Buttons -->
                        <a href="edit_subcategory.php?id=<?= $subcategory['id'] ?>"
                            class="btn btn-warning btn-sm">Edit</a>
                        <a href="sub_category.php?delete_id=<?= $subcategory['id'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this subcategory?')">Delete</a>
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