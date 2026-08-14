<?php 
$count = 0;
foreach ($featured_articles as $article): 
    $count++;
    $bg_img = (defined('BASE_URL') ? BASE_URL : '/turningpoint/') . "admin/" . htmlspecialchars($article['top_image']);
    $article_url = (defined('BASE_URL') ? BASE_URL : '/turningpoint/') . "article/" . generate_slug($article['title']) . "?id=" . $article['id'];
    
    // Make the first item larger
    if ($count === 1) {
        $grid_class = 'tp-masonry-large';
        $grid_style = 'grid-column: span 2; grid-row: span 2;';
    } else {
        $grid_class = 'tp-masonry-small';
        $grid_style = '';
    }
?>
<a href="<?= $article_url ?>" class="tp-masonry-item <?= $grid_class ?>" style="<?= $grid_style ?> display: block; position: relative; border-radius: 8px; overflow: hidden; text-decoration: none;">
    <div class="tp-masonry-bg" style="position: absolute; top:0; left:0; width:100%; height:100%; background-image: url('<?= $bg_img ?>'); background-size: cover; background-position: center; transition: transform 0.5s ease;"></div>
    <div class="tp-masonry-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0) 100%); transition: background 0.3s ease;"></div>
    
    <div class="tp-masonry-content" style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px; box-sizing: border-box;">
        <span class="tp-masonry-badge" style="display: inline-block; background-color: #cc0000; color: white; padding: 4px 10px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; border-radius: 4px; margin-bottom: 10px; letter-spacing: 1px;">
            <?= htmlspecialchars($article['edition_name']) ?>
        </span>
        <h3 class="tp-masonry-title" style="color: white; margin: 0 0 5px 0; font-family: 'Playfair Display', serif; <?= ($count === 1) ? 'font-size: 2rem;' : 'font-size: 1.3rem;' ?> line-height: 1.2; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
            <?= htmlspecialchars($article['title']) ?>
        </h3>
        <?php if (!empty($article['writer'])): ?>
        <p class="tp-masonry-writer" style="color: #ddd; margin: 0; font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif;">
            By <?= htmlspecialchars($article['writer']) ?>
        </p>
        <?php endif; ?>
    </div>
</a>
<?php endforeach; ?>
