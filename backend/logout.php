<?php
require_once __DIR__ . '/auth.php';
// Log out the admin and redirect to login page
adminLogout();
header('Location: /admin/login.php');
exit;
