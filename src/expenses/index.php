<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get current month and year (allow URL params for navigation)
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');

$month_name = DateTime::createFromFormat('!m', $month)->format('F');

// Fetch all expenses for this user this month
$stmt = $pdo->prepare(
    "SELECT * FROM expenses
     WHERE user_id = :uid
       AND YEAR(expense_date)  = :year
       AND MONTH(expense_date) = :month
     ORDER BY expense_date DESC"
);
$stmt->execute([':uid' => $user_id, ':year' => $year, ':month' => $month]);
$expenses = $stmt->fetchAll();

// Call stored procedure for monthly summary
$summary_stmt = $pdo->prepare("CALL GetMonthlySummary(:uid, :year, :month)");
$summary_stmt->execute([':uid' => $user_id, ':year' => $year, ':month' => $month]);
$summary = $summary_stmt->fetchAll();

// Calculate grand total
$total = array_sum(array_column($expenses, 'amount'));

// Prev / next month navigation
$prev_month = $month == 1 ? 12 : $month - 1;
$prev_year  = $month == 1 ? $year - 1 : $year;
$next_month = $month == 12 ? 1 : $month + 1;
$next_year  = $month == 12 ? $year + 1 : $year;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — SpendWise</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">SpendWise</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small">Hello, <?= htmlspecialchars($username) ?>!</span>
            <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Month navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="?year=<?= $prev_year ?>&month=<?= $prev_month ?>"
           class="btn btn-outline-secondary btn-sm">&larr; Previous</a>
        <h5 class="mb-0"><?= $month_name ?> <?= $year ?></h5>
        <a href="?year=<?= $next_year ?>&month=<?= $next_month ?>"
           class="btn btn-outline-secondary btn-sm">Next &rarr;</a>
    </div>

    <div class="row g-4 mb-4">

        <!-- Monthly summary card -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title text-muted">Monthly Summary</h6>
                    <?php if (empty($summary)): ?>
                        <p class="text-muted small">No expenses this month.</p>
                    <?php else: ?>
                        <table class="table table-sm mb-2">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['category']) ?></td>
                                    <td class="text-end">
                                        $<?= number_format($row['total'], 2) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>$<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses list -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title text-muted mb-0">Expenses</h6>
                        <a href="add.php" class="btn btn-primary btn-sm">+ Add Expense</a>
                    </div>
                    <?php if (empty($expenses)): ?>
                        <p class="text-muted small">
                            No expenses yet.
                            <a href="add.php">Add your first one!</a>
                        </p>
                    <?php else: ?>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($expenses as $exp): ?>
                                <tr>
                                    <td><?= htmlspecialchars($exp['expense_date']) ?></td>
                                    <td><?= htmlspecialchars($exp['title']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($exp['category']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        $<?= number_format($exp['amount'], 2) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="edit.php?id=<?= $exp['id'] ?>"
                                           class="btn btn-outline-secondary btn-xs btn-sm">Edit</a>
                                        <a href="delete.php?id=<?= $exp['id'] ?>"
                                           class="btn btn-outline-danger btn-xs btn-sm"
                                           onclick="return confirm('Delete this expense?')">Del</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
