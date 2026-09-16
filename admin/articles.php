<?php
include 'connection2.php';
session_start();
include 'consent.php';

// Validate edition_id parameter
$editionId = filter_input(INPUT_GET, 'edition_id', FILTER_VALIDATE_INT);
if ($editionId === false || $editionId === null) {
    die("Invalid edition ID specified");
}

$pdo = connect();

// Fetch articles filtered by edition_id
$query = "SELECT id, title, top_image, writer 
          FROM articles 
          WHERE edition_id = :edition_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':edition_id' => $editionId]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    
    </style>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>


<body id="body">
    <?php include "nav.php"; ?>
    <?php include "sidebar.php"; ?>
    <div id="page-content-wrapper">
    <div class="container-fluid">
        <h2 class="fw-bold mb-4">Articles</h2>
        <div class="sep"></div>
        <div class="row">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($article['top_image'])): ?>
                        <img loading="lazy" src="<?= htmlspecialchars($article['top_image']) ?>" class="card-img-top" alt="Article Image">
                        <?php else: ?>
                        <img loading="lazy" src="assets/uploads/default.jpg" class="card-img-top" alt="Default Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                            <p class="card-text"><?= $article['writer'] ?></p>
                            <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-warning" id="btn-wide">
                                Edit</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No articles found for this edition.</p>
                </div>
            <?php endif; ?>
        </div>
        <a href="add_article.php?edition_id=<?= $editionId ?>" class="btn btn-primary mt-4">Add New Article</a>
    </div>
    </div>
</body>

</html>

