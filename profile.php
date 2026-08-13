
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
 <style>
        body {
           
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        /* .container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .profile-info {
            margin: 20px 0;
            padding: 10px;
        }
        .profile-info label {
            font-weight: bold;
        }
        .logout {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: white;
            background: red;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout a:hover {
            background: darkred;
        } */
    </style>
    <style>
.profile-container {
  background-color: #ffffff;
  padding: 30px 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(220, 53, 69, 0.08);
  max-width: 1000px;
  margin: 40px auto;
  font-family: 'Segoe UI', sans-serif;
}

.profile-container h2 {
  color: #dc3545;
  font-size: 24px;
  margin-bottom: 20px;
  border-left: 5px solid #dc3545;
  padding-left: 12px;
}

.profile-info p {
  font-size: 16px;
  margin: 12px 0;
  color: #333;
}

.profile-info label {
  font-weight: bold;
  color: #dc3545;
  margin-right: 8px;
}

.logout {
  margin-top: 25px;
}

.logout a {
  display: inline-block;
  padding: 10px 18px;
  background-color: #dc3545;
  color: white;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.logout a:hover {
  background-color: #c82333;
}
</style>
<style>
.orders-section {
  background-color: #ffffff;
  padding: 30px 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(220, 53, 69, 0.08); /* soft red shadow */
  max-width: 1000px;
  margin: 40px auto;
  font-family: 'Segoe UI', sans-serif;
}

.orders-section h3 {
  color: #dc3545; /* red headline */
  font-size: 24px;
  margin-bottom: 20px;
  border-left: 5px solid #dc3545;
  padding-left: 12px;
}

.orders-table {
  width: 100%;
  border-collapse: collapse;
  background-color: #ffffff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.orders-table thead {
  background-color: #dc3545;
  color: #ffffff;
  text-transform: uppercase;
  font-size: 14px;
}

.orders-table th,
.orders-table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #f2f2f2;
  font-size: 15px;
}

.orders-table tbody tr:nth-child(even) {
  background-color: #fdf2f3; /* soft red tint */
}

.orders-table tbody tr:hover {
  background-color: #ffe6e9; /* light red on hover */
  transition: background-color 0.2s ease;
}

.orders-section p {
  font-style: italic;
  color: #dc3545;
  background-color: #fff5f5;
  border: 1px solid #f5c2c7;
  padding: 12px;
  border-radius: 6px;
  text-align: center;
  margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .orders-table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
}
</style>
<body>

<?php
    require_once ('shop/inc/header.php');
?>

<div class="next-head-text">
    <h1>My Profile</h1>
    <h2 id="b-crumb-h2">
        <a href="index.php">Home </a>/<a href="shop.php"> Shop </a>/ Profile
    </h2>
</div>
<hr>

<?php
// Check if there is a success and token in the URL
if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['token'])):
    $token = $_GET['token'];
    $decoded = json_decode(base64_decode($token), true);

    if ($decoded && is_array($decoded)):
?>
<section class="profile-container">
  <div class="container">
    <div class="order-success-message" style="background-color: #d4edda; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
      <h2>Payment Successful!</h2>
      <p><label>Invoice ID:</label> <?php echo htmlspecialchars($decoded['invoice_id']); ?></p>
      <p><label>Name:</label> <?php echo htmlspecialchars($decoded['first_name'] . ' ' . $decoded['last_name']); ?></p>
      <p><label>Email:</label> <?php echo htmlspecialchars($decoded['email']); ?></p>
      <p><label>Total Amount Paid:</label> Ksh <?php echo htmlspecialchars($decoded['amount']); ?></p>
    </div>
  </div>
</section>
<?php
    endif;
endif;
?>

<!-- Profile Section -->

<!-- <section class="profile-container">
  <div class="container">
    <h2>User Profile</h2>
    <div class="profile-info">
      <p><label>Name:</label> <?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?></p>
      <p><label>Email:</label> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><label>Contact:</label> <?php echo htmlspecialchars($user['contact']); ?></p>
    </div>

    <div class="logout">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</section>

<hr>

<section class="orders-section" id="orders_area">
  <h3>Your Orders:</h3>

  <?php if ($orders): ?>
    <table class="orders-table">
      <thead>
        <tr>
          <th>Invoice ID</th>
          <th>Date Created</th>
          <th>Total Amount</th>
          <th>Products</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['invoice_id']); ?></td>
            <td><?php echo htmlspecialchars($order['date_created']); ?></td>
            <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
            <td><?php echo htmlspecialchars($order['product_names']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No orders found.</p>
  <?php endif; ?>
</section> -->

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