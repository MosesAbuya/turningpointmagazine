<!-- sidebar.php -->
<style>
/* Style for active sidebar link */
.components li.active a {
    border-bottom: 3px solid #ffc107;
    /* You can change the color */
    padding-bottom: 10px;
}

.components li a:hover {
    background-color: #495057;
}
</style>
<style>
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

/* Sidebar Styles */
#wrapper {
    display: flex;
    min-height: 100vh;
}

#sidebar {
    width: 250px;
    background-color: #343a40;
    color: white;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    transition: 0.3s ease;
}

.sidebar-header {
    text-align: center;
    padding: 15px;
    background-color: #212529;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 18px;
}

.components {
    padding-left: 0;
    list-style: none;
}

.components li a {
    padding: 10px 20px;
    display: block;
    color: white;
    text-decoration: none;
    font-size: 16px;
}

.components li a:hover {
    background-color: #495057;
}

.components li.active a {
    border-bottom: 3px solid #ffc107;
    padding-bottom: 10px;
}

/* Page Content Area */
#page-content-wrapper {
    flex: 1;
    padding: 20px;
    margin-left: 250px;
}

/* Styling for Small Screens */
@media (max-width: 767px) {
    #sidebar {
        width: 100%;
        position: relative;
        height: auto;
    }

    #page-content-wrapper {
        margin-left: 0;
    }
}
</style>



<!-- sidebar.php -->
<div class="d-flex" id="wrapper">
    <div class="bg-dark" id="sidebar">
        <div class="sidebar-header text-center py-4">
            <h3 class="text-white">Products</h3>
        </div>
        <ul class="list-unstyled components">
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>">
                <a href="home.php" class="text-white">Home</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'subscribers.php') ? 'active' : ''; ?>">
                <a href="subscribers.php" class="text-white">Subscribers</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'editions.php') ? 'active' : ''; ?>">
                <a href="editions.php" class="text-white">Editions</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'active' : ''; ?>">
                <a href="feedback.php" class="text-white">Feedback</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'stories.php') ? 'active' : ''; ?>">
                <a href="stories.php" class="text-white">Stories</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'blog.php') ? 'active' : ''; ?>">
                <a href="blog.php" class="text-white">Blog</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'category.php') ? 'active' : ''; ?>">
                <a href="category.php" class="text-white">Story Categories</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'sub_category.php') ? 'active' : ''; ?>">
                <a href="sub_category.php" class="text-white">Story Sub Categories</a>
            </li>
             <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'attendees.php') ? 'active' : ''; ?>">
                <a href="attendees.php" class="text-white">Attendees</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'attendance_dashboard.php') ? 'active' : ''; ?>">
                <a href="attendance_dashboard.php" class="text-white">Attendance Roster</a>
            </li>
             <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'booths.php') ? 'active' : ''; ?>">
                <a href="booking.php" class="text-white">Booth Management</a>
            </li>
            <li class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['add-spotlight.php', 'manage-spotlight.php'])) ? 'active' : ''; ?>">
                <a href="manage-spotlight.php" class="text-white">Spotlight</a>
            </li>
            <li class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['directories_add.php', 'directories_list.php', 'directories_edit.php'])) ? 'active' : ''; ?>">
                <a href="directories_list.php" class="text-white">Directories</a>
            </li>
             <li class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['awards_to_apply.php', 'add_award_to_apply.php', 'edit_award_to_apply.php'])) ? 'active' : ''; ?>">
                <a href="awards_to_apply.php" class="text-white">Awards to Apply</a>
            </li>
            <li class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['personal_awards_won.php', 'add_personal_award.php', 'edit_personal_award.php'])) ? 'active' : ''; ?>">
                <a href="personal_awards_won.php" class="text-white">Personal Awards</a>
            </li>
            <li class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['award_applicants.php', 'view_applicant.php'])) ? 'active' : ''; ?>">
                <a href="award_applicants.php" class="text-white">Award Applicants</a>
            </li>


        </ul>
    </div>
</div>