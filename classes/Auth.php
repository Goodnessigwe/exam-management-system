<?php
// classes/Auth.php
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/../config/Database.php';

class Auth
{
    private static ?Auth $instance = null;
    private ?User $currentUser = null;

    private function __construct()
    {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $this->currentUser = User::findById($_SESSION['user_id']);
        }
    }

    public static function getInstance(): Auth
    {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }

    public function login(string $email, string $password): bool
    {
        $user = User::findByEmail($email);
        if ($user && $user->verifyPassword($password)) {
            $this->currentUser = $user;

            $_SESSION['user_id']    = $user->getId();
            $_SESSION['user_name']  = $user->getName();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_role']  = $user->getRole();

            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $this->currentUser = null;
        session_unset();
        session_destroy();
    }

    public function check(): bool
    {
        return $this->currentUser !== null;
    }

    public function user(): ?User
    {
        return $this->currentUser;
    }
}