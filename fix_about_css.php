<?php
$lines = file('about.php');
$startIdx = -1;
$endIdx = -1;

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], '.team-grid {') !== false) {
        $startIdx = $i;
    }
    if ($startIdx !== -1 && strpos($lines[$i], '.alert-box {') !== false) {
        $endIdx = $i;
        break;
    }
}

if ($startIdx !== -1 && $endIdx !== -1) {
    $newCss = <<<EOT
    .team-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 2.5rem;
        margin-bottom: 4rem;
    }

    .team-card {
        flex: 0 1 350px;
        width: 100%;
        max-width: 380px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--brand-teal);
    }
    
    .team-card-inner {
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        flex-grow: 1;
    }

    .avatar {
        width: 160px;
        height: 160px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--brand-teal), var(--brand-pink));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        transform: rotate(-3deg);
        transition: transform 0.4s ease;
    }
    
    .team-card:hover .avatar {
        transform: rotate(0deg) scale(1.05);
    }

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
        font-size: 1.7rem;
        margin-bottom: 0.2rem;
    }

    .team-role {
        font-family: 'Poppins', sans-serif;
        color: var(--brand-red);
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        background: rgba(232, 0, 61, 0.08);
        padding: 6px 16px;
        border-radius: 20px;
    }

    .team-summary {
        color: var(--text-light);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .team-full-info {
        display: none; 
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--text-dark);
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        text-align: left;
        border-left: 4px solid var(--brand-teal);
        width: 100%;
    }

    .team-full-info.open {
        display: block;
        animation: fadeIn 0.4s ease-in-out;
    }
    
    .toggle-btn {
        background: transparent;
        border: 2px solid var(--brand-teal);
        color: var(--brand-teal);
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px 24px;
        border-radius: 30px;
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .toggle-btn:hover {
        background: var(--brand-teal);
        color: #fff;
    }

    .toggle-btn i {
        transition: transform 0.3s ease;
    }

    .toggle-btn.open {
        background: var(--brand-pink);
        border-color: var(--brand-pink);
        color: #fff;
    }
    .toggle-btn.open i {
        transform: rotate(180deg);
    }

EOT;

    array_splice($lines, $startIdx, $endIdx - $startIdx, [$newCss]);
    file_put_contents('about.php', implode("", $lines));
    echo "Replaced CSS successfully";
} else {
    echo "Could not find boundaries: start=\$startIdx, end=\$endIdx";
}
?>
