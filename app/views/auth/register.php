<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'QuestBooking' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php
$title = 'Реєстрація - QuestBooking';
$currentPage = 'auth';?>



    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Реєстрація</h2>
                <p class="auth-subtitle">Створіть новий акаунт</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
                <script>
                    setTimeout(() => {
                        window.location.href = "<?= htmlspecialchars($redirect) ?>";
                    }, 2000);
                </script>
            <?php endif; ?>



            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name" class="form-label">Повне ім'я</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        value="<?= htmlspecialchars($name ?? '') ?>"
                        required
                        autocomplete="name"
                    >
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Номер телефону</label>
                    <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-input"
                            value="<?= htmlspecialchars($phone ?? '') ?>"
                            required
                            pattern="\+?\d{10,15}"
                            title="Введіть номер телефону (10-15 цифр, можна з + на початку)"
                            autocomplete="tel"
                    >
                    <small class="form-help">Формат: +380123456789 або 0123456789</small>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="<?= htmlspecialchars($email ?? '') ?>"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Пароль</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        minlength="6"
                    >
                    <small class="form-help">Мінімум 6 символів</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Підтвердіть пароль</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="form-input"
                        required
                        autocomplete="new-password"
                        minlength="6"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-full">Зареєструватися</button>
            </form>

            <div class="auth-footer">
                <p>Вже маєте акаунт? <a href="/auth/login" class="auth-link">Увійти</a></p>
            </div>
        </div>
    </div>

</body>
