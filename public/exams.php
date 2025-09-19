<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Exam.php';

$auth = Auth::getInstance();

// Redirect if not logged in
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

$user = $auth->user();
$exams = Exam::getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Exams - Exam System</title>
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="exams.php">Exams</a></li>
                    <li class="nav-item"><a class="nav-link" href="results.php">Results</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Exams Section -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">📚 Available Exams</h2>
            <p class="text-muted">Choose an exam and start when you’re ready.</p>
        </div>

        <div class="row g-4">
            <?php if ($exams): ?>
            <?php foreach ($exams as $exam): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($exam->getTitle()); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($exam->getDescription()); ?></p>
                        <p class="text-muted small">⏱ Duration: <?= $exam->getDuration(); ?> minutes</p>
                        <a href="take_exam.php?id=<?= $exam->getId(); ?>" class="btn btn-primary w-100">Start Exam</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No exams are available at the moment.</div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; <?= date("Y"); ?> Exam System. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>