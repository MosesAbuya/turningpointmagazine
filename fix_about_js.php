<?php
$c = file_get_contents('about.php');

$jsPattern = '/function toggleInfo\(btn\) \{.*?btn\.innerHTML = \'<i class="fas fa-chevron-down"><\/i>\';\s*\}/s';

$jsReplace = <<<EOT
function toggleInfo(btn) {
            const card = btn.closest('.team-card');
            const fullInfo = card.querySelector('.team-full-info');
            const span = btn.querySelector('span') || document.createElement('span');
            if(!btn.contains(span)) btn.prepend(span);
            
            // Toggle open classes
            fullInfo.classList.toggle('open');
            btn.classList.toggle('open');
            
            if (fullInfo.classList.contains('open')) {
                span.innerText = 'Read Less';
                btn.innerHTML = span.outerHTML + ' <i class="fas fa-chevron-up"></i>';
            } else {
                span.innerText = 'Read More';
                btn.innerHTML = span.outerHTML + ' <i class="fas fa-chevron-down"></i>';
            }
        }
EOT;

$c = preg_replace($jsPattern, $jsReplace, $c);
file_put_contents('about.php', $c);
echo "about.php JS updated.";
?>
