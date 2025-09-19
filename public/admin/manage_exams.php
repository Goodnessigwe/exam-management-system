<?php
// admin/manage_exams.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php'; // Database connection class

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$db = Database::getInstance()->getConnection();

// Fetch all exams
$stmt = $db->query("SELECT id, title, description, duration, created_at FROM exams ORDER BY created_at DESC");
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>



    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Manage Exams</h2>
        <p class="text-muted">Here you can create, edit, and delete exams.</p>

        <!-- Actions -->
        <div class="mb-4">
            <a href="create_exam.php" class="btn btn-primary">➕ Create New Exam</a>
        </div>

        <!-- Exam List -->
        <div class="card shadow-sm p-3">
            <h5 class="fw-bold mb-3">All Exams</h5>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Duration (mins)</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($exams): ?>
                    <?php foreach ($exams as $index => $exam): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($exam['title']) ?></td>
                        <td><?= htmlspecialchars($exam['description']) ?></td>
                        <td><?= htmlspecialchars($exam['duration']) ?></td>
                        <td><?= htmlspecialchars($exam['created_at']) ?></td>
                        <td>
                            <a href="edit_exam.php?id=<?= $exam['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <a href="delete_exam.php?id=<?= $exam['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this exam?');">🗑️ Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No exams found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>