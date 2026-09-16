<!-- nav.php -->
<nav class="navbar navbar-expand-lg admin-navbar fixed-top" style="z-index: 1030;">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center">
            <!-- Mobile Toggle -->
            <button class="btn border-0 me-3 d-md-none" id="sidebarToggle" type="button">
                <i class="fas fa-bars" style="font-size: 1.2rem;"></i>
            </button>
            <a class="navbar-brand m-0" href="home.php">Turning<span>Point</span> Admin</a>
        </div>
        
        <div class="d-flex align-items-center ms-auto">
            <!-- Notifications (Example) -->
            <a href="#" class="text-secondary me-4 position-relative">
                <i class="fas fa-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="sr-only visually-hidden">New alerts</span>
                </span>
            </a>

            <!-- User Info Dropdown -->
            <div class="dropdown nav-user-profile">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/img/avatar-placeholder.png" alt="Admin" class="rounded-circle me-2" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=e8003d&color=fff'">
                    <span class="d-none d-md-block fw-semibold">Administrator</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="smtp_settings.php"><i class="fas fa-cog me-2 text-muted"></i> SMTP Settings</a></li>
                    <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-key me-2 text-muted"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = userDropdown.nextElementSibling;
    
    userDropdown.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
});
</script>
<!-- Enable SPA Routing and SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function() {
    if (window.spaRouterInitialized) return;
    window.spaRouterInitialized = true;

    // Override global alert
    window.alert = function(msg) {
        Swal.fire({
            title: 'Notification',
            text: msg,
            icon: 'info',
            confirmButtonColor: '#e8003d'
        });
    };

    // Intercept inline confirms
    document.addEventListener("click", function(e) {
        let el = e.target.closest('[onclick*="return confirm"]');
        if (el) {
            e.preventDefault();
            e.stopPropagation();
            let match = el.getAttribute('onclick').match(/confirm\(['"](.*?)['"]\)/);
            let msg = match ? match[1] : 'Are you sure?';
            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e8003d',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    el.removeAttribute('onclick');
                    el.click();
                }
            });
            return;
        }

        let a = e.target.closest('a');
        if (!a) return;
        
        // Only intercept links within sidebar or navbar
        if (!a.closest('#sidebar') && !a.closest('.admin-navbar')) return;

        let url = a.getAttribute('href');
        if (!url || url === '#' || url.startsWith('javascript:') || a.getAttribute('data-bs-toggle')) return;

        e.preventDefault();
        
        // Show loading state on body
        document.body.style.opacity = '0.5';

        fetch(url)
            .then(r => r.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                document.body.innerHTML = doc.body.innerHTML;
                document.body.style.opacity = '1';
                
                history.pushState(null, '', url);

                // Re-execute scripts in the new body
                Array.from(document.body.querySelectorAll("script")).forEach(oldScript => {
                    let newScript = document.createElement("script");
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.textContent = oldScript.textContent;
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
                
                // Trigger DOMContentLoaded manually for jQuery scripts that rely on it
                window.document.dispatchEvent(new Event("DOMContentLoaded", {
                    bubbles: true,
                    cancelable: true
                }));
            })
            .catch(() => {
                // Fallback to normal navigation if fetch fails
                window.location.href = url;
            });
    });

    window.addEventListener("popstate", function() {
        window.location.reload();
    });
})();
</script>