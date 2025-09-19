<?php
// public/results.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';

$auth = Auth::getInstance();
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

$user_id = $auth->user()->getId();

$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("
    SELECT DISTINCT e.id AS exam_id, e.title, e.description, e.duration, e.created_at
    FROM exams e
    INNER JOIN student_answers sa ON e.id = sa.exam_id
    WHERE sa.user_id = ?
    ORDER BY e.created_at DESC
");
$stmt->execute([$user_id]);
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Your Results - Exam System</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Exam System</a>
            <div class="ms-auto text-white">
                <?= htmlspecialchars($auth->user()->getName()) ?> —
                <small><?= htmlspecialchars($auth->user()->getRole()) ?></small>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Your Exam Results</h3>

                <?php if (empty($exams)): ?>
                <div class="alert alert-info">You have not taken any exams yet.</div>
                <?php else: ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Exam Title</th>
                            <th>Description</th>
                            <th>Duration (mins)</th>
                            <th>Date Taken</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exams as $exam): ?>
                        <tr>
                            <td><?= htmlspecialchars($exam['title']) ?></td>
                            <td><?= htmlspecialchars($exam['description']) ?></td>
                            <td><?= (int)$exam['duration'] ?></td>
                            <td><?= htmlspecialchars(date("M d, Y H:i", strtotime($exam['created_at']))) ?></td>
                            <td>
                                <a href="view_result.php?exam_id=<?= $exam['exam_id'] ?>"
                                    class="btn btn-sm btn-success">
                                    View Result
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="text-center py-4 text-muted">
        &copy; <?= date('Y') ?> Exam System
    </footer>
</body>

</html>