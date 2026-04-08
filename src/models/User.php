<?php

require_once __DIR__ . '/../core/Database.php';

class User {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id, username, password_hash FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id, username, email FROM users WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $username, string $email, string $password): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, email, password_hash)
                 VALUES (:username, :email, :hash)"
            );
            $stmt->execute([
                ':username' => $username,
                ':email'    => $email,
                ':hash'     => $hash
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}