<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch expense — make sure it belongs to this user
$stmt = $pdo->prepare(
    "SELECT * FROM expenses WHERE id = :id AND user_id = :uid"
);
$stmt->execute([':id' => $id, ':uid' => $_SESSION['user_id']]);
$expense = $stmt->fetch();

if (!$expense) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $amount   = trim($_POST['amount']);
    $category = $_POST['category'];
    $date     = $_POST['expense_date'];

    if (empty($title) || empty($amount) || empty($date)) {
        $error = 'All fields are required.';
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $error = 'Amount must be a positive number.';
    } else {
        $stmt = $pdo->prepare(
            "UPDATE expenses
             SET title = :title, amount = :amount,
                 category = :category, expense_date = :date
             WHERE id = :id AND user_id = :uid"
        );
        $stmt->execute([
            ':title'    => $title,
            ':amount'   => $amount,
            ':category' => $category,
            ':date'     => $date,
            ':id'       => $id,
            ':uid'      => $_SESSION['user_id']
        ]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Expense — SpendWise</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">SpendWise</span>
        <a href="index.php" class="btn btn-outline-light btn-sm">&larr; Back</a>
    </div>
</nav>

<div class="container" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Expense</h5>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control"
                           value="<?= htmlspecialchars($expense['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount ($)</label>
                    <input type="number" name="amount" class="form-control"
                           step="0.01" min="0.01"
                           value="<?= htmlspecialchars($expense['amount']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <?php
                        $categories = ['Food','Transport','Housing','Health','Entertainment','Other'];
                        foreach ($categories as $cat):
                        ?>
                        <option value="<?= $cat ?>"
                            <?= $expense['category'] === $cat ? 'selected' : '' ?>>
                            <?= $cat ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" class="form-control"
                           value="<?= htmlspecialchars($expense['expense_date']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Update Expense</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>