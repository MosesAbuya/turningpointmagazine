<?php
include('connection2.php');
$pdo = connect();

$award_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$award_id) {
    header('Location: awards.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM awards_to_apply WHERE id = :id AND status = 'Active'");
$stmt->execute(['id' => $award_id]);
$award = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$award) {
    header('Location: awards.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Apply for <?= htmlspecialchars($award['title']) ?> - Turning Point Magazine</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/tp-navbar.css">
    <link rel="stylesheet" href="tp-design-system.css">
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
            width: 110%;
        }
    }

    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease-out forwards;
    }

    /* --- HERO & INTRO SECTION --- */
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

    .tp-intro-nav .fa-chevron-right {
        font-size: 0.8em;
        margin: 0 8px;
        color: #666;
    }

    /* --- MAIN CONTENT --- */
    main {
        position: relative;
        z-index: 2;
        background: #fff;
        padding-bottom: 60px;
    }

    .award-detail-container {
        padding: 0;
        max-width: 1000px;
        margin: 40px auto;
        background: #fff;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #eee;
    }

    .award-detail-header img {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .award-detail-title {
        padding: 30px;
        text-align: center;
    }

    .award-detail-title h1 {
        font-family: 'florania', sans-serif;
        font-size: 3.5rem;
        margin-top: 0;
        margin-bottom: 20px;
        position: relative;
        display: inline-block;
        color: #333;
    }

    /* Tilted Yellow Underline */
    .award-detail-title h1::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: 0px;
        height: 10px;
        width: 0;
        background-color: #FFD700;
        opacity: 0.8;
        transform: rotate(-2deg);
        z-index: -1;
        animation: drawUnderline 0.8s ease-out 0.5s forwards;
    }

    .award-detail-body {
        padding: 0 30px 40px 30px;
    }

    .section-title {
        font-family: 'florania', sans-serif;
        font-size: 2.5rem;
        color: #008080;
        margin-bottom: 30px;
        margin-top: 40px;
        position: relative;
        display: inline-block;
    }

    /* Tilted Pink Underline */
    .section-title::after {
        content: "";
        position: absolute;
        left: -5%;
        bottom: 0px;
        height: 8px;
        width: 0;
        background-color: #E6007E;
        transform: rotate(-2deg);
        z-index: -1;
        animation: drawUnderline 0.8s ease-out 0.7s forwards;
    }

    .award-detail-body p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #444;
    }

    .deadline-box {
        background: #f8f9fa;
        border-left: 5px solid #ff0000;
        padding: 20px;
        margin: 30px 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    .apply-btn {
        padding: 15px 30px;
        background-color: #ff0000;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .apply-btn:hover {
        background-color: #cc0000;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* --- MODAL STYLES --- */
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        /* Very high z-index */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
        animation: fadeIn 0.3s;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        /* Centered by flex in .modal */
        padding: 40px;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .close-btn {
        color: #aaa;
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close-btn:hover {
        color: #333;
    }

    .modal-content h2 {
        font-family: 'florania', sans-serif;
        font-size: 2.2rem;
        color: var(--brand-red);
        text-align: center;
        margin-top: 0;
        margin-bottom: 30px;
    }

    /* --- FORM STYLES --- */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group textarea,
    .form-group input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
        background: var(--bg-off-white);
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--brand-red);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background-color: var(--brand-red);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .submit-btn:hover {
        background-color: #cc0000;
    }

    #form-messages {
        margin-top: 20px;
        text-align: center;
        font-weight: 700;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 768px) {
        .tp-intro-main {
            font-size: 2.8rem;
        }

        .tp-intro-sub {
            font-size: 3rem;
        }

        .award-detail-header img {
            height: 300px;
        }

        .award-detail-title h1 {
            font-size: 2.8rem;
        }

        .section-title {
            font-size: 2.2rem;
        }

        .modal {
            display: block;
        }

        /* Allow scrolling on mobile */
        .modal-content {
            margin: 10% auto;
            width: 95%;
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .tp-intro-main {
            font-size: 2.2rem;
        }

        .tp-intro-sub {
            font-size: 2.5rem;
        }

        .award-detail-header img {
            height: 200px;
        }

        .award-detail-title h1 {
            font-size: 2.2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .award-detail-body {
            padding: 0 20px 30px 20px;
        }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main"><?= htmlspecialchars($award['title']) ?></h1>
        <h2 class="tp-intro-sub">Award Application</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <i class="fas fa-chevron-right"></i>
            <a href="awards.php">Awards</a> <i class="fas fa-chevron-right"></i>
            <span>Apply</span>
        </nav>
    </section>

    <main class="award-detail-container fade-in-up" style="animation-delay: 0.2s;">
        <div class="award-detail-header">
            <img loading="lazy" src="admin/<?= htmlspecialchars($award['image_url']) ?>"
                alt="<?= htmlspecialchars($award['title']) ?>">
            <div class="award-detail-title">
                <h1><?= htmlspecialchars($award['title']) ?></h1>
            </div>
        </div>

        <div class="award-detail-body">
            <h2 class="section-title">Full Description</h2>
            <p><?= nl2br(htmlspecialchars($award['full_description'])) ?></p>

            <h2 class="section-title">Eligibility Criteria</h2>
            <p><?= nl2br(htmlspecialchars($award['eligibility_criteria'])) ?></p>

            <div class="deadline-box">
                <i class="fas fa-calendar-alt"></i> <strong>Deadline:</strong>
                <?= date('F j, Y', strtotime($award['application_deadline'])) ?>
            </div>

            <button class="apply-btn" id="openModalBtn">Apply Now <i class="fas fa-arrow-right"></i></button>
        </div>
    </main>

    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h2>Apply for "<?= htmlspecialchars($award['title']) ?>"</h2>
            <form id="applicationForm" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="award_id" value="<?= $award_id ?>">

                <div class="form-group">
                    <label for="applicant_name">Full Name</label>
                    <input type="text" id="applicant_name" name="applicant_name" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label for="applicant_email">Email Address</label>
                    <input type="email" id="applicant_email" name="applicant_email" placeholder="john@example.com"
                        required>
                </div>

                <div class="form-group">
                    <label for="applicant_phone">Phone Number (Optional)</label>
                    <input type="tel" id="applicant_phone" name="applicant_phone" placeholder="+254...">
                </div>

                <div class="form-group">
                    <label for="organization_name">Organization (Optional)</label>
                    <input type="text" id="organization_name" name="organization_name"
                        placeholder="Company or NGO Name">
                </div>

                <div class="form-group">
                    <label for="application_text">Why should you win?</label>
                    <textarea id="application_text" name="application_text" rows="6" placeholder="Tell us your story..."
                        required></textarea>
                </div>

                <div class="form-group">
                    <label for="attachment">Attachment (PDF, DOCX, Image - Optional)</label>
                    <input type="file" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>

                <button type="submit" class="submit-btn">Submit Application</button>

                <div id="form-messages"></div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php';?>

    <script>
    // Modal Logic
    const modal = document.getElementById('applicationModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.onclick = function() {
        modal.style.display = "flex"; // Use flex to center
    }
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // AJAX Submission
    const form = document.getElementById('applicationForm');
    const formMessages = document.getElementById('form-messages');
    const submitBtn = form.querySelector('.submit-btn');
    const originalBtnText = submitBtn.innerHTML;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        formMessages.innerHTML = 'Submitting...';
        formMessages.style.color = '#333';
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending... <i class="fas fa-spinner fa-spin"></i>';

        fetch('submit_application.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    formMessages.innerHTML = `<p style="color: green;">${data.message}</p>`;
                    form.reset();
                    submitBtn.innerHTML = 'Submitted!';
                    setTimeout(() => {
                        modal.style.display = 'none';
                        formMessages.innerHTML = '';
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }, 3000);
                } else {
                    formMessages.innerHTML = `<p style="color: red;">${data.message}</p>`;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                formMessages.innerHTML =
                    '<p style="color: red;">An unknown error occurred. Please try again.</p>';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
    });
    </script>
</body>

</html>

