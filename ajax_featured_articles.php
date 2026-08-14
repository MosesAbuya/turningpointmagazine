<?php
require_once 'connection2.php';
$pdo = connect();

// We need generate_slug function if it's not in connection2.php
if (!function_exists('generate_slug')) {
    function generate_slug($string) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return preg_replace('/-+/', '-', $slug);
    }
}

$target_edition_ids = [];
$editionsQuery = $pdo->query("SELECT id, edition_name FROM editions");
$editions_list = $editionsQuery->fetchAll(PDO::FETCH_ASSOC);
foreach ($editions_list as $e) {
    $slug = generate_slug($e['edition_name']);
    if ($slug === 'first-edition' || $slug === 'second-edition') {
        $target_edition_ids[] = $e['id'];
    }
}

$featured_articles = [];
if (!empty($target_edition_ids)) {
    $placeholders = implode(',', array_fill(0, count($target_edition_ids), '?'));
    // Fetch top 9 articles, prioritizing top story, then randomized
    $stmt = $pdo->prepare("SELECT a.id, a.title, a.writer, a.top_image, a.edition_id, e.edition_name 
                           FROM articles a 
                           JOIN editions e ON a.edition_id = e.id 
                           WHERE a.edition_id IN ($placeholders) 
                           ORDER BY a.is_top_story DESC, RAND() LIMIT 9");
    $stmt->execute($target_edition_ids);
    $featured_articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!empty($featured_articles)) {
    include 'includes/featured_grid.php';
}
?>
