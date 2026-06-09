<?php
declare(strict_types=1);

// Shared bootstrap for the PHP/MySQL backend.
// Manual setup note: update the database credentials and admin password in this file before deploying.

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const DB_HOST = 'localhost';
const DB_NAME = 'kuet_rover_scout';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

// Manual setup note: replace this password with a secure value before production.
const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'Scout@12345';
const REMEMBER_COOKIE = 'kuet_rover_admin';
const REMEMBER_COOKIE_SECRET = 'change-this-secret-before-production';

/**
 * Open the shared PDO connection.
 * If the database is not reachable, return null so the UI can show a connection warning.
 */
function app_pdo(): ?PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    try {
        $serverDsn = sprintf('mysql:host=%s;charset=%s', DB_HOST, DB_CHARSET);
        $serverPdo = new PDO($serverDsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Create the target database automatically when the MySQL user has permission.
        $serverPdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        app_bootstrap_schema($pdo);
        app_seed_content($pdo);
        app_restore_admin_from_cookie($pdo);

        return $pdo;
    } catch (Throwable $exception) {
        return null;
    }
}

/**
 * Create the database tables required by the website and admin panel.
 * Manual setup note: you can also import database.sql if you prefer to create tables through phpMyAdmin.
 */
function app_bootstrap_schema(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            remember_token_hash VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            event_date DATE NOT NULL,
            location VARCHAR(255) NOT NULL,
            event_time VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS camps (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            season VARCHAR(100) NOT NULL,
            duration VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            caption VARCHAR(255) NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL,
            badge VARCHAR(150) NOT NULL,
            bio TEXT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(50) NULL,
            subject VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(30) NOT NULL DEFAULT "new",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(255) NOT NULL,
            student_id VARCHAR(100) NOT NULL,
            department VARCHAR(150) NOT NULL,
            semester VARCHAR(50) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            dob DATE NOT NULL,
            blood_group VARCHAR(10) NOT NULL,
            scouting_experience VARCHAR(100) NOT NULL,
            address VARCHAR(255) NOT NULL,
            skills TEXT NULL,
            motivation TEXT NOT NULL,
            consent TINYINT(1) NOT NULL DEFAULT 0,
            status VARCHAR(30) NOT NULL DEFAULT "pending",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
}

/**
 * Seed a default admin account and starter content when the tables are empty.
 */
function app_seed_content(PDO $pdo): void
{
    $adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($adminCount === 0) {
        $statement = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :password_hash)');
        $statement->execute([
            ':username' => ADMIN_USERNAME,
            ':password_hash' => password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT),
        ]);
    }

    $seedMap = [
        'events' => [
            ['Leadership Training Workshop', '2024-03-15', 'KUET Campus', '10:00 AM - 3:00 PM', 'Intensive workshop on leadership skills, team management, and decision-making for aspiring leaders.', 'Event 1.jpg'],
            ['Community Cleanup Drive', '2024-03-22', 'Khulna City', '8:00 AM - 12:00 PM', 'Join us in serving the community through environmental cleanliness and social awareness programs.', 'Event 2.jpg'],
            ['Skill Development Seminar', '2024-04-05', 'KUET Campus', '2:00 PM - 5:00 PM', 'Learn essential survival skills, first aid, and outdoor techniques with expert instructors.', 'Event demo image.png'],
        ],
        'camps' => [
            ['Summer Adventure Camp', 'Summer', '7 days', '7 days of outdoor adventure with camping, hiking, rock climbing, and wilderness survival skills.', 'Camp 1.jpg'],
            ['Winter Leadership Camp', 'Winter', '5 days', '5 days focused on leadership development, crisis management, and personal growth experiences.', 'Camp 2.jpg'],
            ['Spring Community Service Camp', 'Spring', '4 days', '4 days of meaningful service projects, environmental awareness, and sustainable development.', 'Camp 3.jpg'],
        ],
        'gallery' => [
            ['Campfire Celebration', 'camps', 'Summer Camp 2023', 'Camp 2.jpg', 1],
            ['Leadership Training', 'training', 'April 2024', 'G2.jpg', 2],
            ['Community Outreach', 'service', 'March 2024', 'G3.jpg', 3],
            ['Team Building', 'events', 'February 2024', 'Event demo image.png', 4],
            ['Hiking Adventure', 'camps', 'Summer Camp 2023', 'Camp demo image.png', 5],
            ['Environmental Drive', 'service', 'January 2024', 'Event demo image.png', 6],
            ['Scout Group', 'events', 'Annual Meet 2024', 'G4.jpg', 7],
            ['First Aid Workshop', 'training', 'March 2024', 'G5.jpg', 8],
            ['Bonfire Fellowship', 'camps', 'December 2023', 'G6.jpg', 9],
        ],
        'members' => [
            ['A. R. M. Ariful Islam', 'Group Scout Leader', 'Senior Scout', 'Experienced leader with 10+ years in scouting movement', 'Profile Pic.jpg', 1],
            ['Sk. Nazmus Salehin Nirob', 'Assistant Scout Leader', 'Senior Scout', 'Specializes in community service and youth development', 'Profile Pic.jpg', 2],
            ['Priom Sarkar', 'Training Coordinator', 'Scout', 'Passionate about skill development and outdoor activities', 'Profile Pic.jpg', 3],
            ['Md. Toufiq Hasan', 'Events Manager', 'Scout', 'Organizes engaging events and community outreach programs', 'https://via.placeholder.com/200x200?text=Scout+Member', 4],
            ['Shahariar Abdullah Toufiq', 'Outdoor Activities Lead', 'Scout', 'Expert in camping, trekking, and wilderness survival', 'https://via.placeholder.com/200x200?text=Scout+Member', 5],
            ['Ariful Islam Sheikh', 'Social Media & Communications', 'Scout', 'Manages digital presence and member engagement', 'https://via.placeholder.com/200x200?text=Scout+Member', 6],
        ],
    ];

    foreach ($seedMap as $table => $rows) {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
        if ($count > 0) {
            continue;
        }

        if ($table === 'events') {
            $statement = $pdo->prepare('INSERT INTO events (title, event_date, location, event_time, description, image_path) VALUES (:title, :event_date, :location, :event_time, :description, :image_path)');
            foreach ($rows as $row) {
                $statement->execute([
                    ':title' => $row[0],
                    ':event_date' => $row[1],
                    ':location' => $row[2],
                    ':event_time' => $row[3],
                    ':description' => $row[4],
                    ':image_path' => $row[5],
                ]);
            }
        }

        if ($table === 'camps') {
            $statement = $pdo->prepare('INSERT INTO camps (title, season, duration, description, image_path) VALUES (:title, :season, :duration, :description, :image_path)');
            foreach ($rows as $row) {
                $statement->execute([
                    ':title' => $row[0],
                    ':season' => $row[1],
                    ':duration' => $row[2],
                    ':description' => $row[3],
                    ':image_path' => $row[4],
                ]);
            }
        }

        if ($table === 'gallery') {
            $statement = $pdo->prepare('INSERT INTO gallery (title, category, caption, image_path, sort_order) VALUES (:title, :category, :caption, :image_path, :sort_order)');
            foreach ($rows as $row) {
                $statement->execute([
                    ':title' => $row[0],
                    ':category' => $row[1],
                    ':caption' => $row[2],
                    ':image_path' => $row[3],
                    ':sort_order' => $row[4],
                ]);
            }
        }

        if ($table === 'members') {
            $statement = $pdo->prepare('INSERT INTO members (name, role, badge, bio, image_path, sort_order) VALUES (:name, :role, :badge, :bio, :image_path, :sort_order)');
            foreach ($rows as $row) {
                $statement->execute([
                    ':name' => $row[0],
                    ':role' => $row[1],
                    ':badge' => $row[2],
                    ':bio' => $row[3],
                    ':image_path' => $row[4],
                    ':sort_order' => $row[5],
                ]);
            }
        }
    }
}

/**
 * Restore the admin session from the remember-me cookie when the session has expired.
 */
function app_restore_admin_from_cookie(PDO $pdo): void
{
    if (!empty($_SESSION['admin']) || empty($_COOKIE[REMEMBER_COOKIE])) {
        return;
    }

    $cookieValue = (string) $_COOKIE[REMEMBER_COOKIE];
    $decoded = base64_decode($cookieValue, true);

    if ($decoded === false || !str_contains($decoded, '|')) {
        return;
    }

    [$username, $hash] = explode('|', $decoded, 2);
    if ($username !== ADMIN_USERNAME) {
        return;
    }

    $expectedHash = hash_hmac('sha256', $username, REMEMBER_COOKIE_SECRET);
    if (!hash_equals($expectedHash, $hash)) {
        return;
    }

    $statement = $pdo->prepare('SELECT id, username FROM admins WHERE username = :username LIMIT 1');
    $statement->execute([':username' => ADMIN_USERNAME]);
    $admin = $statement->fetch();

    if (!$admin) {
        return;
    }

    $_SESSION['admin'] = [
        'id' => (int) $admin['id'],
        'username' => (string) $admin['username'],
    ];
}

function app_current_admin(): ?array
{
    if (!empty($_SESSION['admin']) && is_array($_SESSION['admin'])) {
        return $_SESSION['admin'];
    }

    app_pdo();

    return !empty($_SESSION['admin']) && is_array($_SESSION['admin']) ? $_SESSION['admin'] : null;
}

function app_require_admin(): void
{
    if (app_current_admin() !== null) {
        return;
    }

    header('Location: login.php');
    exit;
}

function app_redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function app_login_admin(bool $remember = false): void
{
    $pdo = app_pdo();
    $statement = $pdo?->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1');

    if (!$statement) {
        return;
    }

    $statement->execute([':username' => ADMIN_USERNAME]);
    $admin = $statement->fetch();

    if (!$admin || !password_verify(ADMIN_PASSWORD, (string) $admin['password_hash'])) {
        return;
    }

    $_SESSION['admin'] = [
        'id' => (int) $admin['id'],
        'username' => (string) $admin['username'],
    ];

    if ($remember) {
        $cookiePayload = ADMIN_USERNAME . '|' . hash_hmac('sha256', ADMIN_USERNAME, REMEMBER_COOKIE_SECRET);
        setcookie(REMEMBER_COOKIE, base64_encode($cookiePayload), [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

function app_logout_admin(): void
{
    unset($_SESSION['admin']);

    setcookie(REMEMBER_COOKIE, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function app_flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function app_flash_get(): ?array
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function app_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function app_input(string $key, string $default = ''): string
{
    $value = $_POST[$key] ?? $_GET[$key] ?? $default;
    return is_array($value) ? $default : trim((string) $value);
}

function app_database_status(): array
{
    $pdo = app_pdo();

    if ($pdo instanceof PDO) {
        return [true, 'Database connection is healthy.'];
    }

    return [false, 'Database connection failed. Check includes/bootstrap.php, the MySQL server, and the database name.'];
}

function app_table_count(string $table): int
{
    $pdo = app_pdo();
    if (!$pdo) {
        return 0;
    }

    $statement = $pdo->query('SELECT COUNT(*) FROM ' . $table);
    return (int) $statement->fetchColumn();
}

function app_store_contact_message(array $payload): bool
{
    $pdo = app_pdo();
    if (!$pdo) {
        return false;
    }

    $statement = $pdo->prepare(
        'INSERT INTO contact_messages (full_name, email, phone, subject, message, status) VALUES (:full_name, :email, :phone, :subject, :message, :status)'
    );

    return $statement->execute([
        ':full_name' => $payload['full_name'],
        ':email' => $payload['email'],
        ':phone' => $payload['phone'],
        ':subject' => $payload['subject'],
        ':message' => $payload['message'],
        ':status' => 'new',
    ]);
}

function app_store_registration(array $payload): bool
{
    $pdo = app_pdo();
    if (!$pdo) {
        return false;
    }

    $statement = $pdo->prepare(
        'INSERT INTO registrations (
            full_name, student_id, department, semester, email, phone, dob, blood_group,
            scouting_experience, address, skills, motivation, consent, status
        ) VALUES (
            :full_name, :student_id, :department, :semester, :email, :phone, :dob, :blood_group,
            :scouting_experience, :address, :skills, :motivation, :consent, :status
        )'
    );

    return $statement->execute([
        ':full_name' => $payload['full_name'],
        ':student_id' => $payload['student_id'],
        ':department' => $payload['department'],
        ':semester' => $payload['semester'],
        ':email' => $payload['email'],
        ':phone' => $payload['phone'],
        ':dob' => $payload['dob'],
        ':blood_group' => $payload['blood_group'],
        ':scouting_experience' => $payload['scouting_experience'],
        ':address' => $payload['address'],
        ':skills' => $payload['skills'],
        ':motivation' => $payload['motivation'],
        ':consent' => !empty($payload['consent']) ? 1 : 0,
        ':status' => 'pending',
    ]);
}

function app_sections(): array
{
    return [
        'events' => [
            'label' => 'Events',
            'table' => 'events',
            'order_by' => 'event_date DESC, id DESC',
            'fields' => [
                ['name' => 'title', 'label' => 'Event Title', 'type' => 'text', 'required' => true],
                ['name' => 'event_date', 'label' => 'Event Date', 'type' => 'date', 'required' => true],
                ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'required' => true],
                ['name' => 'event_time', 'label' => 'Time', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => true],
                ['name' => 'image_path', 'label' => 'Image Path', 'type' => 'text', 'required' => true],
            ],
        ],
        'camps' => [
            'label' => 'Camps',
            'table' => 'camps',
            'order_by' => 'id DESC',
            'fields' => [
                ['name' => 'title', 'label' => 'Camp Title', 'type' => 'text', 'required' => true],
                ['name' => 'season', 'label' => 'Season', 'type' => 'text', 'required' => true],
                ['name' => 'duration', 'label' => 'Duration', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => true],
                ['name' => 'image_path', 'label' => 'Image Path', 'type' => 'text', 'required' => true],
            ],
        ],
        'gallery' => [
            'label' => 'Gallery',
            'table' => 'gallery',
            'order_by' => 'sort_order ASC, id DESC',
            'fields' => [
                ['name' => 'title', 'label' => 'Photo Title', 'type' => 'text', 'required' => true],
                ['name' => 'category', 'label' => 'Category', 'type' => 'text', 'required' => true],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => true],
                ['name' => 'image_path', 'label' => 'Image Path', 'type' => 'text', 'required' => true],
                ['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'required' => true],
            ],
        ],
        'members' => [
            'label' => 'Members',
            'table' => 'members',
            'order_by' => 'sort_order ASC, id DESC',
            'fields' => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'role', 'label' => 'Role', 'type' => 'text', 'required' => true],
                ['name' => 'badge', 'label' => 'Badge', 'type' => 'text', 'required' => true],
                ['name' => 'bio', 'label' => 'Bio', 'type' => 'textarea', 'required' => true],
                ['name' => 'image_path', 'label' => 'Image Path', 'type' => 'text', 'required' => true],
                ['name' => 'sort_order', 'label' => 'Sort Order', 'type' => 'number', 'required' => true],
            ],
        ],
    ];
}

function app_fetch_records(string $table, string $orderBy): array
{
    $pdo = app_pdo();
    if (!$pdo) {
        return [];
    }

    return $pdo->query('SELECT * FROM ' . $table . ' ORDER BY ' . $orderBy)->fetchAll();
}

function app_fetch_record(string $table, int $id): ?array
{
    $pdo = app_pdo();
    if (!$pdo) {
        return null;
    }

    $statement = $pdo->prepare('SELECT * FROM ' . $table . ' WHERE id = :id LIMIT 1');
    $statement->execute([':id' => $id]);
    $record = $statement->fetch();

    return is_array($record) ? $record : null;
}

function app_save_record(string $table, array $fields, array $data, ?int $id = null): bool
{
    $pdo = app_pdo();
    if (!$pdo) {
        return false;
    }

    $columnNames = array_map(static fn(array $field): string => $field['name'], $fields);
    $params = [];

    foreach ($columnNames as $column) {
        $params[':' . $column] = $data[$column] ?? null;
    }

    if ($id === null) {
        $placeholders = implode(', ', array_map(static fn(string $column): string => ':' . $column, $columnNames));
        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columnNames) . ') VALUES (' . $placeholders . ')';
        $statement = $pdo->prepare($sql);
        return $statement->execute($params);
    }

    $assignments = implode(', ', array_map(static fn(string $column): string => $column . ' = :' . $column, $columnNames));
    $sql = 'UPDATE ' . $table . ' SET ' . $assignments . ' WHERE id = :id';
    $params[':id'] = $id;
    $statement = $pdo->prepare($sql);

    return $statement->execute($params);
}

function app_delete_record(string $table, int $id): bool
{
    $pdo = app_pdo();
    if (!$pdo) {
        return false;
    }

    $statement = $pdo->prepare('DELETE FROM ' . $table . ' WHERE id = :id');
    return $statement->execute([':id' => $id]);
}

function app_datetime_text(?string $value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    try {
        return (new DateTimeImmutable($value))->format('M d, Y h:i A');
    } catch (Throwable $exception) {
        return $value;
    }
}
