<?php
$activePage = basename($_SERVER['PHP_SELF'] ?? 'dashboard.php');
$activeSection = app_input('section', '');
$currentAdmin = app_current_admin();
?><aside class="admin-sidebar">
    <div class="admin-sidebar-brand">
        <img src="../Bangladesh_Scouts_Logo.png" alt="Bangladesh Scouts logo" class="logo-image">
        <div>
            <h2>KUET ROVER</h2>
            <p>Admin Control Panel</p>
        </div>
    </div>

    <nav class="admin-sidebar-nav" aria-label="Admin sections">
        <a class="admin-side-link <?php echo $activePage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Overview</a>
        <a class="admin-side-link <?php echo $activePage === 'manage-content.php' && $activeSection === 'events' ? 'active' : ''; ?>" href="manage-content.php?section=events">Events</a>
        <a class="admin-side-link <?php echo $activePage === 'manage-content.php' && $activeSection === 'camps' ? 'active' : ''; ?>" href="manage-content.php?section=camps">Camps</a>
        <a class="admin-side-link <?php echo $activePage === 'manage-content.php' && $activeSection === 'gallery' ? 'active' : ''; ?>" href="manage-content.php?section=gallery">Gallery</a>
        <a class="admin-side-link <?php echo $activePage === 'manage-content.php' && $activeSection === 'members' ? 'active' : ''; ?>" href="manage-content.php?section=members">Members</a>
        <a class="admin-side-link <?php echo $activePage === 'messages.php' ? 'active' : ''; ?>" href="messages.php">Messages</a>
        <a class="admin-side-link <?php echo $activePage === 'registrations.php' ? 'active' : ''; ?>" href="registrations.php">Registrations</a>
        <a class="admin-side-link" href="../index.php">Public Site</a>
    </nav>

    <div class="admin-sidebar-footer">
        <p>Signed in as <strong><?php echo app_h((string) ($currentAdmin['username'] ?? 'admin')); ?></strong></p>
        <a href="logout.php" class="btn btn-primary btn-small">Logout</a>
    </div>
</aside>
