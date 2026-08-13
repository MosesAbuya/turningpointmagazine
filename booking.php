
<?php include('connection2.php'); ?>




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
    <link rel="stylesheet" href="nav-2.css">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="footer.css"> <!-- Assuming style.css is minified or optimized -->
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="button.css">
    <link rel="stylesheet" href="booking.css">

    <!-- Preload the Preloader include -->
    <?php include 'includes/preloader.php'; ?>

    <!-- Preload dflip CSS files -->
    <link rel="preload" href="dflip/css/dflip.min.css" as="style">
    <link rel="stylesheet" href="dflip/css/dflip.min.css" type="text/css">

    <!-- Preload themify-icons -->
    <link rel="preload" href="dflip/css/themify-icons.min.css" as="style">
    <link rel="stylesheet" href="dflip/css/themify-icons.min.css" type="text/css">

    <!-- Ensure that you do not include the same file multiple times -->
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
    @media(max-width: 768px){
        #form-c {
        width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }
        .next-head-text{
            h1{
                font-size: 2rem;
            }
        }
        #images{
            display: flex;
            flex-direction: column;
            align-content: center;
            justify-content: center;
        }

    }
     #img-f{
            width: 70%;
            height: auto;
            align-self: center;
         }
         .book-intro{
            width: 100%;
            padding: 40px;
            
            p{
                padding: 10px;
            }
            ul{
                padding: 20px;
            }
         }
    </style>
    <link rel="stylesheet" href="issue.css">
</head>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" name="viewport">
<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="magnify/magnify.min.css">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
<link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
<link rel="stylesheet" href="issue.css">
<link rel="stylesheet" href="cat.css">
<link rel="stylesheet" href="pat.css">
<link rel="stylesheet" href="issue_1.css">
<link rel="stylesheet" href="next-issue.css">
<!-- Favicon and App Icons -->
<link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
<link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">
<script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

<body>
    <?php include 'includes/navbar.php' ?>
   <section class="booking">
    <div class="next-head-text">
        <h1>2025 Africa Exhibition & Trade Expo</h1>
        <h2>Book A Booth With Us</h2>
    </div>

    <!-- Progress Bar
    <div class="progress-bar">
        <span class="step active">1</span>
        <span class="line"></span>
        <span class="step">2</span>
        <span class="line"></span>
        <span class="step">3</span>
    </div>
     -->

<!-- <div class="book-intro">
   <p>
    <strong>The event is open to all and completely free to attend.</strong> However, for organizations and businesses interested in showcasing their products or services, we offer exhibition booths for a nominal participation fee.
</p>

<p>
    <strong>For Local Companies and Organizations:</strong> The cost to reserve a booth is <strong>500 USD</strong>. This fee covers the rental of a <strong>3x3 meter exhibition booth</strong>, providing ample space to display your products or services and engage with the audience effectively.
</p>

<p>
    <strong>For International Partners:</strong> The cost to reserve a booth for international exhibitors is <strong>1000 USD</strong>. This fee also includes a <strong>3x3 meter booth</strong>, ensuring that you have a prominent space to make a lasting impact at the event.
</p>

<ul>
    <li><strong>Local Companies and Organizations:</strong> 500 USD for a 3x3 meter booth.</li>
    <li><strong>International Partners:</strong> 1000 USD for a 3x3 meter booth.</li>
</ul>

<p>
    Both options offer great value and come with all the essential features to ensure your participation is a success.
</p>

<p>
    If you're interested in securing a booth for the event, we encourage you to <strong>reserve your space as soon as possible</strong>. Simply <em>fill out the form below</em> to complete your reservation. Don't miss the opportunity to be part of this exciting event!
</p>
</div> -->

<div class="exhibition-info-container">
  <div class="pricing-section">
    <div class="pricing-card local">
      <h2 class="pricing-title">Local Organizations</h2>
      <p class="pricing-amount" style="color: #28a745;">$500</p>
      <p class="pricing-description">Showcase your local brilliance! This fee unlocks a prime 3x3 meter exhibition booth. Connect with your community and spark new opportunities.</p>
    </div>

    <div class="pricing-card international">
      <h2 class="pricing-title">International Organizations</h2>
      <p class="pricing-amount">$1000</p>
      <p class="pricing-description">Global impact starts here. Secure your 3x3 meter exhibition booth to connect with a diverse audience and expand your reach.</p>
    </div>
  </div>
</div>

    <div class="form-c form" id="form-c" style="align-items: center; justify-content: space-between; transition: all .3s ease; width: 90%; padding: 50px;">
        <div class="event-details-section" style = "transition: all .3s ease;">
            <h2 class="form-title">Event Essentials</h2>
            <ul class="details-list" style = "transition: all .3s ease;">
                <li class="detail-item"><span class="detail-label">Venue:</span>Kenyatta International Convention Centre (KICC), Nairobi, Kenya</li>
                <li class="detail-item"><span class="detail-label">Date:</span>7th–8th March 2025</li>
                <li class="detail-item"><span class="detail-label">Time:</span> 9:00 AM – 5:00 PM (EAT)</li>
                <li class="detail-item"><span class="detail-label">Registration Deadline:</span>5th March 2025</li>
                <li class="detail-item"><span class="detail-label">Contact:</span> +254 718055457</li>
            </ul>
        </div>

       <form id="bookingForm" class="fom" method="POST" style = "transition: all .3s ease;">
    <h2 class="form-title">Fill the form below to book</h2>

    <div class="method">
        <div class="method-item" id="mpesaOption" data-method="MPESA">Mpesa</div>
        <div class="method-item" id="bankOption" data-method="BANK">Bank</div>
    </div>

    <!-- User Information Fields -->
    <div class="form-item">
        <input type="text" class="input" placeholder="First Name" name="first_name" required>
    </div>
    <div class="form-item">
        <input type="text" class="input" placeholder="Last Name" name="last_name" required>
    </div>
    <div class="form-item">
        <input type="text" class="input" placeholder="Your Organization" name="organization" required>
    </div>
    <div class="form-item">
        <input type="email" class="input" placeholder="Email" name="email" required>
    </div>
    <div class="form-item">
        <input type="text" class="input" placeholder="Contact" value="254" name="contact" required>
    </div>
    <div class="form-item select-wrapper">
        <select class="input" name="category" id="category" required>
            <option value="" disabled selected>Select a category</option>
            <option value="local">Local Organization</option>
            <option value="international">International Organization</option>
        </select>
    </div>

    <!-- Hidden Fields -->
    <input type="hidden" id="amount" name="amount" value="">
    <input type="hidden" id="currency" name="currency" value="KES">
    <input type="hidden" id="payment_method" name="payment_method" value="">
    <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo uniqid('INV-'); ?>">

    <!-- Bank Details Section (Initially Hidden) -->
    <div id="bankDetailsSection" style="display: none;">
        <h3>Bank Details</h3>
        <div class="form-item">
            <input type="text" class="input" placeholder="Bank Name" name="bank_name">
        </div>
        <div class="form-item">
            <input type="text" class="input" placeholder="Account Number" name="account_number">
        </div>
        <div class="form-item">
            <input type="text" class="input" placeholder="CVV" name="cvv">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-item dttn">
        <button type="button" id="proceedToPay" class="btn">
            <span class="btn-text">Proceed To Pay</span>
        </button>
    </div>

    <!-- Response Section -->
    <div id="subscribe-response"></div>
</form>

<script>
// JavaScript to handle payment method selection
document.addEventListener('DOMContentLoaded', function () {
    const mpesaOption = document.getElementById('mpesaOption');
    const bankOption = document.getElementById('bankOption');
    const paymentMethodField = document.getElementById('payment_method');
    const bankDetailsSection = document.getElementById('bankDetailsSection');

    // Default selection: Mpesa
    paymentMethodField.value = 'MPESA';
    mpesaOption.classList.add('active'); // Add active styling to Mpesa

    // Add click event listeners for payment method options
    mpesaOption.addEventListener('click', function () {
        paymentMethodField.value = 'MPESA';
        mpesaOption.classList.add('active');
        bankOption.classList.remove('active');
        bankDetailsSection.style.display = 'none';
    });

    bankOption.addEventListener('click', function () {
        paymentMethodField.value = 'BANK';
        bankOption.classList.add('active');
        mpesaOption.classList.remove('active');
        bankDetailsSection.style.display = 'block';
    });
});
</script>



        <!-- jQuery for AJAX -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>

        
            $(document).ready(function () {

            $("#category").change(function () {
            let category = $(this).val();
            let usdToKes = 0.002;
            let amount = category === "local" ? 500 * usdToKes : 1000 * usdToKes;
            $("#amount").val(amount);
            });
    let paymentInterval;

    // Function to check payment status
    function checkPaymentStatus(invoiceId) {
        $.ajax({
            type: "POST",
            url: "stk_initiate.php",
            data: { check_status: true, invoice_id: invoiceId },
            dataType: "json",
            success: function (response) {
                if (response.status !== "pending") {
                    clearInterval(paymentInterval); // Stop checking when no longer pending
                }

                $("#subscribe-response").html('<p style="font-weight: bold;">' + response.message + '</p>');
                
            },
            error: function () {
                console.log("Error checking payment status.");
            }
        });
    }

    // AJAX form submission
    $("#proceedToPay").click(function () {
        let formData = $("#bookingForm").serialize();

        $.ajax({
            type: "POST",
            url: "stk_initiate.php",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $("#proceedToPay").prop("disabled", true).text("Processing...");
            },
            success: function (response) {
                if (response.status === "success") {
                    $("#subscribe-response").html('<p style="color: green;">' + response.message + '</p>');
                    
                    paymentInterval = setInterval(function () {
                        checkPaymentStatus(response.invoice_id);
                    }, 1000);

                    
                } else {
                    $("#subscribe-response").html('<p style="color: red;">' + response.message + '</p>');
                }
                $("#proceedToPay").prop("disabled", false).text("Proceed To Pay");
            },
            error: function () {
                $("#subscribe-response").html('<p style="color: red;">An error occurred. Please try again.</p>');
                $("#proceedToPay").prop("disabled", false).text("Proceed To Pay");
            }
        });
    });
});

        </script>
</div>

<?php include 'includes/footer.php' ?>
</body>
 
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
// Select all the links
const links = document.querySelectorAll('.cat-row a');

// Add a click event listener to each link
links.forEach(link => {
    link.addEventListener('click', () => {
        // Remove 'active' class from all links
        links.forEach(item => item.classList.remove('active'));

        // Add 'active' class to the clicked link
        link.classList.add('active');
    });
});
</script>
<!-- jQuery  -->
<script src="dflip/js/libs/jquery.min.js" type="text/javascript"></script>
<!-- Flipbook main Js file -->
<script src="dflip/js/dflip.min.js" type="text/javascript"></script>