<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
app_require_admin();

$pdo = app_pdo();
$registrations = $pdo ? $pdo->query('SELECT * FROM registrations ORDER BY id DESC')->fetchAll() : [];
$flash = app_flash_get();

if (app_is_post()) {
    $action = app_input('action');
    $registrationId = (int) app_input('id', '0');

    if ($pdo && $registrationId > 0 && $action === 'delete') {
        $statement = $pdo->prepare('DELETE FROM registrations WHERE id = :id');
        $statement->execute([':id' => $registrationId]);
        app_flash_set('success', 'Registration request deleted successfully.');
        header('Location: registrations.php');
        exit;
    }

    if ($pdo && $registrationId > 0 && $action === 'status') {
        $status = app_input('status', 'pending');
        $statement = $pdo->prepare('UPDATE registrations SET status = :status WHERE id = :id');
        $statement->execute([':status' => $status, ':id' => $registrationId]);
        app_flash_set('success', 'Registration status updated.');
        header('Location: registrations.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Requests | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page admin-page">
    <div class="admin-dashboard-shell">
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <div class="admin-main-panel">
            <header class="admin-topbar">
                <div>
                    <span class="admin-kicker">Applications</span>
                    <h1>Registration Requests</h1>
                    <p>Review membership applications submitted from the registration page.</p>
                </div>
                <div class="admin-topbar-actions">
                    <a href="dashboard.php" class="back-link">Dashboard</a>
                    <a href="logout.php" class="btn btn-secondary btn-small">Logout</a>
                </div>
            </header>

            <main class="admin-dashboard-content">
                <?php if ($flash !== null): ?>
                    <p class="form-status <?php echo app_h($flash['type']); ?>"><?php echo app_h($flash['message']); ?></p>
                <?php endif; ?>

                <section class="registration-card">
                    
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>Department</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registrations as $registration): ?>
                                    <tr>
                                        <td><?php echo app_h((string) $registration['id']); ?></td>
                                        <td><?php echo app_h((string) $registration['full_name']); ?></td>
                                        <td><?php echo app_h((string) $registration['student_id']); ?></td>
                                        <td><?php echo app_h((string) $registration['department']); ?></td>
                                        <td><?php echo app_h((string) $registration['email']); ?></td>
                                        <td><?php echo app_h((string) $registration['phone']); ?></td>
                                        <td><?php echo app_h((string) $registration['status']); ?></td>
                                        <td><?php echo app_h(app_datetime_text((string) $registration['created_at'])); ?></td>
                                        <td class="admin-row-actions">
                                            <form method="post" class="admin-inline-form">
                                                <input type="hidden" name="id" value="<?php echo app_h((string) $registration['id']); ?>">
                                                <input type="hidden" name="action" value="status">
                                                <select name="status" class="admin-inline-select">
                                                    <option value="pending" <?php echo $registration['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="reviewed" <?php echo $registration['status'] === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                                    <option value="approved" <?php echo $registration['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                                    <option value="rejected" <?php echo $registration['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                                <button type="submit" class="btn btn-small btn-secondary">Update</button>
                                            </form>
                                            <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this registration?');">
                                                <input type="hidden" name="id" value="<?php echo app_h((string) $registration['id']); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn btn-small btn-primary">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
