<?php
require_once __DIR__ . '/includes/functions.php';

$map = [
    'about' => 'about.php',
    'history' => 'history.php',
    'structure' => 'structure.php',
    'staff' => 'staff.php',
    'education' => 'education.php',
    'achievements' => 'achievements.php',
    'science' => 'science.php',
    'certificates' => 'certificates.php',
    'documents' => 'documents.php',
    'gallery' => 'gallery.php',
    'home' => 'home.php',
];

$key = $_GET['p'] ?? 'home';
if (!isset($map[$key])) {
    http_response_code(404);
    $key = 'home';
}

$activePage = $key;
$pageTitle = ucfirst($key);
require __DIR__ . '/includes/header.php';
include __DIR__ . '/pages/' . $map[$key];
require __DIR__ . '/includes/footer.php';
