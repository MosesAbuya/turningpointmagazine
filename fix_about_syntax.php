<?php
$c = file_get_contents('about.php');

$pattern = '/<script>\s*function toggleInfo\(btn\) \{.*?<\/script>/s';
$replace = <<<EOT
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
EOT;

$c = preg_replace($pattern, $replace, $c);
file_put_contents('about.php', $c);
echo "Syntax error fixed.";
?>
