<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
app_require_admin();

$pdo = app_pdo();
$messages = $pdo ? $pdo->query('SELECT * FROM contact_messages ORDER BY id DESC')->fetchAll() : [];
$flash = app_flash_get();

if (app_is_post()) {
    $action = app_input('action');
    $messageId = (int) app_input('id', '0');

    if ($action === 'delete' && $messageId > 0 && $pdo) {
        $statement = $pdo->prepare('DELETE FROM contact_messages WHERE id = :id');
        $statement->execute([':id' => $messageId]);
        app_flash_set('success', 'Contact message deleted successfully.');
        header('Location: messages.php');
        exit;
    }

    if ($action === 'status' && $messageId > 0 && $pdo) {
        $status = app_input('status', 'new');
        $statement = $pdo->prepare('UPDATE contact_messages SET status = :status WHERE id = :id');
        $statement->execute([':status' => $status, ':id' => $messageId]);
        app_flash_set('success', 'Contact message status updated.');
        header('Location: messages.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page admin-page">
    <div class="admin-dashboard-shell">
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <div class="admin-main-panel">
            <header class="admin-topbar">
                <div>
                    <span class="admin-kicker">Inbox</span>
                    <h1>Contact Messages</h1>
                    <p>Review the messages submitted from the public site contact form.</p>
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
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                    <tr>
                                        <td><?php echo app_h((string) $message['id']); ?></td>
                                        <td><?php echo app_h((string) $message['full_name']); ?></td>
                                        <td><?php echo app_h((string) $message['email']); ?></td>
                                        <td><?php echo app_h((string) $message['subject']); ?></td>
                                        <td><?php echo app_h((string) $message['message']); ?></td>
                                        <td><?php echo app_h((string) $message['status']); ?></td>
                                        <td><?php echo app_h(app_datetime_text((string) $message['created_at'])); ?></td>
                                        <td class="admin-row-actions">
                                            <form method="post" class="admin-inline-form">
                                                <input type="hidden" name="id" value="<?php echo app_h((string) $message['id']); ?>">
                                                <input type="hidden" name="action" value="status">
                                                <select name="status" class="admin-inline-select">
                                                    <option value="new" <?php echo $message['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                                    <option value="read" <?php echo $message['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                                    <option value="closed" <?php echo $message['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                                </select>
                                                <button type="submit" class="btn btn-small btn-secondary">Update</button>
                                            </form>
                                            <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this message?');">
                                                <input type="hidden" name="id" value="<?php echo app_h((string) $message['id']); ?>">
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
