<?php
session_start();
include('connection2.php');
require_once ('shop/inc/Database.php');

$pdo = connect();

// 1. Fetch Editions (For Hero Bento Box)
try {
  $stmt = $pdo->query("SELECT id, front_page_image, edition_name, price FROM editions ORDER BY id DESC LIMIT 4");
  $editions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching editions: " . $e->getMessage());
}

// 2. Fetch Latest Blogs (For Masonry Grid)
try {
  $stmt = $pdo->query("SELECT * FROM blog ORDER BY id DESC LIMIT 6");
  $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Error fetching blogs: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Turning Point Magazine - Africa's Premier Editorial</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design-system.css">
    
    <!-- We still need the universal navbar styles -->
    <link rel="stylesheet" href="includes/new-navbar.css">
    
    <style>
        /* Specific Page Styles for index_v2.php */
        
        /* 1. Bento Box Hero Section */
        .bento-hero {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: var(--space-4);
            margin-top: var(--space-8);
            min-height: 80vh;
        }
        
        @media (max-width: 992px) {
            .bento-hero {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }
        }

        .bento-main {
            grid-row: span 2;
            background-color: var(--tp-grey-900);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            border-radius: 8px; /* Optional slight rounding */
        }
        
        .bento-main img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
            transition: opacity var(--transition-base), transform var(--transition-base);
        }
        
        .bento-main:hover img {
            opacity: 0.5;
            transform: scale(1.03);
        }

        .bento-main-content {
            position: relative;
            z-index: 2;
            padding: var(--space-8);
            color: var(--tp-white);
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0) 100%);
            width: 100%;
        }

        .bento-main-content h1 {
            color: var(--tp-white);
            font-size: var(--text-5xl);
        }

        .bento-side {
            background-color: var(--tp-white);
            border: var(--border-thin);
            padding: var(--space-6);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 8px;
            transition: box-shadow var(--transition-fast);
        }
        
        .bento-side:hover {
            box-shadow: var(--shadow-md);
        }

        /* 2. Smart Brevity Element */
        .smart-brevity-box {
            background-color: var(--tp-grey-100);
            border-left: 4px solid var(--tp-crimson);
            padding: var(--space-3) var(--space-4);
            margin: var(--space-4) 0;
            font-family: var(--font-sans);
            font-size: var(--text-sm);
        }
        .smart-brevity-box strong {
            color: var(--tp-crimson);
        }
        
        /* 3. Masonry Grid */
        .masonry-grid {
            column-count: 3;
            column-gap: var(--space-6);
            margin-top: var(--space-8);
        }
        
        @media (max-width: 992px) {
            .masonry-grid { column-count: 2; }
        }
        @media (max-width: 768px) {
            .masonry-grid { column-count: 1; }
        }

        .masonry-item {
            break-inside: avoid;
            margin-bottom: var(--space-6);
            background: var(--tp-white);
            border: var(--border-thin);
            overflow: hidden;
            border-radius: 8px;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast);
        }
        
        .masonry-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .masonry-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        .masonry-content {
            padding: var(--space-4);
        }

        .masonry-content h3 {
            font-size: var(--text-xl);
            margin-bottom: var(--space-2);
        }
    </style>
</head>
<body class="tp-home-page">

    <?php include 'includes/new-navbar.php'; ?>
    
    <div class="container section-padding">
        
        <!-- TOP KICKER -->
        <div style="text-align: center; margin-bottom: var(--space-8);">
            <span class="kicker">The Global African Voice</span>
            <hr class="tp-divider-accent" style="margin: var(--space-2) auto;">
        </div>
        
        <!-- BENTO BOX HERO -->
        <div class="bento-hero">
            <?php if (!empty($editions)): 
                $mainEdition = $editions[0];
            ?>
            <!-- Main Cover Story -->
            <a href="issue-1.php?edition_id=<?= $mainEdition['id'] ?>" class="bento-main">
                <img src="admin/<?= htmlspecialchars($mainEdition['front_page_image']) ?>" alt="<?= htmlspecialchars($mainEdition['edition_name']) ?>">
                <div class="bento-main-content">
                    <span class="kicker" style="color: var(--tp-white);">Latest Edition</span>
                    <h1 class="heading-display"><?= htmlspecialchars($mainEdition['edition_name']) ?></h1>
                    <p class="subheadline" style="color: var(--tp-grey-300);">An exclusive deep dive into the stories defining the continent right now.</p>
                </div>
            </a>
            <?php endif; ?>
            
            <?php if (count($editions) > 1): ?>
            <!-- Secondary Story -->
            <a href="issue-1.php?edition_id=<?= $editions[1]['id'] ?>" class="bento-side">
                <div>
                    <span class="kicker">Archive Collection</span>
                    <h2 class="heading-2"><?= htmlspecialchars($editions[1]['edition_name']) ?></h2>
                    <p class="meta-text">Revisit one of our most acclaimed editions.</p>
                </div>
                
                <div class="smart-brevity-box">
                    <strong>Why it matters:</strong> A groundbreaking issue covering technological innovation across East Africa.
                </div>
                <div class="tp-image-card" style="margin-top: var(--space-4);">
                     <img src="admin/<?= htmlspecialchars($editions[1]['front_page_image']) ?>" class="img-fluid" style="max-height: 200px; object-fit: cover;">
                </div>
            </a>
            <?php endif; ?>
            
            <?php if (count($editions) > 2): ?>
            <!-- Tertiary Story -->
            <a href="issue-1.php?edition_id=<?= $editions[2]['id'] ?>" class="bento-side" style="background-color: var(--tp-black); color: var(--tp-white); border: none;">
                <div>
                    <span class="kicker" style="color: var(--tp-grey-300);">Culture Focus</span>
                    <h2 class="heading-2" style="color: var(--tp-white);"><?= htmlspecialchars($editions[2]['edition_name']) ?></h2>
                    <p class="meta-text" style="color: var(--tp-grey-300);">Celebrating art and heritage.</p>
                </div>
                <div class="tp-image-card" style="margin-top: var(--space-4); border-radius: 4px;">
                     <img src="admin/<?= htmlspecialchars($editions[2]['front_page_image']) ?>" class="img-fluid" style="max-height: 150px; object-fit: cover; opacity: 0.8;">
                </div>
            </a>
            <?php endif; ?>
        </div>
        
        <hr class="tp-divider-thick">
        
        <!-- MASONRY CULTURE GRID -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <h2 class="heading-1" style="margin-bottom: 0;">Culture & News</h2>
            <a href="blog.php" class="kicker">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="masonry-grid">
            <?php foreach ($blogs as $blog): 
                // Creating a random height class just to simulate masonry flow nicely if images are uniform
                // Usually masonry relies on varying image aspect ratios.
            ?>
            <a href="single-blog.php?id=<?= $blog['id'] ?>" class="masonry-item">
                <div class="tp-image-card">
                    <img src="admin/<?= htmlspecialchars($blog['top_photo']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
                </div>
                <div class="masonry-content">
                    <span class="kicker" style="font-size: 0.7rem;"><?= date('F j, Y', strtotime($blog['date'])) ?></span>
                    <h3><?= htmlspecialchars($blog['title']) ?></h3>
                    <p class="meta-text" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php 
                        // Simulate a small excerpt by stripping tags from full_article
                        echo substr(strip_tags($blog['full_article']), 0, 150) . '...'; 
                        ?>
                    </p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        
    </div>

    <!-- Data-Light Toggle Script (For Africa Optimizations) -->
    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <button id="data-light-toggle" class="btn-primary" style="border-radius: 50px; box-shadow: var(--shadow-lg);">
            <i class="fas fa-bolt"></i> Data-Light Mode
        </button>
    </div>

    <script>
        document.getElementById('data-light-toggle').addEventListener('click', function() {
            document.body.classList.toggle('data-light-mode');
            if(document.body.classList.contains('data-light-mode')) {
                this.innerHTML = '<i class="fas fa-image"></i> Show Images';
                this.style.backgroundColor = 'var(--tp-black)';
            } else {
                this.innerHTML = '<i class="fas fa-bolt"></i> Data-Light Mode';
                this.style.backgroundColor = 'var(--tp-crimson)';
            }
        });
    </script>
</body>
</html>
