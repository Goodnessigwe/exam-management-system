<?php
// admin/edit_student.php
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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']); // optional

    if ($name && $email) {
        $db = Database::getInstance()->getConnection();

        if ($password) {
            // Update with password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
            $ok = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hash,
                ':id' => $student->getId()
            ]);
        } else {
            // Update without password
            $stmt = $db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $ok = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $student->getId()
            ]);
        }

        if ($ok) {
            $message = "✅ Student updated successfully!";
            $student = User::findById($student->getId()); // refresh
        } else {
            $message = "❌ Update failed.";
        }
    } else {
        $message = "⚠️ Name and Email are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Edit Student</h2>
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student->getName()) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($student->getEmail()) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password (Optional)</label>
                <input type="password" name="password" class="form-control"
                    placeholder="Leave empty to keep current password">
            </div>
            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="manage_students.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>