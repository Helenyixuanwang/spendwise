<?php

class Controller {

    protected function view(string $viewPath, array $data = []): void {
        // Extract array keys as variables so views can use $title, $expenses etc
        extract($data);

        $fullPath = __DIR__ . '/../views/' . $viewPath . '.php';

        if (!file_exists($fullPath)) {
            die("View not found: $viewPath");
        }

        require $fullPath;
    }

    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin(): void {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login.php');
        }
    }
}