<?php
include('connection2.php'); // Removed session_start()
$pdo = connect();

// Check if "edition_id" is present in the GET request
if (isset($_GET['edition_id']) && !empty($_GET['edition_id'])) {
    $edition_id = $_GET['edition_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE edition_id = :edition_id");
    $stmt->bindParam(':edition_id', $edition_id, PDO::PARAM_INT);
} else {
    $stmt = $pdo->prepare("SELECT * FROM products");
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<head>
<title>Turning Point Magazine - Africa's Premier Source for News, Culture, and Innovation</title>
    <meta charset="UTF-8">
    <meta name="description"
        content="Turning Point Magazine is a digital platform dedicated to amplifying grassroots voices and celebrating stories of positive change across Africa. Join us in shaping a brighter future through inclusive, transformative content." />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/shop.php">
 <!-- Favicon and App Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <!-- Preconnect to external domains for faster resource loading -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Preload the main stylesheet (minified if possible) -->
    <link rel="preload" href="../style.css" as="style">

    <!-- Combine Font Awesome (Use only one method: CSS or JS) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/f65faecb5f.js" crossorigin="anonymous" defer></script>

    <!-- Preload Bootstrap Icons -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" as="style">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Avoid duplicate includes of 'style.css' -->
    <link rel="stylesheet" href="../style.css"> <!-- Assuming this is your main stylesheet -->
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="tp-design-system.css"> <!-- Assuming style.css is minified or optimized -->
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="button.css">
    <link rel="stylesheet" href="nav-2.css">
    <link rel="stylesheet" href="cat.css">

    <!-- Preload the Preloader include -->
    <?php include 'includes/preloader.php'; ?>

    <!-- Preload dflip CSS files -->
    <link rel="preload" href="dflip/css/dflip.min.css" as="style">
    <link rel="stylesheet" href="dflip/css/dflip.min.css" type="text/css">
    <!-- Favicon and App Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <!-- Preload themify-icons -->
    <link rel="preload" href="dflip/css/themify-icons.min.css" as="style">
    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css">

    <!-- Ensure that you do not include the same file multiple times -->
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

    <!-- Bootstrap CDN -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <link rel="stylesheet" href="shop/style.css">
    <link rel="stylesheet" href="global.css">
    <style>
    #issue {
        height: fit-content;
    }

    .footer {
        margin-top: 100px;
    }

    #issue {

        background-color: rgba(216, 222, 234, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.125);
        background-image: url(assets/h1.jpg);
        background-size: cover;
        background-position: center center;
    }

    #none {
        color: transparent;
        margin-top: -10px;
        margin-bottom: 0;
    }

    @media(max-width: 768px) {
        #a-cat {
            box-shadow: none;
        }
    }

    /* Shop Catalogue Styling */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* Product Grid */
.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* Individual Product Card */
.card {
    width: 100%;
    max-width: 280px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

/* Product Image */
.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 2px solid #ddd;
}

/* Card Body */
.card-body {
    padding: 15px;
    text-align: center;
}

/* Product Name */
.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

/* Product Price */
.price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #e44d26;
}

/* Button Styling */
.btn-primary {
    background-color: #ff6600;
    border: none;
    padding: 10px 15px;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background-color: #cc5500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
        align-items: center;
    }

    .card {
        max-width: 90%;
    }
}
.shop-h2{
    color: purple;
    font-size: 2rem;
    text-align: center;
    padding: 20px;
}
/* Shop Catalog Styling */
.product-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
}

/* Product Card */
.product {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 300px;
    transition: transform 0.3s ease-in-out;
    text-align: center;
    padding: 15px;
    position: relative;
}

/* Product Hover Effect */
.product:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.15);
}

/* Product Image */
.product img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
}

/* Product Title */
.product h3 {
    font-size: 18px;
    font-weight: bold;
    margin: 10px 0;
    color: #333;
}

/* Product Description */
.product p {
    font-size: 14px;
    color: #666;
    margin: 5px 0;
}

/* Price Styling */
.product p:last-of-type {
    font-weight: bold;
    color: #e44d26;
    font-size: 16px;
}

/* Quantity Input */
.product .quantity {
    width: 60px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    font-size: 14px;
    margin-top: 10px;
}

/* Add to Cart Button */
.add-to-cart {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    transition: background 0.3s;
}

/* Hover Effect on Button */
.add-to-cart:hover {
    background: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-container {
        flex-direction: column;
        align-items: center;
    }

    .product {
        width: 90%;
    }
}

/* Popup Styles */
.popup {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: none; /* Hidden by default */
    opacity: 0;
    transition: opacity 0.5s, transform 0.5s;
    transform: translateY(20px);
}

.popup.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

    </style>
</head>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="magnify/magnify.min.css">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
<link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
<link rel="stylesheet" href="issue_1.css">
<link rel="stylesheet" href="cat.css">
<link rel="stylesheet" href="booking.css">

<body>
<?php require_once ('shop/inc/header.php'); ?>

<div class="next-head-text">
    <h1>Shop</h1>
    <h2 id="b-crumb-h2"><a href="index.php">Home</a> / <a href="shop.php">Shop</a></h2>
</div>

<div class="product-container">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img loading="lazy" src="<?php echo htmlspecialchars("../admin/" . ltrim($product['img_path'], '/')); ?>"
                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <p>Price: Ksh <?php echo htmlspecialchars($product['current_price']); ?></p>
                <input type="number" class="quantity" id="qty_<?php echo $product['id']; ?>" value="1" min="1">
                <button class="add-to-cart"
                        data-id="<?php echo $product['id']; ?>"
                        data-price="<?php echo $product['current_price']; ?>">
                    Add to Cart
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products found for this edition.</p>
    <?php endif; ?>
</div>

<p style="text-align: center; color: blue;">
    <a style="color: blue; text-decoration: underline;" href="shop.php">View All Products</a>
</p>

<div id="cart-popup" class="popup">
    <p>✅ Added to cart!</p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showCartPopup() {
    const popup = document.getElementById("cart-popup");
    popup.classList.add("show");
    setTimeout(() => popup.classList.remove("show"), 2000);
}

$(document).on("click", ".add-to-cart", function() {
    const productId = $(this).data("id");
    const quantity = $("#qty_" + productId).val();
    const price = $(this).data("price");

    // Get existing cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Check if product already exists in cart
    const existingItem = cart.find(item => item.product_id == productId);

    if (existingItem) {
        existingItem.quantity += parseInt(quantity);
    } else {
        cart.push({
            product_id: productId,
            quantity: parseInt(quantity),
            price: parseFloat(price)
        });
    }

    // Save back to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Update UI
    const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    $("#cart_count").text(cartCount);
    showCartPopup();

    // Optional: Sync with server
    $.post("cart_action.php", {
        action: "add",
        product_id: productId,
        quantity: quantity,
        cart: JSON.stringify(cart)
    }, function(response) {
        // Handle server response if needed
    });
});

// Initial cart count setup
$(document).ready(function() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    $("#cart_count").text(count);
});
</script>
</body>
<?php include 'includes/footer.php' ?>
<script>
document.getElementById("menu-icon").addEventListener("click", function() {
    var e = document.getElementById("nav-links");
    "flex" === e.style.display ? e.style.display = "none" : e.style.display = "flex"
});
var heroTabs = document.querySelectorAll(".et-hero-tab");
heroTabs.forEach(function(e) {
    e.addEventListener("click", function() {
        document.getElementById("nav-links").style.display = "none"
    })
});
</script>

<script>
const editionCards = document.querySelector('.edition-cards');
const prevBtn = document.getElementById('prev-btn');
const nextBtn = document.getElementById('next-btn');

let currentIndex = 0;
let cardsPerPage = 4; // Default to 4 cards per row
let totalCards = document.querySelectorAll('.edition-card').length;
let maxIndex = Math.ceil(totalCards / cardsPerPage) - 1; // Max number of pages

// Update the number of cards per page based on window width
function updateCardsPerPage() {
    if (window.innerWidth <= 480) {
        cardsPerPage = 1; // 1 card per row on mobile
    } else if (window.innerWidth <= 768) {
        cardsPerPage = 2; // 2 cards per row on tablet
    } else {
        cardsPerPage = 4; // 4 cards per row on desktop
    }

    maxIndex = Math.ceil(totalCards / cardsPerPage) - 1; // Recalculate max index
    updateCardPosition(); // Update card position after changing cards per page
}

// Update the carousel position based on current index
function updateCardPosition() {
    const offset = -(currentIndex * (100 / 1)); // Move by percentage
    editionCards.style.transform = `translateX(${offset}%)`;
}

// Next button functionality
nextBtn.addEventListener('click', () => {
    if (currentIndex < maxIndex) {
        currentIndex++;
        updateCardPosition();
    }
});

// Previous button functionality
prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateCardPosition();
    }
});

// Initialize the carousel
window.addEventListener('load', () => {
    updateCardsPerPage(); // Set initial cards per page
    totalCards = document.querySelectorAll('.edition-card').length; // Get the number of cards
});

// Update the carousel when the window is resized
window.addEventListener('resize', updateCardsPerPage);
</script>
<script>
document.getElementById("menu-icon").addEventListener("click", function() {
    var e = document.getElementById("nav-links");
    "flex" === e.style.display ? e.style.display = "none" : e.style.display = "flex"
});
var heroTabs = document.querySelectorAll(".et-hero-tab");
heroTabs.forEach(function(e) {
    e.addEventListener("click", function() {
        document.getElementById("nav-links").style.display = "none"
    })
});
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- jQuery  -->
<script src="dflip/js/libs/jquery.min.js" type="text/javascript"></script>
<!-- Flipbook main Js file -->
<script src="dflip/js/dflip.min.js" type="text/javascript"></script>
