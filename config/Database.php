<!-- # Database connection (PDO, singleton) -->
<!-- // config/Database.php -->

<?php
class Database
{
    private static ?Database $instance = null;
    private ?PDO $conn = null;

    private function __construct()
    {
        $host = "localhost";
        $db   = "exam_system";
        $user = "root";
        $pass = "";
        $dsn  = "mysql:host={$host};dbname={$db};charset=utf8mb4";

        $this->conn = new PDO(
            $dsn,
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}



// <!-- 
// class Database
// {
//     public $host = "localhost";
//     private $db = "exam_system";
//     private $user = "root";
//     private $pass = "";
//     public $conn;

//     public function connect()
//     {
//         $this->conn = null;
//         try {
//             $this->conn = new PDO(
//                 "mysql:host=" . $this->host . ";dbname=" . $this->db,
//                 $this->user,
//                 $this->pass
//             );
//             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         } catch (PDOException $e) {
//             echo "DB Error: " . $e->getMessage();
//         }
//         return $this->conn;
//     }
// } -->