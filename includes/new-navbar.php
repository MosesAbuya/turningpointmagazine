<?php
include_once('connection2.php'); 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

<link rel="stylesheet" href="new-navbar.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">


<nav class="tp-main-nav" id="tp-main-nav">
    <div class="tp-main-nav-content">
        <div class="tp-nav-logo">
            <a href="index.php">
                <h1 class="tp-logo-text">Turning Point</h1>
            </a>
        </div>

        <div class="tp-hamburger" id="tp-hamburger-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <ul class="tp-nav-menu" id="tp-main-menu">
            <li><a href="index.php">Home</a></li>
            <!--<li class="tp-dropdown">-->
            <!--    <a href="#">Features <span class="tp-arrow-span">▼</span></a>-->
            <!--    <ul class="tp-dropdown-menu">-->
            <!--        <li><a href="index.php#latest">Latest Issue</a></li>-->
            <!--        <li><a href="index.php#collection">Library</a></li>-->
            <!--        <li><a href="index.php#subscribe">Subscribe</a></li>-->
            <!--        <li><a href="index.php#promo-advertise">Advertise</a></li>-->
            <!--    </ul>-->
            <!--</li>-->
            <li><a id="about" href="about.php">About</a></li>
            <li><a id="contact" href="contact.php">Contact</a></li>
            <li><a id="story" href="story.php">Contribute</a></li>
            <li><a id="subscribe" href="index.php#subscribe">Subscribe</a></li>
            <li><a id="shop" href="shop.php" target="_blank">Shop</a></li>

            <li class="tp-search-mobile-wrapper">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px; padding: 10px;">
                <select class="custom-lang-select" onchange="setLanguage(this.value)" onmouseover="this.style.backgroundColor='red'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';" title="Translate Website" style="background: transparent; background-color: transparent; border: 1px solid rgba(255,255,255,0.5); color: white; padding: 5px; border-radius: 4px; font-size: 14px; cursor: pointer; outline: none; height: 35px; transition: all 0.3s ease;">
                    <option value="en">🇬🇧 EN</option>
                    <option value="fr">🇫🇷 FR</option>
                    <option value="de">🇩🇪 DE</option>
                    <option value="es">🇪🇸 ES</option>
                    <option value="pt">🇵🇹 PT</option>
                    <option value="zh-CN">🇨🇳 ZH</option>
                    <option value="ar">🇸🇦 AR</option>
                    <option value="sw">🇰🇪 SW</option>
                </select>
                <div class="tp-search-module" style="width: auto; flex: 0 0 auto;">
                    <button onclick="document.getElementById('tp-search-modal').style.display='flex'; document.getElementById('tp-modal-search-input').focus();" class="tp-search-button" style="position: relative !important; top: auto !important; right: auto !important; transform: none !important; border:none; background:none; color:white; font-size:20px; cursor:pointer; padding:5px 10px;"><i class="fas fa-search"></i></button>
                </div>
                </div>
            </li>
        </ul>
        
        <div class="tp-search-desktop-wrapper" style="display: flex; align-items: center; max-width: 400px; margin-left: 30px;">
            <select class="custom-lang-select" onchange="setLanguage(this.value)" onmouseover="this.style.backgroundColor='red'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';" title="Translate Website" style="background: transparent; background-color: transparent; border: 1px solid rgba(255,255,255,0.5); color: white; padding: 5px; border-radius: 4px; font-size: 14px; cursor: pointer; outline: none; height: 35px; margin-right: 15px; transition: all 0.3s ease;">
                <option value="en">🇬🇧 EN</option>
                <option value="fr">🇫🇷 FR</option>
                <option value="de">🇩🇪 DE</option>
                <option value="es">🇪🇸 ES</option>
                <option value="pt">🇵🇹 PT</option>
                <option value="zh-CN">🇨🇳 ZH</option>
                <option value="ar">🇸🇦 AR</option>
                <option value="sw">🇰🇪 SW</option>
            </select>
            
            <div class="tp-search-module" style="width: auto; flex: 0 0 auto;">
                <button onclick="document.getElementById('tp-search-modal').style.display='flex'; document.getElementById('tp-modal-search-input').focus();" class="tp-search-button" style="position: relative !important; top: auto !important; right: auto !important; transform: none !important; border:none; background:none; color:white; font-size:20px; cursor:pointer; padding:5px 10px;"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
</nav>

<div class="tp-sub-nav" id="tp-sub-nav">
    <div class="tp-sub-nav-content">
        <button class="tp-menu-toggle" id="tp-menu-toggle">☰ Categories</button>

        <div class="tp-menu-row" id="tp-mobile-links-menu">
            <a href="directories.php">Directories</a>
            <a href="spotlight.php">Spotlight</a>
            <a href="awards.php">Awards</a>
        </div>

        <button class="tp-mobile-links-toggle" id="tp-mobile-links-toggle">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <button class="tp-sub-nav-toggle" id="tp-sub-nav-toggle">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
</div>

<div class="tp-sidebar">
    <?php
    $pdo_sidebar = connect();
    try {
        $categoriesStmt_sidebar = $pdo_sidebar->query("SELECT id, name FROM categories");
        $categories_sidebar = $categoriesStmt_sidebar->fetchAll(PDO::FETCH_ASSOC);
        $subcategoriesStmt_sidebar = $pdo_sidebar->prepare("SELECT id, name FROM sub_category WHERE category_id = :category_id");
    } catch (PDOException $e) {
        die("Error fetching categories: " . $e->getMessage());
    }
    ?>
    <nav class="tp-sidebar-menu">
        <div class="tp-sidebar-header">
            <h2 class="tp-sidebar-title">All Categories</h2>
            <button class="tp-close-btn">✖</button>
        </div>
        <ul>
            <?php foreach ($categories_sidebar as $category_sidebar): ?>
            <li>
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category_sidebar['name']) ?>">
                    <?= htmlspecialchars($category_sidebar['name']) ?>
                    <span class="tp-sidebar-arrow">›</span>
                </a>
                <?php
                $subcategoriesStmt_sidebar->execute(['category_id' => $category_sidebar['id']]);
                $subcategories_sidebar = $subcategoriesStmt_sidebar->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($subcategories_sidebar)): ?>
                <ul>
                    <?php foreach ($subcategories_sidebar as $subcategory_sidebar): ?>
                    <li>
                        <a
                            href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category_sidebar['name']) ?>?sub_category_id=<?= htmlspecialchars($subcategory_sidebar['id']) ?>">
                            <?= htmlspecialchars($subcategory_sidebar['name']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php
    closeConnection($pdo_sidebar);
    ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // --- Scroll Behavior ---
    // --- Scroll Behavior ---
    const mainNav = document.getElementById('tp-main-nav');
    const subNav = document.getElementById('tp-sub-nav');
    const subNavToggle = document.getElementById('tp-sub-nav-toggle');

    function handleScroll() {
        // CHECK: If this is the home page, ignore this default logic
        // The home page will handle its own scroll logic via index.php script
        if (document.body.classList.contains('tp-home-page')) return;

        if (window.scrollY > 0) {
            mainNav.classList.add('is-scrolled');
            subNav.classList.add('is-scrolled');
        } else {
            mainNav.classList.remove('is-scrolled');
            subNav.classList.remove('is-scrolled');
            subNav.classList.remove('is-expanded');
            subNavToggle.classList.remove('is-expanded');
        }
    }
    handleScroll();
    window.addEventListener('scroll', handleScroll);

    // --- Sub-Nav Toggle Click ---
    subNavToggle.addEventListener('click', function() {
        if (subNav.classList.contains('is-scrolled')) {
            subNav.classList.toggle('is-expanded');
            this.classList.toggle('is-expanded');
        }
    });

    // --- Active Link Highlight ---
    const navLinks = document.querySelectorAll(".tp-nav-menu li a");
    const currentPath = window.location.pathname.split('/').pop();
    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href').split('/').pop().split('#')[0];
        if (linkPath === currentPath && currentPath && currentPath !== 'index.php') {
            link.classList.add("tp-active-link");
        }
    });

    // --- Mobile Hamburger Toggle ---
    const hamburger = document.getElementById('tp-hamburger-toggle');
    const menu = document.getElementById('tp-main-menu');
    hamburger.addEventListener('click', function() {
        this.classList.toggle('tp-active');
        menu.classList.toggle('tp-active');
    });

    // --- Categories Sidebar Toggle ---
    document.querySelector('.tp-menu-toggle').addEventListener('click', function() {
        document.querySelector('.tp-sidebar').classList.add('tp-open');
    });
    document.querySelector('.tp-close-btn').addEventListener('click', function() {
        document.querySelector('.tp-sidebar').classList.remove('tp-open');
    });

    // --- Main Nav Dropdown ---
    document.querySelectorAll('.tp-dropdown').forEach((dropdown) => {
        dropdown.addEventListener('mouseenter', () => {
            if (window.innerWidth > 992) {
                dropdown.querySelector('.tp-dropdown-menu').style.display = 'block';
            }
        });
        dropdown.addEventListener('mouseleave', () => {
            if (window.innerWidth > 992) {
                dropdown.querySelector('.tp-dropdown-menu').style.display = 'none';
            }
        });
        dropdown.querySelector('a').addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
            }
        });
    });


    // --- Mobile Sub-Nav Links Toggle ---
    const mobileLinksToggle = document.getElementById('tp-mobile-links-toggle');
    const mobileLinksMenu = document.getElementById('tp-mobile-links-menu');

    if (mobileLinksToggle && mobileLinksMenu) {
        mobileLinksToggle.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent clicks from closing it immediately
            this.classList.toggle('tp-active');
            mobileLinksMenu.classList.toggle('tp-active');
        });
    }

    // This document click listener might already exist for search.
    // If it does, you can just add the logic inside it.
    // If not, add this:
    document.addEventListener("click", function(e) {
        // ... (existing code for search results might be here) ...

        // Add this logic to close the mobile dropdown when clicking outside
        if (mobileLinksMenu && mobileLinksMenu.classList.contains('tp-active')) {
            if (!mobileLinksMenu.contains(e.target) && !mobileLinksToggle.contains(e.target)) {
                mobileLinksMenu.classList.remove('tp-active');
                mobileLinksToggle.classList.remove('tp-active');
            }
        }
    });

}); // This is the closing tag of DOMContentLoaded
</script>


