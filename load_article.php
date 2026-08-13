<?php
include('connection2.php');
$pdo = connect();

$category_id = isset($_GET['id']) ? $_GET['id'] : null;
$sub_category_id = isset($_GET['sub_category_id']) ? $_GET['sub_category_id'] : null;

if (!$category_id || !$sub_category_id) {
    echo "<p>Error: Missing category information.</p>";
    exit();
}

// Fetch articles based on BOTH category and sub_category
$query = "
    SELECT 
        a.id, a.title, a.catch_phrase, a.writer, a.edition_id, a.top_image, e.edition_name
    FROM articles a
    LEFT JOIN editions e ON a.edition_id = e.id
    WHERE a.category_id = :category_id AND a.subcategory_id = :sub_category_id
    ORDER BY a.id DESC
";
// --- THIS LINE (ABOVE) IS THE FIX ---
// I changed 'a.sub_category_id' to 'a.subcategory_id' to match your table.

$stmt = $pdo->prepare($query);
$stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$stmt->bindParam(':sub_category_id', $sub_category_id, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($articles)) {
    echo "<p style='text-align:center; padding: 40px;'>No articles found in this sub-category.</p>";
    exit();
}

// Loop through and generate the new themed HTML
foreach ($articles as $article) {
?>
<a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>article/<?= generate_slug($article['title']) ?>" class="article-list-card">

    <div class="article-image-wrapper">
        <img loading="lazy" src="admin/<?= htmlspecialchars($article['top_image']) ?>"
            alt="<?= htmlspecialchars($article['title']) ?>">
    </div>

    <div class="article-content">
        <h3><?= htmlspecialchars($article['title']) ?></h3>

        <p class="catchphrase"><?= htmlspecialchars($article['catch_phrase']) ?></p>

        <div class="article-meta">
            <span>
                <i class="fas fa-user-edit"></i> <?= htmlspecialchars($article['writer']) ?>
            </span>
            <span>
                <i class="fas fa-book-open"></i> <?= htmlspecialchars($article['edition_name']) ?>
            </span>
        </div>
    </div>

</a>
<?php
}
?>
