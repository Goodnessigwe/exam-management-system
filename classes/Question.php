<?php
// classes/Question.php
require_once __DIR__ . '/../config/Database.php';

class Question
{
    private $id;
    private $exam_id;
    private $question_text;
    private $created_at;
    private $options = []; // related options

    public function __construct($row)
    {
        $this->id = $row['id'] ?? null;
        $this->exam_id = $row['exam_id'] ?? null;
        $this->question_text = $row['question_text'] ?? '';
        $this->created_at = $row['created_at'] ?? null;
    }

    // ---------- Getters ----------
    public function getId()
    {
        return $this->id;
    }
    public function getExamId()
    {
        return $this->exam_id;
    }
    public function getText()
    {
        return $this->question_text;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getOptions()
    {
        return $this->options;
    }

    // ---------- Fetch ----------
    public static function getByExam($exam_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM questions WHERE exam_id = ? ORDER BY id ASC");
        $stmt->execute([$exam_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questions = [];
        foreach ($rows as $row) {
            $q = new self($row);
            $q->options = self::fetchOptionsByQuestionId($q->id);
            $questions[] = $q;
        }
        return $questions;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM questions WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $q = new self($row);
            $q->options = self::fetchOptionsByQuestionId($q->id);
            return $q;
        }
        return null;
    }

    // ---------- Admin Create ----------
    public static function create(int $exam_id, string $question_text, array $options): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO questions (exam_id, question_text) VALUES (:exam_id, :question_text)");
        $ok = $stmt->execute([
            ':exam_id' => $exam_id,
            ':question_text' => $question_text
        ]);

        if ($ok) {
            $question_id = (int)$db->lastInsertId();

            foreach ($options as $opt) {
                $optStmt = $db->prepare("INSERT INTO options (question_id, option_text, is_correct) 
                                         VALUES (:question_id, :option_text, :is_correct)");
                $optStmt->execute([
                    ':question_id' => $question_id,
                    ':option_text' => $opt['text'],
                    ':is_correct' => $opt['is_correct'] ? 1 : 0
                ]);
            }

            return self::findById($question_id);
        }
        return null;
    }

    // ---------- Admin Delete ----------
    public function delete(): bool
    {
        $db = Database::getInstance()->getConnection();
        $db->prepare("DELETE FROM options WHERE question_id = ?")->execute([$this->id]);
        $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    // ---------- Helper ----------
    private static function fetchOptionsByQuestionId($question_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM options WHERE question_id = ? ORDER BY id ASC");
        $stmt->execute([$question_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}