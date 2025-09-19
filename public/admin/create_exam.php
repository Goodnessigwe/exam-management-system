<?php
// admin/create_exam.php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = (int) $_POST['duration'];

    // Step 1: Create exam
    $exam = Exam::create($title, $description, $duration);

    if ($exam) {
        $exam_id = $exam->getId();

        // Step 2: Loop through submitted questions
        if (!empty($_POST['questions'])) {
            foreach ($_POST['questions'] as $qIndex => $qData) {
                $question_text = trim($qData['text']);
                $options = [];

                if (!empty($qData['options'])) {
                    foreach ($qData['options'] as $oIndex => $optText) {
                        $options[] = [
                            'text' => $optText,
                            'is_correct' => (isset($qData['correct']) && $qData['correct'] == $oIndex)
                        ];
                    }
                }

                if ($question_text && count($options) > 0) {
                    Question::create($exam_id, $question_text, $options);
                }
            }
        }

        $message = "✅ Exam and questions created successfully!";
    } else {
        $message = "❌ Failed to create exam.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Exams - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/admin.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../includes/admin_sidebar.php'; ?>


    <div class="content">
        <h2>Create Exam</h2>
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" id="examForm">
            <!-- Exam metadata -->
            <div class="mb-3">
                <label class="form-label">Exam Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Duration (minutes)</label>
                <input type="number" name="duration" class="form-control" required>
            </div>

            <hr>
            <h4>Questions</h4>
            <div id="questionsContainer"></div>

            <button type="button" class="btn btn-secondary my-3" onclick="addQuestion()">➕ Add Question</button>
            <br>
            <button type="submit" class="btn btn-primary">Save Exam</button>
            <a href="manage_exams.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>

    <script>
    let qCount = 0;

    function addQuestion() {
        const container = document.getElementById('questionsContainer');
        const qIndex = qCount++;

        const qDiv = document.createElement('div');
        qDiv.classList.add('border', 'p-3', 'mb-3');
        qDiv.innerHTML = `
        <label class="form-label">Question</label>
        <input type="text" name="questions[${qIndex}][text]" class="form-control mb-2" required>

        <label class="form-label">Options</label>
        <div id="options-${qIndex}">
            ${createOptionField(qIndex, 0)}
            ${createOptionField(qIndex, 1)}
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addOption(${qIndex})">➕ Add Option</button>
    `;
        container.appendChild(qDiv);
    }

    function createOptionField(qIndex, oIndex) {
        return `
        <div class="input-group mb-2">
            <div class="input-group-text">
                <input type="radio" name="questions[${qIndex}][correct]" value="${oIndex}">
            </div>
            <input type="text" name="questions[${qIndex}][options][${oIndex}]" class="form-control" placeholder="Option ${oIndex+1}" required>
        </div>
    `;
    }

    function addOption(qIndex) {
        const container = document.getElementById(`options-${qIndex}`);
        const oIndex = container.children.length;
        container.insertAdjacentHTML('beforeend', createOptionField(qIndex, oIndex));
    }
    </script>
</body>

</html>