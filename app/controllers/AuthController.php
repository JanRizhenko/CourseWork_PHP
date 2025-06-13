<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Exception;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = new User();
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        header('Location: /auth/login');
        exit;
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $errors = [];

            if (empty($email) || empty($password)) {
                $errors[] = 'Будь ласка, заповніть всі поля';
            }

            if (empty($errors)) {
                $user = $this->userModel->login($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['is_admin'] = $user['is_admin'];

                    $redirect = $_SESSION['redirect_after_login'] ?? '/';
                    unset($_SESSION['redirect_after_login']);

                    header("Location: $redirect");
                    exit;
                } else {
                    $errors[] = 'Невірний email або пароль';
                }
            }

            $this->view('auth/login', ['errors' => $errors, 'email' => $email]);
        } else {
            $this->view('auth/login');
        }
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            if (empty($name)) $errors[] = 'Ім\'я є обов\'язковим';
            if (empty($email)) $errors[] = 'Email є обов\'язковим';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Невірний формат email';

            if (empty($phone)) $errors[] = 'Номер телефону є обов\'язковим';
            elseif (!preg_match('/^\+?\d{10,15}$/', $phone)) $errors[] = 'Невірний формат телефону';

            if (empty($password)) $errors[] = 'Пароль є обов\'язковим';
            elseif (strlen($password) < 6) $errors[] = 'Пароль повинен містити мінімум 6 символів';

            if ($password !== $confirmPassword) $errors[] = 'Паролі не співпадають';

            if (empty($errors) && $this->userModel->findByEmail($email)) {
                $errors[] = 'Користувач з таким email вже існує';
            }

            if (empty($errors)) {
                $userData = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];

                $userId = $this->userModel->create($userData);

                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_phone'] = $phone;
                    $_SESSION['is_admin'] = false;

                    header('Location: /');
                    exit;
                } else {
                    $errors[] = 'Помилка при створенні акаунту';
                }
            }

            $this->view('auth/register', compact('errors', 'name', 'email', 'phone'));
        } else {
            $this->view('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
}