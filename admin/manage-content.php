<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
app_require_admin();

$sections = app_sections();
$sectionKey = app_input('section', 'events');
if (!isset($sections[$sectionKey])) {
    header('Location: dashboard.php');
    exit;
}

$section = $sections[$sectionKey];
$pdo = app_pdo();
$recordId = (int) app_input('edit', '0');
$currentRecord = $recordId > 0 ? app_fetch_record($section['table'], $recordId) : null;

if (app_is_post()) {
    $action = app_input('action');
    $postedSection = app_input('section');
    if ($postedSection !== $sectionKey) {
        header('Location: manage-content.php?section=' . urlencode($sectionKey));
        exit;
    }

    if ($action === 'delete') {
        $deleteId = (int) app_input('id', '0');
        if ($deleteId > 0 && app_delete_record($section['table'], $deleteId)) {
            app_flash_set('success', ucfirst($sectionKey) . ' item deleted successfully.');
        } else {
            app_flash_set('error', 'Unable to delete the selected item.');
        }
        header('Location: manage-content.php?section=' . urlencode($sectionKey));
        exit;
    }

    $payload = [];
    foreach ($section['fields'] as $field) {
        $name = $field['name'];
        $payload[$name] = app_input($name);
    }

    $saveId = (int) app_input('id', '0');
    if (app_save_record($section['table'], $section['fields'], $payload, $saveId > 0 ? $saveId : null)) {
        app_flash_set('success', ucfirst($sectionKey) . ' item saved successfully.');
    } else {
        app_flash_set('error', 'Unable to save the record. Check the database connection and form values.');
    }

    header('Location: manage-content.php?section=' . urlencode($sectionKey));
    exit;
}

$records = app_fetch_records($section['table'], $section['order_by']);
$flash = app_flash_get();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage <?php echo app_h($section['label']); ?> for KUET Rover Scout Group.">
    <meta name="theme-color" content="#2d5016">
    <title><?php echo app_h($section['label']); ?> Manager | KUET Rover Scout Group</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="registration-page admin-page">
    <div class="admin-dashboard-shell">
        <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

        <div class="admin-main-panel">
            <header class="admin-topbar">
                <div>
                    <span class="admin-kicker">Content Manager</span>
                    <h1><?php echo app_h($section['label']); ?> Manager</h1>
                    <p>Edit the public site content for the <?php echo app_h(strtolower($section['label'])); ?> section.</p>
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
                    <div class="admin-content-layout">
                        <div class="admin-editor-panel">
                            <form method="post" class="contact-form admin-editor-form">
                                <input type="hidden" name="section" value="<?php echo app_h($sectionKey); ?>">
                                <input type="hidden" name="id" value="<?php echo app_h((string) ($currentRecord['id'] ?? '')); ?>">
                                <input type="hidden" name="action" value="save">

                                <?php foreach ($section['fields'] as $field): ?>
                                    <div class="form-group">
                                        <label for="<?php echo app_h($field['name']); ?>"><?php echo app_h($field['label']); ?></label>
                                        <?php if (($field['type'] ?? 'text') === 'textarea'): ?>
                                            <textarea id="<?php echo app_h($field['name']); ?>" name="<?php echo app_h($field['name']); ?>" rows="5" <?php echo !empty($field['required']) ? 'required' : ''; ?>><?php echo app_h((string) ($currentRecord[$field['name']] ?? '')); ?></textarea>
                                        <?php else: ?>
                                            <input
                                                type="<?php echo app_h($field['type'] ?? 'text'); ?>"
                                                id="<?php echo app_h($field['name']); ?>"
                                                name="<?php echo app_h($field['name']); ?>"
                                                value="<?php echo app_h((string) ($currentRecord[$field['name']] ?? '')); ?>"
                                                <?php echo !empty($field['required']) ? 'required' : ''; ?>
                                            >
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <div class="registration-actions">
                                    <button type="submit" class="btn btn-primary">Save <?php echo app_h($section['label']); ?></button>
                                    <a href="manage-content.php?section=<?php echo app_h($sectionKey); ?>" class="btn btn-secondary">Clear Form</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <section class="registration-card admin-list-card">
                    <div class="registration-card-header">
                        <h2>Existing Records</h2>
                        <p>Use the edit and delete actions below to maintain the public content.</p>
                    </div>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <?php foreach ($section['fields'] as $field): ?>
                                        <th><?php echo app_h($field['label']); ?></th>
                                    <?php endforeach; ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td><?php echo app_h((string) $record['id']); ?></td>
                                        <?php foreach ($section['fields'] as $field): ?>
                                            <td><?php echo app_h((string) ($record[$field['name']] ?? '')); ?></td>
                                        <?php endforeach; ?>
                                        <td class="admin-row-actions">
                                            <a href="manage-content.php?section=<?php echo app_h($sectionKey); ?>&edit=<?php echo app_h((string) $record['id']); ?>" class="btn btn-small btn-secondary">Edit</a>
                                            <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this record?');">
                                                <input type="hidden" name="section" value="<?php echo app_h($sectionKey); ?>">
                                                <input type="hidden" name="id" value="<?php echo app_h((string) $record['id']); ?>">
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
