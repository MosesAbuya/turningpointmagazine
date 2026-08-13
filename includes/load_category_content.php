<?php
// Include the connection script
include 'connection2.php';

// Check if the category ID is passed via GET
$category_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($category_id) {
    $pdo = connect();

    // Fetch content based on the category ID (you can modify this to fetch specific category content)
    try {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            // You can modify the below output to display the content for the selected category
            ?>
<h2><?= htmlspecialchars($category['name']) ?></h2>
<p>Content for the category goes here...</p>
<?php
        } else {
            echo "Category not found.";
        }

    } catch (PDOException $e) {
        die("Error fetching category content: " . $e->getMessage());
    }

    // Close the connection
    closeConnection($pdo);
} else {
    echo "Category ID not provided.";
}
?>