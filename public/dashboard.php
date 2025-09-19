<?php
// public/dashboard.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';

// Create DB connection
$db = Database::getInstance()->getConnection();

$auth = Auth::getInstance();

// If not logged in, redirect to login
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

$user = $auth->user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Exam System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Exam System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="exams.php">Exams</a></li>
                    <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Welcome back, <?= htmlspecialchars($user->getName()); ?> 🎉</h2>
            <p class="text-muted">You are logged in as <strong><?= htmlspecialchars($user->getRole()); ?></strong></p>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100 text-center">
                    <h5 class="mb-3">📚 Take Exams</h5>
                    <p>Browse available exams and start attempting.</p>
                    <a href="exams.php" class="btn btn-primary w-100">Start Exams</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100 text-center">
                    <h5 class="mb-3">📊 View Results</h5>
                    <p>Check your performance and track your progress.</p>
                    <a href="results.php" class="btn btn-success w-100">View Results</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4 h-100 text-center">
                    <h5 class="mb-3">⚙️ Account Settings</h5>
                    <p>Update your personal details and preferences.</p>
                    <a href="manage_account.php" class="btn btn-secondary w-100">Manage Account</a>
                </div>
            </div>
        </div>
    </section>
    <!-- 📌 Announcements & Updates -->
    <div class="card shadow-sm mb-4 w-75 mx-auto mt-5">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">📌 Announcements & Updates</h5>
        </div>
        <div class="card-body">
            <?php
            try {
                $stmt = $db->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5");
                $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($announcements) {
                    foreach ($announcements as $a) {
                        echo '<div class="p-3 mb-3 shadow-sm rounded bg-white">';
                        echo '<h6 class="fw-bold mb-1">' . htmlspecialchars($a['title']) . '</h6>';
                        echo '<small class="text-muted">' . date("M d, Y h:i A", strtotime($a['created_at'])) . '</small>';
                        echo '<p class="mb-0">' . nl2br(htmlspecialchars($a['content'])) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-muted text-center">No announcements available at the moment.</p>';
                }
            } catch (Exception $e) {
                echo '<div class="alert alert-danger text-center">⚠️ Error loading announcements.</div>';
            }
            ?>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?= date("Y"); ?> Exam System. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>