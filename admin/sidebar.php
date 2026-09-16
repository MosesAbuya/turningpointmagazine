<!-- sidebar.php -->
<?php 
$current_page = basename($_SERVER['PHP_SELF']); 

// Helper to determine if a group is active (should be expanded)
function isGroupActive($pages, $current) {
    return in_array($current, $pages) ? 'show' : '';
}
function isGroupExpanded($pages, $current) {
    return in_array($current, $pages) ? 'true' : 'false';
}
function isActive($page, $current) {
    return $page === $current ? 'active' : '';
}

// Group definitions
$content_pages = ['editions.php', 'edit_edition.php', 'blog.php', 'edit_blog.php', 'add_blog.php', 'stories.php', 'category.php', 'sub_category.php', 'manage-spotlight.php', 'add-spotlight.php', 'edit-spotlight.php'];
$commerce_pages = ['shop_manage.php', 'shop_product_add.php', 'shop_product_edit.php', 'shop_orders.php', 'shop_order_detail.php', 'shop_sales.php'];
$engagement_pages = ['subscribers.php', 'feedback.php', 'attendees.php', 'attendance_dashboard.php', 'booking.php'];
$awards_pages = ['awards_to_apply.php', 'add_award_to_apply.php', 'edit_award_to_apply.php', 'award_applicants.php', 'view_applicant.php', 'personal_awards_won.php', 'add_personal_award.php', 'edit_personal_award.php'];
$directory_pages = ['directories_list.php', 'directories_add.php', 'directories_edit.php'];
?>

<div id="sidebar" class="shadow-lg">
    <div class="sidebar-header">
        <h3>Turning<span>Point</span></h3>
    </div>
    <ul class="list-unstyled components">
        
        <div class="section-title">Overview</div>
        <li class="<?= isActive('home.php', $current_page) ?>">
            <a href="home.php"><i class="fas fa-home"></i> Dashboard</a>
        </li>

        <div class="section-title">Content</div>
        <li>
            <a href="#contentSubmenu" data-bs-toggle="collapse" aria-expanded="<?= isGroupExpanded($content_pages, $current_page) ?>">
                <i class="fas fa-newspaper"></i> Magazine & Blog
            </a>
            <ul class="collapse list-unstyled <?= isGroupActive($content_pages, $current_page) ?>" id="contentSubmenu">
                <li class="<?= isActive('editions.php', $current_page) ?>"><a href="editions.php">Editions</a></li>
                <li class="<?= isActive('stories.php', $current_page) ?>"><a href="stories.php">Stories</a></li>
                <li class="<?= isActive('blog.php', $current_page) ?>"><a href="blog.php">Blog Posts</a></li>
                <li class="<?= isActive('category.php', $current_page) ?>"><a href="category.php">Categories</a></li>
                <li class="<?= isActive('sub_category.php', $current_page) ?>"><a href="sub_category.php">Sub-Categories</a></li>
                <li class="<?= in_array($current_page, ['manage-spotlight.php','add-spotlight.php','edit-spotlight.php']) ? 'active' : '' ?>"><a href="manage-spotlight.php">Spotlight</a></li>
            </ul>
        </li>

        <div class="section-title">Commerce</div>
        <li>
            <a href="#commerceSubmenu" data-bs-toggle="collapse" aria-expanded="<?= isGroupExpanded($commerce_pages, $current_page) ?>">
                <i class="fas fa-shopping-cart"></i> Shop & Sales
            </a>
            <ul class="collapse list-unstyled <?= isGroupActive($commerce_pages, $current_page) ?>" id="commerceSubmenu">
                <li class="<?= isActive('shop_sales.php', $current_page) ?>"><a href="shop_sales.php">Analytics & P&L</a></li>
                <li class="<?= in_array($current_page, ['shop_orders.php', 'shop_order_detail.php']) ? 'active' : '' ?>"><a href="shop_orders.php">Order Management</a></li>
                <li class="<?= in_array($current_page, ['shop_manage.php', 'shop_product_add.php', 'shop_product_edit.php']) ? 'active' : '' ?>"><a href="shop_manage.php">Products</a></li>
            </ul>
        </li>

        <div class="section-title">Engagement</div>
        <li>
            <a href="#engageSubmenu" data-bs-toggle="collapse" aria-expanded="<?= isGroupExpanded($engagement_pages, $current_page) ?>">
                <i class="fas fa-users"></i> Users & Events
            </a>
            <ul class="collapse list-unstyled <?= isGroupActive($engagement_pages, $current_page) ?>" id="engageSubmenu">
                <li class="<?= isActive('subscribers.php', $current_page) ?>"><a href="subscribers.php">Subscribers</a></li>
                <li class="<?= isActive('feedback.php', $current_page) ?>"><a href="feedback.php">Feedback</a></li>
                <li class="<?= isActive('attendees.php', $current_page) ?>"><a href="attendees.php">Event Attendees</a></li>
                <li class="<?= isActive('attendance_dashboard.php', $current_page) ?>"><a href="attendance_dashboard.php">Attendance Roster</a></li>
                <li class="<?= isActive('booking.php', $current_page) ?>"><a href="booking.php">Booth Bookings</a></li>
            </ul>
        </li>

        <div class="section-title">Awards</div>
        <li>
            <a href="#awardsSubmenu" data-bs-toggle="collapse" aria-expanded="<?= isGroupExpanded($awards_pages, $current_page) ?>">
                <i class="fas fa-trophy"></i> Awards System
            </a>
            <ul class="collapse list-unstyled <?= isGroupActive($awards_pages, $current_page) ?>" id="awardsSubmenu">
                <li class="<?= in_array($current_page, ['awards_to_apply.php', 'add_award_to_apply.php', 'edit_award_to_apply.php']) ? 'active' : '' ?>"><a href="awards_to_apply.php">Awards to Apply</a></li>
                <li class="<?= in_array($current_page, ['award_applicants.php', 'view_applicant.php']) ? 'active' : '' ?>"><a href="award_applicants.php">Applicants</a></li>
                <li class="<?= in_array($current_page, ['personal_awards_won.php', 'add_personal_award.php', 'edit_personal_award.php']) ? 'active' : '' ?>"><a href="personal_awards_won.php">Personal Awards Won</a></li>
            </ul>
        </li>

        <div class="section-title">Directories</div>
        <li>
            <a href="#dirSubmenu" data-bs-toggle="collapse" aria-expanded="<?= isGroupExpanded($directory_pages, $current_page) ?>">
                <i class="fas fa-address-book"></i> Directories
            </a>
            <ul class="collapse list-unstyled <?= isGroupActive($directory_pages, $current_page) ?>" id="dirSubmenu">
                <li class="<?= in_array($current_page, ['directories_list.php', 'directories_add.php', 'directories_edit.php']) ? 'active' : '' ?>"><a href="directories_list.php">Manage Directories</a></li>
            </ul>
        </li>
        
        <div class="section-title">Settings</div>
        <li class="<?= isActive('smtp_settings.php', $current_page) ?>">
            <a href="smtp_settings.php"><i class="fas fa-cog"></i> SMTP Settings</a>
        </li>
    </ul>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('toggled');
        });
    }

    // Custom Accordion Logic to bypass Bootstrap version conflicts
    const collapseToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
    collapseToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            
            if (target) {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                // Close all others first
                collapseToggles.forEach(t => {
                    t.setAttribute('aria-expanded', 'false');
                    const tId = t.getAttribute('href').substring(1);
                    const tTarget = document.getElementById(tId);
                    if (tTarget) {
                        tTarget.classList.remove('show');
                    }
                });

                if (!isExpanded) {
                    this.setAttribute('aria-expanded', 'true');
                    target.classList.add('show');
                }
            }
        });
    });
});
</script>