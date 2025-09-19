<?php
// classes/Exam.php
require_once __DIR__ . '/../config/Database.php';

class Exam
{
    private $id;
    private $title;
    private $description;
    private $duration; // minutes
    private $created_at;

    public function __construct(array $data = [])
    {
        $this->id          = $data['id'] ?? null;
        $this->title       = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->duration    = $data['duration'] ?? 0;
        $this->created_at  = $data['created_at'] ?? null;
    }

    // ---------- Static methods ----------
    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM exams ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $exams = [];
        foreach ($rows as $row) {
            $exams[] = new self($row);
        }
        return $exams;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM exams WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function create(string $title, string $description, int $duration): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO exams (title, description, duration) VALUES (:title, :description, :duration)");
        $ok = $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':duration' => $duration
        ]);
        if ($ok) {
            return self::findById((int)$db->lastInsertId());
        }
        return null;
    }

    // ---------- Instance methods ----------
    public function update(string $title, string $description, int $duration): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE exams SET title = :title, description = :description, duration = :duration WHERE id = :id");
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':duration' => $duration,
            ':id' => $this->id
        ]);
    }

    public function delete(): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM exams WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    // ---------- Getters ----------
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return (string)$this->title;
    }
    public function getDescription(): string
    {
        return (string)$this->description;
    }
    public function getDuration(): int
    {
        return (int)$this->duration;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}