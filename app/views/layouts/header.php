<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'QuestBooking' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="container">
    <a href="/" style="text-decoration: none;">
    <header class="site-header">
        <h1 class="site-title">QuestBooking</h1>
        <p class="site-subtitle">Система бронювання квест-кімнат</p>
    </header>
    </a>

    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="/" class="nav-link">Головна</a>
            </li>
            <li class="nav-item">
                <a href="/quest" class="nav-link">Квести</a>
            </li>
            <li class="nav-item">
                <a href="/booking" class="nav-link">Бронювання</a>
            </li>
            <li class="nav-item">
                <a href="/news" class="nav-link">Новини</a>
            </li>
            <li class="nav-item">
                <a href="/user" class="nav-link">Користувачі</a>
            </li>
            <li class="nav-item">
                <a href="/admin" class="nav-link">Адміністрування</a>
            </li>
        </ul>
    </nav>

    <main class="main-content">