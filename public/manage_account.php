<?php
// public/manage_account.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';

$auth = Auth::getInstance();
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

$user = $auth->user();
$db = Database::getInstance()->getConnection();
$message = "";

// ✅ Handle updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    try {
        if ($name && $email) {
            // Update details
            $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user->getId()]);
            $message = "✅ Account details updated successfully.";
        }

        if ($password) {
            if ($password === $confirm) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed, $user->getId()]);
                $message .= "<br>🔑 Password updated successfully.";
            } else {
                $message .= "<br>❌ Passwords do not match!";
            }
        }

        // ✅ Refresh user info from DB (without setName/setEmail)
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user->getId()]);
        $freshUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($freshUser) {
            // Overwrite $user object manually for this page
            $reflection = new ReflectionClass($user);
            foreach (['name', 'email'] as $field) {
                if (isset($freshUser[$field])) {
                    $prop = $reflection->getProperty($field);
                    $prop->setAccessible(true);
                    $prop->setValue($user, $freshUser[$field]);
                }
            }
        }
    } catch (Exception $e) {
        $message = "⚠️ Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Exam System</a>
            <div class="ms-auto text-white">
                <?= htmlspecialchars($user->getName()) ?> — <small><?= htmlspecialchars($user->getRole()) ?></small>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-header bg-secondary text-white text-center">
                <h3>⚙️ Account Settings</h3>
                <p class="mb-0">Update your personal details and preferences</p>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <form method="POST" class="row g-3">
                    <!-- Personal Info -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="<?= htmlspecialchars($user->getName()) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                    </div>

                    <!-- Password Change -->
                    <div class="col-md-6">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Leave blank to keep old password">
                    </div>
                    <div class="col-md-6">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                            placeholder="Confirm password">
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Save Changes</button>
                        <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>