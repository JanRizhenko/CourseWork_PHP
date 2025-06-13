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
            <div class="header-content">
                <div class="header-left">
                    <h1 class="site-title">QuestBooking</h1>
                    <p class="site-subtitle">Система бронювання квест-кімнат</p>
                </div>

                <div class="auth-section">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <button class="btn btn-profile" id="profileBtn">
                                <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Користувач') ?></span>
                                <span class="dropdown-arrow">▼</span>
                            </button>
                            <div class="profile-dropdown" id="profileDropdown">
                                <a href="/user/profile" class="dropdown-item">
                                    <span class="dropdown-icon"></span>
                                    Мій профіль
                                </a>
                                <a href="/booking/my" class="dropdown-item">
                                    <span class="dropdown-icon"></span>
                                    Мої бронювання
                                </a>
                                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                    <a href="/admin" class="dropdown-item admin-item">
                                        <span class="dropdown-icon"></span>
                                        Панель адміна
                                    </a>
                                <?php endif; ?>
                                <a href="/auth/logout" class="dropdown-item logout-item">
                                    <span class="dropdown-icon"></span>
                                    Вийти
                                </a>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="/auth/login" class="btn btn-login">Увійти</a>
                            <a href="/auth/register" class="btn btn-register">Реєстрація</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>
    </a>

    <nav class="main-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="/" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">Головна</a>
            </li>
            <li class="nav-item">
                <a href="/quest" class="nav-link <?= $currentPage === 'quest' ? 'active' : '' ?>">Квести</a>
            </li>
            <li class="nav-item">
                <a href="/booking" class="nav-link <?= $currentPage === 'booking' ? 'active' : '' ?>">Бронювання</a>
            </li>
            <li class="nav-item">
                <a href="/news" class="nav-link <?= $currentPage === 'news' ? 'active' : '' ?>">Новини</a>
            </li>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li class="nav-item">
                    <a href="/user" class="nav-link <?= $currentPage === 'user' ? 'active' : '' ?>">Користувачі</a>
                </li>
            <?php endif; ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li class="nav-item">
                    <a href="/admin" class="nav-link <?= $currentPage === 'admin' ? 'active' : '' ?>">Адміністрування</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="main-content">