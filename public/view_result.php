<?php
// public/view_results.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Exam.php';
require_once __DIR__ . '/../classes/Question.php';
require_once __DIR__ . '/../config/Database.php';

$auth = Auth::getInstance();
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

$exam_id = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;
if (!$exam_id) {
    die("No exam selected.");
}

$exam = Exam::findById($exam_id);
if (!$exam) {
    die("Exam not found.");
}

$user_id = $auth->user()->getId();
$db = Database::getInstance()->getConnection();

// ✅ Fetch student answers with submitted_at
$stmt = $db->prepare("
    SELECT sa.question_id, sa.option_id, sa.is_correct, sa.submitted_at,
           q.question_text, o.option_text AS selected_option
    FROM student_answers sa
    JOIN questions q ON sa.question_id = q.id
    JOIN options o ON sa.option_id = o.id
    WHERE sa.user_id = ? AND sa.exam_id = ?
");
$stmt->execute([$user_id, $exam_id]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$answers) {
    die("You have not taken this exam yet.");
}

// ✅ Get date taken (from first answer record)
$dateTaken = $answers[0]['submitted_at'] ?? null;

// ✅ Calculate score
$total = count($answers);
$score = 0;
foreach ($answers as $a) {
    if ($a['is_correct']) {
        $score++;
    }
}
$percentage = $total > 0 ? round(($score / $total) * 100, 2) : 0;

// ✅ Decide pass/fail (let’s say 50% is pass mark)
$passed = $percentage >= 50;

// ✅ Fetch correct options for display
$stmt = $db->prepare("
    SELECT question_id, option_text 
    FROM options 
    WHERE question_id IN (SELECT question_id FROM student_answers WHERE user_id = ? AND exam_id = ?) 
    AND is_correct = 1
");
$stmt->execute([$user_id, $exam_id]);
$correctOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$correctMap = [];
foreach ($correctOptions as $c) {
    $correctMap[$c['question_id']] = $c['option_text'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Results - <?= htmlspecialchars($exam->getTitle()) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container my-5">
        <!-- Exam Summary -->
        <div class="card shadow-lg mb-4">
            <div class="card-header bg-success text-white text-center">
                <h3>Exam Results</h3>
            </div>
            <div class="card-body text-center">
                <h4 class="mb-3"><?= htmlspecialchars($exam->getTitle()) ?></h4>
                <p class="text-muted"><?= htmlspecialchars($exam->getDescription()) ?></p>
                <p><strong>Duration:</strong> <?= (int)$exam->getDuration() ?> minutes</p>
                <?php if ($dateTaken): ?>
                <p><strong>Date Taken:</strong> <?= date("F j, Y, g:i a", strtotime($dateTaken)) ?></p>
                <?php endif; ?>

                <div class="my-3">
                    <h5>Score: <strong><?= $score ?> / <?= $total ?></strong></h5>
                    <h5>Percentage: <strong><?= $percentage ?>%</strong></h5>
                    <span class="badge <?= $passed ? 'bg-success' : 'bg-danger' ?> fs-5 px-3 py-2">
                        <?= $passed ? 'PASS ✅' : 'FAIL ❌' ?>
                    </span>
                </div>

                <?php if ($score == $total): ?>
                <p class="text-success fw-bold mt-3">🎉 Excellent! You got everything right!</p>
                <?php elseif ($passed): ?>
                <p class="text-primary fw-bold mt-3">👍 Good job! You passed the exam.</p>
                <?php else: ?>
                <p class="text-danger fw-bold mt-3">😢 Don’t worry, keep practicing and you’ll improve!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Answer Breakdown -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Answer Breakdown</h5>
            </div>
            <div class="card-body">
                <?php foreach ($answers as $index => $a): ?>
                <div class="mb-4">
                    <h6>Q<?= $index + 1 ?>. <?= htmlspecialchars($a['question_text']) ?></h6>
                    <p>
                        <strong>Your Answer:</strong>
                        <span class="<?= $a['is_correct'] ? 'text-success' : 'text-danger' ?>">
                            <?= htmlspecialchars($a['selected_option']) ?>
                            <?= $a['is_correct'] ? "✔" : "✘" ?>
                        </span>
                    </p>
                    <?php if (!$a['is_correct']): ?>
                    <p><strong>Correct Answer:</strong>
                        <span
                            class="text-success"><?= htmlspecialchars($correctMap[$a['question_id']] ?? "N/A") ?></span>
                    </p>
                    <?php endif; ?>
                    <hr>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="results.php" class="btn btn-outline-primary">Back to Results</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>