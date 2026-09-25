<?php
$c = file_get_contents('about.php');

// Remove border-left from .team-full-info
$c = str_replace('border-left: 3px solid var(--brand-teal);', '', $c);
$c = str_replace('border-left: 4px solid var(--brand-teal);', '', $c); // just in case

// Update .toggle-btn.open to use red instead of pink
$btnOpenOld = <<<EOT
.toggle-btn.open {
        background: var(--brand-pink);
        border-color: var(--brand-pink);
        color: #fff;
    }
EOT;

$btnOpenNew = <<<EOT
.toggle-btn.open {
        background: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }
EOT;

$c = str_replace($btnOpenOld, $btnOpenNew, $c);

file_put_contents('about.php', $c);
echo "Updated border and button colors in about.php";
?>
