<?php
// public/register.php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

// If already logged in, optionally redirect to dashboard
if (!empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$old = ['name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic sanitation
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $old['name'] = htmlspecialchars($name, ENT_QUOTES);
    $old['email'] = htmlspecialchars($email, ENT_QUOTES);

    // Validation
    if ($name === '') {
        $errors[] = "Full name is required.";
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check duplicate email
    if (empty($errors)) {
        if (User::findByEmail($email)) {
            $errors[] = "That email is already registered. Try logging in.";
        }
    }

    // If no errors, create user (role = student)
    if (empty($errors)) {
        $user = User::create($name, $email, $password, 'student');
        if ($user) {
            // Optionally set a flash message and redirect to login
            $_SESSION['flash_success'] = "Registration successful. You can now log in.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed due to a server error. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register - Exam System</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: #f4f6f9;
    }

    .card-center {
        max-width: 720px;
        margin: 48px auto;
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Exam System</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="card card-center shadow-sm">
            <div class="card-body">
                <h3 class="card-title mb-3">Create your account</h3>
                <p class="text-muted">Register as a student. Admin accounts must be created by an administrator.</p>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" name="name" value="<?= $old['name'] ?>" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" value="<?= $old['email'] ?>" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="form-text text-muted">Minimum 6 characters.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100">Register</button>
                </form>

                <p class="mt-3 mb-0 text-center">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-muted">
        &copy; <?= date('Y') ?> Exam System
    </footer>

</body>

</html>