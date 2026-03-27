<?php
// inc/require_admin.php
// Auto-prepend file to require admin session for most site pages.
// It allows access to static assets and the admin login page itself.

if (php_sapi_name() === 'cli') return;

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$parsed = parse_url($uri);
$path = $parsed['path'] ?? '/';

// Whitelist common asset folders and the admin login page
$whitelist = [
    '/admin/login.php',
    '/css/',
    '/js/',
    '/images/',
    '/api/',
    '/backend/',
    '/uploads/',
    '/fonts/',
    '/inc/',
];

// Allow a set of public pages (converted .php equivalents of original .html pages)
$publicPages = [
    // '/' and '/index.php' intentionally removed so the homepage requires admin login
    '/about.php',
    '/contact.php',
    '/practice.php',
    '/won.php',
    '/consultar-caso.php',
];

if (in_array($path, $publicPages, true)) return;
foreach ($whitelist as $p) {
    if (strpos($path, $p) === 0) return;
}

// Avoid requiring the full backend (which attempts a DB connection) here —
// we only need to check the session to enforce access. This prevents slow
// DB connect delays on every public page load when the user is not logged in.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['admin_id'])) {
    header('Location: /admin/login.php');
    exit;
}

?>
