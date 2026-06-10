<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

// The admin entry point should always send logged-in users to the dashboard.
if (app_current_admin() !== null) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
