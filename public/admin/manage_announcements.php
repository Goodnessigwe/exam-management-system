<?php
// admin/manage_announcements.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/Announcement.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch announcements
$announcements = Announcement::getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Manage Announcements</h2>
        <p class="text-muted">Post updates and news for students.</p>

        <!-- Actions -->
        <div class="mb-4">
            <a href="create_announcement.php" class="btn btn-primary">➕ New Announcement</a>
        </div>

        <!-- Announcements List -->
        <div class="card shadow-sm p-3">
            <h5 class="fw-bold mb-3">All Announcements</h5>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($announcements): ?>
                    <?php foreach ($announcements as $index => $ann): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($ann->getTitle()) ?></td>
                        <td><?= htmlspecialchars($ann->getContent()) ?></td>
                        <td><?= htmlspecialchars($ann->getCreatedAt()) ?></td>
                        <td>
                            <a href="edit_announcement.php?id=<?= $ann->getId() ?>" class="btn btn-sm btn-warning">✏️
                                Edit</a>
                            <a href="delete_announcement.php?id=<?= $ann->getId() ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this announcement?');">🗑️
                                Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No announcements found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>