<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Load every gallery item so the full gallery page can display them. ---
$pdo = app_pdo();
$galleryItems = $pdo ? app_fetch_records('gallery', 'sort_order ASC, id DESC') : [
    ['title' => 'Campfire Celebration', 'category' => 'camps', 'caption' => 'Summer Camp 2023', 'image_path' => 'Camp 2.jpg'],
    ['title' => 'Leadership Training', 'category' => 'training', 'caption' => 'April 2024', 'image_path' => 'G2.jpg'],
    ['title' => 'Community Outreach', 'category' => 'service', 'caption' => 'March 2024', 'image_path' => 'G3.jpg'],
    ['title' => 'Team Building', 'category' => 'events', 'caption' => 'February 2024', 'image_path' => 'Event demo image.png'],
    ['title' => 'Hiking Adventure', 'category' => 'camps', 'caption' => 'Summer Camp 2023', 'image_path' => 'Camp demo image.png'],
    ['title' => 'Environmental Drive', 'category' => 'service', 'caption' => 'January 2024', 'image_path' => 'Event demo image.png'],
    ['title' => 'Scout Group', 'category' => 'events', 'caption' => 'Annual Meet 2024', 'image_path' => 'G4.jpg'],
    ['title' => 'First Aid Workshop', 'category' => 'training', 'caption' => 'March 2024', 'image_path' => 'G5.jpg'],
    ['title' => 'Bonfire Fellowship', 'category' => 'camps', 'caption' => 'December 2023', 'image_path' => 'G6.jpg'],
    ['title' => 'Test', 'category' => 'events', 'caption' => 'Community Activity', 'image_path' => 'Event 2.jpg'],
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="All gallery photos from KUET Rover Scout Group.">
    <title>Gallery | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- --- Shared navigation for the full gallery experience. --- -->
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
                <li class="nav-item"><a href="index.php#gallery" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="index.php#members" class="nav-link">Members</a></li>
                <li class="nav-item"><a href="index.php#contact" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="admin/login.php" class="nav-link nav-link-admin">Admin</a></li>
            </ul>
            <button class="hamburger" id="hamburger" type="button" aria-label="Toggle navigation menu" aria-controls="navMenu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main class="registration-section">
        <div class="container">
            <section class="registration-card">
                <div class="registration-card-header">
                    <h1>Gallery</h1>
                    <p>Browse the complete collection of photos from events, camps, training sessions, and community service activities.</p>
                </div>
                <div class="gallery-grid">
                    <?php foreach ($galleryItems as $item): ?>
                        <div class="gallery-item" data-category="<?php echo app_h((string) $item['category']); ?>" role="button" tabindex="0" aria-label="Open gallery image: <?php echo app_h((string) $item['title']); ?>">
                            <div class="gallery-image" style="background-image: url('<?php echo app_h((string) $item['image_path']); ?>');"></div>
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <h4><?php echo app_h((string) $item['title']); ?></h4>
                                    <p><?php echo app_h((string) $item['caption']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section"><h5>KUET Rover Scout Group</h5><p>Building leaders, serving communities, and inspiring youth through the values of Scouting.</p></div>
                <div class="footer-section"><h5>Quick Links</h5><ul><li><a href="https://scouts.gov.bd/">Bangladesh Scouts</a></li><li><a href="https://www.scout.org/">World Scouting</a></li><li><a href="https://www.kuet.ac.bd/">KUET</a></li><li><a href="https://service.scouts.gov.bd/">Online Scout Database</a></li></ul></div>
                <div class="footer-section"><h5>Resources</h5><ul><li><a href="https://scouts.gov.bd/">Scout Handbook</a></li><li><a href="https://scouts.gov.bd/">Community Guidelines</a></li><li><a href="index.php#contact">Privacy Policy</a></li><li><a href="index.php#contact">Terms of Service</a></li></ul></div>
                <div class="footer-section"><h5>Connect</h5><ul><li><a href="mailto:rover@club.kuet.ac.bd">Email Us</a></li><li><a href="index.php#contact">Contact Us</a></li><li><a href="reg.php">Join Us</a></li><li><a href="index.php#contact">Volunteer</a></li></ul></div>
            </div>
            <div class="footer-bottom"><p>&copy; 2026 KUET Rover Scout Group. All rights reserved.</p><p>Inspired by the legacy of Robert Baden-Powell | "Be Prepared" | Developed by 2207046</p></div>
        </div>
    </footer>

    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
        <div class="gallery-lightbox-backdrop" data-lightbox-close></div>
        <div class="lightbox-dialog" role="dialog" aria-modal="true" aria-labelledby="lightboxTitle" aria-describedby="lightboxMeta">
            <button type="button" class="lightbox-close" aria-label="Close gallery preview" data-lightbox-close>×</button>
            <div class="lightbox-image" id="lightboxImage"></div>
            <div class="lightbox-caption">
                <p class="lightbox-meta" id="lightboxMeta"></p>
                <h3 id="lightboxTitle"></h3>
            </div>
        </div>
    </div>

    <script src="script.js" defer></script>
</body>
</html>
