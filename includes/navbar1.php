<head>
    <!-- <style>
    /* Basic styling for the search dropdown */
    .search-results-dropdown {
        border: 1px solid #ccc;
        border-radius: 5px;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        position: absolute;
        width: 100%;
        z-index: 1000000;
        display: none;
        /* Hide by default */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Adding a subtle shadow */
    }

    /* Styling for each search result item */
    .search-result-item {
        padding: 10px;
        cursor: pointer;
    }

    .search-result-item:hover {
        background-color: #f0f0f0;
    }

    /* Styling for category results */
    .category-result {
        font-weight: bold;
        color: red;
    }

    /* Styling for article results */
    .article-result {
        font-size: 0.9em;
        color: black;
    }


    .n-search-input {
        width: 100%;

    }

    .n-search-button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        font-size: 20px;
        color: rgb(255, 0, 0);
    }

    .n-search-button:hover {
        color: rgb(0, 0, 0);
    }

    /* Mobile Styles */
    @media (max-width: 600px) {
        .n-search-input {
            padding: 10px;
            font-size: 14px;
        }

        .search-result-item {
            padding: 8px;
        }

        .category-result {
            font-size: 14px;
        }

        .article-result {
            font-size: 13px;
        }

        /* Ensure dropdown appears below input on mobile */
        .n-search-module {
            position: relative;
            width: 100%;
            margin-top: 10px;
        }

        .search-results-dropdown {
            position: absolute;
            /* Position dropdown below input */
            top: 100%;
            /* Place the dropdown immediately below the input */
            left: 0;
            width: 100%;
            /* Match width of the input field */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Add subtle shadow */
            margin-top: 5px;
            /* Small margin to separate the dropdown from the input */
        }

        /* Adjust button to be inline with the input */
        .n-search-button {
            top: 50%;
            transform: translateY(-50%);
        }
    }

    :root {
        --sidebar-bg: #ffffff;
        /* Sidebar background color */
        --text-color: #000000;
        /* Text color */
        --hover-bg: #333;
        /* Background on hover */
        --arrow-color: #aaa;
        /* Arrow color */
        --accent-color: #ff007f;
        /* Button accent color */
        --submenu-bg: #f5f5f5;
        /* Submenu background color */
        --submenu-text-color: #333;
        /* Submenu text color */
    }

    .menu-row {
        display: flex;
        flex-direction: row;
        justify-content: center;
        padding: 10px;
        align-items: center;
        transition: all 0.3s ease;
        height: 50px;
        width: 70%;

        a {
            height: 40px;
            padding-left: 20px;
            padding-right: 20px;
            padding: 10px;
            font-size: 0.84rem;
            text-decoration: none;
            color: #9a8c8c;
            font-weight: 550;

            &:hover {
                border-bottom: 3px solid rgb(255, 0, 0);
            }
        }
    }

    .navbar {
        width: 100%;
        height: 50px;
        background-color: var(--sidebar-bg);
        padding: 16px;
        display: flex;
        justify-content: center;
        flex-direction: row;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.139);

        a {
            text-decoration: none;
            color: black;
        }
    }

    .menu-toggle {
        background: none;
        color: var(--text-color);
        border: none;
        display: flex;
        height: 40px;
        padding: 8px 16px;
        font-size: 16px;
        cursor: pointer;
        font-weight: 700;
        width: fit-content;
        padding-left: 8px;
        /* Adds space between the left edge and the icon */
        padding-right: 8px;
        /* Adds space between the text and the button edge */

        &:hover {
            border-bottom: 3px solid red;
        }
    }

    /* Sidebar */
    .sidebar {
        width: 280px;
        height: fit-content;
        background-color: var(--sidebar-bg);
        color: var(--text-color);
        position: fixed;
        top: 0;
        left: -280px;
        /* Initially off-screen */
        display: flex;
        flex-direction: column;
        padding: 16px;
        transition: left 300ms ease-in-out;
        /* Smooth transition */
        z-index: 1000;
        /* Ensure it appears above all other content */
    }

    .sidebar.open {
        left: 0;
        /* When sidebar is open, align to the left */
    }

    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .close-btn {
        background: none;
        color: var(--text-color);
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .renew-btn {
        background: none;
        color: var(--accent-color);
        border: 2px solid var(--accent-color);
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 16px;
        cursor: pointer;
        text-transform: uppercase;
    }

    .menu {
        flex: 1;
    }

    .menu>ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu>ul>li {
        position: relative;
        margin: 8px 0;
    }

    .menu>ul>li>a {
        display: block;

        padding: 12px 16px;
        color: var(--text-color);
        text-decoration: none;
        font-weight: bold;
        transition: background 200ms ease-in-out;
    }

    .menu>ul>li>a:hover {
        text-decoration: underline;
    }

    .arrow {
        float: right;
        color: var(--arrow-color);
        font-size: 18px;
        margin-left: 8px;
    }

    /* Submenu with arrow styling */
    .menu>ul>li>ul {
        display: none;
        position: absolute;
        left: 100%;
        /* Position to the right of the parent item */
        top: 0;
        background-color: var(--submenu-bg);
        color: var(--submenu-text-color);
        list-style: none;
        padding: 0;
        margin: 0;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        width: 250px;
        z-index: 10;
        /* Ensure it appears above other content */
    }

    /* Triangle arrow pointing to parent */
    .menu>ul>li>ul::before {
        content: "";
        position: absolute;
        top: 12px;
        /* Adjust as needed */
        left: -10px;
        /* Adjust to align with parent */
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 8px 8px 8px 0;
        /* Arrow dimensions */
        border-color: transparent var(--submenu-bg) transparent transparent;
        /* Arrow color */
    }

    /* Hover to show submenu */
    .menu>ul>li:hover>ul {
        display: block;
    }

    .menu>ul>li>ul>li>a {
        font-weight: normal;
        font-size: 14px;
        padding: 12px 16px;
        color: var(--submenu-text-color);
        text-decoration: none;
        transition: background 200ms ease-in-out;
    }

    .menu>ul>li>ul>li>a:hover {
        text-decoration: underline;
    }

    /* Mobile Responsiveness */
    @media screen and (max-width: 768px) {
        .navbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            .menu-toggle {
                align-self: center;
            }

            .menu-row {
                display: none;
            }
        }

        .sidebar {
            width: 100%;
            /* Full width on mobile */
            left: -100%;
            /* Completely off-screen initially */
        }

        .sidebar.open {
            left: 0;
            /* Slide in from the left */
        }

        .menu>ul>li>ul {
            left: 0;
            /* Submenu stays within the sidebar */
            position: relative;
            top: auto;
            margin-top: 8px;
            width: 100%;
            box-shadow: none;
            /* Remove shadow for flat appearance */
            border-radius: 0;
        }

        .menu>ul>li>ul::before {
            display: none;
            /* Hide arrow pointer on mobile */
        }

        .menu-toggle {
            width: 100%;
            text-align: center;
        }
    }
    </style> -->

    <!-- Add FontAwesome CDN link in your HTML head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="new-navbar.css">
</head>
<header class="n-top-bar">
    <div class="n-top-bar-content">
        <span id="t-about">📞 +254 111888938</span>
        <div class="n-social-icons">
            <a href="https://www.facebook.com/profile.php?id=100090750335981"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/malshe_media"><i class="fab fa-instagram"></i></a>
            <a href="www.linkedin.com/malshe-media"><i class="fab fa-linkedin"></i></a>


        </div>
    </div>
</header>

<nav class="n-navbar">
    <div class="n-navbar-logo">
        <h1 class="logo">Turning Point</h1>
    </div>
    <div class="n-hamburger" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <ul class="n-navbar-menu" id="nn-menu" style="flex: 1; display: flex; justify-content: center; list-style: none;">
        <li><a href="index.php#lates">Home</a></li>
        <li class="n-dropdown">
            <a href="#">Features <span id="t-about-1" style="color:red; font-size: 18px;">▼</span></a>
            <ul class="n-dropdown-menu">
                <li><a href="index.php#latest">Latest Issue</a></li>
                <li><a href="index.php#collection">Library</a></li>
                <li><a href="index.php#subscribe">Subscribe</a></li>
                <li><a href="index.php#promo-advertise">Advertise</a></li>
            </ul>
        </li>
        <li><a id="about" href="about.php">About</a></li>
        <li><a id="contact" href="contact.php">Contact</a></li>
        <li><a id="story" href="story.php">Contribute</a></li>
        <li><a id="shop" href="shop.php" target="blank">Shop</a></li>
    </ul>

    <div style="display: flex; align-items: center; margin-left: auto;">
        <select class="custom-lang-select" onchange="setLanguage(this.value)" onmouseover="this.style.backgroundColor='red'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='black';" title="Translate Website" style="background: transparent; background-color: transparent; border: 1px solid rgba(255,255,255,0.5); color: black; padding: 5px; border-radius: 4px; font-size: 14px; cursor: pointer; outline: none; height: 35px; margin-right: 15px; transition: all 0.3s ease;">
            <option value="en">🇬🇧 EN</option>
            <option value="fr">🇫🇷 FR</option>
            <option value="de">🇩🇪 DE</option>
            <option value="es">🇪🇸 ES</option>
            <option value="pt">🇵🇹 PT</option>
            <option value="zh-CN">🇨🇳 ZH</option>
            <option value="ar">🇸🇦 AR</option>
            <option value="sw">🇰🇪 SW</option>
        </select>
        
        <div class="n-search-module" style="width: auto; flex: 0 0 auto;">
            <button onclick="document.getElementById('tp-search-modal').style.display='flex'; document.getElementById('tp-modal-search-input').focus();" class="tp-search-button" style="position: relative !important; top: auto !important; right: auto !important; transform: none !important; border:none; background:none; color:white; font-size:20px; cursor:pointer; padding:5px 10px;"><i class="fas fa-search"></i></button>
        </div>
    </div>

</nav>



<style>

</style>
<div class="navbar">
    <button class="menu-toggle">☰ Categories</button>
    <?php
// Include the connection script


// Connect to the database
$pdo = connect();

// Fetch the first 4 categories
try {
    $stmt = $pdo->query("SELECT id, name FROM categories LIMIT 4");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}
?>

    <div class="menu-row">
        <?php foreach ($categories as $category): ?>
        <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category['name']) ?>">
            <?= htmlspecialchars($category['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php
// Close the database connection
closeConnection($pdo);
?>

</div>

<div class="sidebar">
    <!-- <div class="sidebar-header">
        <button class="renew-btn">RENEW</button>
    </div> -->
    <?php
// Include the database connection file


// Connect to the database
$pdo = connect();

// Fetch categories and their corresponding subcategories
try {
    // Query to fetch categories
    $categoriesStmt = $pdo->query("SELECT id, name FROM categories");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare a statement to fetch subcategories based on the category_id
    $subcategoriesStmt = $pdo->prepare("SELECT id, name FROM sub_category WHERE category_id = :category_id");
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}
?>

    <nav class="menu">
        <ul>
            <button class="close-btn">✖</button>
            <?php foreach ($categories as $category): ?>
            <li>
                <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category['name']) ?>">
                    <?= htmlspecialchars($category['name']) ?>
                    <span class="arrow">›</span>
                </a>
                <?php
                // Fetch subcategories for this category
                $subcategoriesStmt->execute(['category_id' => $category['id']]);
                $subcategories = $subcategoriesStmt->fetchAll(PDO::FETCH_ASSOC);

                // Check if there are subcategories
                if (!empty($subcategories)): ?>
                <ul>
                    <?php foreach ($subcategories as $subcategory): ?>
                    <li>
                        <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category['name']) ?>?sub_category_id=<?= htmlspecialchars($subcategory['id']) ?>">
                            <?= htmlspecialchars($subcategory['name']) ?>
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
// Close the database connection
closeConnection($pdo);
?>

</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll(".n-navbar-menu li a");

    navLinks.forEach(link => {
        link.addEventListener("click", function() {
            // Remove 'active' class from all links
            navLinks.forEach(nav => nav.classList.remove("active"));

            // Add 'active' class to the clicked link
            this.classList.add("active");
        });
    });
});
</script>

<script>
// Toggle the sidebar open and closed with the Sections button
document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
});

// Close the sidebar when the close button is clicked
document.querySelector('.close-btn').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('open');
});
</script>

<script>
// Dropdown menu toggle (for better responsiveness)
document.querySelectorAll('.n-dropdown').forEach((dropdown) => {
    dropdown.addEventListener('mouseenter', () => {
        dropdown.querySelector('.n-dropdown-menu').style.display = 'block';
    });
    dropdown.addEventListener('mouseleave', () => {
        dropdown.querySelector('.n-dropdown-menu').style.display = 'none';
    });
});

// Optional: Log search term
document.querySelector('.n-search-button').addEventListener('click', function() {
    const searchTerm = document.querySelector('.n-search-input').value.trim();
    if (searchTerm) {
        console.log(`Searching for: ${searchTerm}`);
    } else {
        alert('Please enter a search term.');
    }
});
</script>

<script>
function toggleMenu() {
    const menu = document.querySelector('.n-navbar-menu');
    menu.classList.toggle('active');
}
</script>

<script>
// Get DOM elements
const searchInput = document.getElementById("search-input");
const searchResultsContainer = document.getElementById("search-results");

// Function to fetch search results
searchInput.addEventListener("input", function() {
    const searchTerm = this.value.trim();
    console.log('User is typing: ', searchTerm); // Debugging

    if (searchTerm.length > 0) {
        // Send the search term via AJAX to search.php
        fetchSearchResults(searchTerm);
    } else {
        // Hide results if input is empty
        searchResultsContainer.innerHTML = "";
        searchResultsContainer.style.display = "none";
    }
});

// Function to fetch search results from the server
function fetchSearchResults(searchTerm) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?query=" + encodeURIComponent(searchTerm), true);

    // Log if the request is being made
    console.log("Requesting search results for: ", searchTerm);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);
            console.log("Search results received: ", results); // Debugging
            displaySearchResults(results);
        } else {
            console.error("Error with AJAX request:", xhr.status);
        }
    };

    xhr.send();
}

// Function to display search results
function displaySearchResults(results) {
    searchResultsContainer.innerHTML = ""; // Clear previous results

    // Limit results to 10
    const limitedResults = results.slice(0, 10);

    // Show/hide the dropdown based on results
    if (limitedResults.length > 0) {
        searchResultsContainer.style.display = "block";
        limitedResults.forEach(result => {
            const div = document.createElement("div");
            div.classList.add("search-result-item");

            // Check if the result is a category or an article and apply the relevant class
            if (result.name) {
                // Category result (bold, red)
                div.classList.add("category-result");
                div.innerHTML = result.name;
            } else {
                // Article result (normal black, smaller font)
                div.classList.add("article-result");
                div.innerHTML = result.title;
            }

            // Add click event to handle redirection
            div.addEventListener("click", function() {
                if (result.name) {
                    // Redirect to category page
                    window.location.href = `cat.php?id=${result.id}`;
                } else {
                    // Redirect to issue page
                    window.location.href = `issue.php?id=${result.id}&edition_id=${result.edition_id}`;
                }
            });

            searchResultsContainer.appendChild(div);
        });
    } else {
        searchResultsContainer.style.display = "none";
    }
}

// Close the search results when clicking outside
document.addEventListener("click", function(e) {
    const isClickInside = searchInput.contains(e.target) || searchResultsContainer.contains(e.target);
    if (!isClickInside) {
        searchResultsContainer.style.display = "none"; // Close the dropdown if clicking outside
    }
});

// Prevent dropdown from closing when clicking inside
searchResultsContainer.addEventListener("click", function(e) {
    e.stopPropagation(); // Prevent the click event from propagating to the document
});
</script>