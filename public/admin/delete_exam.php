<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../classes/Exam.php';
require_once __DIR__ . '/../../classes/Question.php';

$auth = Auth::getInstance();
$user = $auth->user();

if (!$user || $user->getRole() !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = '';
$exam = null;
$questions = [];

if (!isset($_GET['id'])) {
    header("Location: manage_exams.php");
    exit;
}

$examId = (int) $_GET['id'];
$exam = Exam::findById($examId);

if (!$exam) {
    $message = "❌ Exam not found.";
} else {
    $questions = Question::getByExam($examId);
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete_all') {
        // Cascade delete: questions + options + exam
        foreach ($questions as $q) {
            $q->delete();
        }
        if ($exam->delete()) {
            header("Location: manage_exams.php?msg=exam_deleted");
            exit;
        } else {
            $message = "❌ Failed to delete exam.";
        }
    } elseif ($action === 'delete_questions') {
        // Delete only questions + options
        foreach ($questions as $q) {
            $q->delete();
        }
        $message = "✅ All questions and options deleted. Exam remains.";
        $questions = []; // refresh state → nothing remains
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Exam - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>

    <div class="content">
        <h2>Delete Exam</h2>

        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($exam): ?>
        <div class="card p-3 mb-3">
            <h4><?= htmlspecialchars($exam->getTitle()) ?></h4>
            <p><?= htmlspecialchars($exam->getDescription()) ?></p>
            <p><strong>Duration:</strong> <?= $exam->getDuration() ?> minutes</p>
        </div>

        <?php if (!empty($questions)): ?>
        <h5>Questions & Options</h5>
        <?php foreach ($questions as $q): ?>
        <div class="border rounded p-2 mb-3">
            <p><strong>Q:</strong> <?= htmlspecialchars($q->getText()) ?></p>
            <ul>
                <?php foreach ($q->getOptions() as $opt): ?>
                <li>
                    <?= htmlspecialchars($opt['option_text']) ?>
                    <?php if ($opt['is_correct']): ?>
                    <span class="badge bg-success">Correct</span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="alert alert-warning">⚠️ No questions available for this exam.</div>
        <?php endif; ?>

        <form method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone.');" class="mt-3">
            <button type="submit" name="action" value="delete_all" class="btn btn-danger">
                🗑 Delete Exam (with all Questions & Options)
            </button>
            <button type="submit" name="action" value="delete_questions" class="btn btn-warning">
                ❌ Delete Only Questions & Options
            </button>
            <a href="manage_exams.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>