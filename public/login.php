<?php
// public/login.php
require_once __DIR__ . '/../classes/Auth.php';

$auth = Auth::getInstance();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($auth->login($email, $password)) {
        // Redirect based on role
        if ($auth->user()->getRole() === 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* ✅ Message stays fixed at top, no layout shift */
    #messageBox {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 400px;
        z-index: 2000;
    }
    </style>
</head>

<body class="bg-light">

    <!-- ✅ Floating message container -->
    <div id="messageBox">
        <?php if (isset($_GET['message']) && !empty($_GET['message'])): ?>
        <div id="logoutMessage" class="alert alert-info alert-dismissible fade show text-center shadow" role="alert">
            <?= htmlspecialchars($_GET['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
        // Auto-hide after 3 seconds
        setTimeout(() => {
            const msg = document.getElementById("logoutMessage");
            if (msg) {
                msg.classList.remove("show");
                msg.classList.add("fade");
                setTimeout(() => msg.remove(), 500);
            }
        }, 3000);
        </script>
        <?php endif; ?>
    </div>

    <!-- ✅ Login form stays unaffected -->
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div style="max-width: 400px; width: 100%;">
            <div class="card shadow p-4">
                <h3 class="text-center mb-3">Login</h3>

                <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="text-center mt-3">
                    Don’t have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>