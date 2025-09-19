<?php
// admin/edit_announcement.php
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
$announcement = $id ? Announcement::findById((int)$id) : null;

if (!$announcement) {
    die("Announcement not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title && $content) {
        if (Announcement::update($announcement->getId(), $title, $content)) {
            $message = "✅ Announcement updated successfully!";
            // refresh object
            $announcement = Announcement::findById($announcement->getId());
        } else {
            $message = "❌ Failed to update announcement.";
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
    <title>Edit Announcement - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Edit Announcement</h2>
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($announcement->getTitle()) ?>"
                    class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="5"
                    required><?= htmlspecialchars($announcement->getContent()) ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="manage_announcements.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>