<style>
/* Custom Navbar Styles */
.navbar {
    padding: 15px;
    background-color: rgb(224, 11, 11);
}

.navbar-brand {
    color: #ffffff;
}

.navbar .dropdown-toggle {
    color: #ffffff;
    background-color: #343a40;
}

.navbar .dropdown-toggle:hover {
    background-color: #495057;
}

.navbar .dropdown-menu {
    background-color: #343a40;
}

.navbar .dropdown-item {
    color: #ffffff;
}

.navbar .dropdown-item:hover {
    background-color: #495057;
}

.navbar .dropdown-divider {
    border-color: #495057;
}

img.rounded-circle {
    border-radius: 50%;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="d-flex justify-content-end align-items-center">
            <!-- User Info Dropdown -->
            <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">


                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
            <!-- Sign Out Button -->
            <a href="logout.php" class="btn btn-danger ms-3">
                Sign Out
            </a>
        </div>
    </div>
</nav>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>