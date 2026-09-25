<?php
$c = file_get_contents('about.php');

// 1. Fix HTML: Move .avatar outside .team-card-inner
$htmlPattern = '/<div class="team-card">\s*<div class="team-card-inner">\s*<div class="avatar"(.*?)<\/div>/s';
$htmlReplace = <<<EOT
<div class="team-card">
                    <div class="avatar"$1</div>
                    <div class="team-card-inner">
EOT;
$c = preg_replace($htmlPattern, $htmlReplace, $c);

// Fix the button HTML in the loop
$btnPattern = '/<button class="toggle-btn" aria-label="Read more" onclick="toggleInfo\(this\)">.*?<\/button>/s';
$btnReplace = '<button class="toggle-btn" aria-label="Read more" onclick="toggleInfo(this)"><span class="btn-text">Read More</span> <i class="fas fa-chevron-down"></i></button>';
$c = preg_replace($btnPattern, $btnReplace, $c);


// 2. Fix CSS
$cssPattern = '/\.team-card-inner \{.*?\.avatar \{.*?\.team-card:hover \.avatar \{.*?\}/s';
$cssReplace = <<<EOT
.team-card-inner {
        padding: 2rem 2rem 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        flex-grow: 1;
    }

    .avatar {
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, var(--brand-teal), var(--brand-pink));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
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
EOT;
$c = preg_replace($cssPattern, $cssReplace, $c);


// 3. Fix JS
$jsPattern = '/function toggleInfo\(btn\) \{.*?\n\s*\}/s';
$jsReplace = <<<EOT
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
EOT;
$c = preg_replace($jsPattern, $jsReplace, $c);

file_put_contents('about.php', $c);
echo "about.php updated with full width avatars and fixed JS.";
?>
