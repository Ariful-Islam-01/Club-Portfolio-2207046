<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

// If the admin is already authenticated, skip the login form.
if (app_current_admin() !== null) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

if (app_is_post()) {
    $username = app_input('username');
    $password = app_input('password');
    $remember = isset($_POST['remember']);

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        app_login_admin($remember);
        app_flash_set('success', 'Admin login successful.');
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KUET Rover Scout Group admin login page.">
    <meta name="theme-color" content="#2d5016">
    <title>Admin Login | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page admin-page">
    <nav class="navbar admin-navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="../Bangladesh_Scouts_Logo.png" alt="Bangladesh Scouts logo" class="logo-image">
                <div class="logo-text-group">
                    <a href="../index.php" class="logo-text">KUET ROVER SCOUT GROUP</a>
                    <span class="logo-subtitle">Admin Access</span>
                </div>
            </div>
            <a href="../index.php" class="back-link">Back to Home</a>
            <a href="../index.php" class="btn btn-secondary btn-small"">Go to Home</a>
        </div>
    </nav>

    <main class="registration-section admin-login-section">
        <div class="container">
            <div class="registration-card admin-auth-card">
                <div class="registration-card-header">
                    <h1>Admin Login</h1>
                    <p>Use the protected credentials to open the dashboard and manage the website content.</p>
                </div>

                <div class="registration-form admin-auth-layout">
                    <section class="admin-auth-panel">
                        <?php if ($error !== null): ?>
                            <p class="form-status error"><?php echo app_h($error); ?></p>
                        <?php endif; ?>

                        <?php $flash = app_flash_get(); ?>
                        <?php if ($flash !== null): ?>
                            <p class="form-status <?php echo app_h($flash['type']); ?>"><?php echo app_h($flash['message']); ?></p>
                        <?php endif; ?>

                        <form method="post" class="contact-form admin-login-form">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" required placeholder="admin">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" required placeholder="Enter admin password">
                            </div>

                            <div class="form-group form-checkbox">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Remember me on this device</label>
                            </div>

                            <div class="registration-actions">
                                <button type="submit" class="btn btn-primary btn-block">Login to Dashboard</button>
                                
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
