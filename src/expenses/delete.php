<?php
session_start();
require_once __DIR__ . '/../controllers/ExpenseController.php';

$controller = new ExpenseController();
$controller->delete();

