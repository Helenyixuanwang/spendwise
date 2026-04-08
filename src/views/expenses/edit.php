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
        <a href="/expenses/index.php"
           class="btn btn-outline-light btn-sm">&larr; Back</a>
    </div>
</nav>

<div class="container" style="max-width:480px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h5 class="mb-4">Edit Expense</h5>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title"
                           class="form-control"
                           value="<?= htmlspecialchars($expense['title']) ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount ($)</label>
                    <input type="number" name="amount"
                           class="form-control"
                           step="0.01" min="0.01"
                           value="<?= htmlspecialchars($expense['amount']) ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category"
                            class="form-select" required>
                        <?php
                        $categories = [
                            'Food', 'Transport', 'Housing',
                            'Health', 'Entertainment', 'Other'
                        ];
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
                    <input type="date" name="expense_date"
                           class="form-control"
                           value="<?= htmlspecialchars($expense['expense_date']) ?>"
                           required>
                </div>
                <button type="submit"
                        class="btn btn-primary w-100">Update Expense</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>