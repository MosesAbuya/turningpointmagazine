

<head>
<title>Turning Point Magazine - Africa's Premier Source for News, Culture, and Innovation</title>
    <meta charset="UTF-8">
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
/* Checkout Container */
.checkout-container {
    width: 90%;
    max-width: 800px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

/* Heading Styles */
.checkout-container h2, 
.checkout-container h3 {
    color: #333;
    text-align: center;
}

/* Customer Information */
.customer-info {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.customer-info p {
    font-size: 16px;
    color: #555;
    margin: 5px 0;
}

/* Order Summary Table */
.checkout-container table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    margin-top: 15px;
}

.checkout-container th {
    background: #28a745;
    color: white;
    padding: 10px;
    font-size: 16px;
}

.checkout-container td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 16px;
    color: #555;
}

/* Total Amount */
#total-amount {
    font-weight: bold;
    font-size: 20px;
    color: #e44d26;
    display: block;
    text-align: center;
    margin-top: 10px;
}

/* Payment Selection */
.payment-method {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    margin-top: 20px;
}

.payment-method label {
    font-size: 16px;
    color: #333;
    margin-right: 15px;
    cursor: pointer;
}

/* Radio Buttons */
.payment-method input[type="radio"] {
    margin-right: 8px;
}

/* Checkout Button */
#place-order-btn {
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
#place-order-btn:hover {
    background: #218838;
}

/* Responsive Design */
@media (max-width: 768px) {
    .checkout-container {
        width: 95%;
    }

    .checkout-container table, 
    .checkout-container th, 
    .checkout-container td {
        font-size: 14px;
        padding: 8px;
    }

    #place-order-btn {
        font-size: 14px;
        padding: 10px;
    }
}
 .payment-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        @media(max-width: 768px ){
            .payment-container{
            width: 90%
            }

        }
    </style>
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

<?php

include 'connection2.php';

$pdo = connect();

// Get invoice_id from URL
if (!isset($_GET['invoice_id']) || empty($_GET['invoice_id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['invoice_id'];

// Fetch the order with the matching invoice_id
$stmt = $pdo->prepare("SELECT * FROM orders WHERE invoice_id = ? LIMIT 1");
$stmt->execute([$invoice_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found.");
}

$first_name = $order['first_name'];
$last_name = $order['last_name'];
$email = $order['email'];
$total_amount = $order['total_amount'];
?>

<body>
<?php require_once('shop/inc/header.php'); ?>

<div class="next-head-text">
    <h1>Checkout</h1>
    <h2 id="b-crumb-h2">
        <a href="index.php">Home</a>
        <a href="shop.php">/ Shop</a> / Checkout
    </h2>
</div>

<div class="payment-container">
    <h2>Complete Payment</h2>
    <p><strong>Invoice ID:</strong> <?= htmlspecialchars($invoice_id); ?></p>
    <p><strong>Name:</strong> <?= htmlspecialchars($first_name . " " . $last_name); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
    <p><strong>Total Amount:</strong> Ksh <?= htmlspecialchars($total_amount); ?></p>

    <form id="payment-form">
        <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoice_id); ?>">
        <input type="hidden" name="first_name" value="<?= htmlspecialchars($first_name); ?>">
        <input type="hidden" name="last_name" value="<?= htmlspecialchars($last_name); ?>">
        <input type="hidden" name="organization" value="">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email); ?>">
        <input type="hidden" name="category" value="magazine">
        <input type="hidden" name="amount" value="<?= htmlspecialchars($total_amount); ?>">
        <input type="hidden" name="currency" value="KES">
        <input type="hidden" name="payment_method" value="mpesa">
        <input type="hidden" name="purpose" value="magazine-purchase">

        <label for="contact">M-Pesa Phone Number:</label>
        <input type="tel" name="contact" id="contact" required placeholder="Enter M-Pesa number" value="254">

        <button type="button" id="pay-btn">Pay Now</button>
    </form>

    <div id="payment-status"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#pay-btn").click(function() {
        var contact = $("#contact").val().trim();
        if (contact.length < 10 || isNaN(contact)) {
            alert("Please enter a valid M-Pesa number.");
            return;
        }

        $.ajax({
            url: 'stk_initiate.php',
            type: 'POST',
            data: $("#payment-form").serialize(),
            dataType: 'json',
            beforeSend: function() {
                $("#pay-btn").text("Processing...");
                $("#pay-btn").prop("disabled", true);
            },
            success: function(response) {
                $("#pay-btn").text("Pay Now");
                $("#pay-btn").prop("disabled", false);

                $("#payment-status").html("<p>" + response.message + "</p>");
                
                if (response.status === "success") {
                    checkPaymentStatus(response.invoice_id);
                }
            },
            error: function() {
                $("#pay-btn").text("Pay Now");
                $("#pay-btn").prop("disabled", false);
                $("#payment-status").html("<p>❌ Error processing payment.</p>");
            }
        });
    });

    function checkPaymentStatus(invoice_id) {
        setInterval(function() {
            $.ajax({
                url: 'stk_initiate.php',
                type: 'POST',
                data: { check_status: true, invoice_id: invoice_id },
                dataType: 'json',
                success: function(response) {
                    $("#payment-status").html("<p>" + response.message + "</p>");
                    if (response.status === "complete") {
                        setTimeout(function() {
    var orderDetails = {
        invoice_id: "<?php echo $invoice_id; ?>",
        first_name: "<?php echo $first_name; ?>",
        last_name: "<?php echo $last_name; ?>",
        email: "<?php echo $email; ?>",
        amount: "<?php echo $total_amount; ?>"
    };

    var orderDetailsString = JSON.stringify(orderDetails);
    var encodedToken = btoa(orderDetailsString); // base64 encode

    window.location.href = "profile.php?success=true&token=" + encodeURIComponent(encodedToken) + "#orders_area";
}, 2000);

                    }
                }
            });
        }, 5000);
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