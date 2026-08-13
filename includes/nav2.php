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
<div class="n-area" id="nn-navbar">


    <header class="n-top-bar" id="top-bar-full">
        <div class="n-top-bar-content">
            <span>📞 +254 111888938</span>
            <div class="n-social-icons">
                <a href="https://www.facebook.com/profile.php?id=100090750335981"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/malshe_media"><i class="fab fa-instagram"></i></a>
                <a href="www.linkedin.com/company/malshe-media"><i class="fab fa-linkedin"></i></a>
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
        <ul class="n-navbar-menu">
            <li><a href="index.php">Home</a></li>
            <li class="n-dropdown">
                <a href="#">Features <span id="t-about-1" style="color:red; font-size: 18px;">▼</span></a>
                <ul class="n-dropdown-menu">
                    <li><a href="index.php#latest">Latest Issue</a></li>
                    <li><a href="index.php#collection">Library</a></li>
                    <li><a href="index.php#subscribe">Subscribe</a></li>
                    <li><a href="index.php#promo-advertise">Advertise</a></li>
                </ul>
            </li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="story.php">Contribute</a></li>
            <li><a href="shop.php">Shop</a></li>
        </ul>
        <div class="n-search-module">
            <input type="text" placeholder="Search..." class="n-search-input" id="search-input" />
            <button class="n-search-button"><i class="fas fa-search"></i></button>
            <div id="search-results" class="search-results-dropdown"></div> <!-- Dropdown for search results -->
        </div>
    </nav>
</div>
<!-- <div class="navbar" id="nav-full">
    <button class="menu-toggle">☰ All Categories</button>
    <div class="menu-row">
        <a href="History">History</a>
        <a href="History">History</a>
        <a href="History">History</a>
        <a href="History">History</a>
        <a href="History">History</a>
        <a href="History">History</a>
        <a href="History">History</a>
    </div>
</div>

<div class="sidebar">
    <div class="sidebar-header">
        <button class="renew-btn">RENEW</button>
    </div>
    <nav class="menu">
        <ul> <button class="close-btn">✖</button>
            <li><a href="#">Smart News</a></li>
            <li>
                <a href="#">History <span class="arrow">›</span></a>
                <ul>
                    <li><a href="#">Archaeology</a></li>
                    <li><a href="#">U.S. History</a></li>
                    <li><a href="#">World History</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Science <span class="arrow">›</span></a>
                <ul>
                    <li><a href="#">Human Behavior</a></li>
                    <li><a href="#">Mind & Body</a></li>
                    <li><a href="#">Space</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Innovation <span class="arrow">›</span></a>
                <ul>
                    <li><a href="#">Technology</a></li>
                    <li><a href="#">Health & Medicine</a></li>
                </ul>
            </li>
            <li><a href="#">Arts & Culture</a></li>
            <li><a href="#">Travel</a></li>
            <li><a href="#">At The Smithsonian</a></li>
            <li><a href="#">Podcast</a></li>
            <li><a href="#">Photos</a></li>
            <li><a href="#">Video</a></li>
        </ul>
    </nav>
</div> -->

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