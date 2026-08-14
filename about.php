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

    <link rel="apple-touch-icon" sizes="180x180" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>favicon-16x16.png">
    <link rel="manifest" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>site.webmanifest">

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
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .team-card {
        background: #fff;
        border-radius: 12px;
        padding: 2.5rem 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--brand-teal);
    }

    .avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--brand-teal), var(--brand-pink));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Fallback Initials */
    .avatar::before {
        content: attr(data-initials);
        position: absolute;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: relative;
        z-index: 1;
    }

    .team-name {
        font-family: 'florania', sans-serif;
        color: var(--brand-teal);
        font-size: 1.6rem;
        margin-bottom: 0.3rem;
    }

    .team-role {
        font-family: 'Poppins', sans-serif;
        color: var(--brand-red);
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .team-summary, .team-full-info {
        color: var(--text-light);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .team-full-info {
        display: none; /* Hidden initially */
        border-top: 1px dashed var(--border-light);
        padding-top: 1rem;
        margin-top: 0.5rem;
    }

    /* Toggled State */
    .team-full-info.open {
        display: block;
        animation: fadeIn 0.4s ease-in-out;
    }

    .toggle-btn {
        background: none;
        border: none;
        color: var(--brand-teal);
        font-size: 1.8rem;
        cursor: pointer;
        transition: color 0.3s ease;
        margin-top: auto;
        padding: 10px;
    }

    .toggle-btn:hover {
        color: var(--brand-pink);
    }

    .toggle-btn i {
        transition: transform 0.3s ease;
    }

    .toggle-btn.open i {
        transform: rotate(180deg);
        color: var(--brand-red);
    }

    /* --- ALERT BOX --- */
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
            <h2 class="team-section-title fade-in-up" style="animation-delay: 0.85s;">Advisory Council</h2>
            <div class="team-grid fade-in-up" style="animation-delay: 0.9s;">
                
                <div class="team-card">
                    <div class="avatar" data-initials="JO">
                        <img loading="lazy" src="assets/team/julia_ojiambo.png" alt="Prof. Julia Ojiambo" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Prof. Julia Ojiambo</h3>
                    <p class="team-role">CBS, EBS, MBS</p>
                    <p class="team-summary">A pioneering Kenyan leader and the first Black African woman appointed as a Lecturer at the University of Nairobi’s College of Health Sciences.</p>
                    <div class="team-full-info">
                        She later earned her PhD there and became Kenya’s first female Cabinet Member. In 1976, she received the FAO Ceres Gold Medal for advancing socioeconomic empowerment for rural youth and women. She has also been honored with the President’s Awards of the Order of the Burning Spear (MBS, EBS, and CBS) for her distinguished public service and leadership in the Labour Party of Kenya.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="BA">
                        <img loading="lazy" src="assets/team/betty_adera.png" alt="Ms Betty Adera" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Ms Betty Adera</h3>
                    <p class="team-role">Global Health Leader</p>
                    <p class="team-summary">A dynamic global health leader with over 20 years of experience driving impactful programs across Africa.</p>
                    <div class="team-full-info">
                        A public health expert with strong credentials (MPH, PMD, PGMD) and a PhD candidate, she brings a proven record in strategic leadership, health systems strengthening, policy advocacy, and program management.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="MA">
                        <img loading="lazy" src="assets/team/michael_ager.png" alt="Mr. Michael Ager" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Mr. Michael Ager</h3>
                    <p class="team-role">Global Consultant</p>
                    <p class="team-summary">Global Consultant in Public Relations, Governance, and Advocacy.</p>
                    <div class="team-full-info">
                        He is a passionate, results-driven expert with over a decade of experience collaborating with government agencies (Kenya’s Ministry of Labour), UN bodies (UNDP, UNAIDS), and NGOs across national, regional, and international levels.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="LM">
                        <img loading="lazy" src="assets/team/lucy_mworia.png" alt="Ms Lucy Mworia" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Ms Lucy Mworia</h3>
                    <p class="team-role">HSC - CEO & Founder</p>
                    <p class="team-summary">CEO & Founder of the Kenya Pro-Ageing organization, seconded to the Ministry of Health’s Department of Public Health.</p>
                    <div class="team-full-info">
                        She serves as a key liaison between the Ministry and implementing partners, strengthening coordination in program planning. Previously with the Ministry of Foreign and Diaspora Affairs, she brings diplomatic expertise to advocacy and partnership building. Through the Kenya Pro-Ageing Organization, she champions initiatives improving the welfare, health, and social inclusion of older persons in Kenya.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="BT">
                        <img loading="lazy" src="assets/team/bruce_tushabe.png" alt="Mr Bruce Tushabe" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Mr Bruce Tushabe</h3>
                    <p class="team-role">Regional Training Lead</p>
                    <p class="team-summary">A Ugandan national with over 20 years of global development experience, leading Capacity Strengthening at ARASA.</p>
                    <div class="team-full-info">
                        A public health advocate and activist, he has supported governments and civil society across East and Southern Africa to advance HIV, TB, and sexual and reproductive health programs.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>
            </div>

            <h2 class="team-section-title fade-in-up" style="animation-delay: 1s;">Management Team</h2>
            <div class="team-grid fade-in-up" style="animation-delay: 1.1s;">
                
                <div class="team-card">
                    <div class="avatar" data-initials="SO">
                        <img loading="lazy" src="assets/team/shem_otina.png" alt="Mr. Shem Otina" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Mr. Shem Otina</h3>
                    <p class="team-role">Operations Manager</p>
                    <p class="team-summary">The Lead Creative and Communications professional guiding the magazine’s storytelling vision and creative direction.</p>
                    <div class="team-full-info">
                        He develops people-centered narratives that highlight transformative African voices and translates complex social issues into engaging, accessible content while overseeing editorial consistency, brand identity, and multimedia storytelling.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="NN">
                        <img loading="lazy" src="assets/team/namiso_nafundo.png" alt="Ms Namiso Nafundo" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Ms Namiso Nafundo</h3>
                    <p class="team-role">Creative and Brand Lead</p>
                    <p class="team-summary">Lead Journalist driving impactful storytelling that highlights accountability, social issues, and community change.</p>
                    <div class="team-full-info">
                        She is a communications specialist with a distinct passion for sports journalism, leveraging her platform to elevate grassroots voices.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="FS">
                        <img loading="lazy" src="assets/team/fred_sadia.png" alt="Fred Sadia" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Hon. Fred Sadia</h3>
                    <p class="team-role">Global Partnerships Strategist</p>
                    <p class="team-summary">A distinguished global strategist connecting grassroots movements with international policy frameworks.</p>
                    <div class="team-full-info">
                        He works to advance volunteerism as a professionally managed tool for peacebuilding and sustainable development. Through strategic collaborations and cross-sector partnerships, he strengthens community-driven initiatives to align them with global priorities.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

                <div class="team-card">
                    <div class="avatar" data-initials="MO">
                        <img loading="lazy" src="assets/team/moses.png" alt="Moses" onerror="this.style.display='none'">
                    </div>
                    <h3 class="team-name">Moses Abuya</h3>
                    <p class="team-role">Lead Software Engineer</p>
                    <p class="team-summary">The primary architect behind the magazine's digital framework and seamless online experience.</p>
                    <div class="team-full-info">
                        Moses developed the Turning Point Magazine platform from scratch utilizing HTML, PHP, JavaScript, CSS, and SQL. A passionate web and Android developer, he bridges complex logic with clean user interfaces, ensuring the platform runs reliably to support the magazine's vision across Africa.
                    </div>
                    <button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><i class="fas fa-chevron-down"></i></button>
                </div>

            </div>
        </div>

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
            
            // Toggle open classes
            fullInfo.classList.toggle('open');
            btn.classList.toggle('open');
            
            // Swap icon visually
            if (fullInfo.classList.contains('open')) {
                btn.innerHTML = '<i class="fas fa-chevron-up"></i>';
            } else {
                btn.innerHTML = '<i class="fas fa-chevron-down"></i>';
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

