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
    <link rel="stylesheet" href="tp-design-system.css"> <!-- Assuming style.css is minified or optimized -->
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
        #img-f{
            width: 70%;
            height: auto;
            align-self: center;
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
        <h2>Register Your Attendance</h2>
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

    <div class="form-c form" id="form-c" style="align-items: center; justify-content: space-between;">
         <div class="event-details-section">
            <h2 class="form-title">Event Essentials</h2>
            <ul class="details-list">
                <li class="detail-item"><span class="detail-label">Venue:</span>Kenyatta International Convention Centre (KICC), Nairobi, Kenya</li>
                <li class="detail-item"><span class="detail-label">Date:</span>7th–8th March 2025</li>
                <li class="detail-item"><span class="detail-label">Time:</span> 9:00 AM – 5:00 PM (EAT)</li>
                <li class="detail-item"><span class="detail-label">Registration Deadline:</span>6th March 2025</li>
                <li class="detail-item"><span class="detail-label">Contact:</span> +254 729407723</li>
            </ul>
        </div>

        <!-- Step 1: Booking Form -->
        <form id="bookingForm" class="fom" action="booking_process.php" method="POST">
            <h2 class="form-title">Fill the form below to register</h2>
            <div class="form-item">
                <input type="text" class="input" placeholder="First Name" name="f-name" required>
            </div>
            <div class="form-item">
                <input type="text" class="input" placeholder="Last Name" name="l-name" required>
            </div>
            <div class="form-item">
                <input type="email" class="input" placeholder="Email" name="email" required>
            </div>
            <div class="form-item">
                <input type="text" class="input" placeholder="Contact" name="contact" required>
            </div>
            <div class="form-item dttn">
                <button type="submit" id="proceedToPay" class="btn">
                    <span class="btn-text">Register</span>
                </button>
            </div>
            <div id="subscribe-response" style="width: 300px"></div>
        </form>



        <!-- Step 2: Payment Method Selection 
        <div id="paymentSelection" class="hidden">
            <h2 class="form-title">Select Payment Method</h2>
            <div class="payment-options">
                <button class="payment-btn" data-method="mpesa">Mpesa</button>
                <button class="payment-btn" data-method="bank">Bank</button>
                <button class="payment-btn" data-method="paypal">PayPal</button>
            </div>
            <button class="back-btn" id="backToBooking">← Back</button>
        </div>
        -->

        <!-- Step 3: Payment Details
        <div id="paymentDetails" class="hidden">
            <button class="back-btn" id="backToPayment">← Change Payment Method</button>
            <div id="paymentContent"></div>
        </div>
         -->
    </div>
</section>

 <style>
//     .progress-bar {
//         display: flex;
//         align-items: center;
//         justify-content: center;
//         margin-bottom: 20px;
//     }
//     .step {
//         width: 30px;
//         height: 30px;
//         border-radius: 50%;
//         background: grey;
//         display: flex;
//         align-items: center;
//         justify-content: center;
//         color: white;
//         font-weight: bold;
//         border: none;
//     }

//     .step.active {
//         background: red;
//         border:none;
//     }

//     .line {
//         width: 50px;
//         height: 4px;
//         background: grey;
//         margin: 0 10px;
//         border: none;
//     }

//     .hidden {
//         display: none;
//     }

//     .payment-options {
//         display: flex;
//         justify-content: center;
//         gap: 15px;
//     }

//     .payment-btn {
//         padding: 10px 20px;
//         border: none;
//         background: red;
//         color: white;
//         cursor: pointer;
//         border-radius: 5px;
//     }

//     .back-btn {
//         margin-top: 20px;
//         padding: 8px 15px;
//         background: grey;
//         color: white;
//         border: none;
//         cursor: pointer;
//         border-radius: 5px;
//     }

//     .back-btn:hover {
//         background: darkgrey;
//     }
 </style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#bookingForm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Serialize form data

        // Send form data via AJAX
        $.ajax({
            type: "POST",
            url: "registration_process.php",
            data: formData,
            dataType: "json", // Expect JSON response
            success: function(response) {
                if (response.status === "success") {
                    $('#subscribe-response').html('<p style="color: green;">' + response.message + '</p>');
                    $('#bookingForm')[0].reset(); // Reset form after successful submission
                } else if (response.status === "warning") {
                    $('#subscribe-response').html('<p style="color: orange;">' + response.message + '</p>');
                } 
                 else if (response.status === "exists") {
                    $('#subscribe-response').html('<p style="color: orange;">' + response.message + '</p>');
                }
                else {
                    $('#subscribe-response').html('<p style="color: red;">' + response.message + '</p>');
                }
            },
            error: function() {
                $('#subscribe-response').html('<p style="color: red;">This user is already registered!</p>');
            }
        });
    });
});

</script>

 <script>
//     document.getElementById('proceedToPay').addEventListener('click', function() {
//         document.getElementById('bookingForm').style.display = 'none';
//         document.getElementById('paymentSelection').style.display = 'block';
//         updateProgress(2);
//     });

//     document.getElementById('backToBooking').addEventListener('click', function() {
//         document.getElementById('paymentSelection').style.display = 'none';
//         document.getElementById('bookingForm').style.display = 'block';
//         updateProgress(1);
//     });

//     document.querySelectorAll('.payment-btn').forEach(button => {
//         button.addEventListener('click', function() {
//             const method = this.getAttribute('data-method');
//             showPaymentForm(method);
//             updateProgress(3);
//         });
//     });

//     document.getElementById('backToPayment').addEventListener('click', function() {
//         document.getElementById('paymentDetails').style.display = 'none';
//         document.getElementById('paymentSelection').style.display = 'block';
//         updateProgress(2);
//     });

//     function showPaymentForm(method) {
//         let formHtml = '';

//         if (method === 'mpesa') {
//             formHtml = `<h2 class="form-title">Payment Successful via Mpesa</h2>`;
//         } else if (method === 'bank') {
//             formHtml = `
//                 <h2 class="form-title">Enter Bank Details</h2>
//                 <input type="text" class="input" placeholder="Bank Name" required>
//                 <input type="text" class="input" placeholder="Account Number" required>
//                 <button class="btn">Submit Payment</button>`;
//         } else if (method === 'paypal') {
//             formHtml = `
//                 <h2 class="form-title">Enter PayPal Email</h2>
//                 <input type="email" class="input" placeholder="PayPal Email" required>
//                 <button class="btn">Submit Payment</button>`;
//         }

//         document.getElementById('paymentSelection').style.display = 'none';
//         document.getElementById('paymentContent').innerHTML = formHtml;
//         document.getElementById('paymentDetails').style.display = 'block';
//     }

//     function updateProgress(step) {
//         const steps = document.querySelectorAll('.step');
//         steps.forEach((s, index) => {
//             if (index < step) {
//                 s.classList.add('active');
//             } else {
//                 s.classList.remove('active');
//             }
//         });

//         const lines = document.querySelectorAll('.line');
//         lines.forEach((line, index) => {
//             line.style.background = index < step - 1 ? 'red' : 'grey';
//         });
//     }
// </script>

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