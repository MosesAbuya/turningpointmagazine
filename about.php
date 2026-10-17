<?php include('connection2.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Turning Point Magazine - About Us</title>
    <meta name="description"
        content="Learn about Turning Point Magazine, a publication focused on empowering individuals and communities across Africa through inspiring stories of positive change and social impact." />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="https://www.turningpointmagazine.africa/about.php">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/favicon/site.webmanifest">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="includes/new-navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="global.css">

    <style>
    /* --- FONT --- */
    @font-face {
        font-family: florania;
        src: url("<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>assets/fonts/florania.otf") format("opentype");
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

    /* --- PAGE OVERRIDES & ANIMATIONS --- */
    body {
        padding-top: 0 !important;
        font-family: 'Poppins', sans-serif;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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
        border-bottom: 1px solid #eee;
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
        color: #ff0000;
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
        background: var(--bg-off-white);
        padding-bottom: 60px;
    }

    /* --- INFO CARDS GRID --- */
    .content-grid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }

    .info-card {
        background: #fff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--brand-red);
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--brand-red);
        transform: scaleX(0);
        transition: transform 0.3s ease;
        transform-origin: left;
    }

    .info-card:hover::before {
        transform: scaleX(1);
    }

    .info-card h2 {
        color: var(--brand-teal);
        font-size: 1.8rem;
        margin-bottom: 1rem;
        font-family: 'florania', sans-serif;
    }

    .info-card p,
    .info-list li {
        color: var(--text-light);
        line-height: 1.7;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
    }

    .info-list li {
        margin-bottom: 0.8rem;
        padding-left: 20px;
        position: relative;
    }

    .info-list li::before {
        content: '\f054';
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 4px;
        font-size: 0.8rem;
        color: var(--brand-red);
    }

    /* --- TEAM SECTION STYLING --- */
    .team-container {
        max-width: 1200px;
        margin: 4rem auto 2rem;
        padding: 0 2rem;
    }

    .team-section-title {
        text-align: center;
        font-family: 'florania', sans-serif;
        color: var(--brand-teal);
        font-size: 2.5rem;
        margin: 3rem 0 2rem;
        position: relative;
    }

    .team-section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: var(--brand-pink);
        margin: 10px auto 0;
        border-radius: 2px;
    }

    .team-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .team-card {
        flex: 0 1 280px;
        width: 100%;
        max-width: 320px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
    }

    .team-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-color: var(--brand-teal);
    }
    
    .team-card-inner {
        padding: 1.5rem 1.5rem 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        flex-grow: 1;
    }

    .avatar {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, var(--brand-teal), var(--brand-pink));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        font-weight: bold;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    
    .team-card:hover .avatar img {
        transform: scale(1.05);
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: relative;
        z-index: 1;
        transition: transform 0.4s ease;
    }

    .avatar::before {
        content: attr(data-initials);
        position: absolute;
    }

    .team-name {
        font-family: 'florania', sans-serif;
        color: var(--brand-teal);
        font-size: 1.4rem;
        margin-bottom: 0.2rem;
    }

    .team-role {
        font-family: 'Poppins', sans-serif;
        color: var(--brand-red);
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        background: rgba(232, 0, 61, 0.06);
        padding: 4px 12px;
        border-radius: 20px;
    }

    .team-summary {
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.2rem;
    }
    
    .team-full-info {
        display: none; 
        font-size: 0.9rem;
        line-height: 1.5;
        color: var(--text-dark);
        background: #f8f9fa;
        padding: 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        text-align: left;
        
        width: 100%;
    }

    .team-full-info.open {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .toggle-btn {
        background: transparent;
        border: 2px solid var(--brand-teal);
        color: var(--brand-teal);
        font-size: 0.85rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 6px 20px;
        border-radius: 30px;
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .toggle-btn:hover {
        background: var(--brand-teal);
        color: #fff;
    }

    .toggle-btn i {
        transition: transform 0.3s ease;
    }

    .toggle-btn.open {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }
    .toggle-btn.open i {
        transform: rotate(180deg);
    }
    .alert-box {
        max-width: 800px;
        margin: 2rem auto 0 auto;
        padding: 15px 20px;
        background-color: #fff;
        border-left: 5px solid var(--brand-red);
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
        color: var(--text-dark);
    }

    .alert-box a {
        color: var(--brand-red);
        font-weight: 700;
        text-decoration: none;
    }

    .alert-box a:hover {
        text-decoration: underline;
    }

    /* --- LICENSE CARD SPECIAL STYLING --- */
    .licence-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem 2rem;
    }

    .info-card.licence {
        background: #fff;
        border: 2px solid var(--brand-teal);
    }

    .info-card.licence h2 {
        color: var(--brand-red);
    }

    .info-card.licence b {
        color: var(--text-dark);
    }

    footer {
        z-index: 1;
    }

    /* --- RESPONSIVE STYLES --- */
    @media (max-width: 768px) {
        .breadcrumb-container { height: 75px; }
        .tp-fun-intro { padding: 80px 20px 40px 20px; }
        .tp-intro-main { font-size: 2.8rem; }
        .tp-intro-sub { font-size: 3rem; }
        .content-grid { grid-template-columns: 1fr; padding: 1rem; }
        .team-container { padding: 0 1rem; }
    }

    @media (max-width: 480px) {
        .tp-fun-intro { padding: 60px 20px 30px 20px; }
        .tp-intro-main { font-size: 2.2rem; }
        .tp-intro-sub { font-size: 2.5rem; }
        .info-card { padding: 1.5rem; }
    }
    </style>
</head>

<body>
    <?php include 'includes/preloader.php'; ?>
    <?php include 'includes/new-navbar.php';?>

    <section class="tp-fun-intro fade-in-up" style="animation-delay: 0.2s;">
        <h1 class="tp-intro-main">Turning Point Magazine</h1>
        <h2 class="tp-intro-sub">About Us</h2>
        <nav class="tp-intro-nav">
            <a href="index.php">Home</a> <span><i class="fas fa-chevron-right"></i> About Us</span>
        </nav>
    </section>

    <main>
        <div class="alert-box fade-in-up" style="animation-delay: 0.25s;">
            <i class="fas fa-info-circle" style="color: var(--brand-red); margin-right: 5px;"></i>
            <b>Important:</b> This is a summary of our profile. You can view the full document
            <a href="assets/Turning Point Magazine Pitch.pdf" target="_blank">here <i class="fas fa-file-pdf"></i></a>.
        </div>

        <div class="content-grid">
            <div class="info-card fade-in-up" style="animation-delay: 0.3s;">
                <h2>About Us</h2>
                <p>Turning Point magazine is a digital publication launched in November 29, 2024 dedicated to amplifying
                    grassroots voices and highlighting stories of positive change across Africa.</p>
            </div>
            <div class="info-card fade-in-up" style="animation-delay: 0.4s;">
                <h2>Vision</h2>
                <p>To be the leading platform in driving positive change, fostering inclusivity, authenticity and
                    innovation for a future of shared success.</p>
            </div>
            <div class="info-card fade-in-up" style="animation-delay: 0.5s;">
                <h2>Mission</h2>
                <p>Empowering individuals and communities through inclusive, transformative and inspiring content that
                    elevates grassroots voices and celebrates humanitarian impact.</p>
            </div>
            <div class="info-card fade-in-up" style="animation-delay: 0.6s;">
                <h2>Target Audience</h2>
                <ul class="info-list">
                    <li>Governments: Insights into grassroots impact</li>
                    <li>NGOs: Best practices and success stories</li>
                    <li>Private Sector: CSR opportunities</li>
                    <li>Donors: Transparency on initiatives</li>
                    <li>Volunteers and Communities: Engagement opportunities</li>
                </ul>
            </div>
            <div class="info-card fade-in-up" style="animation-delay: 0.7s;">
                <h2>Content</h2>
                <ul class="info-list">
                    <li>Feature Articles: Stories of transformation</li>
                    <li>Creativity Corner: Art and storytelling</li>
                    <li>Insightful Interviews: Leader perspectives</li>
                    <li>Wellness and Lifestyle: Practical advice</li>
                    <li>Tech and Innovation: Cutting-edge developments</li>
                </ul>
            </div>
            <div class="info-card fade-in-up" style="animation-delay: 0.8s;">
                <h2>Distribution</h2>
                <ul class="info-list">
                    <li>Print and Digital Subscriptions</li>
                    <li>Strategic Advertising Partnerships</li>
                    <li>Branded Events and Merchandise</li>
                </ul>
            </div>
        </div>

                <div class="team-container">
            <?php
            require_once 'connection2.php';
            $pdo = connect();
            // Fetch team members from database
            $stmt = $pdo->query("SELECT * FROM team_members ORDER BY group_name ASC, order_index ASC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Group them by group_name
            $teamGroups = [];
            foreach ($members as $member) {
                $teamGroups[$member['group_name']][] = $member;
            }

            // ORDERING logic
            $groupOrder = [
                'Advisory Council',
                'Secretariat',
                'Design Team',
                'Content Team',
                'Photography',
                'Legal',
                'Printing'
            ];
            
            uksort($teamGroups, function($a, $b) use ($groupOrder) {
                $posA = array_search($a, $groupOrder);
                $posB = array_search($b, $groupOrder);
                if ($posA === false) $posA = 999;
                if ($posB === false) $posB = 999;
                return $posA <=> $posB;
            });

            $delayOffset = 0.85;
            foreach ($teamGroups as $groupName => $groupMembers):
            ?>
            <h2 class="team-section-title fade-in-up" style="animation-delay: <?= $delayOffset ?>s;"><?= htmlspecialchars($groupName) ?></h2>
            <div class="team-grid fade-in-up" style="animation-delay: <?= $delayOffset + 0.05 ?>s;">
                
                <?php foreach ($groupMembers as $m): 
                    $nameParts = explode(' ', $m['name'] ?? ' ');
                    $initials = strtoupper(substr($m['name'], 0, 1) . substr($nameParts[1] ?? '', 0, 1));
                ?>
                <div class="team-card">
                    <div class="avatar" data-initials="<?= $initials ?>">
                            <img loading="lazy" src="<?= htmlspecialchars($m['image']) ?>" alt="<?= htmlspecialchars($m['name']) ?>" onerror="this.style.display='none'">
                        </div>
                    <div class="team-card-inner">
                        <h3 class="team-name"><?= htmlspecialchars($m['name']) ?></h3>
                        <p class="team-role"><?= htmlspecialchars($m['role']) ?></p>
                        <p class="team-summary"><?= htmlspecialchars($m['summary']) ?></p>
                        <div class="team-full-info">
                            <?= nl2br(htmlspecialchars($m['full_info'])) ?>
                        </div>
                        <?php if(!empty($m['full_info'])): ?>
                        <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><span class="btn-text">Read More</span> <i class="fas fa-chevron-down"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <?php 
                $delayOffset += 0.15;
            endforeach; 
            ?>
        </div>
    </section>

        <div class="licence-container">
            <div class="info-card licence fade-in-up" id="licencing" style="animation-delay: 1.2s;">
                <h2><i class="fas fa-shield-alt"></i> We Are Certified to Protect Your Data</h2>
                <p>At Malshe Media, we take data privacy and protection seriously. We are proud to announce that we are
                    officially registered as a Data Controller under the Office of the Data Protection Commissioner,
                    Kenya.</p>
                <p>Our certificate<b> (Serial No. 03323) & Identification (454-5788-A9FE) </b> signifies our compliance
                    with Kenyan data protection regulations.</p>
                <p><strong>What This Means for You:</strong></p>
                <ul class="info-list">
                    <li>Your data is handled with the utmost care and in strict accordance with the law.</li>
                    <li>We are committed to maintaining transparency and integrity in how we collect, process, and store
                        your personal information.</li>
                    <li>Our processes meet the highest standards of data security and privacy protection.</li>
                    <li>You can trust us to safeguard your information.</li>
                </ul>
            </div>
        </div>
    </main>

    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>

    <script>
        function toggleInfo(btn) {
            const card = btn.closest('.team-card');
            const fullInfo = card.querySelector('.team-full-info');
            
            fullInfo.classList.toggle('open');
            btn.classList.toggle('open');
            
            const span = btn.querySelector('.btn-text') || btn.querySelector('span');
            const icon = btn.querySelector('.fas') || btn.querySelector('svg');
            
            if (fullInfo.classList.contains('open')) {
                if (span) span.innerText = 'Read Less';
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            } else {
                if (span) span.innerText = 'Read More';
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
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

