<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Expense.php';

class ExpenseController extends Controller {

    private Expense $expense;

    public function __construct() {
        $this->expense = new Expense();
    }

    public function index(): void {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $year   = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
        $month  = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');

        $expenses   = $this->expense->getAllByMonth($userId, $year, $month);
        $summary    = $this->expense->getMonthlySummary($userId, $year, $month);
        $total      = array_sum(array_column($expenses, 'amount'));
        $monthName  = DateTime::createFromFormat('!m', $month)->format('F');

        $prevMonth  = $month == 1  ? 12 : $month - 1;
        $prevYear   = $month == 1  ? $year - 1 : $year;
        $nextMonth  = $month == 12 ? 1  : $month + 1;
        $nextYear   = $month == 12 ? $year + 1 : $year;

        $this->view('expenses/index', [
            'expenses'   => $expenses,
            'summary'    => $summary,
            'total'      => $total,
            'monthName'  => $monthName,
            'year'       => $year,
            'month'      => $month,
            'prevMonth'  => $prevMonth,
            'prevYear'   => $prevYear,
            'nextMonth'  => $nextMonth,
            'nextYear'   => $nextYear,
            'username'   => $_SESSION['username']
        ]);
    }

    public function add(): void {
        $this->requireLogin();

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title    = trim($_POST['title']        ?? '');
            $amount   = trim($_POST['amount']       ?? '');
            $category = $_POST['category']          ?? '';
            $date     = $_POST['expense_date']      ?? '';

            if (empty($title) || empty($amount) || empty($date)) {
                $error = 'All fields are required.';
            } elseif (!is_numeric($amount) || $amount <= 0) {
                $error = 'Amount must be a positive number.';
            } else {
                $this->expense->create(
                    $_SESSION['user_id'],
                    $title,
                    (float)$amount,
                    $category,
                    $date
                );
                $this->redirect('/expenses/index.php');
            }
        }

        $this->view('expenses/add', ['error' => $error]);
    }

    public function edit(): void {
        $this->requireLogin();

        $error = '';
        $id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        $expense = $this->expense->findById($id, $_SESSION['user_id']);

        if (!$expense) {
            $this->redirect('/expenses/index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title    = trim($_POST['title']        ?? '');
            $amount   = trim($_POST['amount']       ?? '');
            $category = $_POST['category']          ?? '';
            $date     = $_POST['expense_date']      ?? '';

            if (empty($title) || empty($amount) || empty($date)) {
                $error = 'All fields are required.';
            } elseif (!is_numeric($amount) || $amount <= 0) {
                $error = 'Amount must be a positive number.';
            } else {
                $this->expense->update(
                    $id,
                    $_SESSION['user_id'],
                    $title,
                    (float)$amount,
                    $category,
                    $date
                );
                $this->redirect('/expenses/index.php');
            }
        }

        $this->view('expenses/edit', [
            'expense' => $expense,
            'error'   => $error
        ]);
    }

    public function delete(): void {
        $this->requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $this->expense->delete($id, $_SESSION['user_id']);
        $this->redirect('/expenses/index.php');
    }
}