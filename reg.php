<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// --- Read any flash message for the registration flow before rendering the page. ---
$flash = app_flash_get();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register to join KUET Rover Scout Group and become part of a service-driven scouting community.">
    <meta name="theme-color" content="#2d5016">
    <title>Join KUET Rover Scout Group</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="registration-page">
    <!-- --- Shared navigation for the registration page. --- -->
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
                <li class="nav-item"><a href="admin/login.php" class="nav-link nav-link-admin">Admin Login</a></li>
            </ul>
            <button class="hamburger" id="hamburger" type="button" aria-label="Toggle navigation menu" aria-controls="navMenu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <main>
        <section class="registration-hero">
            <div class="container">
                <div>
                    <span class="registration-kicker">Membership Registration</span>
                    <h1>Join KUET Rover Scout Group</h1>
                    <p>Become part of a campus community built on leadership, service, outdoor adventure, and personal growth. Fill out the form below to start your journey with the Rover Scouts.</p>
                    <ul class="registration-highlights">
                        <li>Open to KUET students who want to learn, lead, and serve.</li>
                        <li>Participate in camps, training sessions, and community service programs.</li>
                        <li>Grow your confidence, teamwork, and scouting skills with experienced mentors.</li>
                    </ul>
                </div>

                <aside class="registration-summary">
                    <h2>What to prepare</h2>
                    <p>Please keep the following details ready before applying.</p>
                    <div class="summary-pill">Student ID Card</div>
                    <div class="summary-pill">Contact Number</div>
                    <div class="summary-pill">Email Address</div>
                    <div class="summary-pill">Previous Scouting Details</div>
                </aside>
            </div>
        </section>

        <section class="registration-section">
            <div class="container">
                <?php if ($flash !== null): ?>
                    <p class="form-status <?php echo app_h($flash['type']); ?>"><?php echo app_h($flash['message']); ?></p>
                <?php endif; ?>

                <div class="registration-card">
                    <div class="registration-card-header">
                        <h2>Registration Form</h2>
                        <p>All fields marked required must be completed before submitting your application.</p>
                    </div>

                    <form class="registration-form" action="registration_submit.php" method="post">
                        <div class="registration-grid">
                            <div class="form-group"><label for="fullName">Full Name *</label><input type="text" id="fullName" name="fullName" placeholder="Your full name" required></div>
                            <div class="form-group"><label for="studentId">Student ID *</label><input type="text" id="studentId" name="studentId" placeholder="KUET student roll no" required></div>
                            <div class="form-group"><label for="department">Department *</label><input type="text" id="department" name="department" placeholder="Your department e.g. CSE, EEE, CE" required></div>
                            <div class="form-group"><label for="semester">Current Semester *</label><select id="semester" name="semester" required><option value="">Select semester</option><option value="1">1st Year 1st Semester</option><option value="2">1st Year 2nd Semester</option><option value="3">2nd Year 1st Semester</option><option value="4">2nd Year 2nd Semester</option><option value="5">3rd Year 1st Semester</option><option value="6">3rd Year 2nd Semester</option><option value="7">4th Year 1st Semester</option><option value="8">4th Year 2nd Semester</option></select></div>
                            <div class="form-group"><label for="email">Email Address *</label><input type="email" id="email" name="email" placeholder="your@email.com" required></div>
                            <div class="form-group"><label for="phone">Phone Number *</label><input type="tel" id="phone" name="phone" placeholder="+880 1XXXXXXXXX" required></div>
                            <div class="form-group"><label for="dob">Date of Birth *</label><input type="date" id="dob" name="dob" required></div>
                            <div class="form-group"><label for="bloodGroup">Blood Group *</label><select id="bloodGroup" name="bloodGroup" required><option value="">Select blood group</option><option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option><option value="AB+">AB+</option><option value="AB-">AB-</option><option value="O+">O+</option><option value="O-">O-</option></select></div>
                            <div class="form-group"><label for="scoutingExperience">Previous Scouting Experience *</label><select id="scoutingExperience" name="scoutingExperience" required><option value="">Select an option</option><option value="none">No experience</option><option value="school">As a Cub Scout</option><option value="college">As a Scout</option><option value="rover">Already a Rover Scout</option></select></div>
                            <div class="form-group"><label for="address">Present Address *</label><input type="text" id="address" name="address" placeholder="Your current residence address" required></div>
                            <div class="form-group full-width"><label for="skills">Skills or Interests</label><textarea id="skills" name="skills" rows="3" placeholder="Outdoor activities, volunteering, leadership, first aid, etc."></textarea></div>
                            <div class="form-group full-width"><label for="motivation">Why do you want to join? *</label><textarea id="motivation" name="motivation" rows="4" placeholder="Tell us what inspires you to join KUET Rover Scout Group." required></textarea></div>
                            <div class="form-group full-width form-checkbox"><input type="checkbox" id="consent" name="consent" required><label for="consent">I confirm that the information provided above is accurate and I agree to follow the values and discipline of KUET Rover Scout Group.</label></div>
                        </div>

                        <p class="registration-note">After submission, the scout group leadership will review your application and contact you if any additional information is needed.</p>

                        <div class="registration-actions">
                            <button type="submit" class="btn btn-primary">Submit Application</button>
                            <a href="index.php" class="btn btn-secondary">Return to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
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
