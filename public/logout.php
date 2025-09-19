<?php
// public/logout.php
require_once __DIR__ . '/../classes/Auth.php';

$auth = Auth::getInstance();

// ✅ Log the user out
$auth->logout();

// ✅ Redirect to login page
header("Location: login.php?message=You have been logged out successfully.");
exit;