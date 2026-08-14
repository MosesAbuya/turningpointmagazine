<style>
/* ---
START: New Dark Footer Style Block
--- */
:root {
    --brand-red: #ff0000;
    --brand-teal: #008080;
    --brand-pink: #E6007E;
    --brand-gold: #FFD700;
    --text-dark: #333;
    --text-light-gray: #555;
    --bg-off-white: #f8f9fa;
    --border-light: #eee;

    /* New Dark Palette */
    --footer-bg-dark-primary: #2b2828;
    /* Main footer background */
    --footer-bg-dark-secondary: #1c1c1c;
    /* Sub-footer background */
    --footer-text-light: #ccc;
    /* Main text on dark */
    --footer-text-muted: #999;
    /* Copyright text */
}

/* 1. Main Footer Wrapper */
.footer-main {
    background-color: var(--footer-bg-dark-primary);
    /* CHANGED */
    padding: 60px 20px 40px 20px;
    border-top: 1px solid #444;
    /* CHANGED */
}

.footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
}

/* 2. Footer Columns */
.footer-column h3 {
    font-family: 'florania', sans-serif;
    font-size: 1.8rem;
    color: #fff;
    /* CHANGED */
    margin-top: 0;
    margin-bottom: 20px;
}

/* Column 1: About */
.footer-about .logo {
    font-family: 'florania', sans-serif;
    font-size: 2.5rem;
    color: var(--brand-red);
    line-height: 1;
    margin: 0;
}

.footer-about .punchline {
    font-family: 'Caveat', cursive;
    font-size: 1.5rem;
    color: var(--brand-pink);
    margin: 0 0 15px 0;
}

.footer-about p {
    font-size: 0.9rem;
    color: var(--footer-text-light);
    /* CHANGED */
    line-height: 1.6;
}

/* Column 2: Links */
.footer-links .menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links .menu__item {
    margin-bottom: 12px;
}

.footer-links .menu__link {
    text-decoration: none;
    color: var(--footer-text-light);
    /* CHANGED */
    font-weight: 500;
    transition: all 0.3s ease;
}

.footer-links .menu__link:hover {
    color: var(--brand-red);
    transform: translateX(5px);
}

/* Column 3: Social */
.footer-social .fsocial-icon {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.footer-social .social-icon__link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background-color: #444;
    /* CHANGED */
    border: 1px solid #555;
    /* CHANGED */
    color: #fff;
    /* CHANGED */
    font-size: 1.4rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-social .social-icon__link:hover {
    background-color: var(--brand-red);
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* 3. Sub-Footer (Copyright Bar) */
.footer-sub {
    background-color: var(--footer-bg-dark-secondary);
    /* CHANGED */
    color: var(--footer-text-muted);
    /* CHANGED */
    padding: 25px 20px;
    text-align: center;
    font-size: 0.85rem;
}

.footer-sub p {
    margin: 5px 0;
    line-height: 1.5;
}

.footer-sub a {
    color: var(--brand-gold);
    text-decoration: none;
    font-weight: 600;
}

.footer-sub a:hover {
    text-decoration: underline;
}

/* 4. Responsive */
@media (max-width: 768px) {
    .footer-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .footer-about,
    .footer-links,
    .footer-social {
        text-align: center;
    }

    .footer-social .fsocial-icon {
        justify-content: center;
    }
}
</style>

<footer class="footer">

    <div class="footer-main">
        <div class="footer-grid">

            <div class="footer-column footer-about">
                <h3 class="logo">Turning Point</h3>
                <p class="punchline">Transforming Everyday</p>
                <p>Amplifying grassroots voices and celebrating stories of positive change across Africa.</p>
            </div>

            <div class="footer-column footer-links">
                <h3>Quick Links</h3>
                <ul class="menu">
                    <li class="menu__item"><a class="menu__link" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>#latest">Home</a></li>
                    <li class="menu__item"><a class="menu__link" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>about">About</a></li>
                    <li class="menu__item"><a class="menu__link" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>contact">Contact</a></li>
                    <li class="menu__item"><a class="menu__link" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>#subscribe">Subscribe</a></li>
                    <li class="menu__item"><a class="menu__link" href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>#tp-collection">Library</a></li>
                </ul>
            </div>

            <div class="footer-column footer-social">
                <h3>Follow Us</h3>
                <ul class="fsocial-icon">
                    <li class="social-icon__item"><a class="social-icon__link" href="https://www.facebook.com/turningpointmag" target="_blank">
                            <ion-icon name="logo-facebook"></ion-icon>
                        </a></li>
                    <li class="social-icon__item"><a class="social-icon__link" href="https://twitter.com/turningpointmag" target="_blank">
                            <ion-icon name="logo-twitter"></ion-icon>
                        </a></li>
                    <!--
                    <li class="social-icon__item"><a class="social-icon__link" href="#">
                            <ion-icon name="logo-linkedin"></ion-icon>
                        </a></li>
                    <li class="social-icon__item"><a class="social-icon__link" href="#">
                            <ion-icon name="logo-instagram"></ion-icon>
                        </a></li>
                    -->
                </ul>
            </div>

        </div>
    </div>

    <div class="footer-sub">
        <p>&copy;2024 Copyright Turningpoint & Malshe Media&trade; | All Rights Reserved</p>
        <p>
            Malshe Media is a licensed Data Controller <b>(Serial No. 03323, ID: 454-5788-A9FE)</b> under Kenya's Data
            Protection laws.
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/turningpoint/' ?>about#licencing">Learn More</a>
        </p>
        <p><a href="https://www.mmtechpro.co.ke" target="_blank" rel="noopener">2025&copy; Designed By MM Techpro</a>
        </p>
    </div>

</footer>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Hidden Google Translate Element -->
<div id="google_translate_element"></div>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: 'en,fr,de,es,it,zh-CN,ar,pt,ja,ru,nl,sv,sw', // Added sw (Swahili) since it's an African magazine
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
    autoDisplay: false
  }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script type="text/javascript">
function setLanguage(langCode) {
    var domain = location.hostname;
    var rootDomain = domain.replace(/^www\./, '');
    
    // Try to cover all possible domains where Google Translate might have dropped a cookie
    var domains = [
        '', 
        '.' + domain, 
        '.' + rootDomain, 
        domain, 
        rootDomain
    ];
    
    if (langCode === 'en') { 
      // Fallback: clear cookies to reset to default English
      domains.forEach(function(d) {
          var domainStr = d ? '; domain=' + d : '';
          document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/' + domainStr;
      });
    } else {
      // Set the cookie for the selected language
      domains.forEach(function(d) {
          var domainStr = d ? '; domain=' + d : '';
          document.cookie = 'googtrans=/en/' + langCode + '; path=/' + domainStr;
      });
    }
    
    // Reload the page to apply the translation
    location.reload();
}

document.addEventListener('DOMContentLoaded', function() {
  var cookieMatch = document.cookie.match(/googtrans=\/en\/([a-z\-]+)/);
  if (cookieMatch && cookieMatch[1]) {
    var activeLang = cookieMatch[1];
    
    // Sync all language dropdowns on the page
    var selects = document.querySelectorAll('.custom-lang-select');
    selects.forEach(function(selectElement) {
        selectElement.value = activeLang;
    });
  }
});
</script>


<!-- Unified Search Modal -->
<div id="tp-search-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:999999; align-items:center; justify-content:center; flex-direction:column;">
    <div style="width:90%; max-width:600px; position:relative;">
        <input type="text" id="tp-modal-search-input" placeholder="Search..." style="width:100%; padding:15px 20px; font-size:18px; border-radius:30px; border:none; outline:none; box-shadow:0 4px 15px rgba(0,0,0,0.2);">
        <button onclick="document.getElementById('tp-search-modal').style.display='none'" style="position:absolute; right:15px; top:12px; background:none; border:none; font-size:24px; color:#555; cursor:pointer;">&times;</button>
        <div id="tp-modal-search-results" class="tp-search-results-dropdown" style="width:100%; top:60px; position:absolute; background:white; z-index:1000; border-radius:10px; overflow:hidden;"></div>
    </div>
</div>


<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    // --- AJAX Search Function ---
    function initSearch(inputId, resultsId) {
        const searchInput = document.getElementById(inputId);
        const searchResultsContainer = document.getElementById(resultsId);
        if (!searchInput) return;
        
        // Use BASE_URL correctly for AJAX requests
        const basePath = typeof BASE_URL !== "undefined" ? BASE_URL : "/turningpoint/";

        searchInput.addEventListener("input", function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 0) {
                fetchSearchResults(searchTerm, searchResultsContainer, basePath);
            } else {
                searchResultsContainer.innerHTML = "";
                searchResultsContainer.style.display = "none";
            }
        });
        document.addEventListener("click", function(e) {
            if (searchResultsContainer) {
                const isClickInside = searchInput.contains(e.target) || searchResultsContainer.contains(e.target);
                if (!isClickInside) {
                    searchResultsContainer.style.display = "none";
                }
            }
        });
    }

    function fetchSearchResults(searchTerm, resultsContainer, basePath) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", basePath + "search.php?query=" + encodeURIComponent(searchTerm), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const results = JSON.parse(xhr.responseText);
                    displaySearchResults(results, resultsContainer, basePath);
                } catch (e) {
                    console.error("Error parsing search results:", e, xhr.responseText);
                }
            } else {
                console.error("Error with AJAX request:", xhr.status);
            }
        };
        xhr.send();
    }

    function displaySearchResults(results, resultsContainer, basePath) {
        resultsContainer.innerHTML = "";
        const limitedResults = results.slice(0, 10);
        if (limitedResults.length > 0) {
            resultsContainer.style.display = "block";
            limitedResults.forEach(result => {
                const div = document.createElement("div");
                div.classList.add("tp-search-item");
                div.style.padding = "10px";
                div.style.borderBottom = "1px solid #eee";
                div.style.cursor = "pointer";
                div.style.color = "#333";
                
                if (result.name) {
                    div.classList.add("tp-search-cat-result");
                    div.innerHTML = `<strong>Category:</strong> ${result.name}`;
                    div.addEventListener("click", () => window.location.href = basePath + `category/` + result.name.toLowerCase().replace(/ /g, "-"));
                } else {
                    div.classList.add("tp-search-art-result");
                    div.innerHTML = result.title;
                    div.addEventListener("click", () => window.location.href = basePath + `issue.php?id=${result.id}&edition_id=${result.edition_id}`);
                }
                
                div.addEventListener("mouseenter", () => div.style.backgroundColor = "#f9f9f9");
                div.addEventListener("mouseleave", () => div.style.backgroundColor = "transparent");
                
                resultsContainer.appendChild(div);
            });
        } else {
            resultsContainer.style.display = "none";
        }
    }
    
    initSearch("tp-modal-search-input", "tp-modal-search-results");
});
</script>


<style type="text/css">
/* Guaranteed override for Google Translate Banner */
iframe.goog-te-banner-frame { display: none !important; }
iframe.skiptranslate { display: none !important; visibility: hidden !important; }
.goog-te-balloon-frame { display: none !important; }
body { top: 0px !important; position: static !important; min-height: 100vh !important; }
.VIpgJd-ZVi9od-ORHb-OEVmcd, .VIpgJd-ZVi9od-aZ2wEe-wOHMyf { display: none !important; }
</style>

