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
$title = 'Вхід - QuestBooking';
$currentPage = 'auth';
?>


    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Вхід до системи</h2>
                <p class="auth-subtitle">Увійдіть до свого акаунту</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
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
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
                        autocomplete="current-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-full">Увійти</button>
            </form>

            <div class="auth-footer">
                <p>Ще не маєте акаунту? <a href="/auth/register" class="auth-link">Зареєструватися</a></p>
            </div>
        </div>
    </div>

</body>