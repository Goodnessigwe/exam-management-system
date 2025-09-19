<?php
// admin/create_student.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/User.php';

$auth = Auth::getInstance();
$user = $auth->user();

// Only admin can access
if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($name && $email && $password) {
        $student = User::create($name, $email, $password, 'student');
        if ($student) {
            $message = "✅ Student created successfully!";
        } else {
            $message = "❌ Failed to create student (email may already exist).";
        }
    } else {
        $message = "⚠️ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Create Student</h2>
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Student</button>
            <a href="manage_students.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>