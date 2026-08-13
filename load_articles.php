<?php
include 'connection2.php';

// Get the 'id' (category_id) and 'sub_category_id' from the request
$category_id = isset($_GET['id']) ? $_GET['id'] : null;
$sub_category_id = isset($_GET['sub_category_id']) ? $_GET['sub_category_id'] : null;

if ($category_id) {
    $pdo = connect();

    // Fetch articles based on 'category_id' and filter by 'sub_category_id' if provided
    if ($sub_category_id) {
        $articleQuery = "SELECT * FROM articles WHERE category_id = :category_id AND subcategory_id = :sub_category_id";
        $stmtArticle = $pdo->prepare($articleQuery);
        $stmtArticle->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmtArticle->bindParam(':sub_category_id', $sub_category_id, PDO::PARAM_INT);
    } else {
        $articleQuery = "SELECT * FROM articles WHERE category_id = :category_id";
        $stmtArticle = $pdo->prepare($articleQuery);
        $stmtArticle->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    }

    $stmtArticle->execute();
    $articles = $stmtArticle->fetchAll(PDO::FETCH_ASSOC);

    // Output the articles as HTML
    foreach ($articles as $article) {
        // Fetch edition data for each article
        $editionQuery = "SELECT * FROM editions WHERE id = :edition_id";
        $stmtEdition = $pdo->prepare($editionQuery);
        $stmtEdition->bindParam(':edition_id', $article['edition_id'], PDO::PARAM_INT);
        $stmtEdition->execute();
        $edition = $stmtEdition->fetch(PDO::FETCH_ASSOC);
        ?>
<a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?= generate_slug($article['title']) ?>" id="a-cat">
    <h2 style="text-align:center; color: red; padding: 10px;"><?= htmlspecialchars($article['title']) ?></h2>
    <div class="cat-item" id="cat-item">
        <div class="cat-col">
            <h4><?= htmlspecialchars($edition['date']) ?></h4>
            <h5><?= htmlspecialchars($edition['edition_name']) ?></h5>
        </div>
        <div class="cat-col">
            <p><?= nl2br(htmlspecialchars($article['catch_phrase'])) ?></p>
            <p id="lft-tx" style="color:black; font-size: 0.9rem; font-weight:600; text-align:left;">By:
                <?= htmlspecialchars($article['writer']) ?></p>
        </div>
        <div class="cat-col">
            <img loading="lazy" src="admin/<?= htmlspecialchars($article['top_image']) ?>" alt="">
        </div>
    </div>
</a>
<?php
    }

    closeConnection($pdo);
}
?>
