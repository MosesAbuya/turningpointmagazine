document.addEventListener("DOMContentLoaded", function () {
    // Mobile Menu Toggle
    const menuIcon = document.getElementById("menu-icon");
    const navLinks = document.getElementById("nav-links");

    if (menuIcon && navLinks) {
        menuIcon.addEventListener("click", function () {
            navLinks.classList.toggle("nav-active");
        });
    }

    // Account Dropdown
    const accountBtn = document.getElementById("accountBtn");
    const accountDropdown = document.getElementById("accountDropdown");

    if (accountBtn && accountDropdown) {
        accountBtn.addEventListener("click", function (event) {
            event.preventDefault();
            accountDropdown.classList.toggle("show-dropdown");
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (!accountBtn.contains(event.target) && !accountDropdown.contains(event.target)) {
                accountDropdown.classList.remove("show-dropdown");
            }
        });
    }
});
