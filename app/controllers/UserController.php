<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    public function index()
    {
        $userModel = new User();
        $users = $userModel->getAll();

        $title = 'Список користувачів';
        $welcome = 'Перелік зареєстрованих користувачів';

        require_once __DIR__ . '/../views/users/index.php';
    }
}