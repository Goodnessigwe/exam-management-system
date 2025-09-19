<?php
// public/take_exam.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Exam.php';
require_once __DIR__ . '/../classes/Question.php';
require_once __DIR__ . '/../config/Database.php';

$auth = Auth::getInstance();
if (!$auth->check()) {
    header("Location: login.php");
    exit;
}

// get exam id from query string (accept both ?id= and ?exam_id=)
$exam_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : null);
if (!$exam_id) {
    die("No exam selected.");
}

// Fetch exam
$exam = Exam::findById($exam_id);
if (!$exam) {
    die("Exam not found.");
}

// ✅ Block if already taken
$db = Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT COUNT(*) FROM student_answers WHERE user_id = ? AND exam_id = ?");
$stmt->execute([$auth->user()->getId(), $exam_id]);
$alreadyTaken = $stmt->fetchColumn();

if ($alreadyTaken > 0) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Exam Already Taken</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
        <div class="card shadow p-4 text-center" style="max-width:500px;">
            <h3 class="text-danger mb-3">You have already taken this exam</h3>
            <p class="mb-4">You are only allowed <strong>one attempt</strong> for this exam.</p>
            <a href="view_result.php?exam_id=<?= $exam_id ?>" class="btn btn-primary btn-lg">View Your Result</a>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-lg mt-2">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
<?php
    exit;
}

// Fetch questions
$questions = Question::getByExam($exam_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Take Exam - <?= htmlspecialchars($exam->getTitle()) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .question-card {
        margin-bottom: 1.25rem;
    }

    /* Make radios look like classic visible circles */
    .form-check-input[type="radio"] {
        appearance: auto !important;
        -webkit-appearance: auto !important;
        -moz-appearance: auto !important;
        width: 18px;
        height: 18px;
        margin-right: 10px;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 1rem;
        cursor: pointer;
    }
    </style>
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
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($exam->getTitle()) ?></h3>
                <p class="card-text text-muted"><?= htmlspecialchars($exam->getDescription()) ?></p>
                <p class="mb-0"><strong>Duration:</strong> <?= (int)$exam->getDuration() ?> minutes</p>
            </div>
        </div>

        <?php if (empty($questions)): ?>
        <div class="alert alert-info">There are no questions for this exam yet.</div>
        <?php else: ?>
        <form method="post" action="submit_exam.php">
            <input type="hidden" name="exam_id" value="<?= $exam_id ?>">

            <?php foreach ($questions as $index => $q): ?>
            <div class="card question-card shadow-sm">
                <div class="card-body">
                    <h5>Q<?= $index + 1 ?>. <?= htmlspecialchars($q->getText()) ?></h5>

                    <?php $options = $q->getOptions(); ?>
                    <?php if (!empty($options)): ?>
                    <?php foreach ($options as $optIndex => $opt): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[<?= (int)$q->getId() ?>]"
                            id="opt_<?= (int)$opt['id'] ?>" value="<?= (int)$opt['id'] ?>" required>
                        <label class="form-check-label" for="opt_<?= (int)$opt['id'] ?>">
                            <?= ($optIndex + 1) ?>. <?= htmlspecialchars($opt['option_text']) ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted">No options available for this question.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <button class="btn btn-success btn-lg w-100" type="submit">Submit Exam</button>
        </form>
        <?php endif; ?>
    </main>

    <footer class="text-center py-4 text-muted">
        &copy; <?= date('Y') ?> Exam System
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>