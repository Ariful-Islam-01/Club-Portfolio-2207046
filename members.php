<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Load the member roster and prepare the complete directory view. ---
$pdo = app_pdo();
$members = $pdo ? app_fetch_records('members', 'sort_order ASC, id DESC') : [
    [
        'name' => 'A. R. M. Ariful Islam',
        'role' => 'Group Scout Leader',
        'badge' => 'Senior Scout',
        'bio' => 'Experienced leader with 10+ years in scouting movement',
        'image_path' => 'Profile Pic.jpg',
    ],
    [
        'name' => 'Sk. Nazmus Salehin Nirob',
        'role' => 'Assistant Scout Leader',
        'badge' => 'Senior Scout',
        'bio' => 'Specializes in community service and youth development',
        'image_path' => 'Profile Pic.jpg',
    ],
    [
        'name' => 'Priom Sarkar',
        'role' => 'Training Coordinator',
        'badge' => 'Scout',
        'bio' => 'Passionate about skill development and outdoor activities',
        'image_path' => 'Profile Pic.jpg',
    ],
    [
        'name' => 'Md. Toufiq Hasan',
        'role' => 'Events Manager',
        'badge' => 'Scout',
        'bio' => 'Organizes engaging events and community outreach programs',
        'image_path' => 'https://via.placeholder.com/200x200?text=Scout+Member',
    ],
    [
        'name' => 'Shahariar Abdullah Toufiq',
        'role' => 'Outdoor Activities Lead',
        'badge' => 'Scout',
        'bio' => 'Expert in camping, trekking, and wilderness survival',
        'image_path' => 'https://via.placeholder.com/200x200?text=Scout+Member',
    ],
    [
        'name' => 'Ariful Islam Sheikh',
        'role' => 'Social Media & Communications',
        'badge' => 'Scout',
        'bio' => 'Manages digital presence and member engagement',
        'image_path' => 'https://via.placeholder.com/200x200?text=Scout+Member',
    ],
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="All executive members of the KUET Rover Scout Group.">
    <title>All Members | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- --- Shared navigation for the full member listing page. --- -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="Bangladesh_Scouts_Logo.png" alt="Bangladesh Scouts logo" class="logo-image">
                <div class="logo-text-group">
                    <a href="index.php#home" class="logo-text">KUET ROVER SCOUT GROUP</a>
                    <span class="logo-subtitle">Est. 2010</span>
                </div>
            </div>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="index.php#home" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="index.php#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="events.php" class="nav-link">Events</a></li>
                <li class="nav-item"><a href="camps.php" class="nav-link">Camps</a></li>
                <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="index.php#members" class="nav-link">Members</a></li>
                <li class="nav-item"><a href="index.php#contact" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="admin/login.php" class="nav-link nav-link-admin">Admin</a></li>
            </ul>
            <button class="hamburger" id="hamburger" type="button" aria-label="Toggle navigation menu" aria-controls="navMenu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <main class="registration-section">
        <div class="container">
            <section class="registration-card">
                <div class="registration-card-header">
                    <h1>All Members</h1>
                    <p>Meet the full team of leaders, coordinators, and volunteers behind KUET Rover Scout Group.</p>
                </div>
                <div class="registration-form">
                    <div class="members-grid">
                        <?php foreach ($members as $member): ?>
                            <div class="member-card">
                                <div class="member-image" style="background-image: url('<?php echo app_h((string) ($member['image_path'] ?? 'Profile Pic.jpg')); ?>');"></div>
                                <div class="member-info">
                                    <h4><?php echo app_h((string) ($member['name'] ?? '')); ?></h4>
                                    <p class="member-role"><?php echo app_h((string) ($member['role'] ?? '')); ?></p>
                                    <p class="member-badge">🎖️ <?php echo app_h((string) ($member['badge'] ?? '')); ?></p>
                                    <p class="member-bio"><?php echo app_h((string) ($member['bio'] ?? '')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5>KUET Rover Scout Group</h5>
                    <p>Building leaders, serving communities, and inspiring youth through the values of Scouting.</p>
                </div>
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="https://scouts.gov.bd/">Bangladesh Scouts</a></li>
                        <li><a href="https://www.scout.org/">World Scouting</a></li>
                        <li><a href="https://www.kuet.ac.bd/">KUET</a></li>
                        <li><a href="https://service.scouts.gov.bd/">Online Scout Database</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="https://scouts.gov.bd/">Scout Handbook</a></li>
                        <li><a href="https://scouts.gov.bd/">Community Guidelines</a></li>
                        <li><a href="index.php#contact">Privacy Policy</a></li>
                        <li><a href="index.php#contact">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Connect</h5>
                    <ul>
                        <li><a href="mailto:rover@club.kuet.ac.bd">Email Us</a></li>
                        <li><a href="index.php#contact">Contact Us</a></li>
                        <li><a href="reg.php">Join Us</a></li>
                        <li><a href="index.php#contact">Volunteer</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 KUET Rover Scout Group. All rights reserved.</p>
                <p>Inspired by the legacy of Robert Baden-Powell | "Be Prepared" | Developed by 2207046</p>
            </div>
        </div>
    </footer>

    <script src="script.js" defer></script>
</body>
</html>
