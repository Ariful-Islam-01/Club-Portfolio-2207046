<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

// Logging out clears both the session and the remember-me cookie.
app_logout_admin();
app_flash_set('success', 'You have been logged out successfully.');
header('Location: login.php');
exit;
