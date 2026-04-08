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
            <span class="text-white small">
                Hello, <?= htmlspecialchars($username) ?>!
            </span>
            <a href="/auth/logout.php"
               class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- Month navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>"
           class="btn btn-outline-secondary btn-sm">&larr; Previous</a>
        <h5 class="mb-0"><?= $monthName ?> <?= $year ?></h5>
        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>"
           class="btn btn-outline-secondary btn-sm">Next &rarr;</a>
    </div>

    <div class="row g-4 mb-4">

        <!-- Monthly summary -->
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
                                    <td>
                                        <?= htmlspecialchars($row['category']) ?>
                                    </td>
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
                    <div class="d-flex justify-content-between
                                align-items-center mb-3">
                        <h6 class="card-title text-muted mb-0">Expenses</h6>
                        <a href="/expenses/add.php"
                           class="btn btn-primary btn-sm">+ Add Expense</a>
                    </div>
                    <?php if (empty($expenses)): ?>
                        <p class="text-muted small">
                            No expenses yet.
                            <a href="/expenses/add.php">Add your first one!</a>
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
                                    <td>
                                        <?= htmlspecialchars($exp['expense_date']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($exp['title']) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($exp['category']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        $<?= number_format($exp['amount'], 2) ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="/expenses/edit.php?id=<?= $exp['id'] ?>"
                                           class="btn btn-outline-secondary btn-sm">
                                           Edit
                                        </a>
                                        <a href="/expenses/delete.php?id=<?= $exp['id'] ?>"
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Delete this expense?')">
                                           Del
                                        </a>
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