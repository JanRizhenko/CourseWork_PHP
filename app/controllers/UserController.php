<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->userModel = new User();
    }

    private function requireAdmin()
    {
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: /auth/login');
            exit;
        }
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /auth/login');
            exit;
        }
    }

    public function index()
    {
        $this->requireAdmin();

        $users = $this->userModel->getAll();

        $data = [
            'title' => 'Список користувачів',
            'welcome' => 'Перелік зареєстрованих користувачів',
            'users' => $users
        ];

        $this->view('users/index', $data);
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($name)) {
                $errors[] = 'Ім\'я є обов\'язковим';
            }

            if (empty($email)) {
                $errors[] = 'Email є обов\'язковим';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Невірний формат email';
            } elseif ($email !== $user['email'] && $this->userModel->findByEmail($email)) {
                $errors[] = 'Користувач з таким email вже існує';
            }

            if (empty($phone)) {
                $errors[] = 'Номер телефону є обов\'язковим';
            } elseif (!preg_match('/^\+?\d{10,15}$/', $phone)) {
                $errors[] = 'Невірний формат телефону';
            }

            $updateData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ];

            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $errors[] = 'Для зміни паролю введіть поточний пароль';
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    $errors[] = 'Невірний поточний пароль';
                } else {
                    $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }

            if (empty($errors)) {
                if ($this->userModel->update($userId, $updateData)) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_phone'] = $phone;

                    $success = 'Профіль успішно оновлено';
                    $user = $this->userModel->findById($userId);
                } else {
                    $errors[] = 'Помилка при оновленні профілю';
                }
            }
        }

        $bookingsCount = $this->userModel->getUserBookingsCount($userId);
        $this->view('auth/profile', compact('user', 'bookingsCount', 'errors', 'success'));
    }

    public function delete($userId)
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/list');
            exit;
        }

        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Не валідний CSRF токен';
            header('Location: /user/list');
            exit;
        }

        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Ви не можете видалити власний акаунт';
            header('Location: /user/list');
            exit;
        }

        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'Користувача успішно видалено';
        } else {
            $_SESSION['error'] = 'Помилка при видаленні користувача';
        }

        header('Location: /user/list');
        exit;
    }
}