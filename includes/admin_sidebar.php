<?php
// includes/admin_sidebar.php

// Detect current page (so we can highlight active link)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="manage_exams.php" class="<?= ($current_page == 'manage_exams.php') ? 'active' : '' ?>">📝 Manage Exams</a>
    <a href="manage_students.php" class="<?= ($current_page == 'manage_students.php') ? 'active' : '' ?>">👩‍🎓 Manage
        Students</a>
    <a href="manage_announcements.php" class="<?= ($current_page == 'announcements.php') ? 'active' : '' ?>">📢
        Announcements</a>
    <a href="settings.php" class="<?= ($current_page == 'settings.php') ? 'active' : '' ?>">⚙️ Settings</a>
    <a href="../logout.php">🚪 Logout</a>
</div>