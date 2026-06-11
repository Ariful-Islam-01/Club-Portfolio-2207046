<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Load the event records and format them for the public list page. ---
$pdo = app_pdo();
$events = $pdo ? app_fetch_records('events', 'event_date DESC, id DESC') : [
    [
        'title' => 'Leadership Training Workshop',
        'event_date' => '2024-03-15',
        'location' => 'KUET Campus',
        'event_time' => '10:00 AM - 3:00 PM',
        'description' => 'Intensive workshop on leadership skills, team management, and decision-making for aspiring leaders.',
        'image_path' => 'Event 1.jpg',
    ],
    [
        'title' => 'Community Cleanup Drive',
        'event_date' => '2024-03-22',
        'location' => 'Khulna City',
        'event_time' => '8:00 AM - 12:00 PM',
        'description' => 'Join us in serving the community through environmental cleanliness and social awareness programs.',
        'image_path' => 'Event 2.jpg',
    ],
    [
        'title' => 'Skill Development Seminar',
        'event_date' => '2024-04-05',
        'location' => 'KUET Campus',
        'event_time' => '2:00 PM - 5:00 PM',
        'description' => 'Learn essential survival skills, first aid, and outdoor techniques with expert instructors.',
        'image_path' => 'Event demo image.png',
    ],
    [
        'title' => 'Scout Leadership Camp',
        'event_date' => '2024-05-18',
        'location' => 'Rangamati',
        'event_time' => '6:00 AM - 6:00 PM',
        'description' => 'A full-day leadership training and outdoor challenge experience for active scouts.',
        'image_path' => 'Camp 2.jpg',
    ],
];

function site_date(string $value): string
{
    try {
        return (new DateTimeImmutable($value))->format('F j, Y');
    } catch (Throwable $exception) {
        return $value;
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="All events hosted by the KUET Rover Scout Group.">
    <title>All Events | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- --- Shared navigation for the full event listing page. --- -->
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
                <li class="nav-item"><a href="index.php#events" class="nav-link">Events</a></li>
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
                    <h1>All Events</h1>
                    <p>Explore the complete list of activities, workshops, and camps organized by KUET Rover Scout Group.</p>
                </div>
                <div class="registration-form">
                    <div class="events-list-grid">
                        <?php foreach ($events as $event): ?>
                            <article class="event-card event-card-list">
                                <div class="event-image" style="background-image: url('<?php echo app_h((string) $event['image_path']); ?>');"></div>
                                <div class="event-content">
                                    <span class="event-date"><?php echo app_h(site_date((string) $event['event_date'])); ?></span>
                                    <h3><?php echo app_h((string) $event['title']); ?></h3>
                                    <p><?php echo app_h((string) $event['description']); ?></p>
                                    <div class="event-meta">
                                        <span class="event-location">📍 <?php echo app_h((string) $event['location']); ?></span>
                                        <span class="event-time">⏰ <?php echo app_h((string) $event['event_time']); ?></span>
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
                        <li><a href="mailto:rover@club.kuet.ac.bd?subject=Mailing%20List">Join Mailing List</a></li>
                        <li><a href="index.php#contact">Volunteer</a></li>
                        <li><a href="mailto:rover@club.kuet.ac.bd?subject=Donation%20Inquiry">Donate</a></li>
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
