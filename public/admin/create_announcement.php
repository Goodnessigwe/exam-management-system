<?php
// admin/create_announcement.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/Announcement.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        $ann = Announcement::create($title, $content);
        if ($ann) {
            $message = "✅ Announcement created successfully!";
        } else {
            $message = "❌ Failed to create announcement.";
        }
    } else {
        $message = "⚠️ Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Create Announcement</h2>
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="manage_announcements.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>