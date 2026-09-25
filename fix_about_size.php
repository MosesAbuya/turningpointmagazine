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
        border-left: 3px solid var(--brand-teal);
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
    echo "Replaced CSS successfully (Smaller Cards)";
} else {
    echo "Could not find boundaries: start=\$startIdx, end=\$endIdx";
}
?>
