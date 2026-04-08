<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {

    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    public function showLogin(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('/expenses/index.php');
        }
        $this->view('auth/login');
    }

    public function login(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('/expenses/index.php');
        }

        $error = '';
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'All fields are required.';
        } else {
            $user = $this->user->findByEmail($email);

            if ($user && $this->user->verifyPassword($password, $user['password_hash'])) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('/expenses/index.php');
            } else {
                $error = 'Invalid email or password.';
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    public function showRegister(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('/expenses/index.php');
        }
        $this->view('auth/register');
    }

    public function register(): void {
        $error   = '';
        $success = '';

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $created = $this->user->create($username, $email, $password);
            if ($created) {
                $success = 'Account created! <a href="/auth/login.php">Login here</a>.';
            } else {
                $error = 'Username or email already exists.';
            }
        }

        $this->view('auth/register', [
            'error'   => $error,
            'success' => $success
        ]);
    }

    public function logout(): void {
        session_destroy();
        $this->redirect('/auth/login.php');
    }
}