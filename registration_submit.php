<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!app_is_post()) {
    header('Location: reg.php');
    exit;
}

$payload = [
    'full_name' => app_input('fullName'),
    'student_id' => app_input('studentId'),
    'department' => app_input('department'),
    'semester' => app_input('semester'),
    'email' => app_input('email'),
    'phone' => app_input('phone'),
    'dob' => app_input('dob'),
    'blood_group' => app_input('bloodGroup'),
    'scouting_experience' => app_input('scoutingExperience'),
    'address' => app_input('address'),
    'skills' => app_input('skills'),
    'motivation' => app_input('motivation'),
    'consent' => isset($_POST['consent']),
];

$requiredFields = ['full_name', 'student_id', 'department', 'semester', 'email', 'phone', 'dob', 'blood_group', 'scouting_experience', 'address', 'motivation'];
foreach ($requiredFields as $field) {
    if ($payload[$field] === '') {
        app_flash_set('error', 'Please complete all required registration fields.');
        header('Location: reg.php');
        exit;
    }
}

if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
    app_flash_set('error', 'Please enter a valid email address.');
    header('Location: reg.php');
    exit;
}

if (!$payload['consent']) {
    app_flash_set('error', 'You must confirm that the registration information is accurate.');
    header('Location: reg.php');
    exit;
}

if (!app_store_registration($payload)) {
    app_flash_set('error', 'The registration could not be saved because the database connection is not ready.');
    header('Location: reg.php');
    exit;
}

app_flash_set('success', 'Your registration application has been submitted successfully.');
header('Location: reg.php');
exit;
