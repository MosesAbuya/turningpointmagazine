<?php
require_once('connection2.php');
$pdo = connect();

header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$base_url = 'https://www.turningpointmagazine.africa';

$static_pages = [
    '/',
    '/about',
    '/contact',
    '/blog',
    '/story'
];

$date_now = date('Y-m-d\TH:i:sP');

foreach ($static_pages as $page) {
    echo "  <url>\n";
    echo "      <loc>" . htmlspecialchars($base_url . $page) . "</loc>\n";
    echo "      <lastmod>" . $date_now . "</lastmod>\n";
    echo "      <priority>0.8</priority>\n";
    echo "  </url>\n";
}

// Blog Posts
try {
    $stmt = $pdo->query("SELECT id, title, date FROM blog");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($blogs as $b) {
        $s = generate_slug($b['title']);
        $date = !empty($b['date']) ? date('Y-m-d\TH:i:sP', strtotime($b['date'])) : $date_now;

        echo "  <url>\n";
        echo "      <loc>" . htmlspecialchars($base_url . '/blog/' . $s) . "</loc>\n";
        echo "      <lastmod>" . $date . "</lastmod>\n";
        echo "      <priority>0.7</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {}

// Articles
try {
    $stmt = $pdo->query("SELECT id, title FROM articles");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($articles as $a) {
        echo "  <url>\n";
        echo "      <loc>" . htmlspecialchars($base_url . '/article/' . generate_slug($a['title'])) . "</loc>\n";
        echo "      <lastmod>" . $date_now . "</lastmod>\n";
        echo "      <priority>0.9</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {}

// Editions
try {
    $stmt = $pdo->query("SELECT id, edition_name FROM editions");
    $editions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($editions as $e) {
        echo "  <url>\n";
        echo "      <loc>" . htmlspecialchars($base_url . '/edition/' . generate_slug($e['edition_name'])) . "</loc>\n"; 
        echo "      <lastmod>" . $date_now . "</lastmod>\n";
        echo "      <priority>0.8</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {}

// Categories
try {
    $stmt = $pdo->query("SELECT id, name FROM categories");
    $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cats as $c) {
        echo "  <url>\n";
        echo "      <loc>" . htmlspecialchars($base_url . '/category/' . generate_slug($c['name'])) . "</loc>\n"; 
        echo "      <lastmod>" . $date_now . "</lastmod>\n";
        echo "      <priority>0.8</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {}

echo '</urlset>';
?>
