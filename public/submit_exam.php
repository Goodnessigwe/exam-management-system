<?php
session_start();
require_once __DIR__ . '/../classes/Exam.php';
require_once __DIR__ . '/../classes/Question.php';
require_once __DIR__ . '/../config/Database.php';

// Assuming user_id is stored in session after login
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to take the exam.");
}

$user_id = $_SESSION['user_id'];
$exam_id = isset($_POST['exam_id']) ? (int)$_POST['exam_id'] : 0;
$answers = $_POST['answers'] ?? [];

if (!$exam_id || empty($answers)) {
    die("Invalid exam submission.");
}

$db = Database::getInstance()->getConnection();

$score = 0;
$total = count($answers);

// Loop through answers and store them
foreach ($answers as $question_id => $option_id) {
    // Get correct option for this question
    $stmt = $db->prepare("SELECT id FROM options WHERE question_id = ? AND is_correct = 1 LIMIT 1");
    $stmt->execute([$question_id]);
    $correctOption = $stmt->fetchColumn();

    $is_correct = ($option_id == $correctOption) ? 1 : 0;
    if ($is_correct) {
        $score++;
    }

    // Insert into student_answers
    $stmt = $db->prepare("INSERT INTO student_answers (user_id, exam_id, question_id, option_id, is_correct) 
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $exam_id, $question_id, $option_id, $is_correct]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exam Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-lg text-center">
            <div class="card-header bg-success text-white">
                <h3>Exam Completed</h3>
            </div>
            <div class="card-body">
                <h4>Your Score: <?php echo $score; ?> / <?php echo $total; ?></h4>
                <p class="mt-3">
                    <?php if ($score == $total): ?>
                    🎉 Excellent! You got everything right!
                    <?php elseif ($score >= ($total / 2)): ?>
                    👍 Good job! You passed the exam.
                    <?php else: ?>
                    😢 Don’t worry, keep practicing and you’ll improve!
                    <?php endif; ?>
                </p>
                <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>