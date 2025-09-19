<?php
// public/index.php
require_once __DIR__ . '/../classes/Auth.php';

$auth = Auth::getInstance();
$user = $auth->user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam System - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Hero Section */
    .hero {
        position: relative;
        background: url("assets/images/exam-bg.png") center/cover no-repeat;
        height: 100vh;
        display: flex;
        align-items: center;
        text-align: center;
        color: white;
        overflow: hidden;
    }

    /* Navy Blue Overlay */
    .hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 128, 0.6);
        /* navy blue transparency */
        z-index: 1;
    }

    /* Glitter / Shiny effect overlay */
    .hero::after {
        content: "";
        position: absolute;
        top: 0;
        left: -50%;
        width: 200%;
        height: 100%;
        background: linear-gradient(120deg,
                rgba(255, 255, 255, 0.15) 25%,
                rgba(255, 255, 255, 0.05) 50%,
                rgba(255, 255, 255, 0.15) 75%);
        background-size: 200% 200%;
        animation: shine 8s infinite linear;
        z-index: 2;
    }

    /* Shine Animation */
    @keyframes shine {
        0% {
            transform: translateX(-50%);
        }

        100% {
            transform: translateX(50%);
        }
    }

    /* Sparkles Layer */
    .sparkles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 3;
        /* sits above overlay and shine */
        overflow: hidden;
    }

    /* Individual sparkle dots */
    .sparkles span {
        position: absolute;
        width: 4px;
        height: 4px;
        background: white;
        border-radius: 50%;
        opacity: 0;
        animation: twinkle 4s infinite;
    }

    /* Twinkle animation */
    @keyframes twinkle {

        0%,
        100% {
            opacity: 0;
            transform: scale(0.5);
        }

        50% {
            opacity: 1;
            transform: scale(1.3);
        }
    }

    .hero .container {
        position: relative;
        z-index: 4;
        /* make sure text is above sparkles */
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: bold;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
    }

    .features .card {
        transition: transform 0.3s ease;
    }

    .features .card:hover {
        transform: translateY(-8px);
    }

    footer {
        background: #0d6efd;
        color: white;
        padding: 15px 0;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Exam System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Sparkle overlay -->
        <div class="sparkles">
            <span style="top:20%; left:15%; animation-delay:1s;"></span>
            <span style="top:35%; left:40%; animation-delay:2s;"></span>
            <span style="top:50%; left:70%; animation-delay:3s;"></span>
            <span style="top:65%; left:25%; animation-delay:1.5s;"></span>
            <span style="top:80%; left:55%; animation-delay:2.5s;"></span>
            <span style="top:10%; left:75%; animation-delay:3.5s;"></span>
            <span style="top:45%; left:85%; animation-delay:4s;"></span>
        </div>

        <div class="container">
            <h1>Welcome to the Online Exam System</h1>
            <p>Test your knowledge, track your progress, and achieve excellence.</p>
            <?php if ($user): ?>
            <a href="dashboard.php" class="btn btn-light btn-lg px-4">Go to Dashboard</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-warning btn-lg px-4 me-2">Login</a>
            <a href="register.php" class="btn btn-light btn-lg px-4">Register</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose Us?</h2>
                <p class="text-muted">Quick, reliable and student-friendly platform.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center p-4">
                        <div class="fs-1 text-primary mb-3">📝</div>
                        <h5 class="fw-bold">Take Exams</h5>
                        <p>Access multiple subjects and attempt exams at your convenience.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center p-4">
                        <div class="fs-1 text-success mb-3">📊</div>
                        <h5 class="fw-bold">View Results</h5>
                        <p>Instant grading with detailed performance reports and history.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center p-4">
                        <div class="fs-1 text-danger mb-3">📌</div>
                        <h5 class="fw-bold">Stay Updated</h5>
                        <p>Announcements and updates keep you informed of new features.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <small>&copy; <?= date('Y') ?> Exam System. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>