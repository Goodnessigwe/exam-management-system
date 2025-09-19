<?php
// classes/User.php
require_once __DIR__ . '/../config/Database.php';

class User
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $password; // hashed
    private string $role;
    private ?string $created_at;

    public function __construct(array $data = [])
    {
        $this->id         = $data['id'] ?? null;
        $this->name       = $data['name'] ?? '';
        $this->email      = $data['email'] ?? '';
        $this->password   = $data['password'] ?? '';
        $this->role       = $data['role'] ?? 'student';
        $this->created_at = $data['created_at'] ?? null;
    }

    // ---------- Static helpers / CRUD ----------

    public static function findByEmail(string $email): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function create(string $name, string $email, string $rawPassword, string $role = 'student'): ?self
    {
        if (self::findByEmail($email)) {
            return null;
        }

        $db = Database::getInstance()->getConnection();
        $hash = password_hash($rawPassword, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (name, email, password, role) 
                              VALUES (:name, :email, :password, :role)");
        $ok = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hash,
            ':role' => $role
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
        $stmt = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStudent(int $id, string $name, string $email, ?string $password = null, ?string $role = null): bool
    {
        $db = Database::getInstance()->getConnection();

        if ($password && $role) {
            $query = "UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':role' => $role,
                ':id' => $id
            ]);
        } elseif ($password) {
            $query = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':id' => $id
            ]);
        } elseif ($role) {
            $query = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':role' => $role,
                ':id' => $id
            ]);
        } else {
            $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $id
            ]);
        }
    }

    public static function deleteById(int $id): bool
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ---------- Instance helpers ----------

    public function verifyPassword(string $rawPassword): bool
    {
        return password_verify($rawPassword, $this->password);
    }

    // ---------- Getters ----------
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }
}