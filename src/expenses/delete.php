<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Delete only if this expense belongs to the logged-in user
$stmt = $pdo->prepare(
    "DELETE FROM expenses WHERE id = :id AND user_id = :uid"
);
$stmt->execute([
    ':id'  => $id,
    ':uid' => $_SESSION['user_id']
]);

header('Location: index.php');
exit;
?>