<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/User.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch all students
$students = User::getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Manage Students</h2>
        <p class="text-muted">Here you can view, add, edit, and delete students.</p>

        <!-- Actions -->
        <div class="mb-4">
            <a href="create_student.php" class="btn btn-primary">➕ Add New Student</a>
        </div>

        <!-- Student List -->
        <div class="card shadow-sm p-3">
            <h5 class="fw-bold mb-3">All Students</h5>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $studentsOnly = array_filter($students, fn($s) => $s['role'] === 'student');
                    ?>
                    <?php if ($studentsOnly): ?>
                    <?php foreach ($studentsOnly as $index => $student): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($student['role']) ?></span></td>
                        <td><?= htmlspecialchars($student['created_at']) ?></td>
                        <td>
                            <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-warning">✏️
                                Edit</a>
                            <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this student?');">🗑️
                                Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No students found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>