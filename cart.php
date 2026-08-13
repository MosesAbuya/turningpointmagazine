

<head>
<title>Turning Point Magazine - Africa's Premier Source for News, Culture, and Innovation</title>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description"
        content="Turning Point Magazine is a digital platform dedicated to amplifying grassroots voices and celebrating stories of positive change across Africa. Join us in shaping a brighter future through inclusive, transformative content." />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
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
    <link rel="stylesheet" href="booking.css">

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="footer.css"> <!-- Assuming style.css is minified or optimized -->
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
      <link rel="stylesheet" href="booking.css">
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <!-- Preload themify-icons -->
    <link rel="preload" href="dflip/css/themify-icons.min.css" as="style">
    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css">

    <!-- Ensure that you do not include the same file multiple times -->
    
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

    .shop-h2{
    color: purple;
    font-size: 2rem;
    text-align: center;
    padding: 20px;
    font-weight: 600;
}
/* Cart Styling */
.cart-container {
    width: 90%;
    max-width: 800px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

/* Heading */
.cart-container h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
    margin-bottom: 15px;
}

/* Cart Table */
.cart-container table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
}

/* Table Headers */
.cart-container th {
    background: #28a745;
    color: white;
    padding: 12px;
    font-size: 16px;
}

/* Table Rows */
.cart-container td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    font-size: 16px;
    color: #555;
}

/* Quantity Input */
.cart-qty {
    width: 50px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
    font-size: 14px;
}

/* Remove Button */
.remove-item {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

/* Remove Button Hover Effect */
.remove-item:hover {
    background: #c82333;
}

/* Total Price */
#total-price {
    font-weight: bold;
    font-size: 20px;
    color: #e44d26;
}

/* Checkout Button */
#checkout-btn {
    display: block;
    width: 100%;
    background: #28a745;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    transition: background 0.3s;
}

/* Hover Effect on Checkout Button */
#checkout-btn:hover {
    background: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-container {
        width: 95%;
    }

    .cart-container table, 
    .cart-container th, 
    .cart-container td {
        font-size: 14px;
        padding: 8px;
    }

    .cart-qty {
        width: 40px;
    }

    #checkout-btn {
        font-size: 14px;
        padding: 10px;
    }
}

/* Checkout Section Styles */
.checkout-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.1); /* Light red glow */
}

.checkout-container h2,
.checkout-container h3 {
    color: #d00000; /* Red headings */
    margin-bottom: 20px;
    text-align: center;
}

.customer-info {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #d00000;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #d00000;
    border-radius: 5px;
    outline: none;
}

.form-group input:focus {
    border-color: #ff0000;
    box-shadow: 0 0 5px rgba(255, 0, 0, 0.5);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

table th, table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}

table th {
    background-color: #d00000;
    color: #fff;
}

table td {
    background-color: #fff;
}

.payment-method {
    margin-top: 30px;
    text-align: center;
}

.payment-method h3 {
    margin-bottom: 15px;
    color: #d00000;
}

.payment-method label {
    font-size: 18px;
    color: #333;
}

#place-order-btn {
    display: block;
    width: 100%;
    background-color: #d00000;
    color: white;
    padding: 15px;
    font-size: 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 30px;
    transition: background-color 0.3s ease;
}

#place-order-btn:hover {
    background-color: #ff1a1a;
}

.disclaimer {
    margin-top: 10px;
    font-size: 14px;
    color: #666;
    font-style: italic;
}

/* Error Popup */
.popup {
    display: none;
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #d00000;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
    z-index: 9999;
}

.popup.show {
    display: block;
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
<?php
include('connection2.php');
$pdo = connect();

// Get cart from localStorage via POST
$cart = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cart'])) {
    $cart = json_decode($_POST['cart'], true);
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = localStorage.getItem('cart');
            if (cart) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cart';
                input.value = cart;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>";
    exit;
}

$productIds = array_column($cart, 'product_id');
$totalPrice = 0;

if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
}
?>

<body>
<?php require_once('shop/inc/header.php'); ?>

<div class="next-head-text">
    <h1>Cart</h1>
    <h2 id="b-crumb-h2"><a href="index.php">Home</a> /<a href="shop.php"> Shop </a>/ Cart</h2>
</div>

<div class="cart-container">
    <h2>Your Cart</h2>
    <?php if (!empty($products)): ?>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product):
            $cartItem = current(array_filter($cart, fn($item) => $item['product_id'] == $product['id']));
            $quantity = $cartItem['quantity'] ?? 0;
            $subtotal = $product['current_price'] * $quantity;
            $totalPrice += $subtotal;
        ?>
        <tr id="row_<?php echo $product['id']; ?>">
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td>Ksh <?php echo htmlspecialchars($product['current_price']); ?></td>
            <td>
                <input type="number" class="cart-qty"
                       data-id="<?php echo $product['id']; ?>"
                       value="<?php echo $quantity; ?>"
                       min="1">
            </td>
            <td class="subtotal" id="subtotal_<?php echo $product['id']; ?>">
                Ksh <?php echo number_format($subtotal, 2); ?>
            </td>
            <td>
                <button class="remove-item" data-id="<?php echo $product['id']; ?>">Remove</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <h3>Total Price: Ksh <span id="total-price"><?php echo number_format($totalPrice, 2); ?></span></h3>
    <?php else: ?>
    <p class="empty-cart">Your cart is currently empty.</p>
    <?php endif; ?>

    <button id="checkout-btn" <?php echo empty($products) ? 'disabled' : ''; ?>>Checkout</button>
</div>

<!-- Hidden Checkout Section -->
<div class="checkout-container" id="checkout-section" style="display:none; margin-top: 50px;">
    <h2>Checkout</h2>

    <form id="checkout-form">
        <div class="customer-info">
            <h3>Customer Information</h3>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <!-- <div class="form-group">
                <label for="contact">Phone Number:</label>
                <input type="tel" name="contact" id="contact" required>
            </div> -->

            <p class="disclaimer">
                <strong>Disclaimer:</strong> Delivery fees are not included. You will be contacted after order confirmation.
            </p>
        </div>

        <h3>Order Summary</h3>
        <table id="checkout-cart">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php foreach ($products as $product): 
                $cartItem = current(array_filter($cart, fn($item) => $item['product_id'] == $product['id']));
                $quantity = $cartItem['quantity'] ?? 0;
                $subtotal = $product['current_price'] * $quantity;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td>Ksh <?php echo htmlspecialchars($product['current_price']); ?></td>
                <td><?php echo $quantity; ?></td>
                <td>Ksh <?php echo number_format($subtotal, 2); ?></td>
            </tr>
            <input type="hidden" name="product_id[]" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="quantity[]" value="<?php echo $quantity; ?>">
            <input type="hidden" name="price[]" value="<?php echo $product['current_price']; ?>">
            <?php endforeach; ?>
        </table>

        <h3>Total Amount: Ksh <span id="checkout-total"><?php echo number_format($totalPrice, 2); ?></span></h3>

        <input type="hidden" name="total_amount" value="<?php echo $totalPrice; ?>">
        <input type="hidden" name="cart" value='<?php echo json_encode($cart); ?>'>

        <div class="payment-method">
            <h3>Select Payment Method:</h3>
            <label><input type="radio" name="payment_method" value="mpesa" required checked> M-Pesa</label>
        </div>

        <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">

        <button type="button" id="place-order-btn">Proceed to Payment</button>
    </form>
</div>

<!-- Error Popup -->
<div id="error-popup" class="popup" style="display:none;">
    <p id="popup-message"></p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Cart handling (same as you had)
$(document).ready(function() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const updateCartCount = () => {
        const count = cart.reduce((sum, item) => sum + parseInt(item.quantity), 0);
        $("#cart_count").text(count);
    };

    $(".cart-qty").on("change", function() {
        const productId = $(this).data("id");
        const quantity = parseInt($(this).val());
        
        const itemIndex = cart.findIndex(i => i.product_id == productId);
        if (itemIndex !== -1) {
            cart[itemIndex].quantity = quantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            const price = parseFloat($(this).closest('tr').find('td:nth-child(2)').text().replace('Ksh ', ''));
            const subtotal = price * quantity;
            $("#subtotal_" + productId).text("Ksh " + subtotal.toFixed(2));
            updateTotalPrice();
            updateCartCount();
        }
    });

    $(".remove-item").on("click", function() {
        const productId = $(this).data("id");
        cart = cart.filter(i => i.product_id != productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        $("#row_" + productId).remove();
        updateTotalPrice();
        updateCartCount();
        if (cart.length === 0) location.reload();
    });

    function updateTotalPrice() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).text().replace('Ksh ', ''));
        });
        $("#total-price").text("Ksh " + total.toFixed(2));
    }

    // Checkout button
    $("#checkout-btn").on("click", function() {
        $("#checkout-section").slideDown();
        $('html, body').animate({
            scrollTop: $("#checkout-section").offset().top
        }, 500);
    });

    // Place Order button
    $("#place-order-btn").on("click", function() {
        
       
        const formData = $("#checkout-form").serialize();

        $.ajax({
            url: "process_checkout.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    localStorage.removeItem('cart');
                    window.location.href = "payment.php?invoice_id=" + response.invoice_id;
                } else {
                    showErrorPopup(response.message);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                showErrorPopup("Error processing order. Please try again.");
            }
        });
    });

    function showErrorPopup(message) {
        const popup = $("#error-popup");
        $("#popup-message").text(message);
        popup.fadeIn();
        setTimeout(() => popup.fadeOut(), 3000);
    }
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