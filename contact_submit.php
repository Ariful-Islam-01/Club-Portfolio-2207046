<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!app_is_post()) {
    header('Location: index.php#contact');
    exit;
}

$payload = [
    'full_name' => app_input('fullName'),
    'email' => app_input('email'),
    'phone' => app_input('phone'),
    'subject' => app_input('subject'),
    'message' => app_input('message'),
];

if ($payload['full_name'] === '' || $payload['email'] === '' || $payload['subject'] === '' || $payload['message'] === '') {
    app_flash_set('error', 'Please complete all required contact fields.');
    header('Location: index.php#contact');
    exit;
}

if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
    app_flash_set('error', 'Please enter a valid email address.');
    header('Location: index.php#contact');
    exit;
}

if (!app_store_contact_message($payload)) {
    app_flash_set('error', 'The message could not be saved because the database connection is not ready.');
    header('Location: index.php#contact');
    exit;
}

app_flash_set('success', 'Your message has been submitted successfully.');
header('Location: index.php#contact');
exit;
