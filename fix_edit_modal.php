<?php
$c = file_get_contents('admin/team_manage.php');

// Replace button
$patternBtn = "/onclick='editMember\(<\?= json_encode\(\\\$m\) \?>\)'/s";
$replaceBtn = "data-member=\"<?= htmlspecialchars(json_encode(\$m), ENT_QUOTES, 'UTF-8') ?>\" onclick=\"editMember(this)\"";
$c = preg_replace($patternBtn, $replaceBtn, $c);

// Replace JS function signature
$patternJs = "/function editMember\(m\) \{/s";
$replaceJs = "function editMember(btn) {\n        let m = JSON.parse(btn.getAttribute('data-member'));";
$c = preg_replace($patternJs, $replaceJs, $c);

file_put_contents('admin/team_manage.php', $c);
echo "Replaced editMember code successfully.";
?>
