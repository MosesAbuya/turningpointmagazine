<?php
// We still need the initial values for a direct page load
include('connection2.php');
$pdo = connect();

// --- 1. GET INITIAL FILTERS (for first page load) ---
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$sector_filter = isset($_GET['sector']) ? $_GET['sector'] : '';
$country_filter = isset($_GET['country']) ? $_GET['country'] : '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Directories - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
    <link rel="stylesheet" href="global.css">
    <style>
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    body {
        padding-top: 0 !important;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes drawUnderline {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .breadcrumb-container {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 150px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    }

    .tp-fun-intro {
        position: relative;
        z-index: 2;
        margin-top: 0;
        background: #ffffff;
        padding: 100px 20px 60px 20px;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: #008080;
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 3.8rem;
        color: #E6007E;
        margin: -10px 0 15px 0;
        line-height: 1;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }

    .tp-intro-nav a {
        color: #ff0000;
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .tp-intro-nav a:hover {
        opacity: 0.7;
    }

    .tp-intro-nav .fa-chevron-right {
        font-size: 0.8em;
        margin: 0 8px;
        color: #666;
    }

    main {
        position: relative;
        z-index: 2;
        background: #fff;
    }

    /* --- 4. FILTERS (Desktop Default) --- */
    .filters {
        padding: 20px;
        background-color: #fff;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 148px;
        /* Adjust based on navbar height */
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .filter-form {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    /* Wrapper for extra filters to easily hide/show them */
    #extra-filters {
        display: contents;
        /* On desktop, just act like they are part of the main flex container */
    }

    .filter-input {
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        flex: 1 1 200px;
        max-width: 300px;
        font-size: 1rem;
    }

    .filter-btn {
        padding: 12px 25px;
        background-color: #ff0000;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
        font-size: 1rem;
    }

    .filter-btn:hover {
        background-color: #cc0000;
    }

    .filter-btn.clear-btn {
        background-color: #555;
    }

    .filter-btn.clear-btn:hover {
        background-color: #333;
    }

    .filter-btn.clear-btn.hidden {
        display: none;
    }

    /* Toggle button (hidden on desktop) */
    #filter-toggle-btn {
        display: none;
        width: 100%;
        padding: 10px;
        background: #f0f0f0;
        border: none;
        color: #555;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 5px;
    }

    #filter-toggle-btn i {
        transition: transform 0.3s ease;
    }

    #filter-toggle-btn.active i {
        transform: rotate(180deg);
    }

    /* --- 5. DIRECTORY CONTAINER --- */
    .directory-container-wrapper {
        position: relative;
        min-height: 400px;
    }

    .directory-container {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        transition: opacity 0.3s ease;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ff0000;
        z-index: 5;
        transition: opacity 0.3s ease;
        opacity: 0;
        pointer-events: none;
    }

    .loading-overlay.visible {
        opacity: 1;
        pointer-events: all;
    }

    .loading-overlay .fas {
        margin-right: 10px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .directory-card {
        display: flex;
        flex-direction: column;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .directory-card:hover {
        box-shadow: 0 6px 18px -4px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .directory-card .logo-wrapper {
        width: 100%;
        height: 200px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: #f9f9f9;
        border-bottom: 1px solid #eee;
    }

    .directory-card .logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .directory-card .card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-grow: 1;
    }

    .directory-card h3 {
        margin-top: 0;
        font-size: 1.5rem;
        color: #333;
    }

    .directory-card p {
        margin: 5px 0;
        color: #666;
    }

    .directory-card .description {
        font-size: 0.95em;
        color: #555;
        margin-top: 15px;
        line-height: 1.6;
        flex-grow: 1;
    }

    .directory-card .details-link {
        margin-top: 20px;
        text-decoration: none;
        color: #ff0000;
        font-weight: 700;
        align-self: flex-start;
        display: flex;
        align-items: center;
    }

    .directory-card .details-link i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .directory-card:hover .details-link i {
        transform: translateX(5px);
    }

    /* --- 6. PAGINATION --- */
    .pagination {
        display: flex;
        justify-content: center;
        padding: 40px 20px;
        flex-wrap: wrap;
        position: relative;
        padding-top: 60px;
    }

    .pagination::before {
        content: "Find Your Connection";
        font-family: 'florania', sans-serif;
        font-size: 2rem;
        color: #008080;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .pagination::after {
        content: "";
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background-color: #E6007E;
        animation: drawUnderline 1s ease-out 0.5s forwards;
    }

    .pagination a {
        color: #333;
        text-decoration: none;
        padding: 10px 16px;
        margin: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background-color: #f5f5f5;
    }

    .pagination a.active {
        background-color: #ff0000;
        color: #fff;
        border-color: #ff0000;
    }

    /* --- 7. RESPONSIVE STYLES --- */
    @media (max-width: 768px) {
        .cta-title {
            font-size: 2.0rem;
        }

        .cta-title .playful-pink {
            font-size: 2.5rem;
        }

        /* Mobile Filter Styles */
        .filters {
            top: 148px;
            padding: 15px;
        }

        .filter-form {
            flex-direction: column;
            gap: 10px;
        }

        #filter-search {
            max-width: 100%;
            width: 100%;
        }

        #extra-filters {
            display: none;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        #extra-filters.show {
            display: flex;
            animation: fadeIn 0.3s ease-out;
        }

        .filter-input,
        .filter-btn {
            max-width: 100%;
            width: 100%;
            flex-basis: auto;
        }

        #filter-toggle-btn {
            display: block;
        }

        .directory-container {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .pagination::before {
            font-size: 1.8rem;
        }

        .pagination::after {
            top: 35px;
        }
    }

    @media (max-width: 480px) {
        .cta-title {
            font-size: 1.8rem;
        }

        .cta-title .playful-pink {
            font-size: 2.2rem;
        }

        .cta-subtitle {
            font-size: 1rem;
        }

        .filters {
            top: 148px;
        }

        .directory-container {
            grid-template-columns: 1fr;
            padding: 20px 10px;
        }

        .pagination a {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Our Directories</h1>
        <h2 class="tp-intro-sub">Find Organizations Making a Difference</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Directories</span>
        </nav>
    </section>

    <main>
        <section id="directories">
            <div class="filters fade-in-up" style="animation-delay: 0.4s;">
                <form id="filter-form" class="filter-form">
                    <input type="text" name="search" id="filter-search" class="filter-input"
                        placeholder="Search by name..." value="<?= htmlspecialchars($search_query) ?>">

                    <div id="extra-filters">
                        <select name="type" id="filter-type" class="filter-input">
                            <option value="">All Types</option>
                            <option value="Company" <?= $type_filter == 'Company' ? 'selected' : '' ?>>Company</option>
                            <option value="NGO" <?= $type_filter == 'NGO' ? 'selected' : '' ?>>NGO</option>
                            <option value="Government" <?= $type_filter == 'Government' ? 'selected' : '' ?>>Government
                            </option>
                            <option value="Other" <?= $type_filter == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <input type="text" name="sector" id="filter-sector" class="filter-input"
                            placeholder="Filter by sector..." value="<?= htmlspecialchars($sector_filter) ?>">
                        <input type="text" name="country" id="filter-country" class="filter-input"
                            placeholder="Filter by country..." value="<?= htmlspecialchars($country_filter) ?>">
                        <button type="button" id="clear-filters-btn"
                            class="filter-btn clear-btn <?= (empty($search_query) && empty($type_filter) && empty($sector_filter) && empty($country_filter)) ? 'hidden' : '' ?>">Clear
                            All</button>
                    </div>

                    <button type="button" id="filter-toggle-btn">
                        More Filters <i class="fas fa-chevron-down"></i>
                    </button>
                </form>
            </div>

            <div class="directory-container-wrapper fade-in-up" style="animation-delay: 0.6s;">
                <div id="loading-overlay" class="loading-overlay">
                    <i class="fas fa-spinner"></i> Loading...
                </div>
                <div id="directory-container" class="directory-container">
                </div>
                <nav id="pagination-container" class="pagination">
                </nav>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php';?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filter-form');
        const searchInput = document.getElementById('filter-search');
        const typeInput = document.getElementById('filter-type');
        const sectorInput = document.getElementById('filter-sector');
        const countryInput = document.getElementById('filter-country');
        const clearButton = document.getElementById('clear-filters-btn');

        // New Elements for Mobile Toggle
        const extraFilters = document.getElementById('extra-filters');
        const filterToggleBtn = document.getElementById('filter-toggle-btn');

        const container = document.getElementById('directory-container');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        let searchTimeout;

        // --- MOBILE TOGGLE LOGIC ---
        filterToggleBtn.addEventListener('click', function() {
            extraFilters.classList.toggle('show');
            this.classList.toggle('active');
            // Optional: Change text based on state
            if (extraFilters.classList.contains('show')) {
                this.innerHTML = 'Hide Filters <i class="fas fa-chevron-up"></i>';
            } else {
                this.innerHTML = 'More Filters <i class="fas fa-chevron-down"></i>';
            }
        });

        // --- MAIN AJAX FETCH FUNCTION ---
        async function fetchDirectories(page = 1) {
            loadingOverlay.classList.add('visible');
            container.style.opacity = '0.5';

            const params = new URLSearchParams();
            params.append('search', searchInput.value);
            params.append('type', typeInput.value);
            params.append('sector', sectorInput.value);
            params.append('country', countryInput.value);
            params.append('page', page);

            const queryString = params.toString();

            try {
                const response = await fetch(`ajax-fetch-directories.php?${queryString}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();

                container.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination;

                window.history.pushState({
                    page: page
                }, '', `?${queryString}`);
                toggleClearButton();

            } catch (error) {
                console.error('Error fetching directories:', error);
                container.innerHTML =
                    '<p style="grid-column: 1 / -1; text-align: center; color: red;">Failed to load directories. Please try again.</p>';
            } finally {
                loadingOverlay.classList.remove('visible');
                container.style.opacity = '1';
                document.querySelectorAll('.directory-card').forEach((card, index) => {
                    card.classList.add('fade-in-up');
                    card.style.animationDelay = `${index * 0.05}s`;
                });
            }
        }

        function debounce(func, delay = 300) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(func, delay);
        }

        [typeInput, sectorInput, countryInput].forEach(input => {
            input.addEventListener('change', () => fetchDirectories(1));
        });

        searchInput.addEventListener('keyup', () => {
            debounce(() => fetchDirectories(1), 300);
        });

        paginationContainer.addEventListener('click', function(e) {
            e.preventDefault();
            const target = e.target.closest('a');
            if (target && target.href) {
                const url = new URL(target.href);
                const page = url.searchParams.get('page') || 1;
                fetchDirectories(page);
                // Scroll back to top of results on mobile
                if (window.innerWidth <= 768) {
                    document.getElementById('directories').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });

        clearButton.addEventListener('click', () => {
            searchInput.value = '';
            typeInput.value = '';
            sectorInput.value = '';
            countryInput.value = '';
            fetchDirectories(1);
        });

        function toggleClearButton() {
            if (searchInput.value || typeInput.value || sectorInput.value || countryInput.value) {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
        }

        fetchDirectories(<?= $page ?>);

        window.addEventListener('popstate', (e) => {
            const urlParams = new URLSearchParams(window.location.search);
            searchInput.value = urlParams.get('search') || '';
            typeInput.value = urlParams.get('type') || '';
            sectorInput.value = urlParams.get('sector') || '';
            countryInput.value = urlParams.get('country') || '';
            const page = urlParams.get('page') || 1;
            fetchDirectories(page);
        });
    });
    </script>
</body>

</html>
