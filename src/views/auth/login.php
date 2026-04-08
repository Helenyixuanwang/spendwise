<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — SpendWise</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="card-title mb-4 text-center">SpendWise</h3>
            <h5 class="mb-3">Login</h5>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control" required>
                </div>
                <button type="submit"
                        class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center small">
                No account?
                <a href="/auth/register.php">Register</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>