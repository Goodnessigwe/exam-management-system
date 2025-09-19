<?php
// admin/delete_announcement.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/Announcement.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id && Announcement::deleteById((int)$id)) {
    header("Location: manage_announcements.php?msg=deleted");
    exit;
} else {
    die("❌ Failed to delete announcement.");
}