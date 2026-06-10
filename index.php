<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Load the shared site content and prepare the homepage data. ---
$pdo = app_pdo();
$flash = app_flash_get();
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
];
$camps = $pdo ? app_fetch_records('camps', 'id DESC') : [
    [
        'title' => 'Summer Adventure Camp',
        'season' => 'Summer',
        'description' => '7 days of outdoor adventure with camping, hiking, rock climbing, and wilderness survival skills.',
        'image_path' => 'Camp 1.jpg',
    ],
    [
        'title' => 'Winter Leadership Camp',
        'season' => 'Winter',
        'description' => '5 days focused on leadership development, crisis management, and personal growth experiences.',
        'image_path' => 'Camp 2.jpg',
    ],
    [
        'title' => 'Spring Community Service Camp',
        'season' => 'Spring',
        'description' => '4 days of meaningful service projects, environmental awareness, and sustainable development.',
        'image_path' => 'Camp 3.jpg',
    ],
];
$galleryItems = $pdo ? app_fetch_records('gallery', 'sort_order ASC, id DESC') : [
    [
        'title' => 'Campfire Celebration',
        'category' => 'camps',
        'caption' => 'Summer Camp 2023',
        'image_path' => 'Camp 2.jpg',
    ],
    [
        'title' => 'Leadership Training',
        'category' => 'training',
        'caption' => 'April 2024',
        'image_path' => 'G2.jpg',
    ],
    [
        'title' => 'Community Outreach',
        'category' => 'service',
        'caption' => 'March 2024',
        'image_path' => 'G3.jpg',
    ],
    [
        'title' => 'Team Building',
        'category' => 'events',
        'caption' => 'February 2024',
        'image_path' => 'Event demo image.png',
    ],
];
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
];
$eventCards = array_slice($events, 0, 3);
$campCards = array_slice($camps, 0, 3);
$memberCards = array_slice($members, 0, 6);

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
    <meta name="description" content="KUET Rover Scout Group - Khulna University of Engineering and Technology Scout Group. Inspiring youth through scouting values and community service.">
    <meta name="theme-color" content="#2d5016">
    <title>KUET Rover Scout Group - Be Prepared</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- --- Shared public navigation and branding for the home page. --- -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="Bangladesh_Scouts_Logo.png" alt="Bangladesh Scouts logo" class="logo-image">
                <div class="logo-text-group">
                    <a href="#home" class="logo-text">KUET ROVER SCOUT GROUP</a>
                    <span class="logo-subtitle">Est. 2010</span>
                </div>
            </div>
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item"><a href="#home" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
                <li class="nav-item"><a href="#events" class="nav-link">Events</a></li>
                <li class="nav-item"><a href="#camps" class="nav-link">Camps</a></li>
                <li class="nav-item"><a href="#gallery" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="#members" class="nav-link">Members</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="admin/login.php" class="nav-link nav-link-admin">Admin</a></li>
            </ul>
            <button class="hamburger" id="hamburger" type="button" aria-label="Toggle navigation menu" aria-controls="navMenu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- --- Hero section with the club introduction and main calls to action. --- -->
    <section class="hero" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Khulna University of Engineering and Technology</h1>
            <p class="hero-subtitle">Rover Scout Group</p>
            <p class="hero-tagline">"Be Prepared" - Building Leaders, Serving Communities</p>
            <div class="hero-buttons">
                <a href="#about" class="btn btn-primary">Learn More</a>
                <a href="reg.php" class="btn btn-primary">Join Us</a>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <span class="scroll-text">Scroll to Explore</span>
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- --- About section describing the club mission, history, and values. --- -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-header">
                <h2>About Us</h2>
                <p class="section-subtitle">Inspiring Tomorrow's Leaders</p>
            </div>
            <div class="about-intro">
                <p class="about-text">The Khulna University of Engineering and Technology Rover Scout Group is part of the international Scouting movement, dedicated to developing character, leadership, and citizenship among university students. Founded on the principles established by Baden-Powell, we combine discipline, adventure, and service to create meaningful experiences for our members.</p>
                <p class="about-quote"><em>"The most worth-while thing is to try to put happiness into the lives of others." — Robert Baden-Powell</em></p>
            </div>

            <!-- Timeline Section -->
            <div class="timeline">
                <h3 class="timeline-title">Our Journey</h3>
                <div class="timeline-container">
                    <div class="timeline-item"><div class="timeline-date">2010</div><div class="timeline-content"><h4>Founded</h4><p>KUET Rover Scout Group established with 22 founding members</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2011</div><div class="timeline-content"><h4>Recognition</h4><p>Officially recognized by Bangladesh Scouts</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2014</div><div class="timeline-content"><h4>Expansion</h4><p>Established Rover Scout Den and expanded programs and community outreach initiatives</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2015</div><div class="timeline-content"><h4>Innovation</h4><p>Launched new skill development and mentorship programs</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2016</div><div class="timeline-content"><h4>Participation</h4><p>Participated in national and international scouting events and jamborees</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2018</div><div class="timeline-content"><h4>Achievement</h4><p>Three Rover Scouts received the President's Rover Scout Award</p></div></div>
                    <div class="timeline-item"><div class="timeline-date">2022</div><div class="timeline-content"><h4>Programs</h4><p>Arranged Rover Scout Group Camp and Top Achiever Gathering</p></div></div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="values-grid">
                <div class="value-card"><div class="value-icon"></div><h4>Leadership</h4><p>Developing confident leaders who inspire others and make a positive impact</p></div>
                <div class="value-card"><div class="value-icon"></div><h4>Teamwork</h4><p>Building strong bonds through collaborative activities and mutual support</p></div>
                <div class="value-card"><div class="value-icon"></div><h4>Service</h4><p>Contributing to society through community projects and volunteer work</p></div>
                <div class="value-card"><div class="value-icon"></div><h4>Adventure</h4><p>Pursuing thrilling experiences and pushing personal boundaries</p></div>
            </div>
        </div>
    </section>

    <!-- ===== EVENTS SECTION ===== -->
    <section class="events" id="events">
        <div class="container">
            <div class="section-header">
                <h2>Upcoming Events</h2>
                <p class="section-subtitle">Join Us for Amazing Experiences</p>
            </div>
            <div class="carousel-container">
                <button class="carousel-btn carousel-prev" id="eventPrev" type="button" aria-label="Previous event">❮</button>
                <div class="events-grid" id="eventsCarousel">
                    <?php foreach ($eventCards as $event): ?>
                        <article class="event-card">
                            <div class="event-image" style="background-image: url('<?php echo app_h((string) $event['image_path']); ?>');"></div>
                            <div class="event-content">
                                <span class="event-date"><?php echo app_h(site_date((string) $event['event_date'])); ?></span>
                                <h3><?php echo app_h((string) $event['title']); ?></h3>
                                <p><?php echo app_h((string) $event['description']); ?></p>
                                <div class="event-meta">
                                    <span class="event-location">📍 <?php echo app_h((string) $event['location']); ?></span>
                                    <span class="event-time">⏰ <?php echo app_h((string) $event['event_time']); ?></span>
                                </div>
                                <a href="#contact" class="event-link" data-event-title="<?php echo app_h((string) $event['title']); ?>" data-event-date="<?php echo app_h(site_date((string) $event['event_date'])); ?>">Learn More →</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-btn carousel-next" id="eventNext" type="button" aria-label="Next event">❯</button>
            </div>

            <!-- Carousel Indicators -->
            <div class="carousel-indicators" id="eventIndicators">
                <?php for ($index = 0; $index < count($eventCards); $index++): ?>
                    <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
                <?php endfor; ?>
            </div>
            <div class="section-cta"><a href="events.php" class="btn btn-primary events-cta">View All Events</a></div>
        </div>
    </section>

    <!-- ===== CAMPS SECTION ===== -->
    <section class="camps" id="camps">
        <div class="container">
            <div class="section-header">
                <h2>Scout Camps</h2>
                <p class="section-subtitle">Build Skills, Create Memories, Embrace Adventure</p>
            </div>
            <div class="camps-grid">
                <?php foreach ($campCards as $camp): ?>
                    <div class="camp-card">
                        <div class="camp-image" style="background-image: url('<?php echo app_h((string) $camp['image_path']); ?>');"></div>
                        <div class="camp-badge"><?php echo app_h((string) $camp['season']); ?></div>
                        <div class="camp-content">
                            <h3><?php echo app_h((string) $camp['title']); ?></h3>
                            <p><?php echo app_h((string) $camp['description']); ?></p>
                            <a href="camps.php" class="btn btn-small">Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="section-cta"><a href="camps.php" class="btn btn-primary">View All Camps</a></div>
        </div>
    </section>

    <!-- ===== GALLERY SECTION ===== -->
    <section class="gallery" id="gallery">
        <div class="container">
            <div class="section-header">
                <h2>Gallery</h2>
                <p class="section-subtitle">Capturing Moments of Adventure and Service</p>
            </div>
            <div class="gallery-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="events">Events</button>
                <button class="filter-btn" data-filter="camps">Camps</button>
                <button class="filter-btn" data-filter="service">Service</button>
                <button class="filter-btn" data-filter="training">Training</button>
            </div>
            <div class="gallery-grid" id="galleryGrid">
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
            <div class="section-cta">
                <a href="gallery.php" id="galleryMoreButton" class="btn btn-primary">View More Photos</a>
            </div>
        </div>
    </section>

    <!-- ===== MEMBERS SECTION ===== -->
    <section class="members" id="members">
        <div class="container">
            <div class="section-header">
                <h2>Our Executive Members</h2>
                <p class="section-subtitle">Meet the Leaders and Scouts</p>
            </div>
            <div class="members-grid">
                <?php foreach ($memberCards as $member): ?>
                    <div class="member-card">
                        <div class="member-image" style="background-image: url('<?php echo app_h((string) $member['image_path']); ?>');"></div>
                        <div class="member-info">
                            <h4><?php echo app_h((string) $member['name']); ?></h4>
                            <p class="member-role"><?php echo app_h((string) $member['role']); ?></p>
                            <p class="member-badge">🎖️ <?php echo app_h((string) $member['badge']); ?></p>
                            <p class="member-bio"><?php echo app_h((string) $member['bio']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($members) > 0): ?>
                <div class="section-cta">
                    <a href="members.php" class="btn btn-primary">View All Members</a>
                </div>
            <?php endif; ?>
            <div class="members-stats">
                <div class="stat-box"><h3><?php echo number_format((int) ($pdo ? app_table_count('members') : count($members))); ?></h3><p>Active Members</p></div>
                <div class="stat-box"><h3><?php echo number_format((int) ($pdo ? app_table_count('events') : count($events))); ?></h3><p>Events Listed</p></div>
                <div class="stat-box"><h3><?php echo number_format((int) ($pdo ? app_table_count('contact_messages') : 0)); ?></h3><p>Messages Logged</p></div>
                <div class="stat-box"><h3>16</h3><p>Years Strong</p></div>
            </div>
        </div>
    </section>

    <!-- ===== CONTACT SECTION ===== -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p class="section-subtitle">Have Questions? We'd Love to Hear from You</p>
            </div>

            <?php if ($flash !== null): ?>
                <p class="form-status <?php echo app_h($flash['type']); ?>"><?php echo app_h($flash['message']); ?></p>
            <?php endif; ?>

            <div class="contact-wrapper">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <h3>Send us a Message</h3>
                    <form class="contact-form" id="contactForm" method="POST" action="contact_submit.php">
                        <div class="form-group"><label for="fullName">Full Name</label><input type="text" id="fullName" name="fullName" required placeholder="Your Name"></div>
                        <div class="form-group"><label for="email">Email Address</label><input type="email" id="email" name="email" required placeholder="your@email.com"></div>
                        <div class="form-group"><label for="phone">Phone Number</label><input type="tel" id="phone" name="phone" placeholder="+880 1XXX XXXXXX"></div>
                        <div class="form-group"><label for="subject">Subject</label><select id="subject" name="subject" required><option value="">-- Select a Subject --</option><option value="membership">Membership Inquiry</option><option value="event">Event Information</option><option value="camp">Camp Registration</option><option value="volunteer">Volunteer Opportunity</option><option value="other">Other</option></select></div>
                        <div class="form-group"><label for="message">Message</label><textarea id="message" name="message" required placeholder="Your message here..." rows="6"></textarea></div>
                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="contact-info-container">
                    <h3>Contact Information</h3>
                    <div class="contact-info-box"><div class="contact-icon">📍</div><div class="contact-details"><h4>Address</h4><p>SWC Room - 211</p><p>Khulna University of Engineering and Technology</p><p>Khulna-9203, Bangladesh</p></div></div>
                    <div class="contact-info-box"><div class="contact-icon">📞</div><div class="contact-details"><h4>Phone</h4><p><a href="tel:+8801631687525">+88 01631 687525</a></p><p><a href="tel:+8801800000000">+88 01800 000000</a></p></div></div>
                    <div class="contact-info-box"><div class="contact-icon">✉️</div><div class="contact-details"><h4>Email</h4><p><a href="mailto:rover@club.kuet.ac.bd">rover@club.kuet.ac.bd</a></p><p><a href="mailto:kuetroverscoutgroup@gmail.com">kuetroverscoutgroup@gmail.com</a></p></div></div>
                    <!-- Social Media Links -->
                    <div class="social-links"><h4>Follow Us</h4><div class="social-icons"><a href="https://www.facebook.com/" class="social-icon" title="Facebook" aria-label="Facebook">f</a><a href="https://www.instagram.com/" class="social-icon" title="Instagram" aria-label="Instagram">📷</a><a href="https://x.com/" class="social-icon" title="Twitter / X" aria-label="Twitter">𝕏</a><a href="https://www.youtube.com/" class="social-icon" title="YouTube" aria-label="YouTube">▶</a></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section"><h5>KUET Rover Scout Group</h5><p>Building leaders, serving communities, and inspiring youth through the values of Scouting.</p></div>
                <div class="footer-section"><h5>Quick Links</h5><ul><li><a href="https://scouts.gov.bd/">Bangladesh Scouts</a></li><li><a href="https://www.scout.org/">World Scouting</a></li><li><a href="https://www.kuet.ac.bd/">KUET</a></li><li><a href="https://service.scouts.gov.bd/">Online Scout Database</a></li></ul></div>
                <div class="footer-section"><h5>Resources</h5><ul><li><a href="https://scouts.gov.bd/">Scout Handbook</a></li><li><a href="https://scouts.gov.bd/">Community Guidelines</a></li><li><a href="index.php#contact">Privacy Policy</a></li><li><a href="index.php#contact">Terms of Service</a></li></ul></div>
                <div class="footer-section"><h5>Connect</h5><ul><li><a href="mailto:rover@club.kuet.ac.bd">Email Us</a></li><li><a href="mailto:rover@club.kuet.ac.bd?subject=Mailing%20List">Join Mailing List</a></li><li><a href="index.php#contact">Volunteer</a></li><li><a href="mailto:rover@club.kuet.ac.bd?subject=Donation%20Inquiry">Donate</a></li></ul></div>
            </div>
            <div class="footer-bottom"><p>&copy; 2026 KUET Rover Scout Group. All rights reserved.</p><p>Inspired by the legacy of Robert Baden-Powell | "Be Prepared" | Developed by 2207046</p></div>
        </div>
    </footer>

    <!-- ===== GALLERY LIGHTBOX ===== -->
    <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
        <div class="gallery-lightbox-backdrop" data-lightbox-close></div>
        <div class="lightbox-dialog" role="dialog" aria-modal="true" aria-labelledby="lightboxTitle" aria-describedby="lightboxMeta">
            <button type="button" class="lightbox-close" aria-label="Close gallery preview" data-lightbox-close>×</button>
            <div class="lightbox-image" id="lightboxImage"></div>
            <div class="lightbox-caption"><p class="lightbox-meta" id="lightboxMeta"></p><h3 id="lightboxTitle"></h3></div>
        </div>
    </div>

    <!-- ===== SCROLL TO TOP BUTTON ===== -->
    <button type="button" class="scroll-to-top" id="scrollToTop" aria-label="Scroll to top"><span>↑</span></button>
    <script src="script.js" defer></script>
</body>
</html>
