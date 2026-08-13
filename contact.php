<?php include('connection2.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Turning Point Magazine - Contact</title>
    <meta name="description"
        content="Get in touch with Turning Point Magazine for inquiries, partnerships, or to share your story." />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/contact.php">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">

    <link rel="stylesheet" href="global.css">
    <style>
    @font-face {
        font-family: florania;
        src: url(assets/fonts/florania.otf) format("opentype");
    }

    :root {
        --brand-red: #ff0000;
        --brand-teal: #008080;
        --brand-pink: #E6007E;
        --text-dark: #333;
        --text-light: #666;
        --bg-off-white: #f8f9fa;
        --border-light: #eee;
    }

    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
    }

    /* --- ANIMATIONS --- */
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

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* --- 1. REVERTED HEADER STYLES (Your Original Design) --- */
    .breadcrumb-container {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/breadcrumbs/bread.jpeg');
        background-size: cover;
        background-position: center;
        position: absolute;
        z-index: 1;
        top: 0;
        width: 100%;
        height: 150px;
        /* Kept your original height */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
    }

    .tp-fun-intro {
        position: relative;
        z-index: 2;
        margin-top: 0;
        background: #ffffff;
        /* Solid white background as before */
        padding: 100px 20px 60px 20px;
        /* Original padding */
        text-align: center;
        border-bottom: 1px solid var(--border-light);
    }

    .tp-intro-main {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--brand-teal);
        margin: 0;
        line-height: 1.2;
    }

    .tp-intro-sub {
        font-family: 'Caveat', cursive;
        font-size: 3.8rem;
        color: var(--brand-pink);
        margin: -10px 0 15px 0;
        line-height: 1;
    }

    .tp-intro-nav {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }

    .tp-intro-nav a {
        color: var(--brand-red);
        text-decoration: none;
        transition: opacity 0.3s ease;
    }

    .tp-intro-nav a:hover {
        opacity: 0.7;
    }

    /* --- 2. MODERNIZED CONTENT SECTIONS --- */
    main {
        position: relative;
        z-index: 2;
        background: #fff;
    }

    .page-section {
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        text-align: center;
        font-family: 'florania', sans-serif;
        font-size: 2.8rem;
        color: var(--brand-teal);
        margin-bottom: 10px;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.1rem;
        color: var(--text-light);
        margin-bottom: 40px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* --- CONTACT FORM --- */
    #contact-form-section {
        background-color: var(--bg-off-white);
    }

    .form-container-flex {
        display: flex;
        gap: 50px;
        align-items: center;
    }

    .form-image-wrapper {
        flex: 1;
        display: flex;
        justify-content: center;
    }

    .form-image {
        max-width: 100%;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .form-wrapper {
        flex: 1.2;
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }

    .form-item {
        margin-bottom: 15px;
    }

    /* New Input Styling */
    input.input,
    select.input,
    textarea.input-message {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        background: #fcfcfc;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    input.input:focus,
    select.input:focus,
    textarea.input-message:focus {
        outline: none;
        border-color: var(--brand-red);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1);
    }

    /* Button Styling */
    .btn {
        width: 100%;
        padding: 15px;
        background-color: var(--brand-red);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn:hover {
        background-color: #cc0000;
    }

    /* --- INFO CARDS --- */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .info-card {
        background: white;
        text-align: center;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--brand-red);
    }

    .info-card i {
        font-size: 2.5rem;
        color: var(--brand-red);
        margin-bottom: 20px;
        background: rgba(255, 0, 0, 0.1);
        width: 70px;
        height: 70px;
        line-height: 70px;
        border-radius: 50%;
    }

    .info-card h3 {
        font-size: 1.4rem;
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .info-card p {
        color: var(--text-light);
        line-height: 1.6;
    }

    /* --- MAP --- */
    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
    }

    .map-container iframe {
        border: 0;
        display: block;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .form-container-flex {
            flex-direction: column;
        }

        .form-image-wrapper {
            display: none;
        }

        /* Hide image on tablet/mobile */
    }

    @media (max-width: 768px) {
        .tp-fun-intro {
            padding: 120px 20px 40px 20px;
        }

        .tp-intro-main {
            font-size: 2.8rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
        }

        .section-title {
            font-size: 2.2rem;
        }
    }
    
    /* --- 7. FORMS (Subscribe & Feedback) --- */
    .form-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: center;
    }

    .form-image img {
        width: 100%;
        border-radius: 8px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-container .input,
    .form-container select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
    }

    #feedbackForm textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        height: 150px;
    }

    .feedback-form {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    p.sub-text {
        font-size: 0.8rem;
        color: #777;
        margin-top: 15px;
        line-height: 1.5;
    }

    p.sub-text a {
        color: var(--brand-red);
    }

    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <div class="breadcrumb-container fade-in-up"></div>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Get In Touch</h1>
        <h2 class="tp-intro-sub">We'd love to hear from you</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Contact Us</span>
        </nav>
    </section>

    <main>
        <section id="contact-form-section" class="page-section">
            <h2 class="section-title fade-in-up">Talk To Us</h2>
            <p class="section-subtitle fade-in-up" style="animation-delay: 0.1s;">
                Have a question, feedback, or a story to share? Fill out the form below and our team will get back to
                you shortly.
            </p>

            <div class="form-container-flex">
                <div class="form-image-wrapper fade-in-up" style="animation-delay: 0.2s;">
                    <img loading="lazy" class="form-image" src="assets/s2.webp" alt="Contact illustration">
                </div>

                <div class="form-wrapper fade-in-up" style="animation-delay: 0.3s;">
                    <form id="contact-form" enctype="multipart/form-data">
                        <div class="form-item">
                            <input type="text" class="input" placeholder="First Name" name="f-name" required>
                        </div>
                        <div class="form-item">
                            <input type="text" class="input" placeholder="Last Name" name="l-name" required>
                        </div>
                        <div class="form-item">
                            <input type="email" class="input" placeholder="Your Email" name="email" required>
                        </div>
                        <div class="form-item">
                            <select class="input" name="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="magazine">Issue with the magazine</option>
                                <option value="advertise">Want to advertise here?</option>
                                <option value="complaint">Raise a complaint</option>
                                <option value="acknowledge">Acknowledge the magazine</option>
                                <option value="feedback">General feedback</option>
                            </select>
                        </div>
                        <div class="form-item">
                            <textarea class="input-message" placeholder="Share your thoughts..." rows="5"
                                name="comments" required></textarea>
                        </div>
                        <div class="form-item">
                            <button type="submit" class="btn">Submit</button>
                        </div>
                        <div id="feedback-message"
                            style="display:none; text-align: center; font-weight: 600; margin-top:15px;"></div>
                    </form>
                </div>
            </div>
        </section>

        <section class="page-section">
            <h2 class="section-title fade-in-up">Our Office</h2>
            <p class="section-subtitle fade-in-up" style="animation-delay: 0.1s;">
                Find us at our headquarters in Nairobi, or reach out through our digital channels.
            </p>

            <div class="info-grid">
                <div class="info-card fade-in-up" style="animation-delay: 0.2s;">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Visit Our Office</h3>
                    <p>Muranga Road Twiga Towers,<br>Next to The Clarion Hotel</p>
                </div>
                <div class="info-card fade-in-up" style="animation-delay: 0.3s;">
                    <i class="fas fa-phone"></i>
                    <h3>Call Us Anytime</h3>
                    <p>+254 718055457</p>
                </div>
                <div class="info-card fade-in-up" style="animation-delay: 0.4s;">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Us</h3>
                    <p>info@turningpointmagazine.africa</p>
                </div>
            </div>
            
            <section class="page-section bg-light fade-in-up" id="subscribe">
            <div>
                <h2 class="section-title">Don't Miss Out</h2>
                <div class="form-section">
                    <div class="form-image">
                        <img loading="lazy" src="assets/about.png" alt="Subscribe">
                    </div>
                    <div class="form-container">
                        <p
                            style="text-align: center; color: var(--text-light-gray); font-size: 1.1rem; margin-bottom: 20px;">
                            Subscribe now to get regular
                            updates whenever we have a new publication.</p>
                        <form id="subscribeForm" name="sub" method="POST">
                            <input type="text" placeholder="First Name" class="input" name="f-name" required>
                            <input type="text" placeholder="Last Name" class="input" name="l-name" required>
                            <input type="email" placeholder="Email Address" class="input" name="email" required>
                            <select class="input" name="address" required>
                                <option value="" disabled selected>Select a country
                                </option>
                            </select>
                            <button type="submit" class="btn">
                                <span class="btn-text">Subscribe</span>
                            </button>
                            <div id="subscribe-response"></div>
                        </form>
                        <p class="p-s sub-text">
                            By subscribing, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.
                            Of You acknowledge we are a <a href="about.php#licencing">licensed
                                Data Controller</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>

            <div class="map-container fade-in-up" style="animation-delay: 0.5s;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8167574889!2d36.81976531475396!3d-1.2838539990635897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d66318933d%3A0x3613574429000000!2sTwiga%20Towers!5e0!3m2!1sen!2ske!4v1680000000000!5m2!1sen!2ske"
                    width="100%" height="450" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php' ?>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/smooth-scroll@16.1.3/dist/smooth-scroll.min.js"></script>

    <script src="dflip/js/dflip.min.js" type="text/javascript"></script>

    <script type="text/javascript">

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contact-form');
        const feedback = document.getElementById('feedback-message');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const btn = form.querySelector('.btn');
            const originalText = btn.innerText;

            btn.disabled = true;
            btn.innerText = 'Sending...';

            fetch('cont-sub.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    feedback.style.display = 'block';
                    feedback.textContent = data.message;
                    feedback.style.color = (data.status === 'success') ? 'green' : 'red';
                    btn.disabled = false;
                    btn.innerText = originalText;
                    if (data.status === 'success') {
                        form.reset();
                    }
                })
                .catch(err => {
                    feedback.style.display = 'block';
                    feedback.textContent = 'An unexpected error occurred. Please try again.';
                    feedback.style.color = 'red';
                    btn.disabled = false;
                    btn.innerText = originalText;
                });
        });
    });
    </script>
    
    <script>
             // --- Subscribe Form AJAX ---
        $('#subscribeForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "subscribe.php",
                data: formData,
                success: function(response) {
                    $('#subscribe-response').html(
                        '<p style="color: green; font-weight: 500;">' + response +
                        '</p>');
                    $('#subscribeForm')[0].reset();
                },
                error: function() {
                    $('#subscribe-response').html(
                        '<p style="color: red;">An error occurred. Please try again.</p>'
                    );
                }
            });
        });
        
         // --- Country List Generator ---
        const countrySelect = document.querySelector('select[name="address"]');
        if (countrySelect) {
            const countries = new Intl.DisplayNames(['en'], {
                type: 'region'
            });
            const countryCodes = ["AF", "AL", "DZ", "AD", "AO", "AG", "AR", "AM", "AU", "AT", "AZ", "BS", "BH",
                "BD", "BB", "BY", "BE", "BZ", "BJ", "BT", "BO", "BA", "BW", "BR", "BN", "BG", "BF", "BI",
                "CV", "KH", "CM", "CA", "CF", "TD", "CL", "CN", "CO", "KM", "CG", "CR", "HR", "CU", "CY",
                "CZ", "CD", "DK", "DJ", "DM", "DO", "EC", "EG", "SV", "GQ", "ER", "EE", "SZ", "ET", "FJ",
                "FI", "FR", "GA", "GM", "GE", "DE", "GH", "GR", "GD", "GT", "GN", "GW", "GY", "HT", "HN",
                "HU", "IS", "IN", "ID", "IR", "IQ", "IE", "IL", "IT", "JM", "JP", "JO", "KZ", "KE", "KI",
                "KP", "KR", "XK", "KW", "KG", "LA", "LV", "LB", "LS", "LR", "LY", "LI", "LT", "LU", "MG",
                "MW", "MY", "MV", "ML", "MT", "MH", "MR", "MU", "MX", "FM", "MD", "MC", "MN", "ME", "MA",
                "MZ", "MM", "NA", "NR", "NP", "NL", "NZ", "NI", "NE", "NG", "MK", "NO", "OM", "PK",
                "PW",
                "PS", "PA", "PG", "PY", "PE", "PH", "PL", "PT", "QA", "RO", "RU", "RW", "KN", "LC", "VC",
                "WS", "SM", "ST", "SA", "SN", "RS", "SC", "SL", "SG", "SK", "SI", "SB", "SO", "ZA", "SS",
                "ES", "LK", "SD", "SR", "SE", "CH", "SY", "TJ", "TZ", "TH", "TL", "TG", "TO", "TT", "TN",
                "TR", "TM", "TV", "UG", "UA", "AE", "GB", "US", "UY", "UZ", "VU", "VA", "VE",
                "VN", "YE",
                "ZM", "ZW"
            ];
            countryCodes.forEach(code => {
                const option = document.createElement('option');
                const countryName = countries.of(code);
                option.value = countryName;
                option.textContent = countryName;
                countrySelect.appendChild(option);
            });
        }
    </script>

    <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/674ee80f2480f5b4f5a713d3/1ie63kv61';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
    </script>
</body>

</html>
