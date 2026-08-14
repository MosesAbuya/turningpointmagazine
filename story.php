<?php include('connection2.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description"
        content="Share your story with Turning Point Magazine! We are dedicated to showcasing grassroots voices, transformative impact, and inspiring stories of change from across Africa." />
    <title>Turning Point Magazine - Share Your Story</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/story.php">

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
    <link rel="stylesheet" href="footer.css">
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

    /* --- HERO & INTRO --- */
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

    /* --- MAIN CONTENT --- */
    main {
        position: relative;
        z-index: 2;
        background: var(--bg-off-white);
    }

    .page-section {
        padding: 60px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .story-form-container {
        display: flex;
        gap: 50px;
        align-items: flex-start;
    }

    .story-images {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .story-images img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .story-images img:hover {
        transform: scale(1.02);
    }

    .form-wrapper {
        flex: 1.2;
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }

    .form-title {
        font-family: 'florania', sans-serif;
        font-size: 2rem;
        color: var(--brand-teal);
        margin-bottom: 25px;
        text-align: center;
    }

    .form-item {
        margin-bottom: 20px;
    }

    /* --- FORM STYLING --- */
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

    .form-divider-title {
        text-align: center;
        margin: 30px 0 20px;
        color: var(--text-light);
        font-size: 1.1rem;
        font-weight: 600;
        position: relative;
    }

    .form-divider-title::before,
    .form-divider-title::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: #ddd;
    }

    .form-divider-title::before {
        left: 0;
    }

    .form-divider-title::after {
        right: 0;
    }

    /* File Input Styling */
    input[type="file"] {
        padding: 10px;
        background: #f8f9fa;
        border: 1px dashed #ccc;
        width: 100%;
        border-radius: 8px;
    }

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
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #cc0000;
    }

    .preview {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .preview img {
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .story-form-container {
            flex-direction: column-reverse;
        }

        .story-images {
            display: none;
        }

        /* Hide images on tablet/mobile to focus on form */
        .form-wrapper {
            width: 100%;
            padding: 20px;
        }
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
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Share Your Story</h1>
        <h2 class="tp-intro-sub">Inspire Change with Your Voice</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> Contribute</span>
        </nav>
    </section>

    <main>
        <section class="page-section">
            <div class="story-form-container">

                <div class="story-images fade-in-up" style="animation-delay: 0.4s;">
                    <img loading="lazy" src="assets/sp2.webp" alt="Community Story">
                    <img loading="lazy" src="assets/sp1.webp" alt="Impact Story">
                </div>

                <div class="form-wrapper fade-in-up" style="animation-delay: 0.5s;">
                    <form id="storyForm" enctype="multipart/form-data">
                        <h2 class="form-title">Submission Form</h2>

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
                                <option value="" disabled selected>Select an area</option>
                                <option value="My story">My story</option>
                                <option value="Environmental conservation">Environmental conservation</option>
                                <option value="My organisation">My organisation</option>
                                <option value="Charity">Charity</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-item">
                            <textarea class="input-message" placeholder="Type your story here..." rows="8"
                                name="story"></textarea>
                        </div>

                        <div class="form-divider-title">OR Upload a Document</div>

                        <div class="form-item">
                            <textarea class="input-message" placeholder="Short description of the document(s)..."
                                rows="3" name="p_description"></textarea>
                        </div>

                        <div class="form-item">
                            <input type="file" id="imageInput" name="photo[]" accept="image/*, .pdf, .docx, .doc, .webp"
                                multiple>
                        </div>

                        <div class="preview" id="preview"></div>

                        <div class="form-item">
                            <button type="button" id="submitStory" class="btn">Submit Story</button>
                        </div>

                        <div id="loading-message"
                            style="display: none; text-align: center; margin-top: 15px; color: var(--brand-teal);">
                            <i class="fas fa-spinner fa-spin"></i> Uploading, please wait...
                        </div>
                        <div id="feedback-response" style="margin-top: 20px; text-align:center; font-weight: 600;">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php' ?>

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

    <script>
    document.getElementById('submitStory').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('loading-message').style.display = 'block';
        const form = document.getElementById('storyForm');
        const formData = new FormData(form);

        fetch('story-sub.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                const feedback = document.getElementById('feedback-response');
                document.getElementById('loading-message').style.display = 'none';
                feedback.style.color = data.status === 'success' ? 'green' : 'red';
                feedback.textContent = data.message;
                if (data.status === 'success') form.reset();
            })
            .catch(error => {
                document.getElementById('loading-message').style.display = 'none';
                document.getElementById('feedback-response').textContent =
                    'An error occurred. Please try again.';
                document.getElementById('feedback-response').style.color = 'red';
            });
    });

    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                const card = document.createElement('div');
                card.style.padding = "5px 10px";
                card.style.border = "1px solid #ddd";
                card.style.borderRadius = "5px";
                card.style.background = "#f0f0f0";
                card.textContent = file.name;
                preview.appendChild(card);
            }
        });
    });
    </script>
</body>

</html>

