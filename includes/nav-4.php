<head>
    <style>
    /* Basic styling for the search dropdown */
    .search-results-dropdown {
        border: 1px solid #ccc;
        border-radius: 5px;
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        position: absolute;
        width: 100%;
        z-index: 100000;
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
    </style>
    <!-- Add FontAwesome CDN link in your HTML head -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="nav-2.css">
</head>

<!-- <header class="n-top-bar" id="top-bar-full">
    <div class="n-top-bar-content">
        <span>📞 +254 111888938</span>
        <div class="n-social-icons">
            <a href="https://www.facebook.com/profile.php?id=100090750335981"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/malshe_media"><i class="fab fa-instagram"></i></a>
            <a href="www.linkedin.com/company/malshe-media"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>
    >
</header> -->

<div class="n-new" id="nn-navbar-s">

    <nav class="n-navbar">
        <div class="n-navbar-logo" id="nn-navbar-logo">
            <h1 class="logo" id="nn-logo">Turning Point</h1>
        </div>
        <div class="n-hamburger" id="nn-hamburger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="n-navbar-menu" id="nn-menu" style="flex: 1; display: flex; justify-content: center; list-style: none;">
            <li><a href="index.php#lates" class="active">Home</a></li>
            <li class="n-dropdown" id="nn-dropdown">
                <a href="#">Features <span id="t-about-1" style="color:red; font-size: 18px;">▼</span></a>
                <ul class="n-dropdown-menu" id="nn-dropdown-menu">
                    <li><a href="index.php#latest">Latest Issue</a></li>
                    <li><a href="index.php#collection">Library</a></li>
                    <li><a href="index.php#subscribe">Subscribe</a></li>
                    <li><a href="index.php#promo-advertise">Advertise</a></li>
                </ul>
            </li>
            <li><a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>about">About</a></li>
            <li><a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>contact">Contact</a></li>
            <li><a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>story">Contribute</a></li>
             <li><a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>shop">Shop</a></li>
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


    <div class="navbar">
        <button class="menu-toggle">☰ Categories</button>
        <?php
// Include the connection script
include('connection2.php');

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
        <div class="sidebar-header">
            <button class="renew-btn">RENEW</button>
        </div>
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
                            <a
                                href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>category/<?= generate_slug($category['name']) ?>?sub_category_id=<?= htmlspecialchars($subcategory['id']) ?>">
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

</div>
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