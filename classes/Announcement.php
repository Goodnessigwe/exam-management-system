<?php
// classes/Announcement.php
require_once __DIR__ . '/../config/Database.php';

class Announcement
{
    private ?int $id;
    private string $title;
    private string $content;
    private ?string $created_at;

    public function __construct(array $data = [])
    {
        $this->id         = $data['id'] ?? null;
        $this->title      = $data['title'] ?? '';
        $this->content    = $data['content'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
    }

    // ---------- CRUD ----------
    public static function create(string $title, string $content): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO announcements (title, content) VALUES (:title, :content)");
        $ok = $stmt->execute([
            ':title' => $title,
            ':content' => $content
        ]);

        if ($ok) {
            $id = (int)$db->lastInsertId();
            return self::findById($id);
        }

        return null;
    }

    public static function getAll(): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM announcements ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new self($row), $rows);
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM announcements WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new self($row) : null;
    }

    public static function update(int $id, string $title, string $content): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE announcements SET title = :title, content = :content WHERE id = :id");
        return $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':id' => $id
        ]);
    }

    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM announcements WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ---------- Getters ----------
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
}