<?php

require_once __DIR__ . '/../core/Database.php';

class Expense {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function getAllByMonth(int $userId, int $year, int $month): array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM expenses
             WHERE user_id = :uid
               AND YEAR(expense_date)  = :year
               AND MONTH(expense_date) = :month
             ORDER BY expense_date DESC"
        );
        $stmt->execute([
            ':uid'   => $userId,
            ':year'  => $year,
            ':month' => $month
        ]);
        return $stmt->fetchAll();
    }

    public function getMonthlySummary(int $userId, int $year, int $month): array {
        $stmt = $this->pdo->prepare(
            "CALL GetMonthlySummary(:uid, :year, :month)"
        );
        $stmt->execute([
            ':uid'   => $userId,
            ':year'  => $year,
            ':month' => $month
        ]);
        return $stmt->fetchAll();
    }

    public function findById(int $id, int $userId): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM expenses
             WHERE id = :id AND user_id = :uid"
        );
        $stmt->execute([':id' => $id, ':uid' => $userId]);
        return $stmt->fetch();
    }

    public function create(
        int $userId,
        string $title,
        float $amount,
        string $category,
        string $date
    ): bool {
        $stmt = $this->pdo->prepare(
            "INSERT INTO expenses (user_id, title, amount, category, expense_date)
             VALUES (:uid, :title, :amount, :category, :date)"
        );
        return $stmt->execute([
            ':uid'      => $userId,
            ':title'    => $title,
            ':amount'   => $amount,
            ':category' => $category,
            ':date'     => $date
        ]);
    }

    public function update(
        int $id,
        int $userId,
        string $title,
        float $amount,
        string $category,
        string $date
    ): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE expenses
             SET title = :title, amount = :amount,
                 category = :category, expense_date = :date
             WHERE id = :id AND user_id = :uid"
        );
        return $stmt->execute([
            ':title'    => $title,
            ':amount'   => $amount,
            ':category' => $category,
            ':date'     => $date,
            ':id'       => $id,
            ':uid'      => $userId
        ]);
    }

    public function delete(int $id, int $userId): bool {
        $stmt = $this->pdo->prepare(
            "DELETE FROM expenses
             WHERE id = :id AND user_id = :uid"
        );
        return $stmt->execute([
            ':id'  => $id,
            ':uid' => $userId
        ]);
    }
}