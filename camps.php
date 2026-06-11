<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Load all camp records and prepare them for the public listing view. ---
$pdo = app_pdo();
$camps = $pdo ? app_fetch_records('camps', 'id DESC') : [
    [
        'title' => 'Summer Adventure Camp',
        'season' => 'Summer',
        'duration' => '7 days',
        'description' => '7 days of outdoor adventure with camping, hiking, rock climbing, and wilderness survival skills.',
        'image_path' => 'Camp 1.jpg',
    ],
    [
        'title' => 'Winter Leadership Camp',
        'season' => 'Winter',
        'duration' => '5 days',
        'description' => '5 days focused on leadership development, crisis management, and personal growth experiences.',
        'image_path' => 'Camp 2.jpg',
    ],
    [
        'title' => 'Spring Community Service Camp',
        'season' => 'Spring',
        'duration' => '4 days',
        'description' => '4 days of meaningful service projects, environmental awareness, and sustainable development.',
        'image_path' => 'Camp 3.jpg',
    ],
    [
        'title' => 'Rover Skill Development Camp',
        'season' => 'Autumn',
        'duration' => '6 days',
        'description' => 'A transformative camp focused on teamwork, survival techniques, and scouting discipline.',
        'image_path' => 'Camp demo image.png',
    ],
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="All camps hosted by the KUET Rover Scout Group.">
    <title>All Camps | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- --- Shared navigation for the full camp listing page. --- -->
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
                <li class="nav-item"><a href="index.php#camps" class="nav-link">Camps</a></li>
                <li class="nav-item"><a href="index.php#gallery" class="nav-link">Gallery</a></li>
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
                    <h1>All Camps</h1>
                    <p>Browse the complete list of camps, training adventures, and outdoor learning experiences organized by KUET Rover Scout Group.</p>
                </div>
                <div class="registration-form">
                    <div class="events-list-grid">
                        <?php foreach ($camps as $camp): ?>
                            <article class="event-card event-card-list">
                                <div class="event-image" style="background-image: url('<?php echo app_h((string) $camp['image_path']); ?>');"></div>
                                <div class="event-content">
                                    <span class="event-date"><?php echo app_h((string) $camp['season']); ?></span>
                                    <h3><?php echo app_h((string) $camp['title']); ?></h3>
                                    <p><?php echo app_h((string) $camp['description']); ?></p>
                                    <div class="event-meta">
                                        <span class="event-location">⛺ <?php echo app_h((string) $camp['duration']); ?></span>
                                    </div>
                                    <a href="index.php#contact" class="event-link">Learn More →</a>
                                </div>
                            </article>
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
