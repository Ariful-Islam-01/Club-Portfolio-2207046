<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
app_require_admin();

$databaseStatus = app_database_status();
$sections = app_sections();
$stats = [
    'events' => app_table_count('events'),
    'camps' => app_table_count('camps'),
    'gallery' => app_table_count('gallery'),
    'members' => app_table_count('members'),
    'messages' => app_table_count('contact_messages'),
    'registrations' => app_table_count('registrations'),
];
$currentAdmin = app_current_admin();
$flash = app_flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KUET Rover Scout Group admin dashboard.">
    <meta name="theme-color" content="#2d5016">
    <title>Admin Dashboard | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page admin-page">
    <div class="admin-dashboard-shell">
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <div class="admin-main-panel">
            <header class="admin-topbar">
                <div>
                    <span class="admin-kicker">Control Center</span>
                    <h1>Dashboard Overview</h1>
                    <p>Manage your scout club website in one elegant workspace.</p>
                </div>
                <div class="admin-topbar-actions">
                    <a href="../index.php" class="back-link">View Site</a>
                    <a href="logout.php" class="btn btn-secondary btn-small"">Logout</a>
                </div>
            </header>

            <main class="admin-dashboard-content">
                <?php if ($flash !== null): ?>
                    <p class="form-status <?php echo app_h($flash['type']); ?>"><?php echo app_h($flash['message']); ?></p>
                <?php endif; ?>

                <section class="admin-hero-panel">
                    <div class="admin-hero-copy">
                        <span class="admin-kicker">Welcome back</span>
                        <h2>Hello, <?php echo app_h((string) ($currentAdmin['username'] ?? 'admin')); ?>!</h2>
                        <p>Keep the club portfolio fresh with updates for events, camps, gallery content, members, and incoming messages.</p>
                        <div class="admin-hero-actions">
                            <a href="messages.php" class="btn btn-primary">Review Messages</a>
                        </div>
                    </div>
                    <div class="admin-hero-status">
                        <p class="registration-note">Database connection: <strong><?php echo $databaseStatus[0] ? 'OK' : 'Not connected'; ?></strong></p>
                        <p class="registration-note"><?php echo app_h($databaseStatus[1]); ?></p>
                    </div>
                </section>

                <section class="admin-stats-grid">
                    <div class="admin-stat-card"><h3><?php echo $stats['events']; ?></h3><p>Events</p></div>
                    <div class="admin-stat-card"><h3><?php echo $stats['camps']; ?></h3><p>Camps</p></div>
                    <div class="admin-stat-card"><h3><?php echo $stats['gallery']; ?></h3><p>Gallery Items</p></div>
                    <div class="admin-stat-card"><h3><?php echo $stats['members']; ?></h3><p>Members</p></div>
                    <div class="admin-stat-card"><h3><?php echo $stats['messages']; ?></h3><p>Messages</p></div>
                    <div class="admin-stat-card"><h3><?php echo $stats['registrations']; ?></h3><p>Registrations</p></div>
                </section>

                <section class="admin-action-grid">
                    <?php foreach ($sections as $key => $section): ?>
                        <a class="admin-action-card" href="manage-content.php?section=<?php echo app_h($key); ?>">
                            <h3><?php echo app_h($section['label']); ?></h3>
                            <p>Add, update, reorder, or remove <?php echo app_h(strtolower($section['label'])); ?> entries.</p>
                        </a>
                    <?php endforeach; ?>
                    <a class="admin-action-card" href="messages.php">
                        <h3>Contact Messages</h3>
                        <p>Review public inquiries and keep conversations moving.</p>
                    </a>
                    <a class="admin-action-card" href="registrations.php">
                        <h3>Registration Requests</h3>
                        <p>Process new membership submissions quickly and professionally.</p>
                    </a>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
