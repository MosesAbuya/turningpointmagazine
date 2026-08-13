<style>
    /* General Styles */
body, ul {
    margin: 0;
    padding: 0;
    
}

.n-top-bar {
    background: black;
    color: white;
    padding: 10px;
    text-align: center;
}

#b-crumb-h2{
    text-decoration: none;
    font-spacing: 2px;
    a{
        color: black;
        &:hover{
        text-decoration: none;
        underline: none;
            border-bottom: 3px solid red;
        }
    }
}
.n-top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: auto;
}

.n-social-icons a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
}

.n-navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #444;
    padding: 15px 20px;
    color: white;
    position: sticky;
    top: 0;
}

.navbar-brand {
    text-decoration: none;
    color: white;
}

.nav-links {
    display: flex;
    list-style: none;
    align-items: center;
}

.nav-links li {
    margin: 0 10px;
    position: relative;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 16px;
}

/* Shopping Cart Icon */
.nav-links i {
    font-size: 18px;
}

#cart_count {
    background: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    position: relative;
    top: -10px;
    left: -5px;
}

/* Dropdown Container */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Toggle Button */
.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    color: #2c3e50;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.dropdown-toggle:hover {
    background: #f8f9fa;
    color: black;
}

/* Dropdown Menu */
.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: -100px;
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    min-width: 200px;
    padding: 0.5rem 0;
    z-index: 1000;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

/* Dropdown Items */
.dropdown-menu li {
    padding: 0.5rem 1rem;
    border-bottom: 1px solid #eee;
}

.dropdown-menu li:last-child {
    border-bottom: none;
}

.dropdown-menu a {
    color: #2c3e50;
    text-decoration: none;
    display: block;
    padding: 0.5rem 1rem;
    transition: background 0.3s ease;
}

.dropdown-menu a:hover {
    background: #f8f9fa;
    
}

.dropdown-menu .dropdown-text {
    color: #6c757d;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-bottom: 1px solid #eee;
}

.dropdown-menu hr {
    margin: 0.5rem 0;
    border-color: #eee;
}

/* Logout Button */
.logout {
    color: #e74c3c;
}

.logout:hover {
    color: #c0392b;
}

/* Mobile Optimization */
@media (max-width: 768px) {
    .dropdown-menu {
        min-width: 100%;
        left: -1px;
    }
    
    .dropdown-menu li {
        padding: 0.5rem;
    }
    
    .dropdown-menu a {
        font-size: 1rem;
    }
}



/* Mobile Menu */
.menu-icon {
    display: none;
    font-size: 24px;
    cursor: pointer;
}

@media screen and (max-width: 768px) {
    .nav-links {
        display: flex;
        flex-direction: row-reverse;
        justify-content: space-between;
        position: sticky;
        top: 60px;
        left: 0;
        width: 100%;
        background: black;

    }
    #b-crumb-h2{
        font-size: 1.5rem;
    }
    .n-navbar-logo-h1{
        font-size: 2rem;
    }
    .n-navbar{
        flex-direction: column;
        background-color: white;
    }
    #cat-h{
        display: none;
    }

    .nav-links li {
        text-align: center;
        padding: 10px;
    }

    .menu-icon {
        display: none;
    }

    .nav-active {
        display: flex;
    }
}

</style>
<header class="n-top-bar">
    <div class="n-top-bar-content">
        <span id="t-about">📞 +254 718055457</span>
        <div class="n-social-icons">
            <a href="https://www.facebook.com/profile.php?id=100090750335981"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com/malshe_media"><i class="fab fa-instagram"></i></a>
            <a href="www.linkedin.com/malshe-media"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>
</header>

<header id="header">
    <nav class="n-navbar">
        <a href="index.php" class="navbar-brand">
            <h3 class="n-navbar-logo-h1">
                <i class="fas fa-store"></i> Turning Point Shop
            </h3>
        </a>

        <!-- Hamburger Menu (for mobile) -->
        <div class="menu-icon" id="menu-icon">&#9776;</div>

        <ul class="nav-links" id="nav-links">
           <li><a href="cart.php">
    <i class="fas fa-shopping-cart"></i>
    <!-- Remove PHP session code, use JavaScript-powered count -->
    <span id="cart_count">0</span>
</a></li>


            <!-- Account Dropdown -->
         <!-- Account Dropdown -->
<!-- <li class="dropdown">
    <a href="#" id="accountBtn" class="dropdown-toggle">
        <i class="fas fa-user"></i> Account
    </a>
    <ul class="dropdown-menu" id="accountDropdown">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="dropdown-text">👤 <?php echo htmlspecialchars($_SESSION['email']); ?></li>
            <hr>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php" class="logout">Logout</a></li>
        <?php else: ?>
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="signin.php">Login</a></li>
        <?php endif; ?>
    </ul>
</li> -->



        </ul>
    </nav>
</header>

<!-- FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/f65faecb5f.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Mobile Menu Toggle
    const menuIcon = document.getElementById("menu-icon");
    const navLinks = document.getElementById("nav-links");

    menuIcon.addEventListener("click", function () {
        navLinks.classList.toggle("nav-active");
    });

    // Account Dropdown
   document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.getElementById("accountDropdown");
    const accountBtn = document.getElementById("accountBtn");

    accountBtn.addEventListener("mouseover", function () {
        dropdown.style.display = "block";
    });

    document.addEventListener("click", function (event) {
        if (!accountBtn.contains(event.target) && !dropdown.contains(event.target)) {
            setTimeout(function() {
                dropdown.style.display = "none";
            }, 800); // Delay disappearance by 0.8 seconds
        }
    });

    dropdown.addEventListener("mouseover", function () {
        clearTimeout(); // Prevent disappearance when hovering over dropdown
    });
});



    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!accountBtn.contains(event.target) && !accountDropdown.contains(event.target)) {
            accountDropdown.style.display = "none";
        }
    });
});

</script>