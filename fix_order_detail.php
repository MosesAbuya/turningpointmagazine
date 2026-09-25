<?php
$c = file_get_contents('admin/shop_order_detail.php');

$pattern = '/<td>\s*<img src="\.\.\/<\?= htmlspecialchars\(\$item\[\'img_path\'\]\) \?>"[^>]+>\s*<\?= htmlspecialchars\(\$item\[\'name\'\]\) \?>\s*<\/td>/s';

$replacement = <<<EOT
                                    <td>
                                        <img src="../<?= htmlspecialchars(\$item['img_path']) ?>" alt="Img" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                        <?= htmlspecialchars(\$item['name']) ?>
                                        <?php if (!empty(\$item['size'])): ?>
                                            <span class="badge bg-secondary ms-2"><?= htmlspecialchars(\$item['size']) ?></span>
                                        <?php endif; ?>
                                    </td>
EOT;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents('admin/shop_order_detail.php', $c);
echo "Replaced in admin/shop_order_detail.php";
?>
