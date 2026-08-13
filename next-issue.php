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
    <section class="next-container">
        <div class="next-head-text">
            <h1>Theme</h1>
            <h2>"For ALL women and girls: Rights.Equality. Empowerment."</h2>
            <h3>Next Edition</h3>
        </div>
        <div class="next-intro">
            <div class="next-img-container">
                <img loading="lazy" src="assets/wmn.jpg" alt="next-issue">
            </div>
            <div class="next-intro-text">
                <h1 style="text-align: left;">Introduction</h1>
                <p><strong>Turning Point Magazine Africa</strong> is seeking partners to support the <strong>2025
                        International Women’s Day Celebration</strong>, which will feature a <strong>two-day Trade
                        Fair</strong> at the <em>Kenyatta International Convention Centre (KICC)</em> Courtyard,
                    Nairobi, Kenya, on <strong>7th-8th March 2025</strong>. The event will host over <strong>100
                        exhibitors</strong> from government agencies, NGOs, and corporate companies across Kenya and
                    the
                    Pan-African region, with an expected audience of over <strong>20,000 attendees</strong>.
                    Themed <em>"Accelerate Action,"</em> the Trade Fair aims to fast-track initiatives addressing
                    systemic gender inequalities by showcasing projects that empower women, promote gender equality, and
                    drive sustainable development. Key highlights include the launch of the <strong>second issue of
                        Turning Point Magazine</strong>, which will amplify grassroots voices, spotlight transformative
                    solutions, and inspire collective action toward women’s empowerment and regional progress.
                    Additionally, the event will celebrate <strong>25 influential African women</strong> and provide a
                    platform for exhibitors to <strong>launch products, programs, and projects</strong>. This initiative
                    aligns with <strong>Kenya’s Gender Agenda</strong>, the <em>African Union’s Agenda 2063</em>, and
                    the overarching <em>Agenda 2030 for development</em>.</p>
                <!-- <button class="btn-issue"><a href="assets/concept.pdf">Read The Full Concept
                        Note</a></button> -->
            </div>
        </div>

    <div class="buy">
<a href="registration.php">
        <img loading="lazy" src="assets/reg.png"/>
        </a>
    </div>

        <div class="next-sponsor">
            <div class="next-sponsor-text">
                <h1>Invitation To Sponsor International Women’s Day 2025
                    Celebration</h1>
                <p>The <strong>International Women’s Day 2025 Celebration</strong> offers a unique opportunity for sponsors to partner in a transformative two-day trade fair at the <em>KICC Courtyard, Nairobi</em>, focused on empowering women, promoting gender equality, and celebrating excellence.  

Sponsorship packages, including <strong>Platinum, Gold, Bronze, Media, Gala Dinner</strong>, and <strong>Registration sponsorships</strong>, provide extensive branding, promotional, and networking opportunities. Benefits range from premium visibility on event materials, websites, and social media, to exclusive speaking slots, exhibition booths, and recognition during the event.  

This partnership aligns with a shared vision of driving collective action for women’s empowerment while offering businesses a platform to showcase their contributions and engage with key stakeholders.</p>

                <button class="btn-issue"><a href="assets/sponsorship.pdf">Read
                        More</a></button>
            </div>
            <div class="next-img-container">
                <img loading="lazy" src="assets/next1.jpg" alt="next-issue">
            </div>
        </div>

<div class="buy">
<a href="">
        <img loading="lazy" src="assets/buy1.png"/>
        </a>
    </div>

        <div class="faqs-container">
            <div class="faqs-head">
                <h3>Turning Point Magazine Africa</h3>
                <h2>International Women's Day Celebrations 2025 Africa Exhibition and Trade Fair </h2>
                <h1>FAQ'S About The Event</h1>
            </div>
            <div class="faqs-area">
                <p>
                    <span style="color: red; font-weight: bold;">What is the event about?</span><br>
                    Turning Point Magazine Africa invites partners to support the International Women's Day Celebration
                    2025. This exciting two-day event will feature a Trade Fair at the Kenyatta International Convention
                    Centre (KICC) Courtyard in Nairobi, Kenya, on 7th–8th March 2025. The event will host 100+
                    exhibitors from government agencies, NGOs, and corporate organizations across Kenya and the
                    Pan-African region, drawing an anticipated 20,000 attendees.<br>
                    Under the theme "Accelerate Action," the event aims to amplify efforts towards systemic gender
                    equality, empowering women and fostering sustainable development.<br>
                    - Key Highlight: Launch of the Second Issue of Turning Point Magazine—showcasing grassroots voices
                    and transformative solutions driving women's empowerment.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Main Objectives</span><br>
                    1. Accelerate Gender Equality: Address economic, political, and social disparities with a special
                    focus on Kenya.<br>
                    2. Showcase Women’s Leadership: Highlight impactful initiatives led by Kenyan and Pan-African women
                    in governance, business, and community development.<br>
                    3. Strengthen Regional Collaboration: Promote partnerships among public, private, and civil society
                    organizations for inclusive policies and programs.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">When and where is the event taking place?</span><br>
                    Location: <a href="https://kicc.co.ke/" style="color: black; text-decoration: none;">Kenyatta
                        International Convention Centre (KICC)</a>, Nairobi, Kenya<br>
                    Date: 7th–8th March 2025<br>
                    Time: 9:00 AM – 5:00 PM (EAT)
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">How can I register for the event?</span><br>
                    Email: partner@turningpointmagazine.africa<br>
                    Call/WhatsApp: +254 718055457 / +254 787565851
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Is there a registration fee?</span><br>
                    The event is free to attend. For partners interested in exhibiting, the participation fees are as
                    follows:<br>
                    - 500 USD for local companies or organizations<br>
                    - 1000 USD for international partners<br>
                    This fee includes a 3x3m exhibition booth.<br>
                    *Note:* International travel tickets, meals, and accommodation are not included. For assistance,
                    email michael@turningpointmagazine.africa (CC partner@turningpointmagazine.africa).
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">What should I bring to the event?</span><br>
                    Please bring IEC materials. Identification, conference materials, and snacks will be provided.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Will the event be available virtually?</span><br>
                    No, this is an in-person event.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Can I bring a guest?</span><br>
                    Yes! The event is free for all. Register at partner@turningpointmagazine.africa.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Is parking available?</span><br>
                    Yes, parking is available at KICC for 3 USD per day.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">What is the dress code?</span><br>
                    Business casual attire is recommended for delegates attending the business forums.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Will the event be recorded?</span><br>
                    Yes, the event will be recorded, and photos will be shared with all exhibitors and sponsors.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">Can I get proof of attendance?</span><br>
                    Yes, certificates will be issued to all exhibitors and sponsors.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">What happens if I can no longer attend?</span><br>
                    We offer a 50% refund for cancellations, issued two weeks after the event.
                </p>

                <p>
                    <span style="color: red; font-weight: bold;">How can I contact event organizers for more
                        information?</span><br>
                    Office Address:<br>
                    Muranga Road, Twiga Towers, 6th Floor, Room 616<br>
                    P.O. Box 28537-00100, Nairobi, Kenya<br>
                    *(Next to The Clarion Hotel)*<br>
                    Email: info@turningpointmagazine.africa<br>
                    Tel: +254 718055457 / +254 787565851
                </p>

            </div>
        </div>
    </section>
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
