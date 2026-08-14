<?php
require_once('connection2.php');
$pdo = connect();

// 1. GET THE BLOG POST BASED ON THE SLUG
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$current_blog = null;

if ($slug) {
    // Fetch all titles to find the matching slug (since we don't have a slug column)
    $stmt = $pdo->query("SELECT id, title FROM blog");
    $all_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($all_blogs as $b) {
        // Recreate the slug logic to match the URL
        $s = strtolower(preg_replace('/\s+/', '-', $b['title']));
        $s = preg_replace('/[^a-z0-9\-]/', '', $s);
        $s = preg_replace('/-+/', '-', $s);
        $s = trim($s, '-');

        if ($s === $slug) {
            $current_id = $b['id'];
            break;
        }
    }

    if (isset($current_id)) {
        // Fetch the full post details
        $stmt = $pdo->prepare("SELECT * FROM blog WHERE id = :id");
        $stmt->execute(['id' => $current_id]);
        $current_blog = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Redirect if not found
if (!$current_blog) {
    header("Location: /blog");
    exit;
}

// Generate Meta Description Excerpt
$excerpt = strip_tags(html_entity_decode($current_blog['description'] ?? ''));
$excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
$meta_description = mb_strlen($excerpt) > 155 ? mb_substr($excerpt, 0, 155) . '...' : $excerpt;

// 2. FETCH RECENT POSTS (For the Sidebar)
// Get 4 recent posts, excluding the current one
$stmt = $pdo->prepare("SELECT * FROM blog WHERE id != :id ORDER BY date DESC LIMIT 4");
$stmt->execute(['id' => $current_blog['id']]);
$recent_blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

closeConnection($pdo);

// Helper for generating links
function make_slug($string) {
    $s = strtolower(preg_replace('/\s+/', '-', $string));
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);
    $s = preg_replace('/-+/', '-', $s);
    return trim($s, '-');
}

// SIMULATED AI GENERATED SUMMARY
$ai_summary = "In a rapidly evolving landscape, this piece explores the critical intersections of policy, culture, and innovation. Key takeaways include the shifting dynamics of local economies, the rise of grassroots movements, and the undeniable impact of digital transformation on traditional sectors.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://www.turningpointmagazine.africa/blog/<?php echo htmlspecialchars($slug); ?>">
    <title><?= htmlspecialchars($current_blog['title']) ?> - Turning Point Magazine</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>design-system.css">
    <link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>includes/new-navbar.css">

    <!-- Open Graph & Twitter Cards -->
    <meta property="og:title" content="<?= htmlspecialchars($current_blog['title']) ?> - Turning Point Magazine" />
    <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://www.turningpointmagazine.africa/blog/<?= htmlspecialchars($slug) ?>" />
    <meta property="og:image" content="https://www.turningpointmagazine.africa/admin/<?= htmlspecialchars($current_blog['top_photo']) ?>" />
    <meta name="twitter:card" content="summary_large_image" />

    <style>
        /* --- PROGRESS BAR --- */
        #reading-progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            z-index: 9999;
        }
        #reading-progress-bar {
            height: 100%;
            background: var(--tp-crimson);
            width: 0%;
        }

        /* --- ARTICLE HEADER --- */
        .article-hero {
            position: relative;
            width: 100%;
            height: 60vh;
            background-color: var(--tp-grey-900);
            display: flex;
            align-items: flex-end;
            margin-bottom: var(--space-8);
        }
        .article-hero img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4;
        }
        .article-hero-content {
            position: relative;
            z-index: 2;
            padding: var(--space-8);
            width: 100%;
            max-width: var(--container-reading);
            margin: 0 auto;
        }
        .article-hero-content h1 {
            color: var(--tp-white);
            font-size: var(--text-5xl);
            margin-bottom: var(--space-4);
        }
        .article-hero-meta {
            color: var(--tp-grey-300);
            font-family: var(--font-sans);
            font-size: var(--text-sm);
            display: flex;
            gap: var(--space-4);
            align-items: center;
        }

        /* --- AI SUMMARY & AUDIO --- */
        .ai-tools-container {
            background-color: var(--tp-grey-100);
            border-top: 4px solid var(--tp-black);
            padding: var(--space-6);
            margin-bottom: var(--space-8);
        }
        .ai-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-4);
        }
        .ai-title {
            font-family: var(--font-sans);
            font-weight: 700;
            font-size: var(--text-base);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .ai-audio-player {
            display: flex;
            align-items: center;
            gap: 15px;
            background: var(--tp-white);
            padding: var(--space-2) var(--space-4);
            border-radius: 50px;
            border: var(--border-thin);
        }
        .ai-audio-btn {
            background: var(--tp-crimson);
            color: var(--tp-white);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .ai-audio-track {
            height: 4px;
            width: 100px;
            background: var(--tp-grey-300);
            border-radius: 2px;
            position: relative;
        }
        .ai-audio-progress {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 30%;
            background: var(--tp-crimson);
            border-radius: 2px;
        }

        .ai-summary-text {
            font-family: var(--font-serif);
            font-size: var(--text-lg);
            font-style: italic;
            color: var(--tp-grey-800);
            border-left: 2px solid var(--tp-crimson);
            padding-left: var(--space-4);
        }

        /* --- CONTENT STYLING --- */
        .article-body {
            font-family: var(--font-serif);
            font-size: var(--text-lg);
            line-height: 1.8;
            color: var(--tp-black);
        }
        .article-body p {
            margin-bottom: var(--space-6);
        }
        .article-body img {
            max-width: 100%;
            height: auto;
            margin: var(--space-6) 0;
        }
        
        /* Premium Pull Quotes */
        blockquote {
            margin: var(--space-8) 0;
            padding: 0 var(--space-8);
            border-left: 4px solid var(--tp-crimson);
            font-size: var(--text-2xl);
            font-style: italic;
            color: var(--tp-crimson-dark);
            line-height: 1.4;
            position: relative;
        }
        blockquote::before {
            content: "“";
            position: absolute;
            top: -20px;
            left: -10px;
            font-size: 80px;
            color: rgba(201, 0, 0, 0.1);
            font-family: var(--font-serif);
        }

    </style>
</head>
<body>

    <div id="reading-progress-container">
        <div id="reading-progress-bar"></div>
    </div>

    <?php include 'includes/new-navbar.php'; ?>

    <article>
        <!-- HERO SECTION -->
        <header class="article-hero">
            <img src="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>admin/<?= htmlspecialchars($current_blog['top_photo']) ?>" alt="Cover Image">
            <div class="article-hero-content">
                <span class="kicker" style="color: var(--tp-white);">Features</span>
                <h1><?= htmlspecialchars($current_blog['title']) ?></h1>
                <div class="article-hero-meta">
                    <span>By Turning Point Editorial</span>
                    <span>•</span>
                    <span><?= date('F j, Y', strtotime($current_blog['date'])) ?></span>
                    <span>•</span>
                    <span>5 Min Read</span>
                </div>
            </div>
        </header>

        <div class="reading-container">
            
            <!-- AI TOOLS SECTION -->
            <div class="ai-tools-container">
                <div class="ai-header">
                    <div class="ai-title">
                        <i class="fas fa-robot" style="color: var(--tp-crimson);"></i> AI Summary (TL;DR)
                    </div>
                    <div class="ai-audio-player">
                        <button class="ai-audio-btn"><i class="fas fa-play"></i></button>
                        <span style="font-size: 12px; font-family: var(--font-sans); font-weight: 600;">Listen to Article</span>
                        <div class="ai-audio-track">
                            <div class="ai-audio-progress"></div>
                        </div>
                        <span style="font-size: 12px; color: var(--tp-grey-800);">5:20</span>
                    </div>
                </div>
                <div class="ai-summary-text">
                    <?= $ai_summary ?>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="article-body">
                <?php 
                    // Decode and display the full article content.
                    // The CSS we added will naturally style the text and images nicely.
                    // If the user's content has blockquotes, our premium styling will take over.
                    echo html_entity_decode($current_blog['full_article']);
                ?>
            </div>

            <hr class="tp-divider">
            
        </div>
    </article>

    <script>
        // Reading Progress Bar
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById('reading-progress-bar').style.width = scrolled + '%';
        });

        // AI Audio Simulator
        const audioBtn = document.querySelector('.ai-audio-btn');
        let isPlaying = false;
        audioBtn.addEventListener('click', () => {
            isPlaying = !isPlaying;
            if(isPlaying) {
                audioBtn.innerHTML = '<i class="fas fa-pause"></i>';
                audioBtn.style.backgroundColor = 'var(--tp-black)';
            } else {
                audioBtn.innerHTML = '<i class="fas fa-play"></i>';
                audioBtn.style.backgroundColor = 'var(--tp-crimson)';
            }
        });
    </script>
</body>
</html>
