<?php
// admin/delete_student.php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/User.php';

$auth = Auth::getInstance();
$user = $auth->user();

if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$student = $id ? User::findById((int)$id) : null;

if (!$student) {
    header("Location: manage_students.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id AND role = 'student'");
    $ok = $stmt->execute([':id' => $student->getId()]);

    if ($ok) {
        header("Location: manage_students.php?msg=deleted");
        exit;
    }
    $error = "❌ Failed to delete student.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Delete Student</h2>
        <div class="alert alert-warning">
            ⚠️ Are you sure you want to delete <strong><?= htmlspecialchars($student->getName()) ?></strong>
            (<?= htmlspecialchars($student->getEmail()) ?>)?
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
            <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>