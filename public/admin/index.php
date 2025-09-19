<?php
// admin/index.php
require_once __DIR__ . '/../../classes/Auth.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Restrict access to admins only
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Detect current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">

</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>


    <!-- Content -->
    <div class="content">
        <h2 class="mb-4">Welcome, <?= htmlspecialchars($user->getName()) ?> 👋</h2>
        <p class="text-muted">Here’s an overview of your admin dashboard.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-4">
                    <div class="fs-1 text-primary mb-2">📝</div>
                    <h5 class="fw-bold">Manage Exams</h5>
                    <p>Create, edit and delete exams for students.</p>
                    <a href="manage_exams.php" class="btn btn-primary btn-sm">Go</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-4">
                    <div class="fs-1 text-success mb-2">👩‍🎓</div>
                    <h5 class="fw-bold">Manage Students</h5>
                    <p>View and manage registered students.</p>
                    <a href="manage_students.php" class="btn btn-success btn-sm">Go</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-4">
                    <div class="fs-1 text-danger mb-2">📢</div>
                    <h5 class="fw-bold">Announcements</h5>
                    <p>Post updates and news for students.</p>
                    <a href="manage_announcements.php" class="btn btn-danger btn-sm">Go</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>